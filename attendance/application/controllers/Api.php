<?php
require_once "/www/attendance/application/libraries/REST_Controller.php";
// require_once "../../application/libraries/REST_Controller.php";

class Api extends REST_Controller
{
    /*
     * 访客系统接口
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('att_model');
        $this->load->helper('url_helper');
    }

    private function getPut($key){
        return $this->input->input_stream($key);
    }
    private function check_duplicate($vid){
        /*
         * 判断重复
         */
        // $isexist = $this->att_model->get_tab_one('visitors', 'vID', $vid);
        $sql = "select * from visitors where vID=$vid and Type=0";
        $isexist = $this->att_model->get_tab_diy($sql);
        if($isexist){
            return 1;
        }else{
            return 0;
        }

    }
    public function visitor_get(){
        /*
         * 获取访客信息
         */
        $id = $this->get('id');
        if($id != null){
            $sql = "select * from visitors where vID=$id";
            $res = $this->att_model->get_tab_diy($sql);	
        }else{
            $res = $this->att_model->get_tab_all('visitors');
        }
        $this->set_response($res, REST_Controller::HTTP_OK);
    }
    public function visitor_post(){
        /*
         * 添加访客信息
         */
        $info = [
            'id' => $this->post('id'),
            'phonenum' => $this->post('phonenum'),
            'name' => $this->post('name'),
            'carlic' => $this->post('carlic'),
            'stime' => $this->post('stime'),
            'etime' => $this->post('etime'),
            'enable' => $this->post('enable'),
            'doorids' => $this->post('doorids'),
            'type' => $this->post('type')
        ];
        $isempty = 0;
        
        foreach($info as $key =>$getoutvisnull)
        {
            if($getoutvisnull == null and $key != 'carlic')
            {
                $isempty = 1;
            }
        }
        
        if($isempty == 0){
//            $doorids = '1,8,9,13,15';
            $doorids = $info['doorids'];
            $id = $info['id'];
            if($this->check_duplicate($id) == 0){
                $phonenum = $info['phonenum'];
                $name = $info['name'];
                $carlic = $info['carlic'];
                $stime = $info['stime'];
                $etime = $info['etime'];
                $enable = $info['enable'];
                $type = $info['type'];
                $addtime = date('Y-m-d H:i:s',time());
                $stime_fmt = date('Y-m-d H:i:s',$stime);
                $etime_fmt = date('Y-m-d H:i:s',$etime);
                $sql_insert = "insert into visitors(vID, strPlateID, phoneNum, Name, bEnable, TMCreate, TMBegin, TMEnd, doorIDs, Type) values($id, '$carlic', '$phonenum', '$name', $enable, '$addtime', '$stime_fmt', '$etime_fmt', '$doorids', $type)";
                $this->db->query($sql_insert);
                $code = '201';  //成功添加
            }else{
                $code = '409'; //重复

            }
        }else{
            $code = '500'; //参数错误
        }
        $ret_arr = ['code'=>$code];
        $this->set_response($ret_arr, $code);

    }

    public function visitor_put(){
        /*
         * 修改访客信息(全部修改)
         */
        $id = $this->get('id');
        $info = [
            'phonenum' => $this->getPut('phonenum'),
            'name' => $this->getPut('name'),
            'carlic' => $this->getPut('carlic'),
            'stime' => $this->getPut('stime'),
            'etime' => $this->getPut('etime'),
            'enable' => $this->getPut('enable'),
            'doorids' => $this->getPut('doorids')

        ];
        $isempty = 0;
        foreach($info as $key =>$getoutvisnull)
        {
            if($getoutvisnull == null)
            {
                $isempty = 1;
            }
        }
        if($id != null and $isempty == 0){
            if($this->check_duplicate($id) == 1){
                $phonenum = $info['phonenum'];
                $name = $info['name'];
                $carlic = $info['carlic'];
                $stime = $info['stime'];
                $etime = $info['etime'];
                $enable = $info['enable'];
                $doorids = $info['doorids'];
                // $addtime = date('Y-m-d H:i:s',time());
                $stime_fmt = date('Y-m-d H:i:s',$stime);
                $etime_fmt = date('Y-m-d H:i:s',$etime);
                $sql_update = "update visitors set phoneNum='$phonenum',Name='$name',strPlateID='$carlic',TMBegin='$stime_fmt',TMEnd='$etime_fmt',bEnable=$enable,doorIDs='$doorids' where vID=$id";
                $this->db->query($sql_update);
                $code = '200';  //更新成功
            }else{
                $code = '404';  //未找到id
            }
        }else{
            $code = '500'; //参数错误
        }
        $ret_arr = ['code'=>$code];
        $this->set_response($ret_arr, $code);
        //		$this->set_response($info, $code);

    }

    public function visitor_patch(){
        /*
         * 修改访客信息(部分修改)
         */
        $id = $this->get('id');
        $info = [
            'phonenum' => $this->getPut('phonenum'),
            'name' => $this->getPut('name'),
            'carlic' => $this->getPut('carlic'),
            'stime' => $this->getPut('stime'),
            'etime' => $this->getPut('etime'),
            'enable' => $this->getPut('enable'),
            'doorids' => $this->getPut('doorids'),
            'initcount' => $this->getPut('initcount')
        ];
        $isempty = 0;
        foreach($info as $key =>$getoutvisnull)
        {
            if($getoutvisnull != null)
            {
                $isempty = 1;
            }
        }
        if($id != null and $isempty == 1) {
            if ($this->check_duplicate($id) == 1) {
                $update_count = 0;
                if ($info['phonenum'] != null) {
                    $phonenum = $info['phonenum'];
                    $sql_update = "update visitors set phoneNum='$phonenum' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['name'] != null) {
                    $name = $info['name'];
                    $sql_update = "update visitors set Name='$name' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['carlic'] != null) {
                    $carlic = $info['carlic'];
                    $sql_update = "update visitors set strPlateID='$carlic' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['stime'] != null) {
                    $stime = $info['stime'];
                    $stime_fmt = date('Y-m-d H:i:s', $stime);
                    $sql_update = "update visitors set TMBegin='$stime_fmt' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['etime'] != null) {
                    $etime = $info['etime'];
                    $etime_fmt = date('Y-m-d H:i:s', $etime);
                    $sql_update = "update visitors set TMEnd='$etime_fmt' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['enable'] != null) {
                    $enable = $info['enable'];
                    $sql_update = "update visitors set bEnable=$enable where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;
                }
                if ($info['doorids'] != null) {
                    $doorids = $info['doorids'];
                    $sql_update = "update visitors set doorIDs='$doorids' where vID=$id";
                    $this->db->query($sql_update);
                    $update_count = $update_count + 1;

                }
                if ($info['initcount'] != null) {
                    $count = $info['initcount'];
                    $redis = new Redis();
                    $redis->connect('127.0.0.1', 6379);
                    $keyname = "visitor_" . $id;
                    $redis->set($keyname, $count);
                    $redis->expire($keyname, 1296000); //默认30天
                    $update_count = $update_count + 1;

                }
                if ($update_count > 0) {
                    $code = '200';  //更新成功
                } else {
                    $code = '204';  //无更新内容
                }
            } else {
                $code = '404';  //未找到id
            }
        }else{
            $code = '500'; //参数错误
        }
        $ret_arr = ['code'=>$code];
        $this->set_response($ret_arr, $code);
        //$this->set_response($info, $code);

    }

    public function visitor_delete(){
        /*
         * 删除访客信息
         */

        $id = $this->get('id');
#		echo $id;
        if($id != null){
            if($this->check_duplicate($id) == 1){
                $sql = "delete from visitors where  vID=$id";
                $this->db->query($sql);
                $code = '200';
            }else{
                $code = '404';
            }
        }else{
            $code = '500';
        }
        $ret_arr = ['code'=>$code];
        $this->set_response($ret_arr, $code);
    }



}


