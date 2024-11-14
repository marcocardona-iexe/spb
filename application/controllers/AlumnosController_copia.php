<?php
// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City');
defined('BASEPATH') or exit('No direct script access allowed');
include 'materiasactivas.class.php';

require_once APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AlumnosController_copia extends CI_Controller
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PlataformasModel');
        $this->load->model('AlumnosModel_copia');
        $this->load->model('MateriasActivasModel');
        $this->load->model('UsuariosModel');

        $this->load->helper('url');
        $this->load->library('session');

        $this->URL_API_FINACIERO = "https://beta.dockserver.lat/retraso_en_pagos";
        $this->isopais = array(
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "BS" => "Bahamas",
            "BB" => "Barbados",
            "BZ" => "Belize",
            "BO" => "Bolivia",
            "BR" => "Brazil",
            "CA" => "Canada",
            "CL" => "Chile",
            "CO" => "Colombia",
            "CR" => "Costa Rica",
            "CU" => "Cuba",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "SV" => "El Salvador",
            "GD" => "Grenada",
            "GT" => "Guatemala",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HN" => "Honduras",
            "JM" => "Jamaica",
            "MX" => "Mexico",
            "NI" => "Nicaragua",
            "PA" => "Panama",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "VC" => "Saint Vincent and the Grenadines",
            "SR" => "Suriname",
            "TT" => "Trinidad and Tobago",
            "US" => "United States of America",
            "UY" => "Uruguay",
            "VE" => "Venezuela"
        );
    }

    public function testeo()
    {
        $data = $this->PlataformasModel->local_materias_activas();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function elimnar_alumnos()
    {

        try {

            $logFile = APPPATH . 'registroCron/elimnarAlumnos.txt';
            $logEntry = date('Y-m-d H:i:s') . "| elimnarAlumnos" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        } catch (Exception $e) {
        }

        $response = $this->PlataformasModel->eliminar_alumnos(); 
        $chunks = array_chunk($response, 100);
        
        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                $this->AlumnosModel->elimnar_alumnos($item->username);
            }
        }
        
    }

    public function ingresa_alumnos_activos()
    {

        try {

            $logFile = APPPATH . 'registroCron/IngresaAlumnosActivos.txt';
            $logEntry = date('Y-m-d H:i:s') . "| ingresa_alumnos_activos" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        } catch (Exception $e) {
        }

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

            if ($ap->username != 'mapp24x001' && $ap->username != 'mige24x001') {
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
                        "is_active" => 1,
                        "estatus_descripcion" => $ap->descripcionestatus
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
                        "variable_financiera" => 0,
                        "estatus_descripcion" => $ap->descripcionestatus
                    );
                }
            }
        }

        $batchSize = 100;
        
        if (!empty($array_in)) {
            foreach (array_chunk($array_in, $batchSize) as $batch) {
                $this->AlumnosModel->desactivar_batch($batch);
            }
        }
        
        if (!empty($dataInsert)) {
            foreach (array_chunk($dataInsert, $batchSize) as $batch) {
                $this->AlumnosModel->insert_batch($batch);
            }
        }
        
        if (!empty($dataUpdate)) {
            foreach (array_chunk($dataUpdate, $batchSize) as $batch) {
                $this->AlumnosModel->update_batch($batch);
            }
        }

        $this->ultimoBloqueo();

    }


    // Función principal que pinta la vista de todo el módulo
    public function index()
    {
        // Verificar sesión
        $this->check_session();

        // Obtener datos de sesión para el menú
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        // Verificar si hay roles en la sesión para mostrar opciones select
        $sesion = $this->session->userdata('seguimiento_iexe');
        $ver_select = (count($sesion['roles']) > 0) ? 1 : 0;

        // Obtener totales de alumnos por estatus de bloqueo
        $activos = $this->AlumnosModel_copia->no_alumnos_activos();
        $bloqueados = $this->AlumnosModel_copia->no_alumnos_bloqueados();
        //$inscritos = $this->AlumnosModel_copia->no_alumnos_inscritos();
        // Obtener totales de alumnos por cada nivel de probabilidad
        $total_r1 = $this->AlumnosModel_copia->no_alumnos_por_probabilidad("r1");
        $total_r2 = $this->AlumnosModel_copia->no_alumnos_por_probabilidad("r2");
        $total_r3 = $this->AlumnosModel_copia->no_alumnos_por_probabilidad("r3");


        // Obtener listas de consejeras y financieros
        $consejeras = $this->UsuariosModel->get_usuario_by_rol(2);
        $financieros = $this->UsuariosModel->get_usuario_by_rol(3);

        // Preparar datos para pasar a la vista
        $data = array(
            "total_r1" => $total_r1,
            "total_r2" => $total_r2,
            "total_r3" => $total_r3,
            "ver_select" => $ver_select,
            "consejeras" => $consejeras,
            "financieros" => $financieros,
            "bloqueados" => $bloqueados,
            "activos" => $activos,
            //"inscritos" => $inscritos
        );

        // Cargar vistas y pasar datos a ellas
        $this->load->view('head', array("css" => "assets/css/lista_usuarios.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('lista_alumnos_copia', $data);
        $this->load->view('footer', array("js" => "assets/js/lista_alumnos_copia.js"));
    }


    private function response_to_datatable($model, $get_limit_method, $get_all_method, $where, $user_where)
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


        $items = $this->$model->$get_limit_method($start, $length, $order_column, $order_dir, $where, $user_where);
        $total_items = $this->$model->$get_all_method($where);

        $data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => count($items),
            "recordsFiltered" =>  count($total_items),
            "data" => $items
        );

        echo json_encode($data);
    }

    public function alumnos_inscritos()
    {
        $this->response_to_datatable('AlumnosModel', 'get_inscritos', 'get_alumnos_where_inscritos', array("estatus_descripcion" => "Inscrito"), true);
    }

    public function alumnos_bloqueados()
    {
        $this->response_to_datatable('AlumnosModel', 'get_bloqueados', 'get_alumnos_where', array("estatus_descripcion" => "Bloqueado por pagos"), true);
    }

    public function alumnos_activos()
    {
        $this->response_to_datatable('AlumnosModel', 'get_activos', 'get_alumnos_where_activos', array("estatus_descripcion" => "Bloqueado por pagos"), true);
    }

    public function alumnos_probabilidad_baja($nivel_probabilidad)
    {
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
                return;
        }
        $this->response_to_datatable('AlumnosModel', 'get_por_probabilidad_baja', 'get_por_probabilidad_baja_total', $where, true);
    }


    public function alumnos_buscar_seguimientos($tipo)
    {
        $this->response_to_datatable('AlumnosModel', 'get_alumnos_por_seguimiento', 'get_total_por_seguimiento', $tipo, true);
    }


    private function get_data($matricula)
    {
        $dataMaterias = new materiasactivas();
        preg_match('/[^0-9]+/', $matricula, $programa);


        $siglas = (count($programa) > 0) ? $programa[0] : "";
        switch (strtolower($siglas)) {
                #lic
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

            default:
                $arr_tipo  = null;
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




    //Validador de que este una sesion activa
    private function check_session()
    {
        if (!$this->session->userdata('seguimiento_iexe')) {
            redirect(base_url());
        }
    }



    public function data_tabla_inicial()
    {



        $this->response_to_datatable('AlumnosModel_copia', 'get_limit_alumnos', 'get_todos_alumnos', null, false);
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
        $dataAsignaciones = $this->UsuariosModel->get_usuarios_financieros_alumnos_total();

        $data = array("usuarios" => $dataFinancieros, "asignaciones" => $dataAsignaciones);

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
        $this->load->view('dashboard');
        $this->load->view('footer', array("js" => "assets/js/dashboard.js"));
    }

    public function obtener_datos_alumnos_ajax_dashboard()
    {



        $mapp = $this->AlumnosModel->get_count_alumnos_por_programa('mapp');
        $mepp = $this->AlumnosModel->get_count_alumnos_por_programa('mepp');
        $mspp = $this->AlumnosModel->get_count_alumnos_por_programa('mspp');
        $dpp = $this->AlumnosModel->get_count_alumnos_por_programa('dpp');
        $dsp = $this->AlumnosModel->get_count_alumnos_por_programa('dsp');
        $lae = $this->AlumnosModel->get_count_alumnos_por_programa('lae');
        $lce = $this->AlumnosModel->get_count_alumnos_por_programa('lce');
        $lcpap = $this->AlumnosModel->get_count_alumnos_por_programa('lcpap');
        $ld = $this->AlumnosModel->get_count_alumnos_por_programa('ld');
        $lsp = $this->AlumnosModel->get_count_alumnos_por_programa('lsp');
        $madis = $this->AlumnosModel->get_count_alumnos_por_programa('mais');
        $mag = $this->AlumnosModel->get_count_alumnos_por_programa('mag');
        $man = $this->AlumnosModel->get_count_alumnos_por_programa('man');
        $mcd = $this->AlumnosModel->get_count_alumnos_por_programa('mcd');
        $mfp = $this->AlumnosModel->get_count_alumnos_por_programa('mfp');
        $mgpm = $this->AlumnosModel->get_count_alumnos_por_programa('mgpm');
        $mige = $this->AlumnosModel->get_count_alumnos_por_programa('mige');
        $miti = $this->AlumnosModel->get_count_alumnos_por_programa('miti');
        $mmpop = $this->AlumnosModel->get_count_alumnos_por_programa('mmpop');
        $mspajo = $this->AlumnosModel->get_count_alumnos_por_programa('mspajo');

        $dataGrafica = [
            ['programa' => 'DPP', 'val' => $dpp],
            ['programa' => 'DSP', 'val' => $dsp],
            ['programa' => 'LAE', 'val' => $lae],
            ['programa' => 'LCE', 'val' => $lce],
            ['programa' => 'LD', 'val' => $ld],
            ['programa' => 'LSP', 'val' => $lsp],
            ['programa' => 'LCPAP', 'val' => $lcpap],
            ['programa' => 'MAPP', 'val' => $mapp],
            ['programa' => 'MEPP', 'val' => $mepp],
            ['programa' => 'MSPP', 'val' => $mspp],
            ['programa' => 'MAIS', 'val' => $madis],
            ['programa' => 'MAG', 'val' => $mag],
            ['programa' => 'MAN', 'val' => $man],
            ['programa' => 'MCD', 'val' => $mcd],
            ['programa' => 'MFP', 'val' => $mfp],
            ['programa' => 'MGPM', 'val' => $mgpm],
            ['programa' => 'MIGE', 'val' => $mige],
            ['programa' => 'MITI', 'val' => $miti],
            ['programa' => 'MMPOP', 'val' => $mmpop],
            ['programa' => 'MSPAJO', 'val' => $mspajo],

        ];

        $dataTable = [
            ['programa' => 'Doctorado en Políticas Públicas', 'matricula' => 'DPP', 'val' => $dpp],
            ['programa' => 'Doctorado en Seguridad Pública', 'matricula' => 'DSP', 'val' => $dsp],
            ['programa' => 'Licenciatura en Administración de Empresas', 'matricula' => 'LAE', 'val' => $lae],
            ['programa' => 'Licenciatura en Ciencias de la Educación', 'matricula' => 'LCE', 'val' => $lce],
            ['programa' => 'Licenciatura en Derecho', 'matricula' => 'LD', 'val' => $ld],
            ['programa' => 'Licenciatura en Seguridad Pública', 'matricula' => 'LSP', 'val' => $lsp],
            ['programa' => 'Licenciatura en Ciencias Políticas y Administración Pública', 'matricula' => 'LCPAP', 'val' => $lcpap],
            ['programa' => 'Maestría en Evaluación de Políticas Públicas', 'matricula' => 'MEPP', 'val' => $mepp],
            ['programa' => 'Maestría en Administración y Políticas Públicas', 'matricula' => 'MAPP', 'val' => $mapp],
            ['programa' => 'Maestría en Seguridad Pública y Políticas Públicas', 'matricula' => 'MSPP', 'val' => $mspp],
            ['programa' => 'Maestría en Instituciones de Salud', 'matricula' => 'MADIS', 'val' => $madis],
            ['programa' => 'Maestría en Auditoría Gubernamental', 'matricula' => 'MAG', 'val' => $mag],
            ['programa' => 'Maestría en Administración de Negocios', 'matricula' => 'MAN', 'val' => $man],
            ['programa' => 'Maestría en Ciencias de Datos', 'matricula' => 'MCD', 'val' => $mcd],
            ['programa' => 'Maestría en Finanzas Públicas', 'matricula' => 'MFP', 'val' => $mfp],
            ['programa' => 'Maestría en Gestión Pública Municipal', 'matricula' => 'MGPM', 'val' => $mgpm],
            ['programa' => 'Maestría en Innovación y Gestión Educativa', 'matricula' => 'MIGE', 'val' => $mige],
            ['programa' => 'Maestría en Ingeniería en Tecnologias de la Información y la Comunicación', 'matricula' => 'MITI', 'val' => $miti],
            ['programa' => 'Maestría en Marketing Político y Opinión Pública', 'matricula' => 'MMPOP', 'val' => $mmpop],
            ['programa' => 'Maestría en Sistema Penal Acusatorio y Juicio Oral', 'matricula' => 'MSPAJO', 'val' => $mspajo],

        ];

        $data = array(
            "grafica" => $dataGrafica,
            "tabla" => $dataTable
        );
        echo json_encode($data);
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



        $where = array();
        if (!empty($nombre)) {
            $where['alumnos.nombre LIKE'] = '%' . $nombre . '%';
        }
        if (!empty($apellidos)) {
            $where['alumnos.apellidos LIKE'] = '%' . $apellidos . '%';
        }
        if (!empty($correo)) {
            $where['alumnos.correo LIKE'] = '%' . $correo . '%';
        }
        if (!empty($matricula)) {
            $where['alumnos.matricula LIKE'] = '%' . $matricula . '%';
        }
        if (!empty($programa)) {
            $where['alumnos.programa LIKE'] = '%' . $programa . '%';
        }
        if (!empty($periodo)) {
            $where['alumnos.periodo LIKE'] = '%' . $periodo . '%';
        }
        if (!empty($periodoMensual)) {
            $where['alumnos.periodoMensual LIKE'] = '%' . $periodoMensual . '%';
        }
        if (!empty($estatusPlataforma)) {
            $where['alumnos.estatus_plataforma LIKE'] = '%' . $estatusPlataforma . '%';
        }
        if (!empty($consejera)) {
            $where['alumnos.consejera LIKE'] = '%' . $consejera . '%';
        }
        if (!empty($financiero)) {
            $where['alumnos.financiero LIKE'] = '%' . $financiero . '%';
        }


        $this->response_to_datatable('AlumnosModel', 'busuqeda_avanzada', 'total_busuqeda_avanzada', $where, true);
    }





    public function variable_academica($programa)
    {

        try {

            $logFile = APPPATH . 'registroCron/variableAcademica.txt';
            $logEntry = date('Y-m-d H:i:s') . "| variableAcademica". $programa . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        } catch (Exception $e) {
        }

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

        try {

            $logFile = APPPATH . 'registroCron/variableFinanciera.txt';
            $logEntry = date('Y-m-d H:i:s') . "| variableFinanciera" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
            
        } catch (Exception $e) {
        }

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
            $matricula == 'MAPP'  || $matricula == 'MEPP'  || $matricula == 'MSPPP' || $matricula == 'MFP'   || $matricula == 'MAIS' ||
            $matricula == 'MBA'   || $matricula == 'MIGE'  || $matricula == 'MMPOP' || $matricula == 'MADIS' || $matricula == 'MSPP' ||
            $matricula == 'MAG'   || $matricula == 'MGPM'  || $matricula == 'MSPJO' || $matricula == 'MCDA'  || $matricula == 'MCDIA'  ||
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

    public function variable_financiera_matricula_modificado($matricula)
    {
        $url = "https://dockserver.lat/ver_adeudos?totales=1&muestra_todos=1&datos_p=1&matricula=" . $matricula;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $decodedResponse = json_decode($response, true);
        
        curl_close($ch);
        
        $fechaServidor = date('Y-m-d H:i:s');
        $timestampServidor = strtotime($fechaServidor);
        
        if (isset($decodedResponse["datos"]) && is_array($decodedResponse["datos"])) {

            $url = "https://dockserver.lat/retraso_en_pagos";

            $data = [
                "matriculas" => [$matricula]
            ];
            
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
            $response = curl_exec($ch);
            $decodedResponseAdeudo = json_decode($response, true);
            
            curl_close($ch);

            $fechasUnicas = []; // Para almacenar fechas únicas
            
            // Filtrar fechas anteriores a la fecha de hoy
            foreach ($decodedResponse["datos"] as $value) {
                if (isset($value["fecha"]) && strtotime($value["fecha"]) < $timestampServidor) {
                    $fecha = $value["fecha"];
                    // Solo añadir si no está ya en el array
                    if (!isset($fechasUnicas[$fecha])) {
                        $fechasUnicas[$fecha] = [
                            'fecha' => $fecha,
                            'adeudo' => $value["adeudo"] ?? 0,
                            'concepto' => $value["concepto"] ?? 'N/A'
                        ];
                    }
                }
            }

            // Ordenar fechas de más recientes a más antiguas
            usort($fechasUnicas, function($a, $b) {
                return strtotime($b["fecha"]) - strtotime($a["fecha"]);
            });
            
            // Obtener las últimas 3 fechas únicas
            $ultimasTresFechas = array_slice($fechasUnicas, 0, 3);
            
            $adeudoCero = null;

            foreach ($decodedResponse["datos"] as $value) {
                if (isset($value["adeudo"]) && $value["adeudo"] != 0 && strtotime($value["fecha"]) < $timestampServidor) {
                    $fecha = $value["fecha"];
                    $concepto = $value["concepto"] ?? 'N/A';
                    
                    // Comprobar si la fecha y concepto ya están en las últimas tres fechas
                    $fechasDeUltimasTres = array_column($ultimasTresFechas, 'fecha');
                    $conceptosDeUltimasTres = array_column($ultimasTresFechas, 'concepto');

                    // Solo agregar si la fecha no está en las últimas tres fechas y el concepto es diferente
                    if (!in_array($fecha, $fechasDeUltimasTres) && !in_array($concepto, $conceptosDeUltimasTres)) {
                        $adeudoCero = [
                            'fecha' => $fecha,
                            'adeudo' => $value["adeudo"],
                            'concepto' => $concepto
                        ];
                        break; // Solo tomar el primero que encuentra
                    }
                }
            }
            
            // Si existe un adeudo diferente de 0, no repetido en fechas ni conceptos, añadirlo
            if ($adeudoCero) {
                $ultimasTresFechas[] = $adeudoCero;
            }

            // Preparar la respuesta final
            $response = new stdClass();
            $response->ultimasFechas = array_values($ultimasTresFechas); // Asegurarse de que es un array indexado
            $response->decodedResponseAdeudo = $decodedResponseAdeudo;
            
            return $response;
        }
        
        return null;            
    }


    public function probabilidad_baja($matricula)
    {
        $vfinanciera =  $this->variable_financiera_matricula_modificado($matricula);
        $vacademica =  $this->variable_academica_alumno_modificado($matricula);

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
        $dataAlumnos = $this->AlumnosModel->reporte_excel_todos();

        foreach ($dataAlumnos as &$alumno) {
            // Verificar si ultimoacceso es nulo
            if ($alumno->ultimoacceso === null) {
                $alumno->ultimoacceso = 'nunca';
            } else {
                // Convertir la fecha a un formato UNIX timestamp para una comparación segura
                $ultimoAccesoTimestamp = strtotime($alumno->ultimoacceso);
                $fechaEspecifica = strtotime('1976-10-10');
        
                if ($ultimoAccesoTimestamp <= $fechaEspecifica) {
                    $alumno->ultimoacceso = 'Nunca';
                } else {
                    // Si es una fecha válida diferente, formatearla como dd/mm/YYYY
                    $alumno->ultimoacceso = date('d/m/Y', $ultimoAccesoTimestamp);
                }
            }

            $alumno->estatus_descripcion_plataforma = $alumno->estatus_descripcion;

            if ($alumno->estatus_descripcion === "Regular" && $alumno->dias_bloqueado > 0) {
                $alumno->estatus_descripcion = "Desbloqueado";
            } else if ($alumno->estatus_descripcion === "Regular") {
                $alumno->estatus_descripcion = "Activo";
            }

        }

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
            'apellidos' => 'APELLIDOS',
            'matricula' => 'MATRICULA',
            'correo' => 'CORREO ELECTRONICO',
            'telefono' => 'TELEFONO',
            'sexo' => 'SEXO',
            'mes' => 'MES',
            'nombre_consejera' => 'CONSEJERA',
            'nombre_financiero' => 'ASESOR FINANCIERO',
            'periodo' => 'PERIODO',
            'periodo_mensual' => 'TRIMESTRE/CUATRIMESTRE',
            'complemento' => 'REGISTRO COMPLETO',
            'ultimoacceso' => 'ÚLTIMO ACCESO',
            'probabilidad_baja' => 'PROBABILIDAD DE BAJA',
            'estatus_plataforma' => 'ESTATUS EN PLATAFORMA',
            'programa' => 'PROGRAMA',
            'variable_academica' => 'VARIABLE ACADEMICA',
            'variable_financiera' => 'VARIABLE FINANCIERA',
            'estatus_descripcion' => 'ESTATUS DESCRIPCION',
            'estatus_descripcion_plataforma' => 'ESTATUS ORIGINAL'
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

        // Añadir datos
        $rowNumber = 2;
        foreach ($dataAlumnos as $row) {
            // Aplicar color de fondo según probabilidad_baja
            $row['programa'] = $row['programa'] == 'MCD' ? 'MCDA' : $row['programa'];
            switch ($row['probabilidad_baja']) {
                case 'Baja R3':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => 'ffffff']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '33b78f']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                    ];
                    break;
                case 'Baja R2':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0EA75']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                    ];
                    break;
                case 'Baja R1':
                    $backgroundStyle = [
                        'font' => ['bold' => false, 'color' => ['rgb' => 'ffffff']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'd9534f']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                    ];
                    break;
                default:
                    $backgroundStyle = null;
                    break;
            }

            // Aplicar estilo de fondo condicional a toda la fila
            if ($backgroundStyle) {
                $sheet->getStyle('C' . $rowNumber)->applyFromArray($backgroundStyle);
                $sheet->getStyle('N' . $rowNumber)->applyFromArray($backgroundStyle);
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
        header('Content-Disposition: attachment;filename="Excel alumnos-' . date("Y-m-d H:i") . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }




    public function actualiza_alumnos_registro()
    {

        try {

            $logFile = APPPATH . 'registroCron/actualizaAlumnosRegistro.txt';
            $logEntry = date('Y-m-d H:i:s') . "| actualizaAlumnosRegistro" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        } catch (Exception $e) {
        }

        // Obtiene todos los alumnos que están activos
        $dataAlumnos  = $this->AlumnosModel->get_todos_activos();


        // Itera sobre cada alumno activo
        foreach ($dataAlumnos as $a) {
            // Obtiene la información del alumno de otra fuente (PlataformasModel) usando su matrícula
            $dataInformacion = $this->PlataformasModel->get_alumnos_por_matricula_registro($a->matricula);

            // URL a la que se realizará la solicitud
            $url = "https://iexe.app/alumnos/obtenerRegistroAlumno?matricula=" . $a->matricula;

            // Inicializar cURL
            $ch = curl_init($url);

            // Configurar opciones de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            // Ejecutar la solicitud cURL
            $response = json_decode(curl_exec($ch));

            // Determinar el país y clave del país con prioridad en response
            if (!empty($response->pais)) {
                $pais = $response->pais;
            } elseif (!empty($dataInformacion) && !empty($dataInformacion[0]->pais)) {
                $pais = $this->isopais[$dataInformacion[0]->pais];
            } else {
                $pais = '---';
            }

            if (!empty($response->clavePais)) {
                $isopais = $response->clavePais;
            } elseif (!empty($dataInformacion) && !empty($dataInformacion[0]->pais)) {
                $isopais = $dataInformacion[0]->pais;
            } else {
                $isopais = '---';
            }

            // Si hay información del alumno disponible
            if (!empty($dataInformacion)) {
                // Prepara los datos de actualización con la información obtenida
                $dataUpdate = array(
                    "fnacimiento" => !empty($dataInformacion[0]->fnacimiento) ? $dataInformacion[0]->fnacimiento : '---',
                    "ecivil" => !empty($dataInformacion[0]->ecivil) ? $dataInformacion[0]->ecivil : '---',
                    "rfc" => !empty($dataInformacion[0]->rfc) ? $dataInformacion[0]->rfc : '---',
                    "direccion" => !empty($dataInformacion[0]->dcalle) ? $dataInformacion[0]->dcalle : '---',
                    "colonia" => !empty($dataInformacion[0]->colonia) ? $dataInformacion[0]->colonia : '---',
                    "cpostal" => !empty($dataInformacion[0]->cpostal) ? $dataInformacion[0]->cpostal : '---',
                    "estado" => !empty($dataInformacion[0]->estado) ? $dataInformacion[0]->estado : '---',
                    "telefono" => !empty($dataInformacion[0]->telefono) ? $dataInformacion[0]->telefono : '---',
                    "celular" => !empty($dataInformacion[0]->celular) ? $dataInformacion[0]->celular : '---',
                    "pais" => $pais,
                    "isopais" => $isopais
                );
            } else {
                // Si no hay información del alumno, usa datos por defecto
                $dataUpdate = array(
                    "fnacimiento" => '---',
                    "ecivil" => '---',
                    "rfc" => '---',
                    "direccion" => '---',
                    "colonia" => '---',
                    "cpostal" => '---',
                    "estado" => '---',
                    "telefono" => '---',
                    "celular" => '---',
                    "pais" => '---',
                    "isopais" => '---'
                );
            }

            // Actualiza los datos del alumno en la base de datos
            $this->AlumnosModel->editar_por_id($a->id, $dataUpdate);
        }
    }


    public function cardex($plataforma)
    {
        if ($plataforma[0] == 'm' || $plataforma[0] == 'i') {
            $minima = 7;
            $prom = 7.5;
        } elseif ($plataforma[0] == 'l') {
            $minima = 6;
            $prom = 6.5;
        } elseif ($plataforma[0] == 'd') {
            $minima = 8;
            $prom = 8.5;
        }
        $dataAlumnos = $this->AlumnosModel->get_alumnos_where(array('plataforma' => $plataforma, "is_active" => 1));
        foreach ($dataAlumnos as $a) {
            $dataKardex = $this->PlataformasModel->get_kardex($plataforma, $a->matricula, $minima);
            foreach ($dataKardex as $k) {
                $calificacion_moodle = $this->truncarnumero($k->finalgrade, 1); //calificación original de moodle
                //inicia proceso para redondear a las reglas de iexe la calificación del alumno.
                if (is_nan($calificacion_moodle) || $calificacion_moodle === NULL) {
                    $calificacion = "--";
                } else {
                    if ($calificacion_moodle <= $prom) {
                        $calificacion = floor($calificacion_moodle);
                    } else {
                        if (round($calificacion_moodle - floor($calificacion_moodle), 1) < 0.6) {
                            $calificacion = floor($calificacion_moodle);
                        } else {
                            $calificacion = ceil($calificacion_moodle);
                        }
                    }
                }

                $dataInsert = array(
                    "idAlumnos" => $a->id,
                    "materiaClave" => $k->materia_clave,
                    "MateriNombre" => $k->fullname,
                    "calificacion" => $calificacion,
                    "CalificacionFecha" => $k->calificacionFecha
                );
                $this->PlataformasModel->insert_data_kardex($dataInsert);
            }
        }
    }

    function truncarnumero($number, $precision = 2)
    {
        $negative = $number / abs($number);
        $number = abs($number);
        $precision = pow(10, $precision);
        return floor($number * $precision) / $precision * $negative;
    }

    public function asigna_complemento()
    {
        $alumnos = $this->AlumnosModel->get_all_alumnos();
        $data_to_update = [];

        // Obtener todas las encuestas
        $data_encuestas = $this->PlataformasModel->data_encuesta();

        // Preparar los datos para update_batch
        foreach ($alumnos as $a) {
            if (isset($data_encuestas[strtoupper($a->matricula)])) {
                $data_to_update[] = [
                    'matricula' => $a->matricula,
                    'complemento' => $data_encuestas[strtoupper($a->matricula)]
                ];
            } else {
                $data_to_update[] = [
                    'matricula' => $a->matricula,
                    'complemento' => 'NO'
                ];
            }
        }
        // Enviar los datos a AlumnosModel para hacer el update_batch
        if (!empty($data_to_update)) {
            $this->AlumnosModel->update_batch_data($data_to_update);
        }
    }

    public function actualiza_promotor()
    {

        try {

            $logFile = APPPATH . 'registroCron/actualizaPromotor.txt';
            $logEntry = date('Y-m-d H:i:s') . "| actualizaPromotor" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        } catch (Exception $e) {
        }

        $dataAlumnos = $this->AlumnosModel->get_todos_activos();
        echo "<pre>";
        print_r($dataAlumnos);
        echo "</pre>";
        $matriculas = [];

        $array_update = [];
        foreach ($dataAlumnos as $a) {
            $matriculas[] = $a->matricula;
        }


        $dataPromotores = $this->PlataformasModel->get_promotores_crm($matriculas);
        echo "<pre>";
        print_r($dataPromotores);
        echo "</pre>";
        foreach ($dataAlumnos as $a) {
            if (isset($dataPromotores[strtoupper($a->matricula)])) {
                $dataPromotor = $dataPromotores[strtoupper($a->matricula)];
                // Procesa los datos
            } elseif (isset($dataPromotores[strtolower($a->matricula)])) {
                // Maneja el caso cuando la matrícula no está en los resultados
                $dataPromotor = $dataPromotores[strtolower($a->matricula)];
            }
            $array_update[] = array(
                "id" => $a->id,
                "promotor" => $dataPromotor['promotor'],
                "correo_promotor" => $dataPromotor['correo']
            );
        }
        echo "<pre>";
        print_r($array_update);
        echo "</pre>";
        if (!empty($array_update)) {
            $this->AlumnosModel->update_batch($array_update);
        }
    }



    public function get_status_actividades_realizadas($matricula)
    {
        $codigoMaterias = $this->get_data($matricula);
        $secciones = array();

        $encontroCalificacion = false;
        preg_match('/[^0-9]+/', $matricula, $programa);
        $siglas = (count($programa) > 0) ? $programa[0] : "";
        $conexionMap = [
            'lce' => 'lic',
            'lae' => 'lic',
            'ld' => 'lic',
            'lsp' => 'lic',
            'lcpap' => 'lic',
            'mapp' => 'mapp',
            'mepp' => 'mepp',
            'mspp' => 'mspp',
            'man' => 'maestria',
            'mfp' => 'maestria',
            'mige' => 'mige',
            'mcda' => 'iexetec',
            'mcdia' => 'iexetec',
            'mba' => 'iexetec',
            'miti' => 'iexetec',
            'mais' => 'masters',
            'mgpm' => 'masters',
            'mspajo' => 'masters',
            'mmpop' => 'masters',
            'mag' => 'masters',
            'dsp' => 'doctorado',
            'dpp' => 'doctorado'
        ];
        $siglasLower = strtolower($siglas);
        $plataforma = $conexionMap[$siglasLower] ?? 0;


        if ($plataforma === 0) {
            $response = [
                'matricula' => $matricula,
                'status_actividad' => $encontroCalificacion,
                'message' => "Matricula no encontrada",
                'status' => 0,
                "elimina" => 0

            ];
            // Devolver la respuesta en formato JSON
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
            return;
        }
        #Obtenemos el programa de la matricula
        $idMoodle = $this->PlataformasModel->get_moodle_id($matricula, $plataforma);
        if (!$idMoodle) {
            $response = [
                'matricula' => $matricula,
                'status_actividad' => $encontroCalificacion,
                'message' => "No se encuentra la matricula registrada en plataforma",
                'status' => 0,
                "elimina" => 0

            ];
            // Devolver la respuesta en formato JSON
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
            return;
        }
        // Iterar sobre cada alumno activo
        // Obtener las materias en las que está inscrito el alumno
        $materias_enroladas  = $this->PlataformasModel->get_materia_activas($idMoodle, strtolower($plataforma));



        // Bandera para indicar si el alumno ha sido dado de baja académicamente

        // Verificar si el alumno está inscrito en materias
        if (is_array($materias_enroladas) && !empty($materias_enroladas)) {
            foreach ($materias_enroladas as $m) {

                // Obtener el código de la materia
                $array_codigo = explode('-', $m->fullname);
                $codigo = $array_codigo[0];

                // Obtener la fórmula configurada para la materia
                $formula_materia = $this->PlataformasModel->formula_materia($m->id, strtolower($plataforma), $codigo);
                if ($formula_materia !== null) {
                    // Obtener las actividades de la fórmula
                    preg_match_all("/gi\d+/", $formula_materia->calculation, $actividades);
                    $actividades = str_replace("gi", "", $actividades[0]);

                    // Convertir el arreglo de actividades en una cadena separada por comas
                    $string_in = implode(',', $actividades);

                    // Obtener información de las actividades
                    if ($string_in != '') {
                        $lista_actividades = $this->PlataformasModel->get_info_actividades(strtolower($plataforma), $string_in);

                        // Verificar si el alumno ha participado en las actividades

                        foreach ($lista_actividades as  $ac) {
                            $fechaActividad = new DateTime($ac->finalizacion);
                            $hoy = new DateTime();

                            //$year = $fechaActividad->format('Y');

                            // Verificar si la fecha de la actividad es válida
                            // if ($year != 1969) {
                            $dataParticipacion = $this->PlataformasModel->obtiene_participacion(strtolower($plataforma), $ac->grade_item_id, $idMoodle);

                            $ac->opcional = 0;
                            // Verificar si el alumno no ha participado y la fecha de la actividad ha pasado
                            if ($dataParticipacion[0]->participation == 0 && $hoy > $fechaActividad) {
                                $ac->notificacion = 1;
                            } else {
                                $ac->notificacion = 0;
                            }
                            $ac->calificacion_actividad = isset($dataParticipacion[0]->calificacion) ? $dataParticipacion[0]->calificacion : 0;
                            // } else {
                            //     $ac->opcional = 1;
                            //     $ac->notificacion = 0;
                            // }
                        }
                        $m->actividades = $lista_actividades;
                    }
                }
                foreach ($codigoMaterias as $key => $value_trimestre) {
                    if (stristr($m->fullname, $key)) {
                        $secciones[] = $m;
                    }
                }
            }
            // echo "<pre>";
            // print_r($secciones);
            // echo "</pre>";
            // echo "....";

            $encontroCalificacion = false;

            // Buscar calificación en las secciones
            foreach ($secciones as $a) {
                if (!empty($a->finalgrade) && $a->finalgrade != 0) {
                    $encontroCalificacion = true;
                    break; // Rompe si encuentra una calificación != 0
                }
            }

            // Si no encontró calificación en las secciones, busca en las actividades
            if (!$encontroCalificacion) {
                foreach ($secciones as $a) {
                    foreach ($a->actividades as $ac) {
                        if (!empty($ac->calificacion_actividad) && $ac->calificacion_actividad != 0) {
                            $encontroCalificacion = true;
                            break 2; // Rompe ambos bucles (secciones y actividades)
                        }
                    }
                }
            }

            // Enviar mensaje según el resultado
            if ($encontroCalificacion) {
                //echo "Se encontró actividad o materia con calificación.";
                $response = [
                    'matricula' => $matricula,
                    'status_actividad' => $encontroCalificacion,
                    'message' => "Datos enviados correctamente",
                    'status' => 1,
                    "elimina" => "n"
                ];
            } else {
                //echo "No se encontró ninguna actividad o materia con calificación.";
                $response = [
                    'matricula' => $matricula,
                    'status_actividad' => $encontroCalificacion,
                    'message' => "Datos enviados correctamente",
                    'status' => 1,
                    "elimina" => "s"
                ];
            }
        } else {
            $response = [
                'matricula' => $matricula,
                'status_actividad' => $encontroCalificacion,
                'message' => "El alumno no cuenta con materias asignadas",
                'status' => 1,
                "elimina" => "s"
            ];
        }

        // echo "<pre>";
        // print_r($response);
        // echo "</pre>";
        // die;

        // Devolver la respuesta en formato JSON
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function ultimoBloqueo()
    {

        $this->AlumnosModel->setear_alumnos();
        
        $matriculas = $this->AlumnosModel->obtener_matriculas();

        $matriculas_str = '';

        for ($i = 0; $i < count($matriculas); $i++) {
            $matriculas_str .= "'" . $matriculas[$i]->matricula . "'";
            if ($i < count($matriculas) - 1) {
                $matriculas_str .= ',';
            }
        }

        $response = $this->AlumnosModel->revisar_bloqueo($matriculas_str);

        $batch_size = 100;
        $response_batches = array_chunk($response, $batch_size);
        
        foreach ($response_batches as $batch) {
            foreach ($batch as $row) {
                $this->AlumnosModel->ActualizarDias($row->matricula, $row->diferencia_dias);
            }
        }

    }

    public function actualizar_alumno_finaciero()
    {

        try {

            $logFile = APPPATH . 'registroCron/actualizarAlumnoFinaciero.txt';
            $logEntry = date('Y-m-d H:i:s') . "| actualizarAlumnoFinaciero" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        } catch (Exception $e) {
        }

        $response = $this->AlumnosModel_copia->obtenerTodasLasMatriculas();
        $batches = array_chunk($response, 300);

        $resultados = [];
        
        foreach ($batches as $index => $batch) {
            $matriculas = [];
        
            foreach ($batch as $element) {

                $matriculas[] = strtoupper($element->matricula); 

            }
            
            $url = "https://dockserver.lat/retraso_en_pagos";
        
            $data = [
                "matriculas" => $matriculas
            ];
            
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
            $response = curl_exec($ch);
            $decodedResponse = json_decode($response, true);
            
            curl_close($ch);
            
            if (isset($decodedResponse['retrasos'])) {
                $retrasos = $decodedResponse['retrasos'];
                
                foreach ($retrasos as $matricula => $retraso) {
                    $resultados[$matricula] = $retraso;
                }
            }
            
        }

        $batchesResultados = array_chunk($resultados, 100, true);

        foreach ($batchesResultados as $batchResultados) {
            foreach ($batchResultados as $matricula => $retraso) {
                $this->AlumnosModel->actualizar_variable_financiera($matricula, $retraso);
            }
        }

    }

    public function variable_academica_consulta_tabla()
    {

        $response = $this->AlumnosModel->obtenerTodasLasMatriculasRevision();
        $batches = array_chunk($response, 100);
        
        $resultados = [];
        
        foreach ($batches as $batch) {
            
            $matriculas = [];

            foreach ($batch as $value) {
                $matriculas[] = $value->matricula;
            }

            $response = $this->AlumnosModel->variable_academica_consulta_tabla($matriculas);

            foreach ($response as $value) {

                $estado = 0;

                foreach ($value["items"] as $valueItem) {

				    if ($valueItem["actividad_finalizada"] == 1 && (int) floatval($valueItem["calificacion"]) == 0) {
                        $estado = 1;
                        break;
                    }else{
                        $estado = 0;
                    }

                }

                $this->AlumnosModel->actualiza_variable_academica($value["matricula"],$estado);
            }
            
        }

    }

    public function variable_academica_alumno_modificado($matricula)
    {
        $response = $this->AlumnosModel_copia->variable_academica_consulta_tabla_modificado($matricula);
        return $response;
    }

    public function variable_academica_alumno_v2($matricula)
    {

        #Obtenemos el programa de la matricula
        $infoalumno = $this->AlumnosModel_copia->get_all_alumnos_where(array('matricula' => $matricula));
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

}