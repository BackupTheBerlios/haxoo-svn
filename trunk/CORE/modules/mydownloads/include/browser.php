<?php
//
// Check User Browser
//
function UsrBrowserAgent(&$browser,&$version) {
    if (preg_match('@Opera(/| )([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
        $version= $log_version[2];
        $browser='OPERA';
    } elseif (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
        $version= $log_version[1];
        $browser='IE';
    } elseif (preg_match('@OmniWeb/([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
        $version= $log_version[1];
        $browser='OMNIWEB';
    } elseif (preg_match('@(Konqueror/)(.*)(;)@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
        $version= $log_version[2];
        $browser='KONQUEROR';
    } elseif (preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)
               && preg_match('@Safari/([0-9]*)@', $_SERVER['HTTP_USER_AGENT'], $log_version2)) {
        $version= $log_version[1] . '.' . $log_version2[1];
        $browser='SAFARI';
    } elseif (preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
        $version= $log_version[1];
        $browser='MOZILLA';
    } else {
        $version= 0;
        $browser='OTHER';
    }
    return $browser;
}
?>
