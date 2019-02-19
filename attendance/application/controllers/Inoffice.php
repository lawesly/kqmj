<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inoffice extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }
    private function myshow()
    {
        $dwDate = date('Ymd',time());
        $userinfo = $this->att_model->get_tab_diy("select * from userinfo where phoneNum <> 'NULL' and dwEnrollNumber<>1 group by phoneNum order by Name");
        $res = array();
        $present = 0;
        foreach($userinfo as &$arr){
            $Name = $arr['Name'];
            $arr['first'] = "未打卡";
            $arr['last'] = "未打卡";
            $atts = $this->att_model->get_tab_diy("select * from attendance where dwDate='$dwDate' and Name='$Name'");
            $num = 0;
            $last = '';
            foreach($atts as $att){
                if($num > 0){
                    $thisone = $att['dwTime'];
                    $thisone_arr = explode(":",$thisone);
                    $last_arr = explode(":",$last);
                    $thisone_unix = mktime("$thisone_arr[0]","$thisone_arr[1]","$thisone_arr[2]","01","01","2016");
                    $last_unix = mktime("$last_arr[0]","$last_arr[1]","$last_arr[2]","01","01","2016");
                    if($thisone_unix - $last_unix > 30){
                        $num = $num + 1;
                        $last = $thisone;
                    }
                    $arr['last'] = $thisone;
                }else{
                    $num = $num + 1;
                    $last = $att['dwTime'];
                    $arr['first'] = $last;
                }
            }
            $manArr = $this->att_model->get_tab_diy("select count(id) from anomaly where dwDate='$dwDate' and Name='$Name' and (type=1 or type=4)");
            $man = $manArr[0]['count(id)'];
            $num = $num + $man;
            if($num != 1){
                $num = 0;
            }
            $tmp = array('id'=>$arr['dwEnrollNumber'],'name'=>$arr['Name'],'work'=>$num,'depname'=>$arr['depname'],'first'=>$arr['first'],'last'=>$arr['last']);
            $res[] = $tmp;
            $present = $present + $num;

        }
        $res_new = array('present'=>$present,'result'=>$res);
        return $res_new;
    }

    public function index()
    {
        $userinfo = $this->att_model->get_tab_diy("select * from userinfo where phoneNum <> 'NULL' and dwEnrollNumber<>1 group by phoneNum");
        $count = count($userinfo);
        $line=6 ;
        $group = ceil($count/$line);
        $userinfo_group = array();
        $dwDate = date('Ymd',time());
        for($i=0;$i<$group;$i++){
            $start = $i * $line;
            $end = ($i + 1) * $line;
            $tmp = array();
            for($j=$start;$j<$end;$j++){
                if($j < $count){
                    $tmp[] = $userinfo[$j];
                }
            }
            foreach($tmp as &$arr){
                $Name = $arr['Name'];
                $atts = $this->att_model->get_tab_diy("select * from attendance where dwDate='$dwDate' and Name='$Name'");
                $num = 0;
                $last = "";
                foreach($atts as $att){
                    if($num > 0){
                        $thisone = $att['dwTime'];
                        $thisone_arr = explode(":",$thisone);
                        $last_arr = explode(":",$last);
                        $thisone_unix = mktime("$thisone_arr[0]","$thisone_arr[1]","$thisone_arr[2]","01","01","2016");
                        $last_unix = mktime("$last_arr[0]","$last_arr[1]","$last_arr[2]","01","01","2016");
                        if($thisone_unix - $last_unix > 30){
                            $num = $num + 1;
                            $last = $thisone;
                        }
                    }else{
                        $num = $num + 1;
                        $last = $att['dwTime'];
                    }
                }
                //$num = $numArr[0]['count(dwDate)'];
                $manArr = $this->att_model->get_tab_diy("select count(id) from anomaly where dwDate='$dwDate' and Name='$Name' and (type=1 or type=4)");
                $man = $manArr[0]['count(id)'];
                $num = $num + $man;
                $arr['count'] = $num;
                //if($num == 1){
                //	$color = "#00DDAA";
                //}else{
                //	$color = "FFFFFF";
                //}
                //$arr['color'] = $color;

            }
            $userinfo_group[] = $tmp;
        }
        $data['userinfoGroup'] = $userinfo_group;
        $data['dwDate'] = $dwDate;
        $this->load->view('inoffice',$data);
    }

    public function show()
    {
        /*
         */
        $res = $this->myshow();
        echo json_encode($res);
        //	$count = count($userinfo);
        //	$this->load->view('inoffice',$data);
    }

    public function update(){
        // $url = "http://172.16.150.10:5000/update_zk/";
        /* $post_data = array(
           'key' => 'fccs2017',  
           ); */
        // $postdata = http_build_query($post_data);
        /*$options = array(
          'http' => array(  
          'method' => 'POST',  
          'header' => 'Content-type:application/x-www-form-urlencoded',  
          'content' => $postdata,  
          'timeout' => 15 * 60 // 超时时间（单位:s）  
          )  
          );*/
        // $context = stream_context_create($options);
        // $result = file_get_contents($url, false, $context);
        $res = $this->myshow();
        echo json_encode($res);


    }
}

