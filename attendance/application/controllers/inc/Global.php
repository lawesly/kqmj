<?php
$monthDays = array(
        '01'=>31,
        '02'=>29,
        '03'=>31,
        '04'=>30,
        '05'=>31,
        '06'=>30,
        '07'=>31,
        '08'=>31,
        '09'=>30,
        '10'=>31,
        '11'=>30,
        '12'=>31
        );
$groupArr = array(
        3=>'普通人员',
        4=>'外来人员',
        2=>'操作员',
        1=>'管理员'
        );
$anomalyTypeArr = array(
        2=>'请假',
        1=>'未打卡',
        3=>'加班',
        4=>'公出'
        );
$anomalyTypesubArr = array(
        2=>array("调休","年休假","病假","事假","婚假","产假","护理假","丧假"),
        1=>array("上班未打卡","下班未打卡"),
        3=>array("调休","折现"),
        4=>array("公出")
        );
$ackArr = array(
        0=>'否',
        1=>'是',
        2=>'是'
        );
$statusArr = array(
    0=>'禁用',
    1=>'启用',
    2=>'启用+道闸',
    3=>'道闸'
);
$sumTimeArr = array(0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5,5.5,6,6.5,7,7.5,8,8.5,9,9.5,10,10.5,11,11.5,12,12.5,13,13.5,14,14.5,15);
function formatTime($time){
    $arr = explode(":", $time);
    $hour = sprintf("%02d", $arr[0]);
    $minute = sprintf("%02d", $arr[1]);
    $formatTime = "$hour:$minute";
    return $formatTime;

}

