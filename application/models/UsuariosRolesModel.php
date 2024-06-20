<?php
class UsuariosRolesModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'usuario_roles';
    }

    public function agregar_roles($usuario_id, $roles)
    {
        // Iterar sobre los roles y agregarlos uno por uno
        foreach ($roles as $rol_id) {
            // Intenta insertar el rol para el usuario
            $insert_data = array(
                'idusuario' => $usuario_id,
                'idrol' => $rol_id
            );
            if (!$this->db->insert($this->table, $insert_data)) {
                // Si hay un error al insertar el rol, devuelve false
                return false;
            }
        }

        // Si se agregan todos los roles con éxito, devuelve true
        return true;
    }
    public function obtener_roles_usuario($usuario_id)
    {
        // Realizar la consulta para obtener los roles del usuario
        $this->db->select('roles.*');
        $this->db->from('usuario_roles');
        $this->db->join('roles', 'usuario_roles.idrol = roles.idrol');
        $this->db->where('usuario_roles.idusuario', $usuario_id);
        $query = $this->db->get();
        return $query->result();
    }
}
