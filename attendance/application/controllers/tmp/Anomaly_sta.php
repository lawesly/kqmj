<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("inc/Global.php");

class Anomaly_sta extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
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
					$stime = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");   //标准上班时间
					$etime = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");   //标准下班时间
					$onwork = mktime("$onwork_arr[0]","$onwork_arr[1]","0","$month","$day","$year");   //上班时间
					$offwork = mktime("$offwork_arr[0]","$offwork_arr[1]","0","$month","$day","$year");       //下班时间
					$worktime = $offwork - $onwork; //工作时间
					$stime_tx = $iswr->stime_tx;    //上班是否弹性
					$etime_tx = $iswr->etime_tx;    //下班是否弹性
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
		session_start();
		if(!isset($_SESSION['userid'])){
			header("Location:?/login/");
			exit();
		}
		$userid=$_SESSION['userid'];
		$username=$_SESSION['username'];
		$gid = $_SESSION['gid'];
		if($gid != 1 and $gid != 2){
			header("Location:?/login/");	
		}
		$header['gid'] = $gid;
		$header['username'] = $username;
		$header['title'] = "异常统计";
		$data['isacks'] = array(0=>'未确认',1=>'已确认',2=>'全部');
		$this->load->view('header',$header);
		$this->load->view('Node/anomaly_sta/index',$data);
		$this->load->view('footer');
	}

	public function search()
	{
		session_start();
		if(!isset($_SESSION['userid'])){
			header("Location:?/login/");
			exit();
		}
		$userid=$_SESSION['userid'];
		$username=$_SESSION['username'];
		$gid = $_SESSION['gid'];
		if($gid != 1 and $gid != 2){
			header("Location:?/login/");
		}
		$header['gid'] = $gid;
		$header['username'] = $username;
		$header['title'] = "考勤管理";


		$fromdate = $_POST['fromdate'];
		$todate = $_POST['todate'];
		$isack = $_POST['isack'];
		if($fromdate == "" or $todate == ""){
			header("Location:?/anomaly_sta/");
			exit();
		}
		$data['fromdate'] = $fromdate;
		$data['todate'] = $todate;
		$data['isack'] = $isack;
		$data['isacks'] = array(0=>'未确认',1=>'已确认',2=>'全部');
		$fromdate_arr = explode("/",$fromdate);
		$fromdate = $fromdate_arr[2].$fromdate_arr[0].$fromdate_arr[1];
		$todate_arr = explode("/",$todate);
		$todate = $todate_arr[2].$todate_arr[0].$todate_arr[1];
		if($isack == 2){
			$entrys = $this->att_model->get_tab_diy("select * from anomaly_sta where dwDate >= '$fromdate' and dwDate <= '$todate'");
		}else{	
			$entrys = $this->att_model->get_tab_diy("select * from anomaly_sta where dwDate >= '$fromdate' and dwDate <= '$todate' and isack = $isack");
		}
		foreach($entrys as &$entry){
			$dwDate = $entry['dwDate'];
			$phoneNum = $entry['phoneNum'];
			$dwWeek_arr = array('日','一','二','三','四','五','六');
			$dwWeek = date('w',strtotime($dwDate));
			$entry['dwWeek'] = $dwWeek_arr[$dwWeek];
			$atts = $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate'");
			$dwTime = array();
			foreach($atts as $att){
				$entry['Name'] = $att['Name'];
				$entry['depname'] = $att['depname'];
				$dwTime[] = $att['dwTime'];
			}
			$entry['stime'] = null;
			$entry['etime'] = null;
			foreach($dwTime as $dt){
				if($entry['stime'] == null and $entry['etime'] == null){
					if($dt >= "17:00:00"){
						$entry['etime'] = $dt;
					}else{
						$entry['stime'] = $dt;
					}
				}else{
					$entry['etime'] = $dt;
				}	
			}	
			//$entry['dwTime'] = implode("/",$dwTime);
			if(!isset($entry['Name'])){
				$entry['Name'] = 'NULL';
				$entry['depname'] = 'NULL';
				$is_userinfo = $this->att_model->get_tab_diy("select Name,depname from userinfo where phoneNum='$phoneNum'");
				if($is_userinfo){
					$entry['Name'] = $is_userinfo[0]['Name'];
					$entry['depname'] = $is_userinfo[0]['depname'];
				}else{
					$is_user = $this->att_model->get_tab_diy("select realname from users where phoneNum='$phoneNum'");
					if($is_user){
						$entry['Name'] = $is_user[0]['realname'];
					}
				}
			}
		}
		$data['attendance'] = $entrys;
		$this->load->view('header',$header);
		$this->load->view('Node/anomaly_sta/search', $data);
		$this->load->view('footer');
	}
        public function truncate()

        {
                session_start();
                if(!isset($_SESSION['userid'])){
                        header("Location:?/login/");
                        exit();
                }
                $userid=$_SESSION['userid'];
                $username=$_SESSION['username'];
                $gid = $_SESSION['gid'];
                if($gid != 1 and $gid != 2){
                        header("Location:?/login/");
                }
                $header['gid'] = $gid;
                $header['username'] = $username;
                $header['title'] = "异常统计";
                $this->db->query("truncate anomaly_sta");
		echo '1';
        }
	public function export()
	{
		session_start();
		if(!isset($_SESSION['userid'])){
			header("Location:?/login/");
			exit();
		}
		$userid=$_SESSION['userid'];
		$username=$_SESSION['username'];
		$gid = $_SESSION['gid'];
		if($gid != 1 and $gid != 2){
			header("Location:?/login/");
		}
		$header['gid'] = $gid;
		$header['username'] = $username;
		$header['title'] = "考勤管理";
		$fromdate=$_GET['from'];
		$todate = $_GET['to'];
		$isack = $_GET['isack'];

		$fromdate_arr = explode("/",$fromdate);
		$fromdate = $fromdate_arr[2].$fromdate_arr[0].$fromdate_arr[1];
		$todate_arr = explode("/",$todate);
		$todate = $todate_arr[2].$todate_arr[0].$todate_arr[1];

		if($isack == 2){
			$entrys = $this->att_model->get_tab_diy("select * from anomaly_sta where dwDate >= '$fromdate' and dwDate <= '$todate'");
		}else{ 
			$entrys = $this->att_model->get_tab_diy("select * from anomaly_sta where dwDate >= '$fromdate' and dwDate <= '$todate' and isack = $isack");
		}
		foreach($entrys as &$entry){
			$dwDate = $entry['dwDate'];
			$phoneNum = $entry['phoneNum'];
			$dwWeek_arr = array('日','一','二','三','四','五','六');
			$dwWeek = date('w',strtotime($dwDate));
			$entry['dwWeek'] = $dwWeek_arr[$dwWeek];
			$atts = $this->att_model->get_tab_diy("select * from attendance where phoneNum='$phoneNum' and dwDate='$dwDate'");
			$dwTime = array();
			foreach($atts as $att){
				$dwTime[] = $att['dwTime'];
				$entry['Name'] = $att['Name'];
				$entry['depname'] = $att['depname'];
			} 
                        $entry['stime'] = null;
                        $entry['etime'] = null;
                        foreach($dwTime as $dt){
                                if($entry['stime'] == null and $entry['etime'] == null){
                                        if($dt >= "17:00:00"){
                                                $entry['etime'] = $dt;
                                        }else{  
                                                $entry['stime'] = $dt;
                                        }
                                }else{  
                                        $entry['etime'] = $dt;
                                }       
                        }          
			//$entry['dwTime'] = implode("/",$dwTime);
			if(!isset($entry['Name'])){
				$entry['Name'] = 'NULL';
				$entry['depname'] = 'NULL';
				$is_userinfo = $this->att_model->get_tab_diy("select Name,depname from userinfo where phoneNum='$phoneNum'");
				if($is_userinfo){
					$entry['Name'] = $is_userinfo[0]['Name'];
					$entry['depname'] = $is_userinfo[0]['depname'];
				}else{
					$is_user = $this->att_model->get_tab_diy("select realname from users where phoneNum='$phoneNum'");
					if($is_user){
						$entry['Name'] = $is_user[0]['realname'];
					}
				}
			}
			$anomalys = $this->att_model->get_tab_diy("select * from anomaly where phoneNum='$phoneNum' and dwDate='$dwDate'");
			if($anomalys){
				$j = 1;
				foreach($anomalys as $anomaly){
					$key_type = "type$j";
					$key_type_sub = "type_sub$j";
					$key_anotime = "anotime$j";
					$key_time = "time$j";
					$key_isack = "isack$j";
					$a_stime = $anomaly['stime'];
					$a_etime = $anomaly['etime'];
					$anotime = $a_stime."--".$a_etime;
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
					//if($anomaly['type'] == 2){
					//}elseif($anomaly['type'] == 3){
					//}
					$entry[$key_type] = $anomaly['type'];
					$entry[$key_type_sub] = $anomaly['type_sub'];
					$entry[$key_anotime] = $anotime;
					$entry[$key_time] = $durtime;
					$entry[$key_isack] = $anomaly['isack'];
				}
			}


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
		$objProps->setTitle("异常报表");
		$objProps->setSubject("异常报表");
		$objProps->setDescription("异常报表");
		$objProps->setKeywords("异常");
		$objProps->setCategory("异常报表");
		// 开始操作excel表
		// 操作第一个工作表
		$objExcel->setActiveSheetIndex(0);
		// 设置工作薄名称
		$objActSheet=$objExcel->getActiveSheet();
		$objActSheet->setTitle('异常报表');
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
		$objActSheet->setCellValue('I1', '操作员确认');
		$objActSheet->setCellValue('J1', '异常类型01');
		$objActSheet->setCellValue('K1', '异常说明01');
		$objActSheet->setCellValue('L1', '异常时间01');
		$objActSheet->setCellValue('M1', '异常统计时间01');
		$objActSheet->setCellValue('N1', '异常确认情况01');
		$objActSheet->setCellValue('O1', '异常类型02');
		$objActSheet->setCellValue('P1', '异常说明02');
		$objActSheet->setCellValue('Q1', '异常时间02');
		$objActSheet->setCellValue('R1', '异常统计时间02');
		$objActSheet->setCellValue('S1', '异常确认情况02');
		$i = 1;
		$anomaly_type_arr = array(1=>"未打卡",2=>"请假",3=>"加班",4=>"公出");
		$isack_type_arr = array(0=>"未确认",1=>"已确认");
		foreach($entrys as $arr){
			$i = $i + 1;
			$phoneNum = $arr['phoneNum'];
			$Name = $arr['Name'];
			$depname = $arr['depname'];
			$week = $arr['dwWeek'];
			$dwDate = $arr['dwDate'];
			$type = "工作日";
			$stime = $arr['stime'];
			$etime = $arr['etime'];
			if($arr['isack'] == 0){
				$checkAll = "否";
			}else{
				$checkAll = "是";
			}
			$objActSheet->setCellValue("A$i", "$phoneNum");
			$objActSheet->setCellValue("B$i", "$Name");
			$objActSheet->setCellValue("C$i", "$depname");
			$objActSheet->setCellValue("D$i", "$dwDate");
			$objActSheet->setCellValue("E$i", "$week");
			$objActSheet->setCellValue("F$i", "$type");
			$objActSheet->setCellValue("G$i", "$stime");
			$objActSheet->setCellValue("H$i", "$etime");
			//$objActSheet->setCellValue("I$i", "$onwork");
			//$objActSheet->setCellValue("J$i", "$offwork");
			$objActSheet->setCellValue("i$i", "$checkAll");
			if(isset($arr['type1'])){
				$type1 = $arr['type1'];
				$type1 = $anomaly_type_arr[$type1];
				$objActSheet->setCellValue("J$i", "$type1");
			}
			if(isset($arr['type_sub1'])){
				$type_sub1 = $arr['type_sub1'];
				$objActSheet->setCellValue("K$i", "$type_sub1");
			}

			if(isset($arr['anotime1'])){
				$anotime1 = $arr['anotime1'];
				$objActSheet->setCellValue("L$i", "$anotime1");
			}
			if(isset($arr['time1'])){
				$time1 = $arr['time1'];
				$objActSheet->setCellValue("M$i", "$time1");
			}
			if(isset($arr['isack1'])){
				$isack1 = $arr['isack1'];
				$isack1 = $isack_type_arr[$isack1];	
				$objActSheet->setCellValue("N$i", "$isack1");
			}
			if(isset($arr['type2'])){
				$type1 = $arr['type2'];
				$type1 = $anomaly_type_arr[$type2];
				$objActSheet->setCellValue("O$i", "$type2");
			}
			if(isset($arr['type_sub2'])){
				$type_sub1 = $arr['type_sub2'];
				$objActSheet->setCellValue("P$i", "$type_sub2");
			}

			if(isset($arr['anotime2'])){
				$anotime2 = $arr['anotime2'];
				$objActSheet->setCellValue("Q$i", "$anotime2");
			}
			if(isset($arr['time2'])){
				$time1 = $arr['time2'];
				$objActSheet->setCellValue("R$i", "$time2");
			}
			if(isset($arr['isack2'])){
				$isack1 = $arr['isack2'];
				$isack1 = $isack_type_arr[$isack2];
				$objActSheet->setCellValue("S$i", "$isack2");
			}


		}	
		$filename="export.xls";
		// 从浏览器直接输出$filename
		ob_end_clean();
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
