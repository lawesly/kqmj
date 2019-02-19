<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("inc/Global.php");
require_once("inc/SumTime.php");
ini_set('date.timezone','Asia/Shanghai');

class Anomaly_app extends CI_Controller {
    /*
     * 异常申请模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }

    private function checkadmin($gid){
        /*
         * 判断权限函数
         */
        if($gid == 1 or $gid == 2){
            return 1;
        }else{
            return 0;
        }
    }


    public function index()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        $header['gid']          = $gid;
        $header['username']     = $username;
        $header['title']        = "异常申请";
        $months = array();
        $today = date('Ym',time());
        if(isset($_GET['display'])){
            $display = $_GET['display'];
        }else{
            $display = $today;
        }
        if(isset($_GET['sure'])){
            $sure = $_GET['sure'];
        }else{
            $sure = 0;
        }
        $year = substr($today, 0, 4);
        $month = substr($today, 4, 6);
        $months[] = $today;
        for($i=1; $i<12; $i++){
            $month = $month - 1;
            if($month == 0){
                $month = 12;
                $year = $year - 1;
            }
            $tmp_month=mktime(0,0,0,$month,1,$year);
            $months[] = date("Ym", $tmp_month);

        }
        $data['display'] = $display;
        if($sure == 0){
            $sql_ano = "select * from anomaly where dwDate like '$display%' and isack=0";
        }else{
            $sure = 1;
            $sql_ano = "select * from anomaly where dwDate like '$display%' and isack<>0";
        }
        $data['sure'] = $sure;
        $data['anomaly']        = $this->att_model->get_tab_diy($sql_ano);
        global $anomalyTypeArr;
        global $ackArr;
        foreach($data['anomaly'] as &$arr){
            $arr['type'] = $anomalyTypeArr[$arr['type']];
            $arr['isack'] = $ackArr[$arr['isack']];
            $a_stime = $arr['stime'];
            $a_etime = $arr['etime'];
            $arr['apptime'] = $a_stime."--".$a_etime;
            // $anotime = $a_stime."--".$a_etime;
            $arr['durtime'] = $arr['sumtime'];
            /*
               if($arr['type'] == "请假" or $arr['type'] == '加班'){
               $a_stime_arr = explode(':',$a_stime);
               $a_etime_arr = explode(':',$a_etime);
               $a_stime_1 = mktime("$a_stime_arr[0]","$a_stime_arr[1]","00","01","01","2016");
               $a_etime_1 = mktime("$a_etime_arr[0]","$a_etime_arr[1]","00","01","01","2016");
               $durtime = ($a_etime_1 - $a_stime_1)/3600;
               $month = substr($dwDate,4,2);
               $summer_arr = array('05','06','07','08','09');
               if(in_array($month,$summer_arr)){
               if($a_stime <= "12:00" and $a_etime >="13:30"){
               $durtime = $durtime - 1.5;
               }
               }else{
               if($a_stime <= "12:00" and $a_etime >="13:00"){
               $durtime = $durtime - 1;
               }
               }
               if($arr['type'] == "请假"){
               $durtime = SumTime($durtime,0);
               }else{
               $durtime = SumTime($durtime,1);
               }
               }else{
               $durtime=0;
               }
               $arr['durtime'] = round($durtime,2);
             */
            $anoID = $arr['id'];
            $isinvite = $this->att_model->get_tab_diy("select * from notice where anoID='$anoID'");
            if(isset($isinvite)){
                $inviteAll = array();
                $sureAll = array();
                foreach($isinvite as $line){
                    $invite = $line['username'];
                    $sure = $line['isack'];
                    $isinvitedel = $this->att_model->get_tab_one("users",'username',$invite);
                    if($isinvitedel){
                        $invite = $isinvitedel->realname;
                    }
                    $sure = $ackArr[$sure];
                    $inviteAll[] = $invite;
                    $sureAll[] = $sure;
                }
                $arr['invite'] = implode('/',$inviteAll);
                $arr['sure'] = implode('/',$sureAll);
            }else{
                $arr['invite'] = null;
                $arr['sure'] = null;
            }

        }
        $data['months'] = $months;
        $footer['myjs'] = "anomaly_app_index.js";
        $header['count'] = 0;
        $this->load->view('header', $header);
        $this->load->view('Node/anomaly_app/index', $data);
        $this->load->view('footer', $footer);

    }


    public function cfm(){
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        $header['gid']          = $gid;
        $header['username']     = $username;
        $ids = $_POST['ids'];
        $idsArr = explode(',',$ids);	
        $action = $_POST['action'];
        foreach($idsArr as $id){
            if($id != '0'){
                $id = substr($id,0,-1);
                if($action == 'sure'){
                    $sql1 = "update anomaly set isack=1 where id=$id";
                    $this->db->query($sql1);
                }elseif($action == 'suremark'){
                    $sql1 = "update anomaly set isack=2 where id=$id";
                    $this->db->query($sql1);
                }elseif($action == 'cancel'){
                    $sql1 = "update anomaly set isack=0 where id=$id";
                    $this->db->query($sql1);
                }
            }
        }
        echo '1';
    }
}
