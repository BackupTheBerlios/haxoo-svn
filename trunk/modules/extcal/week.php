<?php

function orderEvents($event1, $event2)
{
    if ($event1['event_start'] == $event2['event_start']) {
        return 0;
    }
    if ($GLOBALS['xoopsModuleConfig']['sort_order'] == 'ASC') {
        $opt1 = -1;
        $opt2 = 1;
    } else {
        $opt1 = 1;
        $opt2 = -1;
    }
    return ($event1['event_start'] < $event2['event_start']) ? $opt1 : $opt2;
}


$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$day = isset($_GET['day']) ? intval($_GET['day']) : date('j');
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'extcal_week.html';
include XOOPS_ROOT_PATH . '/header.php';

if (!defined('CALENDAR_ROOT')) {
    define('CALENDAR_ROOT',
        XOOPS_ROOT_PATH . '/modules/extcal/class/pear/Calendar/');
}

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once CALENDAR_ROOT . 'Week.php';

// Validate the date (day, month and year)
$dayTS = mktime(0, 0, 0, $month, $day, $year);
$offset = date('w', $dayTS) - $xoopsModuleConfig['week_start_day'];
$dayTS = $dayTS - ($offset * 86400);
$year = date('Y', $dayTS);
$month = date('n', $dayTS);
$day = date('j', $dayTS);

// Getting eXtCal object's handler
$catHandler = xoops_getmodulehandler('cat', 'extcal');
$eventHandler = xoops_getmodulehandler('event', 'extcal');
$extcalTimeHandler = ExtcalTime::getHandler();

// Tooltips include
$xoTheme->addScript('modules/extcal/include/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/include/style.css');

$lang = array(
    'start' => _MD_EXTCAL_START, 'end' => _MD_EXTCAL_END, 'calmonth' => _MD_EXTCAL_NAV_CALMONTH, 'calweek' => _MD_EXTCAL_NAV_CALWEEK, 'year' => _MD_EXTCAL_NAV_YEAR, 'month' => _MD_EXTCAL_NAV_MONTH, 'week' => _MD_EXTCAL_NAV_WEEK, 'day' => _MD_EXTCAL_NAV_DAY
);
// Assigning language data to the template
$xoopsTpl->assign('lang', $lang);

// ### Create the navigation form ###

// Year selectbox
$yearSelect = new XoopsFormSelect('', 'year', $year);
for (
    $i = 2004; $i < 2015; $i++
) {
    $yearSelect->addOption($i);
}

// Month selectbox
$monthSelect = new XoopsFormSelect('', 'month', $month);
for (
    $i = 1; $i < 13; $i++
) {
    $monthSelect->addOption($i, $extcalTimeHandler->getMonthName($i));
}

// Day selectbox
$daySelect = new XoopsFormSelect('', 'day', $day);
for (
    $i = 1; $i < 32; $i++
) {
    $daySelect->addOption($i);
}

// Category selectbox
$catsList = $catHandler->getAllCat($xoopsUser);
$catSelect = new XoopsFormSelect('', 'cat', $cat);
$catSelect->addOption(0, ' ');
foreach (
    $catsList as $catList
) {
    $catSelect->addOption($catList->getVar('cat_id'), $catList->getVar('cat_name'));
}

$form = new XoopsSimpleForm('', 'navigSelectBox', 'week.php', 'get');
$form->addElement($yearSelect);
$form->addElement($monthSelect);
$form->addElement($daySelect);
$form->addElement($catSelect);
$form->addElement(new XoopsFormButton("", "", _SEND, "submit"));

// Assigning the form to the template
$form->assign($xoopsTpl);

// Retriving events
$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat), array('cat_id'));
$eventHandler->serverTimeToUserTimes($events);

// Formating date
$eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);

// Treatment for recurring event
$startWeek = mktime(0, 0, 0, $month, $day, $year);
$endWeek = $startWeek + 604800;
$eventsArray = array();
foreach (
    $events as $event
) {

    if (!$event['event_isrecur']) {

        // Formating date
        $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);

        $eventsArray[] = $event;

    } else {

        $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startWeek, $endWeek);

        // Formating date
        $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);

        $eventsArray = array_merge($eventsArray, $recurEvents);

    }

}

// Sort event array by event start
usort($eventsArray, "orderEvents");

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$weekCalObj = new Calendar_Week($year, $month, $day, $xoopsModuleConfig['week_start_day']);
$pWeekCalObj = $weekCalObj->prevWeek('object');
$nWeekCalObj = $weekCalObj->nextWeek('object');
$navig = array(
    'prev'
    => array(
        'uri'
        => 'year=' . $pWeekCalObj->thisYear() . '&amp;month='
            . $pWeekCalObj->thisMonth() . '&amp;day='
            . $pWeekCalObj->thisDay(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $pWeekCalObj->getTimestamp())
    ), 'this'
    => array(
        'uri'
        => 'year=' . $weekCalObj->thisYear() . '&amp;month='
            . $weekCalObj->thisMonth() . '&amp;day='
            . $weekCalObj->thisDay(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $weekCalObj->getTimestamp())
    ), 'next'
    => array(
        'uri'
        => 'year=' . $nWeekCalObj->thisYear() . '&amp;month='
            . $nWeekCalObj->thisMonth() . '&amp;day='
            . $nWeekCalObj->thisDay(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $nWeekCalObj->getTimestamp())
    )
);

// Title of the page
$xoopsTpl->assign(
    'xoops_pagetitle',
    $xoopsModule->getVar('name') . ' ' . $navig['this']['name']
);

// Assigning navig data to the template
$xoopsTpl->assign('navig', $navig);

// Assigning current form navig data to the template
$xoopsTpl->assign('selectedCat', $cat);
$xoopsTpl->assign('year', $year);
$xoopsTpl->assign('month', $month);
$xoopsTpl->assign('day', $day);
$xoopsTpl->assign('view', "week");

include XOOPS_ROOT_PATH . '/footer.php';