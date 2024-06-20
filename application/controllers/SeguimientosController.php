<?php
// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City');
defined('BASEPATH') or exit('No direct script access allowed');

class SeguimientosController extends CI_Controller
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AlumnosModel');
        $this->load->model('SeguimientosModel');
        $this->load->model("HistorialSeguimientosModel");
        $this->load->helper('url');
    }

    public function verificar_seguimientos($idalumno)
    {
        // Llamar al método del modelo para verificar los seguimientos
        $seguimiento = $this->SeguimientosModel->verificar_seguimientos_abiertos($idalumno);

        // Preparar la respuesta según el resultado
        if (empty($seguimiento)) {
            $response = array(
                'status' => 'clean',
                'seguimiento' => 0
            );
        } else {
            #Revisamos los seguimientos hijos del seguimiento
            $historial = $this->HistorialSeguimientosModel->get_por_id($seguimiento[0]['idseguimiento']);
            $response = array(
                'status' => 'active',
                'seguimiento' => $seguimiento,
                'historial' => $historial
            );
        }

        // Devolver la respuesta en formato JSON
        echo json_encode($response);
    }

    public function guardar_seguimiento($idseguimiento, $tipo)
    {
        $dataSeguimiento = $this->input->post();
        if ($tipo == 'activo') {
            if ($idseguimiento == 0) {
                #Seguimiento abierto
                $dataInsert = array(
                    "idusuario_inicio" => 1,
                    "idalumno" => $dataSeguimiento['idalumno'],
                    "periodo" => $dataSeguimiento['periodo'],
                    "estatus" => "Abierto"
                );
                $response = $this->SeguimientosModel->insert($dataInsert);
                if ($response != 0) {
                    unset($dataSeguimiento['idalumno']);
                    unset($dataSeguimiento['periodo']);

                    $dataSeguimiento['idseguimiento'] = $response;
                    $dataSeguimiento['asesor'] = 1;

                    $response = $this->HistorialSeguimientosModel->insert($dataSeguimiento);
                    echo ($response != 0) ?
                        json_encode(array("status" => "OK", "message" => "Seguimiento generado correctamente")) :
                        json_encode(array("status" => "BAD", "message" => "Problemas al guardar el seguimiento."));
                }
            } else {
                unset($dataSeguimiento['idalumno']);
                unset($dataSeguimiento['periodo']);

                $dataSeguimiento['idseguimiento'] = $idseguimiento;
                $dataSeguimiento['asesor'] = 1;

                $response = $this->HistorialSeguimientosModel->insert($dataSeguimiento);
                echo ($response != 0) ?
                    json_encode(array("status" => "OK", "message" => "Seguimiento generado correctamente")) :
                    json_encode(array("status" => "BAD", "message" => "Problemas al guardar el seguimiento."));
            }
        } else if ($tipo == 'cerrado') {
            $dataUpdate = array(
                "idusuario_finalizo" => 1,
                "estatus" => "Cerrado"
            );
            $response = $this->SeguimientosModel->update_id($idseguimiento, $dataUpdate);
            if ($response != 0) {
                unset($dataSeguimiento['idalumno']);
                unset($dataSeguimiento['periodo']);

                $dataSeguimiento['idseguimiento'] = $idseguimiento;
                $dataSeguimiento['asesor'] = 1;

                $response = $this->HistorialSeguimientosModel->insert($dataSeguimiento);
                echo ($response != 0) ?
                    json_encode(array("status" => "OK", "message" => "Seguimiento cerrado correctamente")) :
                    json_encode(array("status" => "BAD", "message" => "Problemas al guardar el seguimiento."));
            }
        }
    }
}
