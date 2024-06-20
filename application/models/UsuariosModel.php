<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsuariosModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function verificar_usuario($email, $password)
    {
        $this->db->where('correo', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('usuarios');

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function agregar_usuario($data)
    {
        $this->db->insert('usuarios', $data);
        return $this->db->insert_id();
    }

    public function usuario_existe($correo)
    {
        $this->db->where('correo', $correo);
        $query = $this->db->get('usuarios');
        return $query->num_rows() > 0;
    }

    public function get_usuario_where($where)
    {
        return $this->db->get_where('usuarios', $where)->row();
    }


    public function eliminar_usuario($usuario_id)
    {
        // Eliminar el usuario de la base de datos
        $this->db->where('id', $usuario_id);
        $this->db->delete('usuarios');

        // Verificar si se eliminó correctamente
        if ($this->db->affected_rows() > 0) {
            return true; // Éxito al eliminar el usuario
        } else {
            return false; // Error al eliminar el usuario
        }
    }

    public function obtener_todos_usuarios()
    {
        // Realizar la consulta para obtener todos los usuarios
        $query = $this->db->get('usuarios');
        return $query->result();
    }



    /**
     * Obtiene los usuarios asociados a un rol específico.
     *
     * @param int $rol_id El ID del rol que se está buscando.
     * @return array Arreglo de objetos de usuario asociados al rol especificado.
     */
    public function get_usuario_by_rol($rol_id)
    {
        // Selecciona el ID de usuario y concatena el nombre y los apellidos como 'nombre'
        $this->db->select('usuarios.id, CONCAT(usuarios.nombre, usuarios.apellidos) AS nombre');
        // Desde la tabla usuarios
        $this->db->from('usuarios');
        // Realiza una unión interna con la tabla usuario_roles utilizando el ID del usuario
        $this->db->join('usuario_roles', 'usuarios.id = usuario_roles.idusuario');
        // Realiza una unión interna con la tabla roles utilizando el ID del rol
        $this->db->join('roles', 'usuario_roles.idrol = roles.idrol');
        // Filtra los resultados para que solo coincidan con el ID de rol proporcionado
        $this->db->where('roles.idrol', $rol_id);

        // Ejecuta la consulta y guarda los resultados
        $query = $this->db->get();
        // Almacena los resultados en un arreglo de objetos
        $resultado = $query->result();

        // Devuelve el arreglo de objetos de usuarios asociados al rol especificado
        return $resultado;
    }
}
