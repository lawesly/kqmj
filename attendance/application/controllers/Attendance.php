<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Attendance extends CI_Controller {
    /*
     * 考勤管理模块(管理员版)
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
        if($gid == 1 or $gid == 2){
            return 1;
        }else{
            return 0;
        }
    }


    private function CheckAnomaly($dwDate,$onwork=null,$offwork=null){
        /*判断异常函数*/
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
                    $stime = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");   //标准上班时间
                    $etime = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");   //标准下班时间
                    $onwork = mktime("$onwork_arr[0]","$onwork_arr[1]","0","$month","$day","$year");   //上班时间
                    $offwork = mktime("$offwork_arr[0]","$offwork_arr[1]","0","$month","$day","$year");       //下班时间
                    $worktime = $offwork - $onwork; //工作时间
                    $stime_tx = $iswr->stime_tx;    //上班是否弹性
                    // $etime_tx = $iswr->etime_tx;    //下班是否弹性
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
        $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");	
        }
        $header['gid'] = $gid;
        $header['username'] = $username;
        $header['title'] = "考勤管理";
        $data['firstday'] = date('m/01/Y');
        $data['today'] = date('m/d/Y');
        $footer['myjs'] = "attendance_index.js";
        $header['count'] = 0;
        $this->load->view('header', $header);
        $this->load->view('Node/attendance/index', $data);
        $this->load->view('footer', $footer);
    }


    public function search()
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
        $header['title'] = "考勤管理";


        $fromdate = $_POST['fromdate'];
        $todate = $_POST['todate'];

        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;
        if($fromdate == "" or $todate == ""){
            header("Location:?/attendance/");
            exit();
        }
        $fromdate_arr = explode("/",$fromdate);
        $fromdate = $fromdate_arr[2].$fromdate_arr[0].$fromdate_arr[1];
        $todate_arr = explode("/",$todate);
        $todate = $todate_arr[2].$todate_arr[0].$todate_arr[1];

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
        $Users = $this->att_model->get_tab_diy("select * from users where groupid=3 and (status=1 or status=2)");
        //$Users = array(0=>array('phoneNum'=>'15024368607'),1=>array('phoneNum'=>'15157345228'));
        $ATT = array();
        // $today = date("Ymd");
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
                    $is_userinfo = $this->att_model->get_tab_diy("select Name,depname from userinfo where phoneNum='$phoneNum'");
                    if($is_userinfo){
                        $arr['Name'] = $is_userinfo[0]['Name'];
                        $arr['depname'] = $is_userinfo[0]['depname'];
                    }else{
                        $is_user = $this->att_model->get_tab_diy("select realname from users where phoneNum='$phoneNum'");
                        if($is_user){
                            $arr['Name'] = $is_user[0]['realname'];
                        }
                    }
                }
                $dwDate = $arr['dwDate'];
                $anomaly_arr = $this->CheckAnomaly($dwDate,$arr['stime'],$arr['etime']);
                $arr['check'] = $anomaly_arr['check'];
                /*
                   $is_exist = $this->att_model->get_tab_diy("select * from anomaly_sta where phoneNum='$phoneNum' and dwDate='$dwDate'");
                   if($anomaly_arr['check'] == 0){
                   if($dwDate < $today){
                   if(!$is_exist){
                   $sql_add = "insert into anomaly_sta(dwDate,phoneNum) values('$dwDate','$phoneNum')";
                //			$this->db->query($sql_add);
                }
                }

                }else{
                if($dwDate < $today){
                if($is_exist){
                $sql_del = "delete from anomaly_sta where phoneNum='$phoneNum' and dwDate='$dwDate'";
                //		$this->db->query($sql_del);
                }
                }
                }
                 */
                $arr['type'] = $anomaly_arr['type'];

            }

            $ATT[$phoneNum] = $attendance;


        }
        $data['attendance'] = $ATT;
        //print_r($ATT);
        $footer['myjs'] = "attendance_search.js";
        $header['count'] = 0;
        $this->load->view('header',$header);
        $this->load->view('Node/attendance/search', $data);
        $this->load->view('footer', $footer);
    }


    public function mjshow()
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


    public function zkshow()
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
        $header['title'] = "考勤管理";
        $phoneNum = $_GET['phoneNum'];
        $dwDate = $_GET['dwDate'];
        $data['zk']     = $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate' order by dwTime asc");
        $this->load->view('Node/attendance/zkshow', $data);

    }


    public function export()
    {
        require_once("inc/SumTime.php");
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
        $header['title'] = "考勤管理";
        $fromdate=$_GET['from'];
        $todate = $_GET['to'];

        $fromdate_arr = explode("/",$fromdate);
        $fromdate = $fromdate_arr[2].$fromdate_arr[0].$fromdate_arr[1];
        $todate_arr = explode("/",$todate);
        $todate = $todate_arr[2].$todate_arr[0].$todate_arr[1];

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
        $Users = $this->att_model->get_tab_diy("select * from users where groupid=3 and (status=1 or status=2)");
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
            $attendance = $result;
            foreach($attendance as &$arr){
                if($arr['phoneNum'] == null){
                    $arr['phoneNum'] = $phoneNum;
                    $is_userinfo = $this->att_model->get_tab_diy("select Name,depname from userinfo where phoneNum='$phoneNum'");
                    if($is_userinfo){
                        $arr['Name'] = $is_userinfo[0]['Name'];
                        $arr['depname'] = $is_userinfo[0]['depname'];
                    }else{  
                        $is_user = $this->att_model->get_tab_diy("select realname from users where phoneNum='$phoneNum'");
                        if($is_user){
                            $arr['Name'] = $is_user[0]['realname'];
                        }
                    }
                }
                $dwDate = $arr['dwDate'];
                $anomaly_arr = $this->CheckAnomaly($dwDate,$arr['stime'],$arr['etime']);
                $arr['check'] = $anomaly_arr['check'];
                $arr['type'] = $anomaly_arr['type'];
                $anomalys = $this->att_model->get_tab_diy("select * from anomaly where phoneNum='$phoneNum' and dwDate='$dwDate'");
                if($anomalys){
                    $j = 1;
                    foreach($anomalys as $anomaly){
                        $key_type = "type$j";
                        $key_type_sub = "type_sub$j";
                        $key_reason = "reason$j";
                        $key_anotime = "anotime$j";
                        $key_time = "time$j";
                        $key_isack = "isack$j";
                        $a_stime = $anomaly['stime'];
                        $a_etime = $anomaly['etime'];
                        $anotime = $a_stime."--".$a_etime;
                        /*
                           $a_stime_arr = explode(':',$a_stime);
                           $a_etime_arr = explode(':',$a_etime);
                           $a_stime = mktime("$a_stime_arr[0]","$a_stime_arr[1]","00","01","01","2016");
                           $a_etime = mktime("$a_etime_arr[0]","$a_etime_arr[1]","00","01","01","2016");

                           $durtime = ($a_etime - $a_stime)/3600;
                           $month = substr($dwDate,4,2);
                           $summer_arr = array('05','06','07','08','09');
                           if(in_array($month,$summer_arr)){
                           if($anomaly['stime'] <= "12:00" and $anomaly['etime'] >="13:30"){
                           $durtime = $durtime - 1.5;
                           }
                           }else{
                           if($anomaly['stime'] <= "12:00" and $anomaly['etime'] >="13:00"){
                           $durtime = $durtime - 1;
                           }
                           }
                           if($anomaly['type'] == 2){
                           $durtime = SumTime($durtime,0);
                           }elseif($anomaly['type'] == 3){
                           $durtime = SumTime($durtime,1);
                           }
                         */
                        $durtime = $anomaly['sumtime'];
                        $arr[$key_type] = $anomaly['type'];
                        $arr[$key_type_sub] = $anomaly['type_sub'];
                        $arr[$key_reason] = $anomaly['reason'];
                        $arr[$key_anotime] = $anotime;
                        //$arr[$key_time] = round($durtime,2);
                        $arr[$key_time] = $durtime;
                        $arr[$key_isack] = $anomaly['isack'];
                        $j = $j + 1;
                    }
                }


            }
            $ATT[$phoneNum] = $attendance;
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
        $objProps->setTitle("考勤报表");
        $objProps->setSubject("考勤报表");
        $objProps->setDescription("考勤报表");
        $objProps->setKeywords("考勤");
        $objProps->setCategory("考勤报表");
        // 开始操作excel表
        // 操作第一个工作表
        $objExcel->setActiveSheetIndex(0);
        // 设置工作薄名称
        $objActSheet=$objExcel->getActiveSheet();
        $objActSheet->setTitle('考勤报表');
        // 设置默认字体和大小
        $objExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        //设置居中 
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        $objActSheet->setCellValue('A1', '手机号码');
        $objActSheet->setCellValue('B1', '姓名');
        $objActSheet->setCellValue('C1', '部门');
        $objActSheet->setCellValue('D1', '日期');
        $objActSheet->setCellValue('E1', '星期');
        $objActSheet->setCellValue('F1', '类型');
        $objActSheet->setCellValue('G1', '上班时间');
        $objActSheet->setCellValue('H1', '下班时间');
        $objActSheet->setCellValue('I1', '签到方式');
        $objActSheet->setCellValue('J1', '签退方式');
        $objActSheet->setCellValue('K1', '是否异常');
        $objActSheet->setCellValue('L1', '异常类型01');
        $objActSheet->setCellValue('M1', '异常说明01');
        $objActSheet->setCellValue('N1', '异常补充01');
        $objActSheet->setCellValue('O1', '异常时间01');
        $objActSheet->setCellValue('P1', '异常统计时间01');
        $objActSheet->setCellValue('Q1', '异常确认情况01');
        $objActSheet->setCellValue('R1', '异常类型02');
        $objActSheet->setCellValue('S1', '异常说明02');
        $objActSheet->setCellValue('T1', '异常补充02');
        $objActSheet->setCellValue('U1', '异常时间02');
        $objActSheet->setCellValue('V1', '异常统计时间02');
        $objActSheet->setCellValue('W1', '异常确认情况02');
        $objActSheet->setCellValue('X1', '异常类型03');
        $objActSheet->setCellValue('Y1', '异常说明03');
        $objActSheet->setCellValue('Z1', '异常补充03');
        $objActSheet->setCellValue('AA1', '异常时间03');
        $objActSheet->setCellValue('AB1', '异常统计时间03');
        $objActSheet->setCellValue('AC1', '异常确认情况03');
        $anomaly_type_arr = array(1=>"未打卡",2=>"请假",3=>"加班",4=>"公出");
        $isack_type_arr = array(0=>"未确认",1=>"已确认",2=>'已确认+标记');
        $i = 1;
        foreach($ATT as $att_arr){
            foreach ($att_arr as $key => &$arr){
                $i = $i + 1;
                $phoneNum = $arr['phoneNum'];
                $Name = $arr['Name'];
                $depname = $arr['depname'];
                $week = $arr['week'];
                $type = $arr['type'];
                $stime = $arr['stime'];
                $etime = $arr['etime'];
                $onwork = $arr['onwork'];
                $offwork = $arr['offwork'];
                if($arr['check'] == 0){
                    $checkAll = "异常";
                }else{
                    $checkAll = null;
                }
                $objActSheet->setCellValue("A$i", "$phoneNum");
                $objActSheet->setCellValue("B$i", "$Name");
                $objActSheet->setCellValue("C$i", "$depname");
                $objActSheet->setCellValue("D$i", "$key");
                $objActSheet->setCellValue("E$i", "$week");
                $objActSheet->setCellValue("F$i", "$type");
                $objActSheet->setCellValue("G$i", "$stime");
                $objActSheet->setCellValue("H$i", "$etime");
                $objActSheet->setCellValue("I$i", "$onwork");
                $objActSheet->setCellValue("J$i", "$offwork");
                $objActSheet->setCellValue("K$i", "$checkAll");
                if(isset($arr['type1'])){
                    $type1 = $arr['type1'];
                    $type1 = $anomaly_type_arr[$type1];
                    $objActSheet->setCellValue("L$i", "$type1");
                }
                if(isset($arr['type_sub1'])){
                    $type_sub1 = $arr['type_sub1'];
                    $objActSheet->setCellValue("M$i", "$type_sub1");
                }
                if(isset($arr['reason1'])){
                    $reason1 = $arr['reason1'];
                    $objActSheet->setCellValue("N$i", "$reason1");
                }
                if(isset($arr['anotime1'])){
                    $anotime1 = $arr['anotime1'];
                    $objActSheet->setCellValue("O$i", "$anotime1");
                }
                if(isset($arr['time1'])){
                    $time1 = $arr['time1'];
                    $objActSheet->setCellValue("P$i", "$time1");
                }
                if(isset($arr['isack1'])){
                    $isack1 = $arr['isack1'];
                    $isack1 = $isack_type_arr[$isack1];
                    $objActSheet->setCellValue("Q$i", "$isack1");
                }

                if(isset($arr['type2'])){
                    $type2 = $arr['type2'];
                    $type2 = $anomaly_type_arr[$type2];
                    $objActSheet->setCellValue("R$i", "$type2");
                }
                if(isset($arr['type_sub2'])){
                    $type_sub2 = $arr['type_sub2'];
                    $objActSheet->setCellValue("S$i", "$type_sub2");
                }
                if(isset($arr['reason2'])){
                    $reason2 = $arr['reason2'];
                    $objActSheet->setCellValue("T$i", "$reason2");
                }
                if(isset($arr['anotime2'])){
                    $anotime2 = $arr['anotime2'];
                    $objActSheet->setCellValue("U$i", "$anotime2");
                }
                if(isset($arr['time2'])){
                    $time2 = $arr['time2'];
                    $objActSheet->setCellValue("V$i", "$time2");
                }
                if(isset($arr['isack2'])){
                    $isack2 = $arr['isack2'];
                    $isack2 = $isack_type_arr[$isack2];
                    $objActSheet->setCellValue("W$i", "$isack2");
                }
                if(isset($arr['type3'])){
                    $type3 = $arr['type3'];
                    $type3 = $anomaly_type_arr[$type3];
                    $objActSheet->setCellValue("X$i", "$type3");
                }
                if(isset($arr['type_sub3'])){
                    $type_sub3 = $arr['type_sub3'];
                    $objActSheet->setCellValue("Y$i", "$type_sub3");
                }
                if(isset($arr['reason3'])){
                    $reason3 = $arr['reason3'];
                    $objActSheet->setCellValue("Z$i", "$reason3");
                }
                if(isset($arr['anotime3'])){
                    $anotime3 = $arr['anotime3'];
                    $objActSheet->setCellValue("AA$i", "$anotime3");
                }
                if(isset($arr['time3'])){
                    $time3 = $arr['time3'];
                    $objActSheet->setCellValue("AB$i", "$time3");
                }
                if(isset($arr['isack3'])){
                    $isack3 = $arr['isack3'];
                    $isack3 = $isack_type_arr[$isack3];
                    $objActSheet->setCellValue("AC$i", "$isack3");
                }


            } 

        }

        $filename="export.xls";
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
