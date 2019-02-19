<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Attendance_user extends CI_Controller {
    /**
     * 考勤管理员工版模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }


    private function CheckAnomaly($dwDate,$onwork=null,$offwork=null){
        $iswr = $this->att_model->get_tab_one("wr",'dwDate',$dwDate);
        $res = array();
        if($iswr){
            if($iswr->type == 1){
                $res['type'] = "休息日";
                $res['check'] = 1;
                return $res;
            }else{
                $res['type'] = "工作日";
                if($onwork == null or $offwork == null){
                    $res['check'] = 0;
                    return $res;
                }else{
                    $stime = $iswr->stime;
                    $etime = $iswr->etime;
                    $year = substr($dwDate,0,4);
                    $month = substr($dwDate,4,2);
                    $day = substr($dwDate,6,2);
                    $stime_arr = explode(':',$stime);
                    $etime_arr = explode(':',$etime);
                    $onwork_arr = explode(':',$onwork);
                    $offwork_arr = explode(':',$offwork);
                    $stime = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");	//标准上班时间
                    $etime = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");	//标准下班时间
                    $onwork = mktime("$onwork_arr[0]","$onwork_arr[1]","0","$month","$day","$year");	//上班时间
                    $offwork = mktime("$offwork_arr[0]","$offwork_arr[1]","0","$month","$day","$year");	//下班时间
                    $worktime = $offwork - $onwork;	//工作时间
                    $stime_tx = $iswr->stime_tx;	//上班是否弹性
                    $etime_tx = $iswr->etime_tx;	//下班是否弹性
                    if($stime_tx == 0 and $stime == 0){
                        $stworktime = $etime - $stime; //标准工作时间
                        $stime_late = $stime + 1800;
                    }elseif($stime_tx == 0){
                        $stworktime = $etime - $stime - 1800;
                        $stime_late = $stime + 1800;

                    }else{
                        $stworktime = $etime - $stime;
                        $stime_late = $stime;
                    }
                    if($onwork > $stime_late or $offwork < $etime or $worktime < $stworktime){
                        $res['check'] = 0;
                        return $res;
                    }else{
                        $res['check'] = 1;
                        return $res;
                    }
                }

            }
        }else{
            $week = date('w',strtotime($dwDate));
            if($week == 0 or $week == 6){
                $res['type'] = "休息日";
                $res['check'] = 1;
                return $res;
            }else{
                $res['type'] = "工作日";
                if($onwork == null or $offwork == null){
                    $res['check'] = 0;
                    return $res;
                }else{
                    $year = substr($dwDate,0,4);
                    $month = substr($dwDate,4,2);
                    $day = substr($dwDate,6,2);
                    $stime = "08:30:00";
                    $summer_arr = array('05','06','07','08','09');
                    if(in_array($month,$summer_arr)){
                        $etime = "17:30:00";
                    }else{
                        $etime = "17:00:00";
                    }
                    if($week == 4){
                        $etime = "20:00:00";
                    }
                    $stime_arr = explode(':',$stime);
                    $etime_arr = explode(':',$etime);
                    $onwork_arr = explode(':',$onwork);
                    $offwork_arr = explode(':',$offwork);
                    $stime = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");   //标准上班时间
                    $etime = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");   //标准下班时间
                    $onwork = mktime("$onwork_arr[0]","$onwork_arr[1]","0","$month","$day","$year");   //上班时间
                    $offwork = mktime("$offwork_arr[0]","$offwork_arr[1]","0","$month","$day","$year");       //下班时间
                    $worktime = $offwork - $onwork; //工作时间
                    $stworktime = $etime - $stime; //标准工作时间
                    $stime_late = $stime + 1800;
                    if($onwork > $stime_late or $offwork < $etime or $worktime < $stworktime){
                        $res['check'] = 0;
                        return $res;
                    }else{
                        $res['check'] = 1;
                        return $res;
                    }
                }

            }
        }

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
        $header['title'] = "考勤管理";
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();
        $data['firstday'] = date('m/01/Y');
        $data['today'] = date('m/d/Y');
        $footer['myjs'] = "attendance_user_index.js";
        $this->load->view('header_user',$header);
        $this->load->view('Node/attendance_user/index',$data);
        $this->load->view('footer_user', $footer);
    }

    public function search()
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
        $header['count'] = $this->db->where('username', $username)->where('isack', 0)->from('notice')->count_all_results();

        $fromdate = $_POST['fromdate'];
        $todate = $_POST['todate'];
        if($fromdate == "" or $todate == ""){
            header("Location:?/attendance_user/");
            exit();
        }
        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;

        $fromdate_arr = explode("/",$fromdate);
        $fromdate = $fromdate_arr[2].$fromdate_arr[0].$fromdate_arr[1];
        $todate_arr = explode("/",$todate);
        $todate = $todate_arr[2].$todate_arr[0].$todate_arr[1];

        $phoneNum = $this->att_model->get_tab_one('users','username',$username)->phoneNum;

        $dwVerifyMode_list = array(0=>'密码验证',1=>'一楼指纹机',2=>'卡验证');
        $dwInOutMode_list = array(0=>'Check-In',1=>'Check-Out',2=>'Break-Out',3=>'Break-In',4=>'OT-In',5=>'OT-Out');
        $Date_List_a1=array(substr($fromdate,0,4),substr($fromdate,4,2),substr($fromdate,6,2));
        $Date_List_a2=array(substr($todate,0,4),substr($todate,4,2),substr($todate,6,2));
        $d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
        $d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
        $Days = round(($d2-$d1)/3600/24);
        $days_arr = array();
        $result_init = array();
        for($i=0;$i<=$Days;$i++){
            $redate = date("Ymd",strtotime("$fromdate   +$i   day"));
            $reweek_arr = array('日','一','二','三','四','五','六');
            $reweek = date('w',strtotime($redate));
            $days_arr[] = $redate;
            $result_init[$redate] = array(
                    'id'=>0,
                    'dwEnrollNumber'=>null,
                    'dwDate'=>$redate,
                    'Name'=>null,
                    'depname'=>null,
                    'stime'=>null,
                    'etime'=>null,
                    'week'=>$reweek_arr[$reweek],
                    'onwork'=>null,
                    'offwork'=>null,
                    'type'=>'工作日',
                    'phoneNum'=>null
                    );
        }
        //$Users = $this->att_model->get_tab_diy("select * from users where groupid=3");
        $Users = array(0=>array('phoneNum'=>$phoneNum));
        $ATT = array();
        foreach($Users as &$User){
            $phoneNum = $User['phoneNum'];
            $sql = "select * from attendance where phoneNum='$phoneNum' and dwDate >= '$fromdate' and dwDate <= '$todate'";
            $attendance = $this->att_model->get_tab_diy($sql);
            $result = $result_init;
            foreach ($attendance as &$arr){
                $rdate = $arr['dwDate'];

                $arr['dwVerifyMode'] = $dwVerifyMode_list[$arr['dwVerifyMode']];
                $arr['dwInOutMode'] = $dwInOutMode_list[$arr['dwInOutMode']];
                if($result[$rdate]['phoneNum'] == null){
                    $result[$rdate]['id'] = $arr['id'];
                    $result[$rdate]['dwEnrollNumber'] = $arr['dwEnrollNumber'];
                    //$result[$rdate]['dwDate'] = $arr['dwDate'];
                    $result[$rdate]['Name'] = $arr['Name'];
                    $result[$rdate]['depname'] = $arr['depname'];
                    $result[$rdate]['phoneNum'] = $arr['phoneNum'];
                    if($arr['dwTime'] >= '17:00:00'){
                        $result[$rdate]['offwork'] = $arr['dwVerifyMode'];
                        $result[$rdate]['etime'] = $arr['dwTime'];
                    }else{
                        $result[$rdate]['onwork'] = $arr['dwVerifyMode'];
                        $result[$rdate]['stime'] = $arr['dwTime'];
                    }
                }else{
                    $result[$rdate]['offwork'] = $arr['dwVerifyMode'];
                    $result[$rdate]['etime'] = $arr['dwTime'];
                }
            }
            //if($attendance){
            $attendance = $result;
            //}
            foreach($attendance as &$arr){
                if($arr['phoneNum'] == null){
                    $arr['phoneNum'] = $phoneNum;
                    $entry = $this->att_model->get_tab_diy("select Name,depname from attendance where phoneNum='$phoneNum'  limit 1");
                    if($entry){
                        $arr['Name'] = $entry[0]['Name'];
                        $arr['depname'] = $entry[0]['depname'];
                    }
                }
                /*
                 */
                $dwDate = $arr['dwDate'];
                $anomaly_arr = $this->CheckAnomaly($dwDate,$arr['stime'],$arr['etime']);
                $arr['check'] = $anomaly_arr['check'];
                $arr['type'] = $anomaly_arr['type'];
                $is_anomaly = $this->att_model->get_tab_diy("select * from anomaly where phoneNum='$phoneNum' and dwDate='$dwDate'");
                if($is_anomaly){
                    $arr['is_anomaly'] = 1;
                }else{
                    $arr['is_anomaly'] = 0;
                }
            }

            $ATT[$phoneNum] = $attendance;


        }
        $data['attendance'] = $ATT[$phoneNum];
        $data['today'] = date('Ymd');
        $footer['myjs'] = "attendance_user_search.js";


        $this->load->view('header_user',$header);
        $this->load->view('Node/attendance_user/search', $data);
        $this->load->view('footer_user', $footer);
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
        //		$name = $_GET['name'];
        //		$Name = urldecode($name);
        $phoneNum = $username;
        $swipedate = $_GET['swipedate'];
        $data['menjin']	= $this->att_model->get_tab_diy("select * from menjin where phoneNum='$phoneNum' and swipeDate='$swipedate' order by swipeTime");
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
        $this->load->view('Node/attendance_user/mjshow', $data);

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
        $phoneNum = $username;
        $dwDate = $_GET['dwDate'];
        $data['zk']	= $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate' order by dwTime asc");
        $this->load->view('Node/attendance_user/zkshow', $data);

    }


    public function anoshow()
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
        $phoneNum = $username;
        $dwDate = $_GET['dwDate'];
        $data['anomaly'] = $this->att_model->get_tab_diy("select * from anomaly where phoneNum='$phoneNum' and dwDate='$dwDate'");
        global $anomalyTypeArr;
        global $ackArr;
        foreach($data['anomaly'] as &$arr){
            $arr['type'] = $anomalyTypeArr[$arr['type']];
            $isack1 = $arr['isack'];
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
                    $invite = $this->att_model->get_tab_one("users",'username',$invite)->realname;
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
        $this->load->view('Node/attendance_user/anoshow', $data);
    }
}
