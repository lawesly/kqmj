<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require("inc/encrypt.php");
require_once("inc/Global.php");
ini_set('date.timezone','Asia/Shanghai');

class Carlicrec extends CI_Controller {
    /**
     * 车牌系别模块
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
        $header['title'] = "车牌管理";

        $data['carlicense_exp'] = $this->att_model->get_tab_all('carlicense_exp');

        $footer['myjs'] = 'carlicrec_index.js';
        $this->load->view('header',$header);
        $this->load->view('Node/carlicrec/index', $data);
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
        $header['title'] = "车牌管理";
        $entrys = $this->att_model->get_tab_diy("select * from carlicense_exp order by uVehicleID desc limit 1");
        $addID = $entrys[0]['uVehicleID'] + 1;
        $data['addID'] = $addID;

        $footer['myjs'] = 'carlicrec_add.js';
        $this->load->view('header',$header);
        $this->load->view('Node/carlicrec/add', $data);
        $this->load->view('footer', $footer);


    }


    public function add_cfm()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        // $username = $this->session->username;
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }

        $addID = $_POST['uVehicleID'];
        $strName = $_POST['strName'];
        $strPlateID = $_POST['strPlateID'];
        $ischongfu = $this->att_model->get_tab_one('carlicense_exp', 'strPlateID', $strPlateID);
        $ischongfu_1 = $this->att_model->get_tab_one('carlicense_exp', 'uVehicleID', $addID);
        if(isset($ischongfu)){
            echo "0";
        }elseif(isset($ischongfu_1)){
            echo "2";
        }else{
            $sql_insert = "insert into carlicense_exp(uVehicleID, strPlateID, uCustomerID, strName, strCode) values($addID, '$strPlateID', '$addID','$strName','$addID')";
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
        $header['title'] = "车牌管理";
        $vid = $_GET['id'];
        $result = $this->att_model->get_tab_one('carlicense_exp','uVehicleID',$vid);
        $strPlateID = $result->strPlateID;
        $strName = $result->strName;
        $data['vid'] = $vid;
        $data['strPlateID'] = $strPlateID;
        $data['strName'] = $strName;
        $footer['myjs'] = 'carlicrec_update.js';
        $this->load->view('header',$header);
        $this->load->view('Node/carlicrec/update', $data);
        $this->load->view('footer', $footer);

    }


    public function update_cfm()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        $vid = $_POST['uVehicleID'];
        $entry = $this->att_model->get_tab_one('carlicense_exp','uVehicleID',$vid);
        $oldstrName = $entry->strName;
        $oldstrPlateID = $entry->strPlateID;
        $newstrName = $_POST['strName'];
        $newstrPlateID = $_POST['strPlateID'];
        // $ischongfu = $this->att_model->get_tab_one('carlicense_exp', 'strPlateID', $newstrPlateID);
        $ischongfu = $this->db->query("select uVehicleID from carlicense_exp where strPlateID='$newstrPlateID' and strPlateID<>'$oldstrPlateID'")->result_array();

        if(!empty($ischongfu)){
            echo "0";
        }else{
            if($newstrName != $oldstrName){
                $sql_update = "update carlicense_exp set strName='$newstrName' where uVehicleID=$vid";
                $this->db->query($sql_update);
            }
            if($newstrPlateID != $oldstrPlateID){
                $sql_update = "update carlicense_exp set strPlateID='$newstrPlateID' where uVehicleID=$vid";
                $this->db->query($sql_update);
            }

            echo "1";
        }
    }


    public function del()
    {
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        $vid = $_GET['id'];
        $sql_del = "delete  from  carlicense_exp where uVehicleID=$vid";
        $this->db->query($sql_del);
        echo "1";

    }


    public function export(){
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
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
        $objProps->setTitle("车牌表");
        $objProps->setSubject("车牌表");
        $objProps->setDescription("车牌表");
        $objProps->setKeywords("车牌");
        $objProps->setCategory("车牌表");
        // 开始操作excel表
        // 操作第一个工作表
        $objExcel->setActiveSheetIndex(0);
        // 设置工作薄名称
        $objActSheet=$objExcel->getActiveSheet();
        $objActSheet->setTitle('车牌表');
        // 设置默认字体和大小
        $objExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        //设置居中
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        $objActSheet->setCellValue('A1', '车牌ID');
        $objActSheet->setCellValue('B1', '车牌号');
        $objActSheet->setCellValue('C1', '用户ID');
        $objActSheet->setCellValue('D1', '姓名');
        $objActSheet->setCellValue('E1', '用户编码');
        $entrys = $this->att_model->get_tab_all('carlicense_exp');
        $i = 1;
        foreach ($entrys as $entry){
            $i++;
            $objActSheet->setCellValue("A$i", $entry['uVehicleID']);
            $objActSheet->setCellValue("B$i", $entry['strPlateID']);
            $objActSheet->setCellValue("C$i", $entry['uCustomerID']);
            $objActSheet->setCellValue("D$i", $entry['strName']);
            $objActSheet->setCellValue("E$i", $entry['strCode']);
        }
        $filename="export-carlicrec.xls";
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


    public function wl(){
        if(empty($this->session->has_userdata('userid'))){
            header("Location:?/login/");
        }
        $gid = $this->session->gid;
        if($this->checkadmin($gid) == 0){
            header("Location:?/login/");
        }
        $action = $_GET['action'];
        if($action == 'import'){
            shell_exec("python /usr/local/TcpClient/wl_import.py");
            shell_exec("python /usr/local/TcpClient/wl_update_db.py");
        }
        header("Location:?/carlicrec/");
    }
}
