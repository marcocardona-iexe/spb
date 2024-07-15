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

    public function get_historial_fecha($finicial, $ffinal)
    {


        $custom_filters = "historial_seguimientos.insert_date BETWEEN '{$finicial} 00:00:00' AND '{$ffinal} 23:59:59'";

        // Construir la consulta
        $this->db->select('seguimientos.idseguimiento, historial_seguimientos.id, usuarios.nombre, usuarios.apellidos, usuarios.correo, historial_seguimientos.metodo_contacto, historial_seguimientos.estatus_seguimiento, historial_seguimientos.estatus_acuerdo, historial_seguimientos.comentarios, alumnos.matricula, 	historial_seguimientos.insert_date');
        $this->db->from($this->table);
        $this->db->join('usuarios', 'historial_seguimientos.asesor = usuarios.id', 'inner');
        $this->db->join('seguimientos', 'historial_seguimientos.idseguimiento = seguimientos.idseguimiento', 'inner');
        $this->db->join('alumnos', 'seguimientos.idalumno = alumnos.id', 'inner');

        $this->db->where($custom_filters);
        $this->db->order_by('historial_seguimientos.insert_date', 'DESC');

        $query = $this->db->get();

        // Retornar el resultado de la consulta
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array(); // Retorna un array vacío si no hay resultados
        }
    }
}
