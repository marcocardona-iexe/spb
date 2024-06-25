<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsuariosController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UsuariosModel');
        $this->load->model('UsuariosRolesModel');

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    private function check_session()
    {
        if (!$this->session->userdata('seguimiento_iexe')) {
            redirect('');
        }
    }

    public function index()
    {
        // Verificar si la sesión está activa
        if ($this->session->userdata('seguimiento_iexe')) {
            // La sesión está activa, redirigir al usuario a la página deseada
            redirect('lista-alumnos');
        } else {
            // La sesión no está activa, cargar la vista de login
            $this->load->view("login");
        }
    }

    public function get_usuario($id)
    {
        $dataUsuario = $this->UsuariosModel->get_usuario_where(array("id" => $id));
        $roles = $this->UsuariosRolesModel->obtener_roles_usuario($dataUsuario->id);
        $dataUsuario->roles = $roles;

        echo json_encode($dataUsuario);
    }


    public function usuarios()
    {


        $this->check_session();
        $dataMenu['sesion'] = $this->session->userdata('seguimiento_iexe');

        // Obtener todos los usuarios
        $usuarios = $this->UsuariosModel->obtener_todos_usuarios();

        // Verificar si se obtuvieron usuarios
        if ($usuarios) {
            // Iterar sobre cada usuario
            foreach ($usuarios as &$usuario) {
                // Obtener los roles del usuario actual
                $roles = $this->UsuariosRolesModel->obtener_roles_usuario($usuario->id);
                $badge = '';
                $admin = 0;
                $academico = 0;
                $financiero = 0;
                foreach ($roles as $r) {
                    switch ($r->idrol) {
                        case 1:
                            $badge .= ' <span class="badge bg-info tipo_usuario">Administrador</span> ';
                            $admin = 1;
                            break;
                        case 2:
                            $badge .= ' <span class="badge bg-academico tipo_usuario">Consejera</span>';
                            $academico = 1;
                            break;
                        case 3:
                            $badge .= ' <span class="badge bg-warning text-dark">Asesor financiero</span> ';
                            $financiero = 1;
                            break;
                    }
                }

                if ($admin == 1 && $financiero == 0 && $academico == 0) {
                    $li_asignar = '';
                } elseif ($financiero == 1 && $academico == 0) {
                    $li_asignar = '<li><a class="dropdown-item asignar" data-tipo = "financiero" data-idusuario = "' . $usuario->id . '"><i class="fa-solid fa-address-book"></i> Asignar</a></li>';
                } elseif ($academico == 1 && $financiero == 0) {
                    $li_asignar = '<li><a class="dropdown-item asignar" data-tipo = "academico" data-idusuario = "' . $usuario->id . '"><i class="fa-solid fa-address-book"></i> Asignar</a></li>';
                }


                // Agregar el atributo 'roles' al usuario actual
                $usuario->badge = $badge;

                $usuario->estatus = ($usuario->estatus == 1) ? '<span class="badge bg-success status_usuarios">Activo</span>' : '<span class="badge bg-danger">Suspendido</span>';
                $estatus_activa_suspende  = ($usuario->estatus == 1) ? '<i class="fa-solid fa-user-minus"></i> Suspender' : '<i class="fa-solid fa-user-plus"></i> Suspender';

                $usuario->acciones = '
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle btn-modal" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gears"></i> Acciones
                        </button>
                        <ul class="dropdown-menu" id="element_acciones" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" onclick=editar_usuario("' . $usuario->id . '")><i class="fa-solid fa-pen-to-square"></i> Editar</a></li>
                            <li><a class="dropdown-item" >' . $estatus_activa_suspende . '</a></li>
                            ' . $li_asignar . '
                        </ul>
                    </div>';
            }
        }

        // Pasar los usuarios a la vista
        $data['usuarios'] = $usuarios;

        $this->load->view('head', array("css" => "assets/css/lista_usuarios.css"));
        $this->load->view('menu', $dataMenu);
        $this->load->view('usuarios', $data);
        $this->load->view('footer', array("js" => "assets/js/lista_usuarios.js"));
    }



    public function validar_usuario()
    {
        // Obtener los datos del formulario
        $correo = $this->input->post('correo');
        $password = sha1($this->input->post('password'));

        $usuario = $this->UsuariosModel->verificar_usuario($correo, $password);
        if ($usuario) {
            // Obtener los roles del usuario actual
            $roles = $this->UsuariosRolesModel->obtener_roles_usuario($usuario->id);
            $combinacion = $usuario->nombre[0] . $usuario->apellidos[0];
            $sesion = array(
                "idusuario" => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'correo' => $usuario->correo,
                "roles" => $roles
            );
            $this->session->set_userdata('seguimiento_iexe', $sesion);
            $response = array(
                'code' => 'is_ok',
                'error_message' => 'Sesion validada'
            );
        } else {
            // Usuario inválido, devolver un JSON con mensaje de error
            $response = array(
                'code' => "is_not_ok",
                'error_message' => 'Correo electrónico o contraseña incorrectos.'
            );
        }
        echo json_encode($response);
    }

    // Método para cerrar sesión
    public function logout()
    {
        // Eliminar los datos de la sesión
        $this->session->unset_userdata('seguimiento_iexe'); // Ajusta 'user_data' al nombre de tu sesión de usuario
        $this->session->sess_destroy(); // Destruir la sesión

        // Redireccionar al usuario a la página de inicio de sesión o a la página principal
        redirect(''); // Ajusta 'login' a tu controlador/método de inicio de sesión
    }

    public function agregar_usuario()
    {
        // Configuración de validación de formulario
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
        $this->form_validation->set_rules('correo', 'Correo Electrónico', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');
        $this->form_validation->set_rules('rol[]', 'Rol', 'required', array('required' => 'Por favor, seleccione al menos un rol.'));

        // Verifica si el formulario es válido
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }

        // Datos del usuario
        $data = [
            'nombre' => $this->input->post('nombre'),
            'apellidos' => $this->input->post('apellidos'),
            'correo' => $this->input->post('correo'),
            'password' => sha1($this->input->post('password'))
        ];

        // Roles seleccionados
        $roles = $this->input->post('rol');

        // Intenta agregar usuario y roles
        $usuario_id = $this->UsuariosModel->agregar_usuario($data);
        if ($usuario_id) {
            if ($this->UsuariosRolesModel->agregar_roles($usuario_id, $roles)) {
                // Respuesta exitosa
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario agregado exitosamente.'
                ]);
            } else {
                // Si hay un error al agregar los roles, eliminamos el usuario
                $this->UsuariosModel->eliminar_usuario($usuario_id);

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Ha ocurrido un error al asignar roles al usuario.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al agregar el usuario.'
            ]);
        }
    }
}
