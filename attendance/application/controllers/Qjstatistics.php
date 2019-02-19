<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Qjstatistics extends CI_Controller {
    /**
     * 请假情况统计模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        if($this->session->change == 1){
            header("Location:?/passwd/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "请假统计";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $months = array();
        $today = date('Ym',time());
        if(isset($_POST['display'])){
            $display = $_POST['display'];
        }else{
            $display = $today;
        }

        if(isset($_POST['depname'])){
            $depdisplay = $_POST['depname'];
        }else{
            $depdisplay = "技术部";
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
        
        $data['months'] = $months;
        //$Users = $this->att_model->get_tab_diy("select * from users where groupid=3");
        $data['depdisplay'] = $depdisplay;
        $data['depnames'] = $this->att_model->get_tab_diy("select distinct depname from userinfo");
        $data['tongji'] = $this->att_model->get_tab_diy("select * from tongji where dwDate like '$display%' and depname = '$depdisplay'");

        $footer['myjs'] = 'qjstatistics.js';
        $this->load->view('header_user',$header);
        $this->load->view('Node/qjstatistics/index',$data); 
        $this->load->view('footer_user', $footer);
    }
}

