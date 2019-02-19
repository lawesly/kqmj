<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require("inc/encrypt.php");
require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');


class Users extends CI_Controller {
    /**
     * 用户管理
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
        $header['title'] = "用户管理";

        $data['users'] = $this->att_model->get_tab_all('users');
        global $groupArr;
        global $statusArr;
        foreach ($data['users'] as &$arr){
            $groupid = $arr['groupid'];
            $groupname = $groupArr[$groupid];
            //$arr['groupname'] = getGroupname($groupid);
            $arr['groupname'] = $groupname;
            $arr['status'] = $statusArr[$arr['status']];
            $uid = $arr['userid'];
            $lastlogin = $this->db->query("select logintime from login where userid='$uid' order by id desc limit 1")->result_array();
            if($lastlogin){
                $lastlogin = $lastlogin[0]['logintime'];
            }else{
                $lastlogin = "";
            }
            $arr['lastlogin'] = $lastlogin;
        }

        $footer['myjs'] = "users_index.js";
        $this->load->view('header',$header);
        $this->load->view('Node/users/users', $data);
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
        $header['title'] = "用户管理";
        global $groupArr;
        $data['groupArr'] = $groupArr;
        $footer['myjs'] = "users_add.js";
        $this->load->view('header',$header);
        $this->load->view('Node/users/users_add',$data);
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
        $username = $_POST['username'];
        $pass = $_POST['passwd'];
        $passwd = encrypt($pass, 'E', 'nowamagic');
        $phoneNum = $_POST['phoneNum'];
        $realname = $_POST['realname'];
        $groupid = $_POST['groupid'];
        $status = $_POST['status'];
        $mail = $_POST['mail'];
        $carlicense = $_POST['carlicense'];
        $ischongfu = $this->att_model->get_tab_one('users','username',$username);
        $ischongfu_1 = $this->att_model->get_tab_one('users','phoneNum',$phoneNum);
        if(isset($ischongfu)){
            echo "0";
        }elseif(isset($ischongfu_1)){
            echo "2";
        }else{
            $sql_insert = "insert into users(username,passwd,groupid,status,realname,phoneNum,mail,carlicense) values('$username','$passwd',$groupid,$status,'$realname','$phoneNum','$mail','$carlicense')";
            $this->db->query($sql_insert);
            echo "1";
        }
    }


    public function update()
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
        global $groupArr;
        $data['groupArr'] = $groupArr;
        $userid = $_GET['id'];
        $result = $this->att_model->get_tab_one('users','userid',$userid);
        $username = $result->username;
        // $passwd = $result->passwd;
        $phoneNum = $result->phoneNum;
        $realname = $result->realname;
        $status = $result->status;
        $groupid = $result->groupid;
        $mail = $result->mail;
        $carlicense = $result->carlicense;
        $data['uid'] = $userid;
        $data['username'] = $username;
        $data['phoneNum'] = $phoneNum;
        $data['realname'] = $realname;
        $data['status'] = $status;
        $data['groupid'] = $groupid;
        $data['mail'] = $mail;
        $data['carlicense'] = $carlicense;
        $header['title'] = "用户管理";
        $footer['myjs'] = "users_update.js";
        $this->load->view('header',$header);
        $this->load->view('Node/users/users_update', $data);
        $this->load->view('footer', $footer);

    }


    public function update_cfm()
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
        $userid = $_GET['id'];
        $entry = $this->att_model->get_tab_one('users','userid',$userid);
        $oldusername = $entry->username;
        $oldpasswd_enc = $entry->passwd;
        $oldpasswd = encrypt($oldpasswd_enc,'D','nowamagic');
        $oldphoneNum = $entry->phoneNum;
        $oldrealname = $entry->realname;
        $oldgroupid = $entry->groupid;
        $oldstatus = $entry->status;
        $oldmail = $entry->mail;
        $oldcarlicense = $entry->carlicense;
        $newusername = $_POST['username'];
        $newpasswd = $_POST['passwd'];
        $newphoneNum = $_POST['phoneNum'];
        $newrealname = $_POST['realname'];
        $newgroupid = $_POST['groupid'];
        $newstatus = $_POST['status'];
        $newmail = $_POST['mail'];
        $newcarlicense = $_POST['carlicense'];
        $ischongfu = $this->db->query("select userid from users where username='$newusername' and username<>'$oldusername'")->result_array();
        $ischongfu_1 = $this->db->query("select userid from users where phoneNum='$newphoneNum' and phoneNum<>'$oldphoneNum'")->result_array();
        if(!empty($ischongfu)){
            echo "0";
        }elseif(!empty($ischongfu_1)){
            echo "2";
        }else{
            if($newusername != $oldusername){
                $sql_update = "update users set username='$newusername' where userid='$userid'";
                $this->db->query($sql_update);
            }
            if($newpasswd != '111111' and $newpasswd != $oldpasswd){
                $newpasswd = encrypt($newpasswd, 'E', 'nowamagic');
                $this->db->query("update users set passwd='$newpasswd' where userid='$userid'");
            }
            if($newphoneNum != $oldphoneNum){
                $this->db->query("update users set phoneNum='$newphoneNum' where userid='$userid'");
            }
            if($newrealname != $oldrealname){
                $this->db->query("update users set realname='$newrealname' where userid='$userid'");
            }
            if($newgroupid != $oldgroupid){
                $this->db->query("update users set groupid='$newgroupid' where userid='$userid'");
            }
            if($newstatus != $oldstatus){
                $this->db->query("update users set status='$newstatus' where userid='$userid'");
            }
            if($newmail != $oldmail){
                $this->db->query("update users set mail='$newmail' where userid='$userid'");
            }
            if($newcarlicense != $oldcarlicense){
                $this->db->query("update users set carlicense='$newcarlicense' where userid='$userid'");
            }

            echo "1";
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
        $userid = $_GET['id'];
        $sql_del = "delete  from  users where userid='$userid'";
        $this->db->query($sql_del);
        echo 1;

    }


    public function export(){
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        require_once "phpexcel/Classes/PHPExcel.php";
        require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
        require_once 'phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
        require_once 'phpexcel/Classes/PHPExcel/Writer/Excel5.php';
        // 生成新的excel对象
        $objExcel = new PHPExcel();
        // 设置excel文档的属性
        $objProps=$objExcel->getProperties();
        $objProps->setCreator("fccs");
        $objProps->setLastModifiedBy("fccs");
        $objProps->setTitle("用户表");
        $objProps->setSubject("用户表");
        $objProps->setDescription("用户表");
        $objProps->setKeywords("用户");
        $objProps->setCategory("用户表");
        // 开始操作excel表
        // 操作第一个工作表
        $objExcel->setActiveSheetIndex(0);
        // 设置工作薄名称
        $objActSheet=$objExcel->getActiveSheet();
        $objActSheet->setTitle('用户表');
        // 设置默认字体和大小
        $objExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        //设置居中
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        $objActSheet->setCellValue('A1', '用户ID');
        $objActSheet->setCellValue('B1', '用户名');
        $objActSheet->setCellValue('C1', '所属组');
        $objActSheet->setCellValue('D1', '手机号码');
        $objActSheet->setCellValue('E1', '真实姓名');
        $objActSheet->setCellValue('F1', '邮箱');
        $objActSheet->setCellValue('G1', '车牌');
        $objActSheet->setCellValue('H1', '状态');
        $objActSheet->setCellValue('I1', '最后登录时间');
        $data['users'] = $this->att_model->get_tab_all('users');
        global $groupArr;
        global $statusArr;
        $i = 1;
        foreach ($data['users'] as &$arr){
            $i = $i + 1;
            $groupid = $arr['groupid'];
            $groupname = $groupArr[$groupid];
            //$arr['groupname'] = getGroupname($groupid);
            $arr['groupname'] = $groupname;
            $arr['status'] = $statusArr[$arr['status']];
            $uid = $arr['userid'];
            $lastlogin = $this->db->query("select logintime from login where userid='$uid' order by id desc limit 1")->result_array();
            if($lastlogin){
                $lastlogin = $lastlogin[0]['logintime'];
            }else{
                $lastlogin = "";
            }
            $arr['lastlogin'] = $lastlogin;
            $objActSheet->setCellValue("A$i", $arr['userid']);
            $objActSheet->setCellValue("B$i", $arr['username']);
            $objActSheet->setCellValue("C$i", $groupname);
            $objActSheet->setCellValue("D$i", $arr['phoneNum']);
            $objActSheet->setCellValue("E$i", $arr['realname']);
            $objActSheet->setCellValue("F$i", $arr['mail']);
            $objActSheet->setCellValue("G$i", $arr['carlicense']);
            $objActSheet->setCellValue("H$i", $arr['status']);
            $objActSheet->setCellValue("I$i", $lastlogin);
        }
        $filename="export-users.xls";
        // 从浏览器直接输出$filename
        if(ob_get_contents()){
            ob_end_clean();
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=$filename");
        $objWriter->save("php://output");

    }
}
