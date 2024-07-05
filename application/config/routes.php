<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'UsuariosController/index';

$route['banners'] = 'BannersController';
$route['insert_banner'] = 'BannersController/insert_banner';





$route['actualiza_alumnos_registro'] = 'AlumnosController/actualiza_alumnos_registro';
$route['alumnos-bloqueados'] = 'AlumnosController/alumnos_bloqueados';
$route['alumnos_probabilidad_baja/(:any)'] = 'AlumnosController/alumnos_probabilidad_baja/$1';
$route['alumnos_buscar_seguimientos/(:any)'] = 'AlumnosController/alumnos_buscar_seguimientos/$1';

$route['get_usuario/(:any)'] = 'UsuariosController/get_usuario/$1';

$route['exist_seuimiento_abierto_por_alumno/(:any)'] = 'SeguimientosController/exist_seuimiento_abierto_por_alumno/$1';




$route['ingresa_alumnos_activos'] = 'AlumnosController/ingresa_alumnos_activos';
$route['lista-alumnos'] = 'AlumnosController';
$route['data_tabla_inicial'] = 'AlumnosController/data_tabla_inicial';
$route['validar_usuario'] = 'UsuariosController/validar_usuario';
$route['cerrar-sesion'] = 'UsuariosController/logout';
$route['descarga_excel'] = 'AlumnosController/descarga_excel';
$route['detalle-del-alumno/(:any)'] = 'AlumnosController/detalle_alumno/$1';


$route['usuarios'] = 'UsuariosController/usuarios';
$route['dashboard'] = 'AlumnosController/dashboard';

$route['busuqeda_avanzada'] = 'AlumnosController/busuqeda_avanzada';


#Ruta para verificar los seguimientos que tienen un alumno y usarlos en el modal
$route['verificar_seguimientos/(:any)'] = 'SeguimientosController/verificar_seguimientos/$1';
$route['guardar_seguimiento/(:any)/(:any)'] = 'SeguimientosController/guardar_seguimiento/$1/$2';
$route['obtener_periodos_activos_consejeras/(:any)'] = 'AlumnosController/obtener_periodos_activos_consejeras/$1';
$route['obtener_periodos_activos/(:any)'] = 'AlumnosController/obtener_periodos_activos/$1';
$route['asignar_consejera_masivo'] = 'AlumnosController/asignar_consejera_masivo';
$route['verifica_financiero_alumno'] = 'AlumnosController/verifica_financiero_alumno';
$route['asignar_financiero_matricula'] = 'AlumnosController/asignar_financiero_matricula';
$route['obtener_periodos_mensuales_activos/(:any)/(:any)'] = 'AlumnosController/obtener_periodos_mensuales_activos/$1/$2';
$route['obtener_datos_alumnos_ajax_dashboard'] = 'AlumnosController/obtener_datos_alumnos_ajax_dashboard';
$route['asignaciones-financiero'] = 'AlumnosController/asignaciones_financiero';
$route['financiero_masivo'] = 'AlumnosController/financiero_masivo';
$route['consejera_masiva'] = 'AlumnosController/consejera_masiva';
$route['asignaciones-consejeras'] = 'AlumnosController/asignaciones_consejeras';

#Muestra la probabilidad de baja del alumno
$route['probabilidad_baja/(:any)'] = 'AlumnosController/probabilidad_baja/$1';
$route['variable_academica/(:any)'] = 'AlumnosController/variable_academica/$1';
$route['variable_financiera'] = 'AlumnosController/variable_financiera';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



$route['agregar_usuario'] = 'UsuariosController/agregar_usuario';

$route['asigna_complemento'] = 'AlumnosController/asigna_complemento';


// $route['default_controller'] = 'UsuariosController/index';
// $route['validar_usuario'] = 'UsuariosController/validar_usuario';

// $route['ingresa_alumnos_activos'] = 'AlumnosController/ingresa_alumnos_activos';
// $route['lista_alumnos'] = 'AlumnosController/lista_alumnos';
// $route['ajax_lista_alumnos'] = 'AlumnosController/ajax_lista_alumnos';
// $route['dashboard'] = 'AlumnosController/dashboard';
// $route['obtener_periodos_activos/(:any)'] = 'AlumnosController/obtener_periodos_activos/$1';
// $route['obtener_periodos_mensuales_activos/(:any)/(:any)'] = 'AlumnosController/obtener_periodos_mensuales_activos/$1/$2';
// $route['asignar_consejera_masivo'] = 'AlumnosController/asignar_consejera_masivo';
// $route['verifica_financiero_alumno'] = 'AlumnosController/verifica_financiero_alumno';
// $route['asignar_financiero_matricula'] = 'AlumnosController/asignar_financiero_matricula';
// $route['alumnos_buscar_seguimientos/(:any)'] = 'AlumnosController/alumnos_buscar_seguimientos/$1';
// $route['obtener_datos_alumnos_ajax_dashboard'] = 'AlumnosController/obtener_datos_alumnos_ajax_dashboard';
// $route['asignaciones-financiero'] = 'AlumnosController/asignaciones_financiero';
// $route['financiero_masivo'] = 'AlumnosController/financiero_masivo';
// $route['consejera_masiva'] = 'AlumnosController/consejera_masiva';
// $route['asignaciones-consejeras'] = 'AlumnosController/asignaciones_consejeras';
// $route['alumnos_probabilidad_baja/(:any)'] = 'AlumnosController/alumnos_probabilidad_baja/$1';
// $route['probabilidad_baja/(:any)'] = 'AlumnosController/probabilidad_baja/$1';
// $route['variable_academica/(:any)'] = 'AlumnosController/variable_academica/$1';
// $route['variable_financiera'] = 'AlumnosController/variable_financiera';

// $route['verificar_seguimientos/(:any)'] = 'SeguimientosController/verificar_seguimientos/$1';
// $route['guardar_seguimiento/(:any)/(:any)'] = 'SeguimientosController/guardar_seguimiento/$1/$2';
