<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Vehiclerecord extends CI_Controller {
    /**
     * 车辆通行记录模块
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
        if($gid == 1){
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

        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "车辆通行记录";
        $data['firstday'] = date('m/01/Y');
        $data['today'] = date('m/d/Y');
        $footer['myjs'] = 'vehiclerecord_index.js';

        $this->load->view('header', $header);
        $this->load->view('Node/vehiclerecord/index', $data);
        $this->load->view('footer', $footer);
    }


    public function search()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }

        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "车辆通行记录";
        $fromdate = $_POST['fromdate'];
        $todate = $_POST['todate'];
        if($fromdate == "" or $todate == ""){
            header("Location:?/attendance_user/");
            exit();
        }
        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;
        $typeArr = [
            1 => '进门',
            2 => '出门',
            3 => '北进门',
            4 => '北出门'
        ];
        $codeArr = [
            '200' => '成功(考勤/内部)',
            '210' => '成功(考勤)',
            '220' => '成功(内部)',
            '280' => '成功(访客)',
            '290' => '成功(外来车)',
            '403' => '失败(禁止)'
        ];
        $fromdate_arr = explode("/",$fromdate);
        $fromdate = $fromdate_arr[2].'-'.$fromdate_arr[0].'-'.$fromdate_arr[1];
        $todate_arr = explode("/",$todate);
        $todate = $todate_arr[2].'-'.$todate_arr[0].'-'.$todate_arr[1];
        $fromtime = "$fromdate 00:00:00";
        $totime = "$todate 23:59:59";
        $entrys = $this->att_model->get_tab_diy("select * from bglogNew where operTime >= '$fromtime' and operTime <= '$totime'");
        foreach($entrys as &$entry){
            $code = $entry['code'];
            $entry['code'] = $codeArr[$code];
            $type = $entry['type'];
            $entry['type'] = $typeArr[$type];
        }
        $data['results'] = $entrys;

        $footer['myjs'] = 'vehiclerecord_search.js';
        $this->load->view('header',$header);
        $this->load->view('Node/vehiclerecord/search', $data);
        $this->load->view('footer', $footer);
    }

}
