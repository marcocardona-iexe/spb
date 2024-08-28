<?php
// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City');
defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;


class SeguimientosController extends CI_Controller
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AlumnosModel');
        $this->load->model('SeguimientosModel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function filtrar_seguimientos_fecha()
    {
        $fecha_seg_final = $this->input->post('fecha_seg_final');
        $fecha_seg_inicial = $this->input->post('fecha_seg_inicial');
        $idalumno = $this->input->post('idalumno');
        $dataSeguimientos = $this->SeguimientosModel->filtrar_seguimientos_fecha($fecha_seg_inicial, $fecha_seg_final, $idalumno);
        echo json_encode($dataSeguimientos);
    }


    public function obtener_seguimiento_alumno($idalumno)
    {
        $hoy = date("Y-m-d");
        $dataSeguimientos = $this->SeguimientosModel->obtener_por_fecha_idalumno($hoy, $idalumno);
        echo json_encode($dataSeguimientos);
    }



    public function exist_seuimiento_abierto_por_alumno($idalumno)
    {
        $dataSeguimiento = $this->SeguimientosModel->verificar_seguimientos_abiertos($idalumno);
        echo json_encode(array("count" => count($dataSeguimiento)));
    }

    public function guardar_seguimiento()
    {
        $sesion = $this->session->userdata('seguimiento_iexe');

        $dataSeguimiento = $this->input->post();
        $dataSeguimiento['asesor'] = $sesion['idusuario'];

        $response = $this->SeguimientosModel->insert($dataSeguimiento);

        echo ($response != 0) ?
            json_encode(array("status" => "OK", "message" => "Seguimiento generado correctamente")) :
            json_encode(array("status" => "BAD", "message" => "Problemas al guardar el seguimiento."));
    }

    public function descarga_segumientos($finicial, $ffinal)
    {


        $dataSeguimientos = $this->SeguimientosModel->reporte_excel($finicial, $ffinal);


        $customColumnNames = [
            'nombre' => 'NOMBRE ALUMNO',
            'apellidos' => 'APELLIDOS ALUMNO',
            'matricula' => 'MATRICULA',
            'periodo' => 'PERIODO DEL SEGUIMIENTO',
            'metodo_contacto' => 'METODO DE CONTACTO',
            'estatus_seguimiento' => 'ESTATUS DEL SEGUIMIENTO',
            'estatus_acuerdo' => 'ESTATUS DEL ACUERDO',
            'comentarios' => 'COMENTARIOS',
            'nombreusuario' => 'USUARIO DEL SEGUIMIENTO',
            'correo' =>'CORREO DEL USUARIO',
            'insert_date' => 'FECHA'
        ];


        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Definir estilo para la cabecera
        $headerStyle = [
            'font' => ['bold' => false, 'color' => ['rgb' => 'FFFFFF']], // Cambiar bold a false
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '337ab7']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
        ];

        // Ajustar altura de la fila para simular padding
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Añadir encabezados personalizados
        $columnLetter = 'A';
        foreach ($customColumnNames as $originalName => $customName) {
            $sheet->setCellValue($columnLetter . '1', $customName);
            $sheet->getStyle($columnLetter . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true); // Ajustar el ancho automáticamente

            $columnLetter++;
        }


        $rowNumber = 2;

        foreach ($dataSeguimientos as $row) {


            $columnLetter = 'A';
            foreach ($customColumnNames as $originalName => $customName) {
                $sheet->setCellValue($columnLetter . $rowNumber, $row->$originalName);
                $columnLetter++;
            }
            $rowNumber++;
        }

        // Configurar el tipo de respuesta HTTP y enviar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Seguimientos-' . date("Y-m-d H:i") . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
