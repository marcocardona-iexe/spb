<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BannersController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
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
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'banner_' . time(); // Rename the file to avoid conflicts

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('banner')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            echo json_encode($data);
        }

        echo "<pre>";
        print_r($this->input->post());
        echo "</pre>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    }
}
