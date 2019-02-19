<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("inc/Global.php");

class Anomaly extends CI_Controller {
    /**
     * 异常管理模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }


    public function index()
    {
        session_start();
        if(!isset($_SESSION['userid'])){
            header("Location:?/login/");
            exit();
        }
        $username               =$_SESSION['username'];
        $gid                    = $_SESSION['gid'];
        $header['gid']          = $gid;
        $header['username']     = $username;
        $header['title']        = "首页";
        $data['anomaly']        = $this->att_model->get_tab_diy("select * from anomaly");
        global $anomalyTypeArr;
        foreach($data['anomaly'] as &$arr){
            $arr['type']            = $anomalyTypeArr[$arr['type']];
            $arr['durtime']         = $arr['stime']."--".$arr['etime'];
            $isinvite               = $this->att_model->get_tab_one("notice",'anoID',$arr['id']);
            if(isset($isinvite)){
                $invite                 = $isinvite->username;
                $sure                   = $isinvite->isack;
                $arr['invite']          = $this->att_model->get_tab_one("users",'username',$invite)->realname;
                $arr['sure']            = $sure;
            }else{
                $arr['invite']          = null;
                $arr['sure']            = null;
            }
        }
        $this->load->view('header',$header);
        $this->load->view('Node/anomaly/index',$data);
        $this->load->view('footer');
    }


    public function add(){
        session_start();
        if(!isset($_SESSION['userid'])){
            header("Location:?/login/");
            exit();
        }
        $username               =$_SESSION['username'];
        $gid                    = $_SESSION['gid'];
        $header['gid']          = $gid;
        $header['username']     = $username;
        $header['title']        = "首页";
        $header['count']        = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        global $anomalyTypeArr;
        $phoneNum               = $_GET['id'];
        //$dwDate               = $_GET['dwDate'];
        $data['phoneNum']       = $phoneNum;
        // $data['dwDate']      = $dwDate;
        $data['anomalyTypeArr'] = $anomalyTypeArr;
        $data['users']          = $this->att_model->get_tab_all('users');
        $this->load->view('Node/anomaly_user/add', $data);
    }
}
