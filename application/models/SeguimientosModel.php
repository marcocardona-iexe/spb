<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SeguimientosModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'seguimientos'; // Nombre de la tabla
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
    public function obtener_por_fecha_idalumno($fecha, $idAlumno)
    {
        // Construir la consulta
        $this->db->select('seguimientos.*, usuarios.nombre, usuarios.apellidos, usuarios.correo');
        $this->db->from('seguimientos');
        $this->db->join('usuarios', 'seguimientos.asesor = usuarios.id');
        //$this->db->where('DATE(seguimientos.insert_date)', $fecha); // Comparar solo la parte de la fecha
        $this->db->where('idalumno', $idAlumno);

        // Ejecutar la consulta
        $query = $this->db->get();

        // Retornar el resultado
        return $query->result();
    }

    public function filtrar_seguimientos_fecha($fechaInicio, $fechaFin, $idAlumno)
    {
        // Construir la consulta
        $this->db->select('seguimientos.*, usuarios.nombre, usuarios.apellidos, usuarios.correo');
        $this->db->from('seguimientos');
        $this->db->join('usuarios', 'seguimientos.asesor = usuarios.id');
        $this->db->where('DATE(seguimientos.insert_date) >=', $fechaInicio); // Fecha de inicio
        $this->db->where('DATE(seguimientos.insert_date) <=', $fechaFin); // Fecha de fin
        $this->db->where('idalumno', $idAlumno);

        // Ejecutar la consulta
        $query = $this->db->get();
        // Retornar el resultado
        return $query->result();
    }

    public function reporte_excel($finicial, $ffinal)
    {
        $this->db->select('
            alumnos.nombre,
            alumnos.apellidos,
            alumnos.matricula,
            seguimientos.periodo,
            seguimientos.metodo_contacto,
            seguimientos.estatus_seguimiento,
            seguimientos.estatus_acuerdo,
            seguimientos.comentarios,
            CONCAT(usuarios.nombre, " ", usuarios.apellidos) as nombreusuario,
            usuarios.correo,
            seguimientos.insert_date
        ');
        $this->db->from('seguimientos');
        $this->db->join('usuarios', 'seguimientos.asesor = usuarios.id');
        $this->db->join('alumnos', 'seguimientos.idalumno = alumnos.id');
        $this->db->where('DATE(seguimientos.insert_date) >=', $finicial);
        $this->db->where('DATE(seguimientos.insert_date) <=', $ffinal);
        $this->db->where('alumnos.is_active', 1);

        // Ejecutar la consulta
        $query = $this->db->get();

        // Retornar el resultado
        return $query->result();
    }
}
