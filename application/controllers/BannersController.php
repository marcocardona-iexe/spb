<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BannersController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/banners.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('banners');
        $this->load->view('footer', array("js" => "assets/js/banners.js"));
    }

    public function insert_banner()
    {
        echo "<pre>";
        print_r($this->input->post());
        echo "</pre>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    }
}
