<?php
$modversion['name'] = _IMP_MODULE_NAME;
$modversion['version'] = 2.14;
$modversion['description'] = _IMP_MODULE_DESC;
$modversion['credits'] = "Peter Nagl<br>http://www.nagl.ch";
$modversion['author'] = "Peter Nagl";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/slogo.png";
$modversion['dirname'] = "impressum";

//Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";

// Menu
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'impressum_index.html';
$modversion['templates'][1]['description'] = '';

//Config
$modversion['config'][1]['name'] = 'cfgImpressum';
$modversion['config'][1]['title'] = '_IMP_MESSAGE';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textarea';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'Impressum';
?>