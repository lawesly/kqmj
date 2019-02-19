<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barriergate extends CI_Controller {
    /*
     * 道闸接口
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }
    private function check($unixtime){
        $dwDate = date('Ymd',$unixtime);
        $year = substr($dwDate,0,4);
        $month = substr($dwDate,4,2);
        $day = substr($dwDate,6,2);
        $iswr = $this->att_model->get_tab_one("wr",'dwDate',$dwDate);
        if($iswr){
            if($iswr->type == 1){
                return 1;
            }else{
                $stime = $iswr->stime;
                $etime = $iswr->etime;
                $stime_arr = explode(':',$stime);
                $etime_arr = explode(':',$etime);
                $stime_unix = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");
                $etime_unix = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");
                if($unixtime < $stime_unix or $unixtime > $etime_unix){
                    return 1;
                }else{
                    return 0;
                }
            }
        }else{
            $week = date('w',strtotime($dwDate));
            if($week == 0 or $week == 6){
                return 1;
            }else{
                $stime = "08:30";
                if($week == 4){
                    $etime = "20:00";
                }else{
                    $etime = "18:00";
                }
                $stime_arr = explode(':',$stime);
                $etime_arr = explode(':',$etime);
                $stime_unix = mktime("$stime_arr[0]","$stime_arr[1]","0","$month","$day","$year");
                $etime_unix = mktime("$etime_arr[0]","$etime_arr[1]","0","$month","$day","$year");
                if($unixtime < $stime_unix or $unixtime > $etime_unix){
                    return 1;
                }else{
                    return 0;
                }
            }

        }

    }

    public function api()
    {
        $phonenum = $_POST['phonenum'];
        $realname = "unknown";
        $entry = $this->att_model->get_tab_diy("select * from users where phoneNum='$phonenum'");
        if(empty($entry)){
            $code = 404;
            $res = array('status'=>'404');
        }else{
            $status = $entry[0]['status'];
            $realname = $entry[0]['realname'];
            if($status == 2 or $status == 3){
                $command = "python /www/attendance/application/controllers/inc/open_bg.py";
                exec ($command,$retval,$stats);
                $code = 200;
                $res = array('status'=>'200');
            }else{
                $code = 403;
                $res = array('status'=>'403');
            }
        }
        $opertime = date('Y-m-d H:i:s',time());
        $sql = "insert into bglog(phonenum,opertime,status,realname) values('$phonenum','$opertime',$code,'$realname')";
        $this->db->query($sql);
        echo json_encode($res);
    }

    public function qr(){
        if(isset($_POST['uuid']) and isset($_POST['type'])){
            //		if(1 == 1){
            $uuid = $_POST['uuid'];
            $type = $_POST['type'];

            $unixtime = time();
            $opertime = date('Y-m-d H:i:s',time());
            //初始化
            //$curl = curl_init();
            //$url = "http://kq.fccs.cn/?/checkwork/?time=$unixtime";
            //设置抓取的url
            //curl_setopt($curl, CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            //curl_setopt($curl, CURLOPT_HEADER, 0);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
            //执行命令
            //$data = curl_exec($curl);
            //关闭URL请求
            //curl_close($curl);
            //显示获得的数据
            $data = $this->check($unixtime);
            //print $data;
            if($data == 1){
                $command = "python /www/attendance/application/controllers/inc/open_bg.py";
                exec ($command,$retval,$stats);
                $code = '200';
            }else{
                $code = '403';
            }
            $sql = "insert into bglog_QR(uuid, operTime, code, type) values('$uuid', '$opertime', '$code', $type)";
            $this->db->query($sql);
        }else{
            $code = '500';
        }
        $res = array('status'=>$code);
        echo json_encode($res);
        }

    }

