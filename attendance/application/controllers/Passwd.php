<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require("inc/encrypt.php");

class Passwd extends CI_Controller {

    /**
     * 修改密码模块
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
        $header['title'] = "修改密码";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $data['username'] = $username;
        $footer['myjs'] = "passwd.js";
        if($gid == 3){
            $this->load->view('header_user',$header);
            $this->load->view('Node/passwd/index', $data);
            $this->load->view('footer_user', $footer);
        }else{
            $this->load->view('header', $header);
            $this->load->view('Node/passwd/index', $data);
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
        $header['title'] = "修改密码";

        $passwd = $_POST['passwd'];
        $passwd_retype = $_POST['rpasswd'];
        // $entry = $this->att_model->get_tab_one('users', 'username', $username);
        if($passwd != $passwd_retype){
            echo "0";
        }elseif(strlen($passwd) <= 4){
            echo "2";
        }elseif($passwd == 'fccs2016'){
            echo "3";
        }else{
            $passwd_enc = encrypt($passwd, 'E', 'nowamagic');
            $sql_update = "update users set passwd='$passwd_enc' where username='$username'";
            $this->db->query($sql_update);
            $_SESSION['change'] = 0;
            echo "1";
        }

    }


}
