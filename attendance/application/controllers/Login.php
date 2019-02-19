<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require("inc/encrypt.php");
ini_set('date.timezone','Asia/Shanghai');

class Login extends CI_Controller {
    /**
     * 登录模块
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
        if(empty($this->session->has_userdata('username'))){
            if((empty($_COOKIE['username']) || empty($_COOKIE['password']))){
                $this->load->view('login');
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
                    header("Location:?/main/");
                }else{
                    $this->load->view('login');
                }
            }
        }else{
            header("Location:?/main/");
        }
    }

    public function cfm()
    {
        $username = $this->input->post('username');
        $passwd = $this->input->post('passwd');
        $userid_obj = $this->att_model->get_tab_one('users','username',$username);
        if($userid_obj){
            // 获取用户密码（解密）
            $userid = $userid_obj->userid;
            $groupid = $userid_obj->groupid;
            $token=$this->att_model->get_tab_one('users','userid',$userid)->passwd;
            $password=encrypt($token, 'D', 'nowamagic');
            if($passwd!=$password){
                echo "2";
            }else{
                $status = $this->att_model->get_tab_one('users','userid',$userid)->status;
                if($status == 1 or $status == 2){
                    if (!empty($this->input->post('remember'))) {
                        // 如果用户选择了记录登录状态, 就把用户名和加了密的密码放到cookie里面
                        setcookie('username', $username, time() + 3600 * 24 * 30);
                        setcookie('password', $token, time() + 3600 * 24 * 30);
                    }
                    $this->session->set_userdata('username', $username);
                    $this->session->set_userdata('userid', $userid);
                    $this->session->set_userdata('gid', $groupid);
                    $logintime = date("Y-m-d H:i:s",time());
                    $sql_login = "insert into login(userid,logintime) values('$userid','$logintime')";
                    $this->db->query($sql_login);
                    if($password == 'fccs2016'){
                        $this->session->set_userdata('change', 1);
                        echo "4";  // 登陆密码为初始密码
                    }else{
                        $this->session->set_userdata('change', 0);
                        echo "1";  // 正常登陆
                    }
                }else{
                    echo "3";  // 账户禁用
                }
            }
        }else{
            echo "0";  // 账户不存在
        }
    }


}
