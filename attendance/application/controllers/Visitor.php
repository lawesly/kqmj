<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor extends CI_Controller {
    /**
     * Visitor constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }


    private function open_gate($doorID){
        $command = "python /www/attendance/application/controllers/inc/open_gate.py $doorID";
        exec ($command,$retval,$stats);
    }


    private function gatelog($vID,$doorID,$type,$code){
        $opertime = date('Y-m-d H:i:s',time());
        $sql = "insert into gatelog(vID, opertime, doorID, type, code) values('$vID', '$opertime', '$doorID', '$type', '$code')";
        $this->db->query($sql);
    }


    public function open()
    {
        /*
         * 开门接口
         */
        $remain = 5;
        if(isset($_POST['id']) and isset($_POST['doorID']) and isset($_POST['type'])){
            $vID = $_POST['id'];
            $doorID = $_POST['doorID'];
            $type = $_POST['type'];
            /* check vID */
            $entry = $this->att_model->get_tab_one('visitors', 'vID', $vID);

            if(empty($entry)){
                $code = '404'; //未预约id
                $this->gatelog($vID, $doorID, $type, $code);
            }else{
                /* check doorID */
                $doorIDs = $entry->doorIDs;
                $doorIDs_array = explode(',', $doorIDs);
                /* check time */
                $stime = $entry->TMBegin;
                $etime = $entry->TMEnd;
                $nowtime = date('Y-m-d H:i:s', time());
                /* check enable */
                $enable = $entry->bEnable;
                if(in_array($doorID, $doorIDs_array) and strtotime($nowtime) >= strtotime($stime) and strtotime($nowtime) <= strtotime($etime) and $enable == 1){
                    /* check counts (redis) */
                    $redis = new Redis();
                    $redis->connect('127.0.0.1', 6379);
                    $keyname = "visitor_".$vID;
                    if($redis->exists($keyname)){			
                        $count = $redis->get($keyname);
                        if($count >= 5){
                            $code = '501'; //次数超限制
                            $this->gatelog($vID, $doorID, $type, $code);
                            $remain = 0;
                        }else{
                            $this->open_gate($doorID);  //开门
                            $count = $count + 1;
                            $redis->set($keyname,$count);
                            $redis->expire($keyname,1296000); //默认30天
                            $code ='200'; //成功
                            $this->gatelog($vID, $doorID, $type, $code);
                            $remain = 5 - $count;
                        }
                    }else{
                        $this->open_gate($doorID);
                        $redis->set($keyname, 1);
                        $redis->expire($keyname, 1296000); //默认30天
                        $code = '200';
                        $this->gatelog($vID,$doorID, $type, $code);
                        $remain = 4;
                    }
                }else{
                    $code = '403'; //禁止开门(无权限)
                    $this->gatelog($vID, $doorID, $type, $code);
                }
            }
        }else{
            $code = '500'; //参数错误
        }
        $res = array('code'=>$code, 'count'=>$remain);
        echo json_encode($res);
    }

    public function check_plate(){
        /*
         * 判断访客车牌号
         */
        $res = 0;
        if(isset($_GET['plate']) and isset($_GET['action'])){
            $plate = $_GET['plate'];
            $action = $_GET['action'];
            $nowtime = date('Y-m-d H:i:s',time());
            $entrys= $this->att_model->get_tab_diy("select * from visitors where strPlateID='$plate'");
            foreach ($entrys as $entry) {
                $vid = $entry['vID'];
                $stime = $entry['TMBegin'];
                $etime = $entry['TMEnd'];
                $enable = $entry['bEnable'];
                if ($action == 'in' and strtotime($nowtime) >= strtotime($stime) and strtotime($nowtime) <= strtotime($etime) and $enable == 1) {
                    $res = $vid;
                }elseif($action == 'out' and $enable == 1){
                    $res = $vid;
                }
            }
        }
        $this->db->query("insert into tmp(plate, res, time) values('$plate', $res, '$nowtime')");
        echo $res;
    }

    public function check_plate_new(){
        /*
         * 判断访客车牌号
         */
        $res = 0;
        $vid = '';
        $phone = '';
        if(isset($_GET['plate']) and isset($_GET['action'])){
            $plate = $_GET['plate'];
            $action = $_GET['action'];
            $nowtime = date('Y-m-d H:i:s',time());
            $entrys= $this->att_model->get_tab_diy("select * from visitors where strPlateID='$plate'");
            foreach ($entrys as $entry) {
                $vid = $entry['vID'];
                $stime = $entry['TMBegin'];
                $etime = $entry['TMEnd'];
                $enable = $entry['bEnable'];
                $phone = $entry['phoneNum'];
                if ($action == 'in' and strtotime($nowtime) >= strtotime($stime) and strtotime($nowtime) <= strtotime($etime) and $enable == 1) {
                    $res = 1;
                }elseif($action == 'out' and $enable == 1){
                    $res = 1;
                }
            }
            // $this->db->query("insert into tmp(plate, res, time) values('$plate', $res, '$nowtime')");
        }
        $res = array('res'=>$res, 'vid'=>$vid, 'phone'=>$phone);
        echo json_encode($res);
        // echo $res;
    }
}


