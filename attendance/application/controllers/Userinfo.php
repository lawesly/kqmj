<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");

class Userinfo extends CI_Controller {
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
        $header['title'] = "员工信息";
        // $total = $this->db->from('userinfo')->count_all_results();
        // $data['userinfo'] = $this->att_model->get_tab_all('userinfo');
        $sql = "select dwEnrollNumber, Name, cardNum, phoneNum, depname, Privilege 
                from userinfo where phoneNum <> 'NULL' and phoneNum <> 'None' 
                group by phoneNum, dwEnrollNumber, Name, cardNum, depname, Privilege ";
        $data['userinfo'] = $this->att_model->get_tab_diy($sql);
        $Privilege_list = array(0=>'普通用户',3=>'管理员');
        foreach ($data['userinfo'] as &$userinfo_arr){
            $userinfo_arr['Privilege'] = $Privilege_list[$userinfo_arr['Privilege']];
        }
        $footer['myjs'] = 'userinfo_index.js';
        $header['count'] = 0;
        $this->load->view('header',$header);
        $this->load->view('Node/userinfo/userinfo', $data);
        $this->load->view('footer', $footer);
    }


    public function update(){
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
        $header['title'] = "员工信息";

        $url = "http://172.16.150.10:5000/update_userinfo/";
        $post_data = array(
                'key' => 'fccs2017',
                );
        $postdata = http_build_query($post_data);
        $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type:application/x-www-form-urlencoded',
                    'content' => $postdata,
                    'timeout' => 15 * 60 // 超时时间（单位:s）  
                    )
                );
        $context = stream_context_create($options);
        file_get_contents($url, false, $context);
        echo "<script>window.location='/?/userinfo/'</script>";
#redirect('http://kqtest.fccs.cn/?/userinfo/', 'refresh');


    }

}
