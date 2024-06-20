<?php
// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City');
defined('BASEPATH') or exit('No direct script access allowed');
include 'materiasactivas.class.php';

require_once APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AlumnosController extends CI_Controller
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PlataformasModel');
        $this->load->model('AlumnosModel');
        $this->load->model('MateriasActivasModel');
        $this->load->model('UsuariosModel');

        $this->load->helper('url');
        $this->load->library('session');

        $this->URL_API_FINACIERO = "https://beta.dockserver.lat/retraso_en_pagos";
    }


    private function get_data($matricula)
    {
        $dataMaterias = new materiasactivas();
        preg_match('/[^0-9]+/', $matricula, $programa);


        $siglas = (count($programa) > 0) ? $programa[0] : "";
        switch (strtolower($siglas)) {
                #LICENCIATURAS
            case 'lce':
                $arr_tipo  = $dataMaterias->lic($matricula);
                break;

            case 'lae':
                $arr_tipo  = $dataMaterias->lic($matricula);
                break;
            case 'ld':
                $arr_tipo  = $dataMaterias->lic($matricula);
                break;
            case 'lsp':
                $arr_tipo  = $dataMaterias->lic($matricula);
                break;
            case 'lcpap':
                $arr_tipo  = $dataMaterias->lic($matricula);
                break;
                #MAESTRIAS
            case 'mapp':
                $arr_tipo  = $dataMaterias->mapp();
                break;
            case 'mepp':
                $arr_tipo  = $dataMaterias->mepp();
                break;
            case 'mspp':
                $arr_tipo  = $dataMaterias->mspp();
                break;
            case 'man':
                $arr_tipo  = $dataMaterias->maestrias($matricula);
                break;
            case 'mfp':
                $arr_tipo  = $dataMaterias->maestrias($matricula);
                break;
            case 'mige':
                $arr_tipo  = $dataMaterias->mige();
                break;

            case 'mcda':
                $arr_tipo  = $dataMaterias->tec($matricula);
                break;
            case 'mcdia':
                $arr_tipo  = $dataMaterias->tec($matricula);
                break;

            case 'mba':
                $arr_tipo  = $dataMaterias->tec($matricula);
                break;


            case 'miti':
                $arr_tipo  = $dataMaterias->tec($matricula);
                break;

            case 'mais':
                $arr_tipo  = $dataMaterias->master($matricula);
                break;

            case 'mgpm':
                $arr_tipo  = $dataMaterias->master($matricula);
                break;
            case 'mspajo':
                $arr_tipo  = $dataMaterias->master($matricula);
                break;

            case 'mmpop':
                $arr_tipo  = $dataMaterias->master($matricula);
                break;

            case 'mag':
                $arr_tipo  = $dataMaterias->master($matricula);
                break;

                #DOCTORADOS
            case 'dsp':
                $arr_tipo  = $dataMaterias->doctorados($matricula);
                break;
            case 'dpp':
                $arr_tipo  = $dataMaterias->doctorados($matricula);
                break;
        }
        return $arr_tipo;
    }

    public function procesar_formula($clean_formula)
    {
        //divide la expresion matematica en segmentos más pequeños
        preg_match_all("/[^(]+\)/", $clean_formula, $segmentos);

        $resultados = array();
        foreach ($segmentos[0] as $keyc => $segmento) {
            //$num_multiplicadores = substr_count($segmento,'*');//cuantos simbolos de multiplicacion hay en el segmento
            //$num_divisores       = substr_count($segmento,'/'); //cuantos simbolos divisiores hay en el segmento
            // $num_suma            = substr_count($segmento,'+'); //cuantos simbolos suma hay en el segmento
            //$num_elem_gi         = substr_count($segmento,'gi');//cuantos elementos gi hay en la formula

            preg_match_all("/gi[0-9]*/", $segmento, $items_formula); //obtiene todos los elementos gi ejemplo gi8811,gi5814 en el array items_formula

            foreach ($items_formula[0] as $itemform_key => $itemformula) {

                if (preg_match("/(" . $itemformula . ")[*]/i", $segmento)) {
                    preg_match_all("/" . $itemformula . "[*][0-9]+\.?[0-9]*/i", $segmento, $item_aislado);
                    preg_match_all("/[0-9]+\.?[0-9]*/i", $item_aislado[0][0], $arrvalues);

                    $resultados[$itemformula] = (object)array(
                        "id"         => $itemformula,
                        "idclean"    => str_replace("gi", "", $itemformula),
                        "porcentaje" => $arrvalues[0][1] * 10,
                        "describe"   => "multiplicador 1, divisor 0 | un solo multiplicador"
                    );
                } else {
                    $str_apartirdel_item = substr($segmento, strpos($segmento, $itemformula) + 1);
                    if (substr_count($str_apartirdel_item, '*') == 0) {
                        $resultados[$itemformula] = (object)array(
                            "id"         => $itemformula,
                            "idclean"    => str_replace("gi", "", $itemformula),
                            "porcentaje" => 10,
                            "describe"   => "multiplicador 1, divisor 0 | un solo multiplicador"
                        );
                    } else {
                        //preg_match_all("/\*+[0-9]+\.?[0-9]?/i",$segmento,$values);//original formula
                        $multi = 0;
                        preg_match_all("/\*+[0-9]+\.?[0-9]*/i", $segmento, $values);
                        if (isset($values[0][0])) {
                            $mul = str_replace("*", "", $values[0][0]);
                        } else {
                            $mul = 0;
                        }
                        $multi = (is_numeric($mul)) ? $mul * 10 : 0;

                        //echo $itemformula."<br>".$mul;
                        //

                        $resultados[$itemformula] =  (object)array(
                            "id"         => $itemformula,
                            "idclean"    => str_replace("gi", "", $itemformula),
                            "porcentaje" => $multi,
                            "describe"   => "multiplicador 1, divisor 0 | un solo multiplicador"
                        );
                    }
                }
            }
        }
        return $resultados;
    }




    public function detalle_alumno($matricula)
    {
        $this->check_session();
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        $codigoMaterias = $this->get_data($matricula);

        #Obtenemos el programa de la matricula
        $infoalumno = $this->AlumnosModel->get_all_alumnos_where(array('matricula' => $matricula));


        // Iterar sobre cada alumno activo
        $secciones = array();

        foreach ($infoalumno as $a) {
            $materias_enroladas  = $this->PlataformasModel->get_materia_activas($a->moodleid, strtolower($a->plataforma));
            foreach ($materias_enroladas as $m) {
                // Obtener el código de la materia
                $array_codigo = explode('-', $m->fullname);
                $codigo = $array_codigo[0];
                // Obtener la fórmula configurada para la materia
                $formula_materia = $this->PlataformasModel->formula_materia($m->id, strtolower($a->plataforma), $codigo);

                if ($formula_materia !== null) {

                    //echo "<pre>";
                    $activitis = $this->procesar_formula($formula_materia->calculation);

                    foreach ($activitis as $ac) {
                        $actividad = $this->PlataformasModel->obtener_calificacion_por_actividad($ac->idclean, $a->moodleid, strtolower($a->plataforma));

                        foreach ($actividad as $acti) {
                            $ac->calificacion_obtenida = $acti->calificacion_obtenida;
                            $ac->calificacion_obtenida = $acti->calificacion_obtenida;


                            $ac->grade_item_id = $acti->grade_item_id;
                            $ac->courseid = $acti->courseid;
                            $ac->itemname = $acti->itemname;
                            $ac->itemmodule = $acti->itemmodule;
                            $ac->finalizacion = $acti->finalizacion;
                            $ac->fecha_valida = $acti->fecha_valida;

                            $fechaActividad = new DateTime($acti->finalizacion);
                            $hoy = new DateTime();

                            $year = $fechaActividad->format('Y');


                            $ac->tipo = ($year != 1969) ? "Obligatoria" : "Optativa";
                            $dataParticipacion = $this->PlataformasModel->obtiene_participacion(strtolower($a->plataforma), $acti->grade_item_id, $a->moodleid);

                            if ($hoy <= $fechaActividad) {
                                if ($dataParticipacion[0]->participation == 0) {
                                    $ac->message = "Aun no se encuentra la participacion del alumno";
                                    $ac->status = 1;
                                } else {
                                    $ac->message = "El alumno ya ha participado en la actividad";
                                    $ac->status = 0;
                                }
                            } else {
                                $ac->message = "La fecha de la entrega ha finalizado";
                                $ac->status = -1;
                            }
                        }
                    }
                    $m->actividades = $activitis;
                }

                foreach ($codigoMaterias as $key => $value_trimestre) {
                    if (stristr($m->fullname, $key)) {
                        $secciones[$value_trimestre][] = $m;
                    }
                }
            }
        }



        preg_match('/([A-Za-z]+)(\d+)([A-Za-z]+\d+)/', $matricula, $matches);
        if (!empty($matches)) {
            $numero = $matches[2];
            if ($numero == 24) {
                // Definir la matrícula

                // Construir la URL con la matrícula
                $url = "https://dockserver.lat/ver_adeudos?matricula=" . $matricula . "&muestra_todos=1&totales=1";

                // Inicializar cURL
                $ch = curl_init();

                // Configurar las opciones de cURL
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Opcional: Deshabilitar la verificación SSL (no recomendado para producción)

                // Ejecutar la solicitud cURL
                $response = curl_exec($ch);

                // Verificar si ocurrió algún error
                if ($response === false) {
                    $error = curl_error($ch);
                }

                $dataPagos = json_decode($response);
                // Cerrar cURL
                curl_close($ch);
            } else {
                $dataPagos = $this->PlataformasModel->obtenerPagosCRM($matricula);
            }
        }

        $data = array(
            "pagos" => $dataPagos,
            "historial_academico" => $secciones,
            "numero" => $numero
        );


        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/detalle_alumno.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('detalle_alumno', $data);
        $this->load->view('footer', array("js" => "assets/js/detalle_alumno.js"));
    }


    //Funcion principal que pinta la vista de todo el modulo
    public function index()
    {
        // Verificar sesión
        $this->check_session();

        // Obtener datos de sesión para el menú
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        // Verificar si hay roles en la sesión para mostrar opciones select
        $sesion = $this->session->userdata('seguimiento_iexe');
        $ver_select = (count($sesion['roles']) > 0) ? 1 : 0;

        // Obtener totales de alumnos por cada nivel de probabilidad
        $bloqueados = $this->AlumnosModel->no_alumnos_bloqueados();


        $total_r1 = $this->AlumnosModel->no_alumnos_por_probabilidad("r1");
        $total_r2 = $this->AlumnosModel->no_alumnos_por_probabilidad("r2");
        $total_r3 = $this->AlumnosModel->no_alumnos_por_probabilidad("r3");

        // Obtener el total de alumnos con seguimiento cerrado
        $total_cerrados = $this->AlumnosModel->obtener_alumnos_sin_seguimiento_cerrado();

        // Obtener el total de alumnos con seguimiento abierto
        $total_abiertos = $this->AlumnosModel->obtener_total_alumnos_seguimiento_abierto();

        // Obtener listas de consejeras y financieros
        $consejeras = $this->UsuariosModel->get_usuario_by_rol(2);
        $financieros = $this->UsuariosModel->get_usuario_by_rol(3);

        // Preparar datos para pasar a la vista
        $data = array(
            "total_r1" => $total_r1,
            "total_r2" => $total_r2,
            "total_r3" => $total_r3,
            "ver_select" => $ver_select,
            "total_cerrados" => $total_cerrados,
            "total_abiertos" => $total_abiertos,
            "consejeras" => $consejeras,
            "financieros" => $financieros,
            "bloqueados" => $bloqueados
        );

        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/lista_usuarios.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('lista_alumnos', $data);
        $this->load->view('footer', array("js" => "assets/js/lista_alumnos_test.js"));
    }

    //Validador de que este una sesion activa
    private function check_session()
    {
        if (!$this->session->userdata('seguimiento_iexe')) {
            redirect(base_url());
        }
    }



    public function data_tabla_inicial()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post("order");
        $order_column = 0;
        $order_dir = "asc";

        if (!empty($order)) {
            $order_column = $order[0]['column'];
            $order_dir = $order[0]['dir'];
        }


        $items = $this->AlumnosModel->get_limit_alumnos($start, $length, $order_column, $order_dir);
        $total_items = $this->AlumnosModel->get_todos_alumnos();


        $data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => count($total_items),
            "recordsFiltered" =>  count($total_items),
            "data" => $items
        );

        echo json_encode($data);
    }

    public function asignaciones_consejeras()
    {
        $this->check_session();
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        $dataConsejeras = $this->UsuariosModel->get_usuario_by_rol(2);
        $dataProgramasPeriodos = $this->AlumnosModel->programas_periodos_activos();
        $data = array("usuarios" => $dataConsejeras, "programasperiodos" => $dataProgramasPeriodos);

        $this->load->view('head', array("css" => "assets/css/asignaciones_consejeras.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('asignaciones_consejeras', $data);
        $this->load->view('footer', array("js" => "assets/js/asignaciones_consejeras.js"));
    }

    public function asignaciones_financiero()
    {
        $this->check_session();
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        $dataFinancieros = $this->UsuariosModel->get_usuario_by_rol(3);
        $data = array("usuarios" => $dataFinancieros);

        $this->load->view('head', array("css" => "assets/css/asignaciones_financiero.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('asignaciones_financiero', $data);
        $this->load->view('footer', array("js" => "assets/js/asignaciones_financiero.js"));
    }

    public function financiero_masivo()
    {

        if (!isset($_FILES['file'])) {
            $response = [
                'status' => 'error',
                'message' => 'No se ha enviado ningún archivo'
            ];
            echo json_encode($response);
            return;
        }

        $filePath = $_FILES['file']['tmp_name'];


        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Error al cargar el archivo Excel: ' . $e->getMessage()
            ];
            echo json_encode($response);
            return;
        }

        $errors = [];
        $this->db->trans_start();
        for ($i = 1; $i < count($data); $i++) {
            $matricula = $data[$i][0];
            $asesorFinanciero = $data[$i][1];

            // Validar si la matrícula existe
            $alumno = $this->AlumnosModel->get_alumnos_where(array("matricula" => $matricula));
            if (!$alumno) {
                $errors[] = "Error en la Fila $i (La matrícula $matricula no existe).";
                continue;
            }

            // Validar si el asesor financiero existe
            $asesor = $this->UsuariosModel->get_usuario_where(array("correo" => $asesorFinanciero));
            if (!is_object($asesor)) {
                $errors[] = "Error en la Fila $i (El asesor financiero $asesorFinanciero no existe).";
                continue;
            }

            // Actualizar el registro del alumno
            $updateData = [
                'financiero' => $asesor->id
            ];

            if (!$this->AlumnosModel->update_by_matricula($matricula, $updateData)) {
                $errors[] = "Fila $i: Error al actualizar la matrícula $matricula.";
            }
        }
        $this->db->trans_complete();

        if (count($errors) > 0) {
            $response = [
                'status' => 'error',
                'errors' => $errors,
                'message' => "Solo se actualizaron algunas matriculas, la carga del archivo muestra los siguientes errores:"
            ];
        } else {
            $response = [
                'status' => 'success',
                'message' => 'Archivo procesado exitosamente.'
            ];
        }

        echo json_encode($response);
    }

    public function consejera_masiva()
    {

        if (!isset($_FILES['file'])) {
            $response = [
                'status' => 'error',
                'message' => 'No se ha enviado ningún archivo'
            ];
            echo json_encode($response);
            return;
        }

        $filePath = $_FILES['file']['tmp_name'];


        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Error al cargar el archivo Excel: ' . $e->getMessage()
            ];
            echo json_encode($response);
            return;
        }

        $errors = [];
        $this->db->trans_start();
        for ($i = 1; $i < count($data); $i++) {
            $matricula = $data[$i][0];
            $consejera_fila = $data[$i][1];

            // Validar si la matrícula existe
            $alumno = $this->AlumnosModel->get_alumnos_where(array("matricula" => $matricula));
            if (!$alumno) {
                $errors[] = "Error en la Fila $i (La matrícula $matricula no existe).";
                continue;
            }

            // Validar si el asesor financiero existe
            $consejera = $this->UsuariosModel->get_usuario_where(array("correo" => $consejera_fila));
            if (!is_object($consejera)) {
                $errors[] = "Error en la Fila $i (La consejera $consejera_fila no existe).";
                continue;
            }

            // Actualizar el registro del alumno
            $updateData = [
                'consejera' => $consejera->id
            ];

            if (!$this->AlumnosModel->update_by_matricula($matricula, $updateData)) {
                $errors[] = "Fila $i: Error al actualizar la matrícula $matricula.";
            }
        }
        $this->db->trans_complete();

        if (count($errors) > 0) {
            $response = [
                'status' => 'error',
                'errors' => $errors,
                'message' => "Solo se actualizaron algunas matriculas, la carga del archivo muestra los siguientes errores:"
            ];
        } else {
            $response = [
                'status' => 'success',
                'message' => 'Archivo procesado exitosamente.'
            ];
        }

        echo json_encode($response);
    }





    public function dashboard()
    {
        $this->check_session();
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');


        $this->load->view('head', array("css" => "assets/css/dashboard.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('asignaciones');
        $this->load->view('footer', array("js" => "assets/js/dashboard.js"));
    }

    public function obtener_datos_alumnos_ajax_dashboard()
    {
        $dataAlumnos = $this->AlumnosModel->get_count_alumnos_por_programa();
        echo json_encode($dataAlumnos);
    }


    public function obtener_periodos_activos_consejeras($programa)
    {
        // Obtiene los periodos activos del modelo
        $periodos = $this->AlumnosModel->obtener_periodos_activos_consejeras($programa);

        // Convierte los resultados a formato JSON
        echo json_encode($periodos);
    }

    public function obtener_periodos_activos($programa)
    {
        // Obtiene los periodos activos del modelo
        $periodos = $this->AlumnosModel->obtener_periodos_activos($programa);

        // Convierte los resultados a formato JSON
        echo json_encode($periodos);
    }

    public function obtener_periodos_mensuales_activos($programa, $periodo)
    {
        // Obtiene los periodos activos del modelo
        $periodos = $this->AlumnosModel->obtener_periodos_mensuales_activos($programa, $periodo);

        // Convierte los resultados a formato JSON
        echo json_encode($periodos);
    }







    public function busuqeda_avanzada()
    {
        // Obtener los parámetros de paginación del DataTable
        $length = $this->input->post('length'); // Cantidad de registros por página
        $start = $this->input->post('start'); // Índice de inicio
        $draw = $this->input->post('draw'); // Número de veces que se ha dibujado la tabla
        $order = $this->input->post("order");



        $nombre = $this->input->post('nombre');
        $apellidos = $this->input->post('apellidos');
        $correo = $this->input->post('correo');
        $matricula = $this->input->post('matricula');
        $programa = $this->input->post('programa');
        $periodo = $this->input->post('periodo');
        $periodoMensual = $this->input->post('periodoMensual');
        $estatusPlataforma = $this->input->post('estatusPlataforma');
        $consejera = $this->input->post('consejera');
        $financiero = $this->input->post('financiero');


        // Preparar los parámetros para el modelo, solo si existen
        $where = array();
        if (!empty($nombre)) {
            $where['alumnos.nombre'] = $nombre;
        }
        if (!empty($apellidos)) {
            $where['alumnos.apellidos'] = $apellidos;
        }
        if (!empty($correo)) {
            $where['alumnos.correo'] = $correo;
        }
        if (!empty($matricula)) {
            $where['alumnos.matricula'] = $matricula;
        }
        if (!empty($programa)) {
            $where['alumnos.programa'] = $programa;
        }
        if (!empty($periodo)) {
            $where['alumnos.periodo'] = $periodo;
        }
        if (!empty($periodoMensual)) {
            $where['alumnos.periodoMensual'] = $periodoMensual;
        }
        if (!empty($estatusPlataforma)) {
            $where['alumnos.estatus_plataforma'] = $estatusPlataforma;
        }
        if (!empty($consejera)) {
            $where['alumnos.consejera'] = $consejera;
        }
        if (!empty($financiero)) {
            $where['alumnos.financiero'] = $financiero;
        }

        $order_column = 0;
        $order_dir = "asc";

        if (!empty($order)) {
            $order_column = $order[0]['column'];
            $order_dir = $order[0]['dir'];
        }
        $alumnos = $this->AlumnosModel->busuqeda_avanzada($where, $start, $length, $order_column, $order_dir);
        $alumnos_total = $this->AlumnosModel->total_busuqeda_avanzada($where);


        // Devolver los resultados en el formato esperado por DataTable
        $response = array(
            'draw' => $draw,
            'recordsTotal' => count($alumnos_total),
            'recordsFiltered' => count($alumnos_total),
            'data' => $alumnos // Datos de los alumnos paginados
        );

        // Devolver los datos como JSON
        echo json_encode($response);
    }



    public function alumnos_probabilidad_baja($nivel_probabilidad)
    {
        // Obtener los parámetros de paginación del DataTable
        $length = $this->input->post('length'); // Cantidad de registros por página
        $start = $this->input->post('start'); // Índice de inicio
        $draw = $this->input->post('draw'); // Número de veces que se ha dibujado la tabla

        $order = $this->input->post("order");
        $order_column = 0;
        $order_dir = "asc";

        if (!empty($order)) {
            $order_column = $order[0]['column'];
            $order_dir = $order[0]['dir'];
        }

        // Determinar las condiciones de la consulta según el nivel de probabilidad
        switch ($nivel_probabilidad) {
            case 'r1':
                $where = array('variable_academica' => 1, 'variable_financiera' => 1);
                break;
            case 'r2':
                $where = array('variable_academica' => 0, 'variable_financiera' => 1);
                break;
            case 'r3':
                $where = "((variable_academica = 1 AND variable_financiera = 0) OR (variable_academica = 0 AND variable_financiera = 0))";
                break;
            default:
                // Nivel de probabilidad no válido, devolver respuesta vacía o error
                // En este ejemplo, se devuelve una respuesta vacía
                $response = array(
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array()
                );
                echo json_encode($response);
                return;
        }


        // Obtener los alumnos con probabilidad de baja paginados y según el nivel de probabilidad
        $alumnos = $this->AlumnosModel->obtener_alumnos_probabilidad_baja($where, $start, $length, $order_column, $order_dir);
        $total_alumnos = $this->AlumnosModel->obtener_total_alumnos_probabilidad_baja($where);

        // Devolver los resultados en el formato esperado por DataTable
        $response = array(
            'draw' => $draw,
            'recordsTotal' => count($total_alumnos),
            'recordsFiltered' =>  count($total_alumnos),
            'data' => $alumnos // Datos de los alumnos paginados
        );

        // Devolver los datos como JSON
        echo json_encode($response);
    }


    public function alumnos_buscar_seguimientos($tipo)
    {
        // Obtener los parámetros de paginación del DataTable
        $length = $this->input->post('length'); // Cantidad de registros por página
        $start = $this->input->post('start'); // Índice de inicio
        $draw = $this->input->post('draw'); // Número de veces que se ha dibujado la tabla

        $order = $this->input->post("order");
        $order_column = 0;
        $order_dir = "asc";

        if (!empty($order)) {
            $order_column = $order[0]['column'];
            $order_dir = $order[0]['dir'];
        }



        // Obtener los alumnos con probabilidad de baja paginados y según el nivel de probabilidad
        $alumnos = $this->AlumnosModel->obtener_alumnos_seguimiento($tipo, $start, $length, $order_column, $order_dir);

        // Obtener el total de registros sin paginación
        $totalRegistros = $this->AlumnosModel->obtener_todos_alumnos_seguimiento($tipo);

        // Devolver los resultados en el formato esperado por DataTable
        $response = array(
            'draw' => $draw,
            'recordsTotal' => $totalRegistros, // Total de registros sin paginación
            'recordsFiltered' => $totalRegistros, // Total de registros después de aplicar filtros (en este caso no hay filtros)
            'data' => $alumnos // Datos de los alumnos paginados
        );

        // Devolver los datos como JSON
        echo json_encode($response);
    }

    public function ingresa_alumnos_activos()
    {

        // Obtener todos los alumnos de las plataformas externas
        $alumnos_plataformas = $this->PlataformasModel->obtener_alumnos_todas_plataformas();

        // Obtener todos los alumnos de la base de datos local
        $alumnos_prediccion  = $this->AlumnosModel->obtener_todos_alumnos_in_array();

        // Arreglo para almacenar los IDs de los alumnos de las plataformas externas
        $array_in = array();

        // Arreglo para almacenar los datos de los alumnos nuevos y actualizados
        $dataInsert = $dataUpdate = array();

        // Iterar sobre los alumnos de las plataformas externas
        foreach ($alumnos_plataformas as $ap) {
            // Crear un ID único para cada alumno basado en su programa y moodleid
            $creacion_id = $ap->programa . $ap->moodleid;

            // Agregar el ID a $array_in
            $array_in[] = $creacion_id;

            // Verificar si el ID existe en la lista de alumnos de la base de datos local
            if (in_array($creacion_id, $alumnos_prediccion)) {
                // Si el ID existe, actualizar los datos del alumno
                $dataUpdate[] = array(
                    "id" => $creacion_id,
                    "moodleid" => $ap->moodleid,
                    "plataforma" => $ap->plataforma,
                    "programa" => $ap->programa,
                    "matricula" => $ap->username,
                    "nombre" => $ap->firstname,
                    "apellidos" => $ap->lastname,
                    "sexo" => $ap->sexo,
                    "mes" => $ap->mes,
                    "correo" => $ap->email,
                    "primer_Acceso" => $ap->firstaccess,
                    "ultimo_Acceso" => $ap->lastaccess,
                    "estatus_plataforma" => $ap->estatus_plataforma,
                    "periodo_mensual" => (isset($ap->trimestre)) ? $ap->trimestre : $ap->cuatrimestre,
                    "periodo" => $ap->periodo,
                    "is_active" => 1
                );
            } else {
                // Si el ID no existe, agregar los datos del alumno como nuevo
                $dataInsert[] = array(
                    "id" => $creacion_id,
                    "moodleid" => $ap->moodleid,
                    "plataforma" => $ap->plataforma,
                    "programa" => $ap->programa,
                    "matricula" => $ap->username,
                    "nombre" => $ap->firstname,
                    "apellidos" => $ap->lastname,
                    "sexo" => $ap->sexo,
                    "mes" => $ap->mes,
                    "correo" => $ap->email,
                    "primer_Acceso" => $ap->firstaccess,
                    "ultimo_Acceso" => $ap->lastaccess,
                    "estatus_plataforma" => $ap->estatus_plataforma,
                    "periodo_mensual" => (isset($ap->trimestre)) ? $ap->trimestre : $ap->cuatrimestre,
                    "periodo" => $ap->periodo,
                    "is_active" => 1,
                    "variable_academica" => 0,
                    "variable_financiera" => 0
                );
            }
        }

        // Insertar datos de los nuevos alumnos
        if (!empty($dataInsert)) {
            $this->AlumnosModel->insert_batch($dataInsert);
        }

        // Actualizar datos de los alumnos existentes
        if (!empty($dataUpdate)) {
            $this->AlumnosModel->update_batch($dataUpdate);
        }

        // Desactivar alumnos que no están presentes en las plataformas externas
        if (!empty($array_in)) {
            $this->AlumnosModel->desactivar_batch($array_in);
        }
    }



    public function variable_academica($programa)
    {

        // Queda pendiente MAN
        // Establecer la zona horaria
        date_default_timezone_set('America/Mexico_City');

        // Obtener la configuración de las materias activas para el programa dado
        $materias_activas = $this->MateriasActivasModel->materias_activas_programa($programa);


        // Obtener la lista de alumnos activos para el programa y la fecha actual
        $alumnos = $this->AlumnosModel->alumno_activo_programa_academico($programa, date("Y-m-d"));

        // Inicializar contador y arreglo para actualizar registros
        $i = 0;
        $actualiza_registros = array();

        // Iterar sobre cada alumno activo
        foreach ($alumnos as $a) {

            // Establecer la fecha de actualización cron
            $a->update_cron_academico =  date("Y-m-d");

            // Obtener las materias en las que está inscrito el alumno
            $materias_enroladas  = $this->PlataformasModel->get_materia_activa($a->moodleid, strtolower($a->plataforma), $materias_activas[0]->idsmaterias);

            // Bandera para indicar si el alumno ha sido dado de baja académicamente
            $es_baja = false;

            // Verificar si el alumno está inscrito en materias
            if (is_array($materias_enroladas) && !empty($materias_enroladas)) {
                foreach ($materias_enroladas as $m) {

                    // Obtener el código de la materia
                    $array_codigo = explode('-', $m->fullname);
                    $codigo = $array_codigo[0];

                    // Obtener la fórmula configurada para la materia
                    $formula_materia = $this->PlataformasModel->formula_materia($m->id, strtolower($a->plataforma), $codigo);

                    // Obtener las actividades de la fórmula
                    preg_match_all("/gi\d+/", $formula_materia->calculation, $actividades);
                    $actividades = str_replace("gi", "", $actividades[0]);

                    // Convertir el arreglo de actividades en una cadena separada por comas
                    $string_in = implode(',', $actividades);

                    // Obtener información de las actividades
                    $lista_actividades = $this->PlataformasModel->get_info_actividades(strtolower($a->plataforma), $string_in);

                    // Verificar si el alumno ha participado en las actividades
                    foreach ($lista_actividades as  $ac) {
                        $fechaActividad = new DateTime($ac->finalizacion);
                        $hoy = new DateTime();

                        $year = $fechaActividad->format('Y');

                        // Verificar si la fecha de la actividad es válida
                        if ($year != 1969) {
                            $dataParticipacion = $this->PlataformasModel->obtiene_participacion(strtolower($a->plataforma), $ac->grade_item_id, $a->moodleid);

                            // Verificar si el alumno no ha participado y la fecha de la actividad ha pasado
                            if ($dataParticipacion[0]->participation == 0 && $hoy > $fechaActividad) {
                                $es_baja = true;
                                break;
                            }
                        }
                    }
                    if ($es_baja) break;
                }
            }

            // Actualizar la variable académica del alumno en función de si ha sido dado de baja o no
            if ($es_baja) {
                $a->variable_academica =  1;
            } else {
                $a->variable_academica = 0;
            }

            // Agregar el alumno al arreglo de actualización de registros
            $actualiza_registros[] = $a;
            $i++;

            // Actualizar los registros en lotes de 100 alumnos
            if ($i == 100) {
                $this->AlumnosModel->update_batch($actualiza_registros);
                $i = 0;
                $actualiza_registros = array();
            }
        }

        // Actualizar los registros restantes si existen
        if (!empty($actualiza_registros)) {
            $this->AlumnosModel->update_batch($actualiza_registros);
        }
    }


    public  function get_estatus_financiero_api($matricula)
    {
        $matricula = strtoupper($matricula);
        $data = array(
            "matriculas" => array(strtoupper($matricula))
        );
        // Inicializar cURL
        $ch = curl_init($this->URL_API_FINACIERO);

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Ejecutar la solicitud y obtener la respuesta
        $response = json_decode(curl_exec($ch));

        // Verificar si hubo algún error

        if (curl_errno($ch)) {
            return -1;
            curl_close($ch);
        } else {

            if ($response->status == 'ok') {
                if (property_exists($response->retrasos, $matricula)) {
                    return ($response->retrasos->$matricula > 0) ? 1 : 0;
                } else {
                    return 0;
                }
            } else {
                return -1;
            }
            curl_close($ch);
        }
    }

    public function variable_financiera()
    {
        // Configura la zona horaria a Ciudad de México
        date_default_timezone_set('America/Mexico_City');

        // Obtenemos los alumnos por programa y fecha actual
        $alumnos = $this->AlumnosModel->get_all_alumnos();
        //$alumnos = $this->AlumnosModel->get_all_alumnos_where(array("variable_financiera" => -1));


        $i = 0;
        $actualiza_registros = array();
        // Obtiene el nombre del mes actual
        $mes = $this->obtenerNombreMes(date('m'));



        $i = 0;
        foreach ($alumnos as $a) {
            // Actualiza la fecha de cron financiero del alumno
            $a->update_cron_financiero =  date("Y-m-d");
            // Extrae los números entre las letras de la matrícula del alumno
            preg_match('/([A-Za-z]+)(\d+)([A-Za-z]+\d+)/', $a->matricula, $matches);
            if (!empty($matches)) {
                // Obtiene el número extraído de la matrícula
                $numero = $matches[2];

                if ($numero == 24) {
                    // Si el número es 24, obtiene el estatus financiero del alumno a través de una API
                    $a->variable_financiera = $this->get_estatus_financiero_api($a->matricula);
                } else {
                    // Verifica si el alumno tiene pagos del mes actual
                    $dia_moroso = $this->obtiene_dia_moroso($a->matricula);
                    $hoy = date('d');
                    if ($hoy > $dia_moroso) {
                        //Quiere decir que ya debe pagar mensualidad
                        $pagos = $this->PlataformasModel->pago_del_mes(strtoupper($mes), date('Y'), $a->matricula);

                        $a->variable_financiera = (empty($pagos)) ? 1 : 0;
                    } else {
                        //Verificamos si tiene el pago del mes anterior
                        $mes_anterior = $this->obtenerNombreMes(str_pad(date("m") - 1, 2, "0", STR_PAD_LEFT));
                        $pago_mes_anterior = $this->PlataformasModel->pago_del_mes(strtoupper($mes_anterior), date('Y'), $a->matricula);

                        $a->variable_financiera = (empty($pago_mes_anterior)) ? 1 : 0;
                    }
                }
            } else {
                // Si no se puede extraer el número de la matrícula, se asigna un valor por defecto
                $a->variable_financiera = -2;
            }

            // Si se han procesado 100 alumnos, se actualizan los registros en la base de datos
            $actualiza_registros[] = $a;
            $i++;
            if ($i == 100) {
                $this->AlumnosModel->update_batch($actualiza_registros);
                $i = 0;
                $actualiza_registros = array();
            }
        }

        // Si quedan registros por actualizar, se realiza la actualización final
        if (!empty($actualiza_registros)) {
            $this->AlumnosModel->update_batch($actualiza_registros);
        }
    }


    private function obtenerNombreMes($numeroMes)
    {
        // Array asociativo con los nombres de los meses
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );

        // Verificar si el número de mes está en el rango válido
        if (array_key_exists($numeroMes, $meses)) {
            return $meses[$numeroMes];
        } else {
            return 'Mes inválido';
        }
    }

    private function  obtiene_dia_moroso($matricula)
    {
        preg_match('/^[A-Za-z]+/', $matricula, $matches);
        $matricula  = strtoupper($matches[0]);

        if (
            $matricula == 'MAPP'  || $matricula == 'MEPP'  || $matricula == 'MSPPP' || $matricula == 'MFP'   ||
            $matricula == 'MBA'   || $matricula == 'MIGE'  || $matricula == 'MMPOP' || $matricula == 'MADIS' ||
            $matricula == 'MAG'   || $matricula == 'MGPM'  || $matricula == 'MSPJO' || $matricula == 'MCDA'  ||
            $matricula == 'MCDIA' || $matricula == 'MITI'  || $matricula == 'MAN'   || $matricula == 'MSPAJO'
        ) {
            $moroso = 21;
        } elseif ($matricula == 'LD') {
            $moroso = 11;
        } elseif ($matricula == 'DPP' || $matricula == 'DSP') {
            $moroso = 11;
        } elseif ($matricula == 'LSP' || $matricula == 'LAE' || $matricula == 'LCPAP') {
            $moroso = 11;
        }
        return $moroso;
    }

    public function variable_financiera_matricula($matricula)
    {
        $mes = $this->obtenerNombreMes(date('m'));

        // Extrae los números entre las letras de la matrícula del alumno
        preg_match('/([A-Za-z]+)(\d+)([A-Za-z]+\d+)/', $matricula, $matches);
        if (!empty($matches)) {
            // Obtiene el número extraído de la matrícula
            $numero = $matches[2];
            $dia_moroso = $this->obtiene_dia_moroso($matricula);

            if ($numero == 24) {
                // Si el número es 24, obtiene el estatus financiero del alumno a través de una API
                $variable_financiera = $this->get_estatus_financiero_api($matricula);
                $message =  ($variable_financiera == 0) ? "El alumno se encuentra al corrien" : "El alumno cuenta con atraso financiero";
            } else {
                // Verifica si el alumno tiene pagos del mes actual
                $hoy = date('d');
                if ($hoy > $dia_moroso) {
                    //Quiere decir que ya debe pagar mensualidad
                    $pagos = $this->PlataformasModel->pago_del_mes(strtoupper($mes), date('Y'), $matricula);
                    if (empty($pago_mes_anterior)) {
                        $variable_financiera = 1;
                        $message = 'No se encuentra pago del mes anterior';
                    } else {
                        $variable_financiera = (empty($pagos)) ? 1 : 0;
                        $message = "Ultimo pago del alumno: " . $pagos[0]->Descripcion;
                    }
                } else {
                    //Verificamos si tiene el pago del mes anterior
                    $mes_anterior = $this->obtenerNombreMes(str_pad(date("m") - 1, 2, "0", STR_PAD_LEFT));
                    $pago_mes_anterior = $this->PlataformasModel->pago_del_mes(strtoupper($mes_anterior), date('Y'), $matricula);
                    if (empty($pago_mes_anterior)) {
                        $variable_financiera = 1;
                        $message = 'No se encuentra pago del mes anterior';
                    } else {
                        $variable_financiera = 0;
                        $message = "Ultimo pago del alumno: " . $pago_mes_anterior[0]->Descripcion;
                    }
                    //$variable_financiera = (empty($pago_mes_anterior)) ? 1 : 0;
                }
            }
        } else {
            // Si no se puede extraer el número de la matrícula, se asigna un valor por defecto
            $message =  "La matricula " . $matricula . " no se encuentra en el sistema.";
            $variable_financiera = NULL;
        }

        $response = new stdClass();
        $response->message = $message;
        $response->variable_financiera = $variable_financiera;
        $response->moroso = $dia_moroso;


        return $response;
    }

    public function probabilidad_baja($matricula)
    {
        $vfinanciera =  $this->variable_financiera_matricula($matricula);
        $vacademica =  $this->variable_academica_alumno($matricula);

        echo json_encode(array("financiera" => $vfinanciera, "academica" => $vacademica));
    }

    public function variable_academica_alumno($matricula)
    {

        #Obtenemos el programa de la matricula
        $infoalumno = $this->AlumnosModel->get_all_alumnos_where(array('matricula' => $matricula));
        $programa = $infoalumno[0]->programa;

        // Obtener la configuración de las materias activas para el programa dado
        $materias_activas = $this->MateriasActivasModel->materias_activas_programa($programa);


        // Iterar sobre cada alumno activo
        foreach ($infoalumno as $a) {

            // Obtener las materias en las que está inscrito el alumno
            $materias_enroladas  = $this->PlataformasModel->get_materia_activa($a->moodleid, strtolower($a->plataforma), $materias_activas[0]->idsmaterias);

            // Bandera para indicar si el alumno ha sido dado de baja académicamente

            // Verificar si el alumno está inscrito en materias
            if (is_array($materias_enroladas) && !empty($materias_enroladas)) {
                foreach ($materias_enroladas as $m) {

                    // Obtener el código de la materia
                    $array_codigo = explode('-', $m->fullname);
                    $codigo = $array_codigo[0];

                    // Obtener la fórmula configurada para la materia
                    $formula_materia = $this->PlataformasModel->formula_materia($m->id, strtolower($a->plataforma), $codigo);

                    // Obtener las actividades de la fórmula
                    preg_match_all("/gi\d+/", $formula_materia->calculation, $actividades);
                    $actividades = str_replace("gi", "", $actividades[0]);

                    // Convertir el arreglo de actividades en una cadena separada por comas
                    $string_in = implode(',', $actividades);

                    // Obtener información de las actividades
                    $lista_actividades = $this->PlataformasModel->get_info_actividades(strtolower($a->plataforma), $string_in);

                    // Verificar si el alumno ha participado en las actividades
                    foreach ($lista_actividades as  $ac) {
                        $fechaActividad = new DateTime($ac->finalizacion);
                        $hoy = new DateTime();

                        $year = $fechaActividad->format('Y');

                        // Verificar si la fecha de la actividad es válida
                        if ($year != 1969) {
                            $dataParticipacion = $this->PlataformasModel->obtiene_participacion(strtolower($a->plataforma), $ac->grade_item_id, $a->moodleid);
                            $ac->opcional = 0;
                            // Verificar si el alumno no ha participado y la fecha de la actividad ha pasado
                            if ($dataParticipacion[0]->participation == 0 && $hoy > $fechaActividad) {
                                $ac->notificacion = 1;
                            } else {
                                $ac->notificacion = 0;
                            }
                        } else {
                            $ac->opcional = 1;
                            $ac->notificacion = 0;
                        }
                    }
                    $m->actividades = $lista_actividades;
                }
                $a->materia = $materias_enroladas;
            }
        }


        return $infoalumno[0];
    }


    public function asignar_consejera_masivo()
    {
        $id_consejera = $this->input->post('idusuario');
        $programa = $this->input->post('programa');
        $periodo = $this->input->post('periodo');
        $existe = $this->AlumnosModel->existe_alumno_con_consejera($id_consejera, $programa, $periodo);

        if ($existe) {
            echo json_encode(array('exists' => 1));
        } else {
            $this->AlumnosModel->asigna_alumno_consejera($id_consejera, $periodo, $programa);
            echo json_encode(array('exists' => 0));
        }
        // Devolver la respuesta en formato JSON
    }

    public function verifica_financiero_alumno()
    {
        // Obtén la matrícula del alumno desde la solicitud POST
        $matricula = $this->input->post('matricula');

        // Carga el modelo si aún no está cargado
        $this->load->model('AlumnosModel');

        // Llama a la función del modelo para verificar si el alumno tiene asesor financiero
        $existe = $this->AlumnosModel->existe_alumno_con_financiero($matricula);

        // Imprime la última consulta ejecutada (para depuración)
        // echo $this->db->last_query();

        // Prepara la respuesta JSON
        if ($existe) {
            echo json_encode(array('exists' => 1));
        } else {
            echo json_encode(array('exists' => 0));
        }
    }

    public function asignar_financiero_matricula()
    {
        $financiero = $this->input->post('idusuario');
        $matricula = $this->input->post('matricula');
        $this->AlumnosModel->asigna_alumno_financiero($financiero, $matricula);
        echo json_encode(array('response' => 'ok'));
    }


    public function descarga_excel()
    {
        // Obtener los datos de los alumnos
        $dataAlumnos = $this->AlumnosModel->get_todos_alumnos();

        // Convertir los objetos a arrays
        $dataAlumnos = json_decode(json_encode($dataAlumnos), true);

        // Verifica si hay datos
        if (empty($dataAlumnos)) {
            echo "No hay datos disponibles para exportar.";
            return;
        }

        // Definir nombres personalizados para las columnas
        $customColumnNames = [
            'nombre' => 'ALUMNO',
            'matricula' => 'MATRICULA',
            'correo' => 'CORREO ELECTRONICO',
            'programa' => 'PROGRAMA',
            'periodo_mensual' => 'TRIMESTRE/CUATRIMESTRE',
            'estatus_plataforma' => 'ESTATUS EN PLATAFORMA',
            'probabilidad_baja' => 'PROBABILIDAD DE BAJA',
            'nombre_consejera' => 'CONSEJERA',
            'nombre_financiero' => 'ASESOR FINANCIERO'
        ];

        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3366FF']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],

        ];

        // Añadir encabezados personalizados
        $columnLetter = 'A';
        foreach ($customColumnNames as $originalName => $customName) {
            $sheet->setCellValue($columnLetter . '1', $customName);
            $sheet->getStyle($columnLetter . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true); // Ajustar el ancho automáticamente

            $columnLetter++;
        }

        // Añadir datos
        $rowNumber = 2;
        foreach ($dataAlumnos as $row) {
            // Aplicar color de fondo según probabilidad_baja
            switch ($row['probabilidad_baja']) {
                case 'Baja R3':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6FE29D']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],

                    ];
                    //$backgroundStyle = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF0000']];
                    break;
                case 'Media R2':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0EA75']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],

                    ];
                    //$backgroundStyle = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']];
                    break;
                case 'Alta R1':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F98989']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],

                    ];
                    //$backgroundStyle = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D16060']];
                    break;
                default:
                    $backgroundStyle = null;
                    break;
            }

            // Aplicar estilo de fondo condicional a toda la fila
            if ($backgroundStyle) {
                $sheet->getStyle('A' . $rowNumber . ':I' . $rowNumber)->applyFromArray($backgroundStyle);
            }

            $columnLetter = 'A';
            foreach ($customColumnNames as $originalName => $customName) {
                $sheet->setCellValue($columnLetter . $rowNumber, $row[$originalName]);
                $columnLetter++;
            }
            $rowNumber++;
        }

        // Configurar el tipo de respuesta HTTP y enviar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="alumnos_data.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
