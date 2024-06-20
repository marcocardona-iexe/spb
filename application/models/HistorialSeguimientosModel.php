<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistorialSeguimientosModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'historial_seguimientos'; // Nombre de la tabla
    }

    public function get_por_id($idalumno)
    {
        // Construir la consulta
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('idseguimiento', $idalumno);
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
}
