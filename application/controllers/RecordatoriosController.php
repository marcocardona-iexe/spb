<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RecordatoriosController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }


    public function recordatorio_consejera($correo)
    {
        // Verificar sesión
        $this->check_session();

        // Obtener datos de sesión para el menú
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        $dataSesion = $this->session->userdata('seguimiento_iexe');

        // echo "<pre>";
        // print_r($dataSesion);
        // echo "</pre>";
        // die;
        $data = array(
            "correo" => $correo,
            //"nombre" => $dataSesion['nombre'] . " " . $dataSesion['apellidos']
        );
        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/detalle_consejera.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view("recordatorio_consejera", $data);
        $this->load->view('footer', array("js" => "assets/js/detalle_consejera.js"));
    }

    //Validador de que este una sesion activa
    private function check_session()
    {
        if (!$this->session->userdata('seguimiento_iexe')) {
            redirect(base_url());
        }
    }

    public function detalle_materia($correo, $clave_materia)
    {
        // Verificar sesión
        $this->check_session();

        // Obtener datos de sesión para el menú
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        $dataSesion = $this->session->userdata('seguimiento_iexe');

        // echo "<pre>";
        // print_r($dataSesion);
        // echo "</pre>";
        // die;
        $data = array(
            "correo" => $correo,
            "clave" => $clave_materia
        );
        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/detalle_materia.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view("detalle_materia", $data);
        $this->load->view('footer', array("js" => "assets/js/detalle_materia.js"));
    }
}
