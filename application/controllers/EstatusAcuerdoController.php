<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstatusAcuerdoController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->model("EstatusAcuerdosModel");
    }


    public function obtener_acuerdos()
    {
        $session = $this->session->userdata('seguimiento_iexe');

        // Inicializa la variable como 0 (por si el usuario solo tiene el rol de "Administrador")
        $rol_diferente_administrador = 0;

        // Verifica si el usuario tiene otros roles además de "Administrador"
        if (count($session['roles']) > 1) {
            foreach ($session['roles'] as $rol) {
                if ($rol->rol !== 'Administrador') {
                    $rol_diferente_administrador = $rol->idrol;
                    break;
                }
            }
        }

        // Obtener acuerdos según el rol encontrado o 0 si solo es "Administrador"
        $dataAcuerdos = $this->EstatusAcuerdosModel->get_acuerdo_por_rol($rol_diferente_administrador);

        // Retornar los datos como JSON
        echo json_encode($dataAcuerdos);
    }
}
