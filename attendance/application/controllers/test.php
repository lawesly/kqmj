<?php

#echo date('w',strtotime('20160908'));

$sdate='20160801';
$edate='20160831';
$Date_List_a1=array(substr($sdate,0,4),substr($sdate,4,2),substr($sdate,6,2));
$Date_List_a2=array(substr($edate,0,4),substr($edate,4,2),substr($edate,6,2));
$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);

$Days = round(($d2-$d1)/3600/24);
#echo date("Ymd",strtotime("$sdate   +30   day"));
$s = mktime('08','39','02','08','27','2016');
$e = mktime('17','40','0','08','27','2016');
$hours = ($e-$s)/3600;
/*
   if($hours < 9){
   echo "late";
   }else{
   echo $hours;
   }
 */
if('18:50:10' > '18:50:47'){
    echo 1;
}
?>
