<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require("inc/encrypt.php");

class Mail extends CI_Controller {

    /**
     * 邮箱设置模块
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
        $header['title'] = "绑定邮箱";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $data['username'] = $username;
        $mailEntry = $this->att_model->get_tab_one('users','username',$username);
        if($mailEntry){
            $data['mail'] = $mailEntry->mail;
        }else{
            $data['mail'] = "";
        }
        $footer['myjs'] = 'mail.js';
        if($gid == 3){
            $this->load->view('header_user', $header);
            $this->load->view('Node/mail/index', $data);
            $this->load->view('footer_user', $footer);
        }else{
            $this->load->view('header', $header);
            $this->load->view('Node/mail/index', $data);
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
        $header['title'] = "绑定邮箱";

        $mail = $_POST['mail'];
        if($mail != ""){
            $sql_update_mail = "update users set mail='$mail' where username='$username'";
            $this->db->query($sql_update_mail);
        }
        echo "1";

    }


}
