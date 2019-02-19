<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require("inc/encrypt.php");

class Carlicense extends CI_Controller {

    /*
     * 车牌号码模块
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
        $username = $this->session->username;
        $gid = $this->session->gid;
        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "添加车牌";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $data['username'] = $username;
        $clEntry = $this->att_model->get_tab_one('users','username',$username);
        if($clEntry){
            $data['carlicense'] = $clEntry->carlicense;
        }else{
            $data['carlicense'] = "";
        }
        $footer['myjs'] = 'carlicense.js';
        if($gid == 3){
            $this->load->view('header_user', $header);
            $this->load->view('Node/carlicense/index', $data);
            $this->load->view('footer_user', $footer);
        }else{
            $this->load->view('header', $header);
            $this->load->view('Node/carlicense/index', $data);
            $this->load->view('footer', $footer);

        }
    }

    
    public function cfm()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "添加车牌";

        $carlicense = $_POST['carlicense'];
        if($carlicense != ""){
            $sql_update = "update users set carlicense='$carlicense' where username='$username'";
            $this->db->query($sql_update);
        }
        echo "1";

    }


}
