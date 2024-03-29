<?php
$modversion['name'] = _MI_TUTORIALS_NAME;
$modversion['version'] = "2.1";
$modversion['description'] = _MI_TUTORIALS_DESC;
$modversion['credits'] = "MyTutorials";
$modversion['author'] = "Thomas Wolf<br />( http://www.mytutorials.info/ )";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/tutorials_slogo.png";
$modversion['dirname'] = "tutorials";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "tutorials";
$modversion['tables'][1] = "tutorials_categorys";
$modversion['tables'][2] = "tutorials_groups";
$modversion['tables'][3] = "tutorials_votedata";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Blocks
$modversion['blocks'][1]['file'] = "tutorials_top.php";
$modversion['blocks'][1]['name'] = _MI_TUTORIALS_BNAME1;
$modvertion['blocks'][1]['description'] = "Shows last tutorials";
$modversion['blocks'][1]['show_func'] = "b_tutorials_top_show";
$modversion['blocks'][1]['edit_func'] = "b_tutorials_top_edit";
$modversion['blocks'][1]['options'] = "date|10";
$modversion['blocks'][2]['file'] = "tutorials_top.php";
$modversion['blocks'][2]['name'] = _MI_TUTORIALS_BNAME2;
$modvertion['blocks'][2]['description'] = "Shows most visited tutorials";
$modversion['blocks'][2]['show_func'] = "b_tutorials_top_show";
$modversion['blocks'][2]['edit_func'] = "b_tutorials_top_edit";
$modversion['blocks'][2]['options'] = "hits|10";
$modversion['blocks'][3]['file'] = "waiting.php";
$modversion['blocks'][3]['name'] = _MI_TUTORIALS_BNAME3;
$modvertion['blocks'][3]['description'] = "Shows all waiting tutorials";
$modversion['blocks'][3]['show_func'] = "b_waiting_show";


// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_TUTORIALS_SMNAME1;
$modversion['sub'][1]['url'] = "submit.php";

// ekke dazu 20020704
// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "tutorial_search";
//ekke ende
?>