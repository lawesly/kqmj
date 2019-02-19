<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");

class Checkwork extends CI_Controller {
    /*
     * 判断是否工作时间
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }
    private function check($unixtime){
        $dwDate = date('Ymd',$unixtime);
        $year = substr($dwDate,0,4);
        $month = substr($dwDate,4,2);
        $day = substr($dwDate,6,2);
        $iswr = $this->att_model->get_tab_one("wr",'dwDate',$dwDate);
        if($iswr){
            if($iswr->type == 1){
                return 1;
            }else{
                $stime = $iswr->stime;
                $etime = $iswr->etime;
                $stime_arr = explode(':',$stime);
                $etime_arr = explode(':',$etime);
                $stime_unix = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");
                $etime_unix = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");
                if($unixtime < $stime_unix or $unixtime > $etime_unix){
                    return 1;
                }else{
                    return 0;
                }
            }
        }else{
            $week = date('w',strtotime($dwDate));
            if($week == 0 or $week == 6){
                return 1;
            }else{
                $stime = "08:30";
                if($week == 4){
                    $etime = "20:00";
                }else{
                    $etime = "17:30";
                }
                $stime_arr = explode(':',$stime);
                $etime_arr = explode(':',$etime);
                $stime_unix = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");
                $etime_unix = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");
                if($unixtime < $stime_unix or $unixtime > $etime_unix){
                    return 1;
                }else{
                    return 0;
                }
            }

        }

    }
    public function index()

    {
        $unixtime = $_GET['time'];
        $ret = $this->check($unixtime);
        echo $ret;

    }

}
