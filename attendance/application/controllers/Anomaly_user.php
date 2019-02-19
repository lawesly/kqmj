<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Anomaly_user extends CI_Controller {
    /**
     * 异常管理员工模块
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
        $header['title'] = "异常管理";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        if(isset($_GET["display"])){
            $line = $_GET["display"];
        }else{
            $line = 50;
        }
        $sql = "select * from anomaly where phoneNum='$username'";
        if($line == 0){
        } else {
            $sql = "$sql order by dwdate desc limit $line";
        }
        $data['anomaly'] = $this->att_model->get_tab_diy($sql);
        global $anomalyTypeArr;
        global $ackArr;
        foreach($data['anomaly'] as &$arr){
            $arr['isupdate'] = 0;
            $arr['type'] = $anomalyTypeArr[$arr['type']];
            $isack1 = $arr['isack'];
            if($isack1 != 0){
                $arr['isupdate'] = 1;
            }
            $arr['isack'] = $ackArr[$isack1];
            $arr['durtime'] = $arr['stime']."--".$arr['etime'];
            $anoID = $arr['id'];
            $isinvite = $this->att_model->get_tab_diy("select * from notice where anoID='$anoID'");
            if(isset($isinvite)){
                $inviteAll = array();
                $sureAll = array();
                foreach($isinvite as $line){
                    $invite = $line['username'];
                    $sure = $line['isack'];
                    if($sure == 1){
                        $arr['isupdate'] = 1;
                    }
                    $isinvitedel = $this->att_model->get_tab_one("users",'username',$invite);
                    if($isinvitedel){
                        $invite = $isinvitedel->realname;
                    }
                    $sure = $ackArr[$sure];
                    $inviteAll[] = $invite;
                    $sureAll[] = $sure;
                }
                $arr['invite'] = implode('/',$inviteAll);
                $arr['sure'] = implode('/',$sureAll);

            }else{
                $arr['invite'] = null;
                $arr['sure'] = null;
            }
        }

        $footer['myjs'] = "anomaly_user_index.js";
        $this->load->view('header_user',$header);
        $this->load->view('Node/anomaly_user/index',$data);
        $this->load->view('footer_user', $footer);

    }


    public function add(){
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
        $header['title'] = "异常管理";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        global $anomalyTypeArr;
        global $sumTimeArr;
        // $phoneNum = $_GET['id'];
        $phoneNum = $username;
        if(isset($_GET["date"])) {
            $dwDate = $_GET['date'];
            $dwDate = substr($dwDate,4,2)."/".substr($dwDate,6,2)."/".substr($dwDate,0,4);
        } else {
            $dwDate = "";
        }
        $data['phoneNum'] = $phoneNum;
        $data['dwDate'] = $dwDate;
        $data['anomalyTypeArr'] = $anomalyTypeArr;
        $data['year'] = date("Y");
        $data['month'] = date("m") - 1;
        $data['lastmonth'] = $data['month'] - 1;
        $data['tdate'] = date("d") - 1 + 1;
        $data['users'] = $this->att_model->get_tab_diy("select * from users where (groupid=3 or username='ch') and (status=1 or status=2)");
        $data['sumTimeArr'] = $sumTimeArr;
        $footer['myjs'] = "anomaly_user_add.js";
        $this->load->view('header_user',$header);
        $this->load->view('Node/anomaly_user/add', $data);
        $this->load->view('footer_user', $footer);
    }


    public function add_cfm(){
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
        $header['title'] = "异常管理";
        $phoneNum = $_POST['phoneNum'];
        $phoneNum_user = $this->att_model->get_tab_one('users','username',$username)->phoneNum;
        if($phoneNum != $phoneNum_user){
            header("Location:?/login/");
        }
        if($_POST['dwDate'] == "" or $_POST['reason'] == "" or $_POST['invite'] == ""){
            echo '0';
        }else{
            $dwDates = $_POST['dwDate'];
            $dwDate_arr = explode(", ",$dwDates);
            $type = $_POST['type'];
            $type_sub = $_POST['type_sub'];
            $reason = $_POST['reason'];
            $addtime = date('Y-m-d H:i:s',time());
            $stime = $_POST['stime'];
            $stime = formatTime($stime);
            $etime = $_POST['etime'];
            $etime = formatTime($etime);
            $sumtime = $_POST['sumtime'];
            $Name = $this->att_model->get_tab_one('users','phoneNum',$phoneNum)->realname;
            foreach($dwDate_arr as $dwDate){
                $dwDateArr = explode('/',$dwDate);
                $dwDate = $dwDateArr[2].$dwDateArr[0].$dwDateArr[1];
                $sql = "insert into anomaly(dwDate,Name,phoneNum,reason,type,stime,etime,addtime,type_sub,sumtime) values('$dwDate','$Name','$phoneNum','$reason',$type,'$stime','$etime','$addtime','$type_sub','$sumtime')";
                $this->db->query($sql);
                if(isset($_POST['invite'])){
                    $inviteArr = $_POST['invite'];
                    $anoID = $this->db->insert_id();
                    if(!empty($inviteArr)){
                        foreach($inviteArr as $invite){
                            $sql = "insert into notice(anoID,username) values($anoID,'$invite')";
                            $this->db->query($sql);
                            $entry = $this->att_model->get_tab_one('users','username',$invite);
                            if($entry->mail){
                                $mail = $entry->mail;
                                $title = "异常申请";
                                $content = $Name."提出异常申请,请登录http://kq.fccs.cn";
                                $script = "/usr/local/bin/mail.sh $mail $title $content &";
                                pclose(popen($script, 'r'));
                                //exec($script);
                            }
                        }
                    }
                }
            }
            echo "1";
        }

    }


    public function update(){
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
        $header['title'] = "异常管理";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        global $anomalyTypeArr;
        global $anomalyTypesubArr;
        global $sumTimeArr;
        $anoID = $_GET['id'];
        $data['anoid'] = $anoID;
        $entry = $this->att_model->get_tab_one("anomaly",'id',$anoID);
        $dwDate = $entry->dwDate;
        $data['dwDate'] = substr($dwDate,4,2)."/".substr($dwDate,6,2)."/".substr($dwDate,0,4);
        $type = $entry->type;
        $data['type'] = $type;
        $data['type_sub_arr'] = $anomalyTypesubArr[$type];
        $data['type_sub'] = $entry->type_sub;
        $data['stime'] = $entry->stime;
        $data['etime'] = $entry->etime;
        $data['reason'] = $entry->reason;
        $data['sumtime'] = $entry->sumtime;
        $notice	= $this->att_model->get_tab_diy("select username from notice where anoID=$anoID");
        $notice_arr = array();
        foreach($notice as $arr){
            $notice_arr[] = $arr['username'];
        }	
        $data['notice_arr'] = $notice_arr;
        $data['phoneNum'] = $username;
        $data['anomalyTypeArr'] = $anomalyTypeArr;
        $data['year'] = date("Y");
        $data['month'] = date("m") - 1;
        $data['lastmonth'] = $data['month'] - 1;
        $data['tdate'] = date("d") - 1 + 1;
        $data['users'] = $this->att_model->get_tab_diy("select * from users where (groupid=3 or username='ch') and (status=1 or status=2)");
        $data['sumTimeArr'] = $sumTimeArr;
        $footer['myjs'] = "anomaly_user_update.js";
        $this->load->view('header_user',$header);
        $this->load->view('Node/anomaly_user/update', $data);
        $this->load->view('footer_user', $footer);

    }


    public function update_cfm(){
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
        $header['title'] = "异常管理";
        $phoneNum = $_POST['phoneNum'];
        $anoid = $_POST['anoid'];
        $phoneNum_user = $this->att_model->get_tab_one('users','username',$username)->phoneNum;
        if($phoneNum != $phoneNum_user){
            header("Location:?/login/");
        }
        $isack = $this->att_model->get_tab_diy("select * from anomaly where id=$anoid and isack=1");
        $issure = $this->att_model->get_tab_diy("select * from notice where anoID=$anoid and isack=1");
        if($isack or $issure){
            echo '0';
        }else if($_POST['dwDate'] == "" or $_POST['reason'] == "" or $_POST['invite'] == ""){
            echo '0';
        }else{
            $dwDates = $_POST['dwDate'];
            $dwDate_arr = explode(", ",$dwDates);
            $type = $_POST['type'];
            $type_sub = $_POST['type_sub'];
            $reason = $_POST['reason'];
            $addtime = date('Y-m-d H:i:s',time());
            $stime = $_POST['stime'];
            $stime = formatTime($stime);
            $etime = $_POST['etime'];
            $etime = formatTime($etime);
            $sumtime = $_POST['sumtime'];
            $Name = $this->att_model->get_tab_one('users','phoneNum',$phoneNum)->realname;
            foreach($dwDate_arr as $dwDate){
                $dwDateArr = explode('/',$dwDate);
                $dwDate = $dwDateArr[2].$dwDateArr[0].$dwDateArr[1];
                $sql = "insert into anomaly(dwDate,Name,phoneNum,reason,type,stime,etime,addtime,type_sub,sumtime) values('$dwDate','$Name','$phoneNum','$reason',$type,'$stime','$etime','$addtime','$type_sub','$sumtime')";
                $this->db->query($sql);
                if(isset($_POST['invite'])){
                    $inviteArr = $_POST['invite'];
                    $anoID = $this->db->insert_id();
                    if(!empty($inviteArr)){
                        foreach($inviteArr as $invite){
                            $sql = "insert into notice(anoID,username) values($anoID,'$invite')";
                            $this->db->query($sql);
                            $entry = $this->att_model->get_tab_one('users','username',$invite);
                            if($entry->mail){
                                $mail = $entry->mail;
                                $title = "异常申请";
                                $content = $Name."提出异常申请,请登录http://kq.fccs.cn";
                                $script = "/usr/local/bin/mail.sh $mail $title $content &";
                                pclose(popen($script, 'r'));
                                //exec($script);
                            }
                        }
                    }
                }
            }
            $sql_del1 = "delete from anomaly where id=$anoid";
            $sql_del2 = "delete from notice where anoID=$anoid";
            $this->db->query($sql_del1);
            $this->db->query($sql_del2);
            echo "1";
        }

    }

    public function del(){
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
        $header['title'] = "异常管理";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $id = $_GET['id'];
        $isack = $this->att_model->get_tab_one("anomaly",'id',$id)->isack;
        if($isack == 0){
            $sql_del = "delete from anomaly where id=$id";
            $this->db->query($sql_del);
            $sql_del_notice = "delete from notice where anoID=$id";	
            $this->db->query($sql_del_notice);
            echo '1';
        }else{
            echo '0';
        }
    }

}
