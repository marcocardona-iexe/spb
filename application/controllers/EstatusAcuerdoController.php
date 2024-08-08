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

        $rol_diferente_administrador = 0;
        foreach ($session['roles'] as $rol) {
            if ($rol->rol !== 'Administrador') {
                $rol_diferente_administrador = $rol->idrol;
                break;
            }
        }
        $dataAcuerdos = $this->EstatusAcuerdosModel->get_acuerdo_por_rol($rol_diferente_administrador);

        echo json_encode($dataAcuerdos);
    }
}
