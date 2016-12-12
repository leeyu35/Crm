<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
$year = (int)$_GET['year'];
$week = (int)$_GET['week'];
$weeks = date("W", mktime(0, 0, 0, 12, 28, $year));
echo $year . '年一共有' . $weeks . '周<br />';
if ($week > $weeks || $week <= 0)
{
    $week = 1;
}
if ($week < 10)
{
    $week = '0' . $week;
}
$timestamp['start'] = strtotime($year . 'W' . $week);
$timestamp['end'] = strtotime('+1 week -1 day', $timestamp['start']);
echo $year . '年第' . $week . '周开始时间戳：' . $timestamp['start'] . '<br />';
echo $year . '年第' . $week . '周结束时间戳：' . $timestamp['end'] . '<br />';
echo $year . '年第' . $week . '周开始日期：' . date("Y-m-d", $timestamp['start']) . '<br />';
echo $year . '年第' . $week . '周结束日期：' . date("Y-m-d", $timestamp['end']);
?>