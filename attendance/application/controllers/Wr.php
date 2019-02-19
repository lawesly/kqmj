<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("inc/Global.php");

class Wr extends CI_Controller {
    /**
     * Wr constructor.
     * 作息管理模块
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
        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "作息管理";

        $wrs = $this->att_model->get_tab_diy("select * from wr order by id desc limit 100");
        $typeArr = array(0=>'工作',1=>'休息');
        $txArr = array(0=>'是',1=>'否');
        foreach($wrs as &$arr){
            $arr['type'] = $typeArr[$arr['type']];
            $stime = $arr['stime'];
            $etime = $arr['etime'];
            $worktime = "$stime~$etime";
            $arr['worktime'] = $worktime;
            $stime_tx = $arr['stime_tx'];
            $etime_tx = $arr['etime_tx'];
            if(isset($stime_tx)){
                $arr['stime_tx'] = $txArr[$stime_tx];
            }
            if(isset($etime_tx)){
                $arr['etime_tx'] = $txArr[$etime_tx];
            }
        }
        $data['wrs'] = $wrs;
        $footer['myjs'] = 'wr_index.js';
        $header['count'] = 0;
        $this->load->view('header',$header);
        $this->load->view('Node/wr/index',$data);
        $this->load->view('footer', $footer);
    }


    public function add()
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

        $header['title'] = "作息管理";
        $footer['myjs'] = 'wr_add.js';
        $header['count'] = 0;
        $this->load->view('header',$header);
        $this->load->view('Node/wr/add');
        $this->load->view('footer', $footer);
    }


    public function add_cfm()
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
        $dwDate = $_POST['dwDate'];
        if($dwDate == ""){
            echo "0";
            exit();
        }
        $dwDate_arr = explode("/",$dwDate);
        $dwDate = $dwDate_arr[2].$dwDate_arr[0].$dwDate_arr[1];
        $type = $_POST['type'];
        $des = $_POST['des'];
        $isexist = $this->att_model->get_tab_one('wr','dwDate',$dwDate);
        if(isset($isexist)){
            echo '0';
        }else{
            if($type == 0){
                $stime = $_POST['stime'];
                $etime = $_POST['etime'];
                $stime_tx = $_POST['stime_tx'];
                $etime_tx = $_POST['etime_tx'];
                $sql = "insert into wr(dwDate,type,stime,etime,des,stime_tx,etime_tx) values('$dwDate',$type,'$stime','$etime','$des',$stime_tx,$etime_tx)";
            }else{
                $sql = "insert into wr(dwDate,type,des) values('$dwDate',$type,'$des')";
            }
            $this->db->query($sql);
            echo '1';
        }

    }


    public function del()
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

        $id = $_GET['id'];
        $sql_del = "delete  from  wr where id = $id";
        $this->db->query($sql_del);
        echo "1";

    }
}
