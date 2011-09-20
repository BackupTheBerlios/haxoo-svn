<?php

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'extcal_calendar-month.html';
include XOOPS_ROOT_PATH . '/header.php';

if (!defined('CALENDAR_ROOT')) {
    define('CALENDAR_ROOT',
        XOOPS_ROOT_PATH . '/modules/extcal/class/pear/Calendar/');
}

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once CALENDAR_ROOT . 'Util/Textual.php';
require_once CALENDAR_ROOT . 'Month/Weeks.php';
require_once CALENDAR_ROOT . 'Day.php';

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

// Category selectbox
$catsList = $catHandler->getAllCat($xoopsUser);
$catSelect = new XoopsFormSelect('', 'cat', $cat);
$catSelect->addOption(0, ' ');
foreach (
    $catsList as $catList
) {
    $catSelect->addOption($catList->getVar('cat_id'), $catList->getVar('cat_name'));
}

$form = new XoopsSimpleForm('', 'navigSelectBox', 'calendar-month.php', 'get');
$form->addElement($yearSelect);
$form->addElement($monthSelect);
$form->addElement($catSelect);
$form->addElement(new XoopsFormButton("", "form_submit", _SEND, "submit"));

// Assigning the form to the template
$form->assign($xoopsTpl);

// Retriving events and formatting them
$events = $eventHandler->objectToArray($eventHandler->getEventCalendarMonth($month, $year, $cat), array('cat_id'));
$eventHandler->serverTimeToUserTimes($events);

// Calculating timestamp for the begin and the end of the month
$startMonth = mktime(0, 0, 0, $month, 1, $year);
$endMonth = mktime(23, 59, 59, $month + 1, 0, $year);

/*
*  Adding all event occuring during this month to an array indexed by day number
*/
$eventsArray = array();
foreach (
    $events as $event
) {

    if (!$event['event_isrecur']) {

        // Formating date
        $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_month']);

        $eventHandler->addEventToCalArray($event, $eventsArray, $startMonth, $endMonth);

    } else {

        $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);

        // Formating date
        $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_month']);

        foreach (
            $recurEvents as $recurEvent
        ) {
            $eventHandler->addEventToCalArray($recurEvent, $eventsArray, $startMonth, $endMonth);
        }

    }
}

/*
*  Making an array to create tabbed output on the template
*/
// Flag current day
$selectedDays = array(
    new Calendar_Day(date('Y', xoops_getUserTimestamp(time(), $extcalTimeHandler->_getUserTimeZone($xoopsUser))), date('n', xoops_getUserTimestamp(time(), $extcalTimeHandler->_getUserTimeZone($xoopsUser))), date('j', xoops_getUserTimestamp(time(), $extcalTimeHandler->_getUserTimeZone($xoopsUser))))
);

// Build calendar object
$monthCalObj = new Calendar_Month_Weeks($year, $month, $xoopsModuleConfig['week_start_day']);
$pMonthCalObj = $monthCalObj->prevMonth('object');
$nMonthCalObj = $monthCalObj->nextMonth('object');
$monthCalObj->build();

$tableRows = array();
$rowId = 0;
$cellId = 0;
while ($weekCalObj = $monthCalObj->fetch()) {
    $weekCalObj->build($selectedDays);
    $tableRows[$rowId]['weekInfo'] = array(
        'week' => $weekCalObj->thisWeek('n_in_year'), 'day' => $weekCalObj->thisDay(), 'month' => $weekCalObj->thisMonth(), 'year' => $weekCalObj->thisYear()
    );
    while ($dayCalObj = $weekCalObj->fetch()) {
        $tableRows[$rowId]['week'][$cellId] = array('isEmpty' => $dayCalObj->isEmpty(), 'number' => $dayCalObj->thisDay(), 'isSelected' => $dayCalObj->isSelected());
        if (@count($eventsArray[$dayCalObj->thisDay()]) > 0
            && !$dayCalObj->isEmpty()
        ) {
            $tableRows[$rowId]['week'][$cellId]['events'] = $eventsArray[$dayCalObj->thisDay()];
        } else {
            $tableRows[$rowId]['week'][$cellId]['events'] = '';
        }
        $cellId++;
    }
    $cellId = 0;
    $rowId++;
}

// Assigning events to the template
$xoopsTpl->assign('tableRows', $tableRows);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Retriving weekdayNames
$weekdayNames = Calendar_Util_Textual::weekdayNames();
for (
    $i = 0; $i < $xoopsModuleConfig['week_start_day']; $i++
) {
    $weekdayName = array_shift($weekdayNames);
    $weekdayNames[] = $weekdayName;
}
// Assigning weekdayNames to the template
$xoopsTpl->assign('weekdayNames', $weekdayNames);

// Retriving monthNames
$monthNames = Calendar_Util_Textual::monthNames();

// Making navig data
$navig = array(
    'prev'
    => array(
        'uri'
        => 'year=' . $pMonthCalObj->thisYear() . '&amp;month='
            . $pMonthCalObj->thisMonth(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $pMonthCalObj->getTimestamp())
    ), 'this'
    => array(
        'uri'
        => 'year=' . $monthCalObj->thisYear() . '&amp;month='
            . $monthCalObj->thisMonth(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $monthCalObj->getTimestamp())
    ), 'next'
    => array(
        'uri'
        => 'year=' . $nMonthCalObj->thisYear() . '&amp;month='
            . $nMonthCalObj->thisMonth(), 'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $nMonthCalObj->getTimestamp())
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
$xoopsTpl->assign('view', "calmonth");

include XOOPS_ROOT_PATH . '/footer.php';