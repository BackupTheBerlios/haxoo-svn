<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: user.php 5204 2010-09-06 20:10:52Z mageg $
 */
include 'header.php';
xoops_cp_header();

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
if ( $op == "editordelete" ) {
    $op = isset($_REQUEST['delete']) ? "delete" : "edit";
}
$handler =& xoops_gethandler('member');

switch($op ) {
    default:
    case "list":
        include_once $GLOBALS['xoops']->path( "/class/xoopsformloader.php" );
        $form = new XoopsThemeForm(_PROFILE_AM_EDITUSER, 'form', 'user.php');
        $form->addElement(new XoopsFormSelectUser(_PROFILE_AM_SELECTUSER, 'id') );
        $form->addElement(new XoopsFormHidden('op', 'editordelete') );
        $button_tray = new XoopsFormElementTray('');
        $button_tray->addElement(new XoopsFormButton('', 'edit', _EDIT, 'submit') );
        $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit') );
        $form->addElement($button_tray);
        $form->display();

    case "new":
        xoops_loadLanguage("main", $GLOBALS['xoopsModule']->getVar('dirname', 'n') );
        include_once '../include/forms.php';
        $obj =& $handler->createUser();
        $obj->setGroups(array(XOOPS_GROUP_USERS) );
        $form = profile_getUserForm($obj);
        $form->display();
        break;

    case "edit":
        xoops_loadLanguage("main", $GLOBALS['xoopsModule']->getVar('dirname', 'n') );
        $obj =& $handler->getUser($_REQUEST['id']);
        if ( in_array(XOOPS_GROUP_ADMIN, $obj->getGroups() ) && !in_array(XOOPS_GROUP_ADMIN, $GLOBALS['xoopsUser']->getGroups() ) ) {
            // If not webmaster trying to edit a webmaster - disallow
            redirect_header("user.php", 3, _US_NOEDITRIGHT);
        }
        include_once '../include/forms.php';
        $form = profile_getUserForm($obj);
        $form->display();
        break;

    case "save":
        xoops_loadLanguage("main", $GLOBALS['xoopsModule']->getVar('dirname', 'n') );
        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
            redirect_header('user.php', 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
            exit;
        }

        // Dynamic fields
        $profile_handler =& xoops_getmodulehandler('profile');
        // Get fields
        $fields = $profile_handler->loadFields();
        $userfields = $profile_handler->getUserVars();
        // Get ids of fields that can be edited
        $gperm_handler =& xoops_gethandler('groupperm');
        $editable_fields = $gperm_handler->getItemIds('profile_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        
        $uid = empty($_POST['uid']) ? 0 : intval($_POST['uid']);
        if ( !empty($uid)  ) {
            $user =& $handler->getUser($uid);
            $profile = $profile_handler->get($uid);
            if ( !is_object($profile)  ) {
                $profile = $profile_handler->create();
                $profile->setVar('profile_id', $uid);
            }
        } else {
            $user =& $handler->createUser();
            $profile = $profile_handler->create();
            if ( count($fields) > 0 ) {
                foreach (array_keys($fields) as $i ) {
                    $fieldname = $fields[$i]->getVar('field_name');
                    if ( in_array($fieldname, $userfields)  ) {
                        $default = $fields[$i]->getVar('field_default');
                        if ( $default === '' || $default === null) continue;
                        $user->setVar($fieldname, $default);
                    }
                }
            }
            $user->setVar('user_regdate', time() );
            $user->setVar('level', 1);
        }
        $myts =& MyTextSanitizer::getInstance();
        $user->setVar('uname', $_POST['uname']);
        $user->setVar('email', trim($_POST['email']) );
        if ( isset($_POST['level']) && $user->getVar('level') != intval($_POST['level'])  ) {
            $user->setVar('level', intval($_POST['level']) );
        }
        $password = $vpass = null;
        if ( !empty($_POST['password'])  ) {
            $password = $myts->stripSlashesGPC(trim($_POST['password']) );
            $vpass = @$myts->stripSlashesGPC(trim($_POST['vpass']) );
            $user->setVar('pass', md5($password) );
        } elseif ( $user->isNew()  ) {
            $password = $vpass = '';
        }
        xoops_load("userUtility");
        $stop = XoopsUserUtility::validate($user, $password, $vpass);
        
        $errors = array();
        if ( $stop != "" ) {
            $errors[] = $stop;
        }

        foreach (array_keys($fields) as $i ) {
            $fieldname = $fields[$i]->getVar('field_name');
            if ( in_array($fields[$i]->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])  ) {
                if ( in_array($fieldname, $userfields)  ) {
                    $value = $fields[$i]->getValueForSave($_REQUEST[$fieldname], $user->getVar($fieldname, 'n') );
                    $user->setVar($fieldname, $value);
                } else {
                    $value = $fields[$i]->getValueForSave( ( isset($_REQUEST[$fieldname]) ? $_REQUEST[$fieldname] : ""), $profile->getVar($fieldname, 'n') );
                    $profile->setVar($fieldname, $value);
                }
            }
        }

        $new_groups = isset($_POST['groups']) ? $_POST['groups'] : array();

        if ( count($errors) == 0 ) {
            if ( $handler->insertUser($user)  ) {
                $profile->setVar('profile_id', $user->getVar('uid') );
                $profile_handler->insert($profile);
                include_once $GLOBALS['xoops']->path( "/modules/system/constants.php" );
                if ( $gperm_handler->checkRight("system_admin", XOOPS_SYSTEM_GROUP, $GLOBALS['xoopsUser']->getGroups(), 1)  ) {
                    //Update group memberships
                    $cur_groups = $user->getGroups();

                    $added_groups = array_diff($new_groups, $cur_groups);
                    $removed_groups = array_diff($cur_groups, $new_groups);

                    if ( count($added_groups) > 0 ) {
                        foreach ($added_groups as $groupid ) {
                            $handler->addUserToGroup($groupid, $user->getVar('uid') );
                        }
                    }
                    if ( count($removed_groups) > 0 ) {
                        foreach ($removed_groups as $groupid ) {
                            $handler->removeUsersFromGroup($groupid, array($user->getVar('uid') ));
                        }
                    }
                }
                if ( $user->isNew()  ) {
                    redirect_header('user.php', 2, _PROFILE_AM_USERCREATED, false);
                } else {
                    redirect_header('user.php', 2, _US_PROFUPDATED, false);
                }
            }
        } else {
            foreach ($errors as $err ) {
                $user->setErrors($err);
            }
        }
        $user->setGroups($new_groups);
        include_once '../include/forms.php';
        echo $user->getHtmlErrors();
        $form = profile_getUserForm($user, $profile);
        $form->display();
        break;

    case "delete":
        if ( $_REQUEST['id'] == $GLOBALS['xoopsUser']->getVar('uid')  ) {
            redirect_header('user.php', 2, _PROFILE_AM_CANNOTDELETESELF);
        }
        $obj =& $handler->getUser($_REQUEST['id']);
        $groups = $obj->getGroups();
        if ( in_array(XOOPS_GROUP_ADMIN, $groups)  ) {
            redirect_header('user.php', 3, _PROFILE_AM_CANNOTDELETEADMIN, false);
        }
        
        if ( isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1 ) {
            if ( !$GLOBALS['xoopsSecurity']->check()  ) {
                redirect_header('user.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ), false);
            }
            $profile_handler = xoops_getmodulehandler('profile');
            $profile = $profile_handler->get($obj->getVar('uid') );
            if ( !$profile || $profile->isNew() || $profile_handler->delete($profile)  ) {
                if ( $handler->deleteUser($obj)  ) {
                    redirect_header('user.php', 3, sprintf(_PROFILE_AM_DELETEDSUCCESS, $obj->getVar('uname') . " (" . $obj->getVar('email') . ")"), false);
                } else {
                    echo $obj->getHtmlErrors();
                }
            } else {
                echo $profile->getHtmlErrors();
            }

        } else {
            xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('uname') . " (" . $obj->getVar('email') . ")") );
        }
        break;
}

xoops_cp_footer();
?>