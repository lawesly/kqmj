<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    /**
     * 首页模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }

    private function open_gate($doorID){
        /*
         * 开门程序
         */
        $command = "python /www/attendance/application/controllers/inc/open_gate.py $doorID";
        exec ($command,$retval,$stats);
    }


    public function index()
    {
        /**
         * 首页
         */
        if(empty($this->session->has_userdata('username'))){
            if((empty($_COOKIE['username']) || empty($_COOKIE['password']))){
                header("Location:?/login/");
                exit();
            }else{
                $username = $_COOKIE['username'];
                $passwd = $_COOKIE['password'];
                $isuser = $this->att_model->get_tab_diy("select * from users where username='$username' and passwd='$passwd'");
                if($isuser){
                    $arraydata = array(
                        'userid'  => $isuser[0]['userid'],
                        'username'     => $username,
                        'gid' => $isuser[0]['groupid'],
                        'change' => $isuser[0]['groupid']
                    );
                    $this->session->set_userdata($arraydata);
                    $userid = $isuser[0]['userid'];
                    $gid = $isuser[0]['groupid'];
                    $logintime = date("Y-m-d H:i:s",time());
                    $sql_login = "insert into login(userid,logintime) values('$userid','$logintime')";
                    $this->db->query($sql_login);
                }else{
                    header("Location:?/login/");
                }
            }
        }else{
            $username = $this->session->username;
            $gid = $this->session->gid;
        }

        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "首页";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $data['username'] = $username;
        $footer['myjs'] = 'main.js';
        if($gid == 3){
            $this->load->view('header_user',$header);
            $this->load->view('main',$data);
            $this->load->view('footer_user', $footer);
        }else{
            $this->load->view('header',$header);
            $this->load->view('main', $data);
            $this->load->view('footer', $footer);
        }

    }

    public function open(){
        /**
         * 懒人开门
         */
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        if(isset($_GET['id'])){
            $doorID = $_GET['id'];
            $this->open_gate($doorID);
        }
        echo "<script>window.location='/?/main/'</script>";

    }

}
