<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");

class Notice_user extends CI_Controller {
    /**
     * Notice_user constructor.
     * 异常通知
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
        $header['title'] = "异常通知";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $sure = 0;
        if(isset($_GET['display'])){
            $sure = $_GET['display'];
        }
        $data['display'] = $sure;
        $notice = $this->att_model->get_tab_diy("select * from notice where username='$username' and isack=$sure order by anoID desc");
        //$isread_arr = array(0=>'未读',1=>'已读');
        $isack_arr = array(0=>'未确认',1=>'已确认');
        global $anomalyTypeArr;
        global $ackArr;
        foreach($notice as &$arr){
            //	$isread = $arr['isread'];
            //	$arr['isread'] = $isread_arr[$isread];
            $isack = $arr['isack'];
            $arr['isack1'] = $isack_arr[$isack];
            $anoID = $arr['anoID'];
            $data['anoID'] = $anoID;
            $entry = $this->att_model->get_tab_one('anomaly','id',$anoID);
            $arr['dwDate'] = $entry->dwDate;
            $arr['Name'] = $entry->Name;
            $arr['phoneNum'] = $entry->phoneNum;
            $arr['type'] = $entry->type;
            $arr['type'] = $anomalyTypeArr[$arr['type']];
            $arr['type_sub'] = $entry->type_sub;
            $arr['reason'] = $entry->reason;
            $arr['sumtime'] = $entry->sumtime;
            $stime = $entry->stime;
            $etime = $entry->etime;
            $arr['durtime'] = $stime."--".$etime;
            $isinvite = $this->att_model->get_tab_diy("select * from notice where anoID='$anoID'");
            if(isset($isinvite)){
                $inviteAll = array();
                $sureAll = array();
                foreach($isinvite as $line){
                    $invite = $line['username'];
                    $sure = $line['isack'];
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
            $phoneNum = $entry->phoneNum;
            $dwDate = $entry->dwDate;
            $atts = $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate' order by dwTime asc");
            $onwork = null;
            $offwork = null;
            foreach($atts as $att){
                if($onwork == null and $att['dwTime'] < '17:00:00'){
                    $onwork = $att['dwTime'];
                }else{
                    $offwork = $att['dwTime'];
                }
            }
            $arr['onwork']	= $onwork;
            $arr['offwork'] = $offwork;


        }
        $data['notice'] = $notice;
        $data['sure'] = 0;
        $footer['myjs'] = 'notice_user_index.js';
        if($username == 'ch'){
            $this->load->view('header', $header);
            $this->load->view('Node/notice_user/index', $data);
            $this->load->view('footer', $footer);
        }else{

            $this->load->view('header_user', $header);
            $this->load->view('Node/notice_user/index', $data);
            $this->load->view('footer_user', $footer);
        }

    }
    public function cfm()
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
        $ids = $_POST['ids'];
        $idsArr = explode(',',$ids);
        $action = $_POST['action'];
        foreach($idsArr as $id){
            if($id != '0'){
                $id = substr($id,0,-1);
                $issafe = $this->att_model->get_tab_diy("select id from notice where id=$id and username='$username'");
                if($issafe){
                    if($action == 'sure'){
                        $sql = "update notice set isack=1 where id=$id";
                    }else{
                        $sql = "update notice set isack=0 where id=$id";
                    }
                    $this->db->query($sql);
                }else{
                    echo '0';
                    exit;
                }
            }
        }
        echo '1';

    }

    public function zkshow()
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
        $header['title'] = "考勤管理";
        $phoneNum = $_GET['phoneNum'];
        $dwDate = $_GET['dwDate'];
        $data['zk']     = $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate' order by dwTime asc");
        $this->load->view('Node/attendance_user/zkshow', $data);

    }

    public function mjshow()
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
        $header['title'] = "考勤管理";
        $phoneNum = $_GET['phoneNum'];
        $swipedate = $_GET['swipedate'];
        $data['menjin'] = $this->att_model->get_tab_diy("select * from menjin where phoneNum='$phoneNum' and swipeDate='$swipedate' order by swipeTime");
        $actionArr = array('01'=>'进门','02'=>'出门');
        $reasonArr = array('01'=>'打开','06'=>'无权限','0f'=>'卡过期或不在有效时间');
        foreach($data['menjin'] as &$arr){
            if(isset($actionArr[$arr['action']])){
                $arr['action'] = $actionArr[$arr['action']];
            }
            if(isset($reasonArr[$arr['reasonNo']])){
                $arr['reasonNo'] = $reasonArr[$arr['reasonNo']];
            }
        }
        $this->load->view('Node/attendance/mjshow', $data);

    }


}
