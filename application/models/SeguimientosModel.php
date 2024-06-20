<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SeguimientosModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'seguimientos'; // Nombre de la tabla
    }

    public function verificar_seguimientos_abiertos($idalumno)
    {
        // Construir la consulta
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('idalumno', $idalumno);
        $this->db->where('estatus', 'Abierto');
        $query = $this->db->get();

        // Retornar el resultado de la consulta
        return $query->result_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id(); // Devuelve el ID del registro insertado
        } else {
            return 0; // Error al insertar
        }
    }

    public function update_id($id, $data)
    {
        $this->db->where('idseguimiento', $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
