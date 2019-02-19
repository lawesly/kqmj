<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
    /**
     * 登出模块
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        // $this->load->model('att_model');
        $this->load->helper('url_helper');
    }


    public function index()
    {
        $this->session->sess_destroy();
        if (!empty($_COOKIE['username']) || !empty($_COOKIE['password'])) {  
            setcookie('username', null, time() - 3600 * 24 * 30);  
            setcookie('password', null, time() - 3600 * 24 * 30);  
        }
        header("Location:?/login/");

    }
}
