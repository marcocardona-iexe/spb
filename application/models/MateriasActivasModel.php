<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MateriasActivasModel extends CI_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        // Cargar la base de datos
        $this->load->database();
    }

    function materias_activas_programa($shortname)
    {
        $shortname = (strtolower($shortname) == 'mcd') ? "MCDA" : $shortname;

        $shortname = (strtolower($shortname) == 'man') ? "MBA" : $shortname;
        $shortname = (strtolower($shortname) == 'mais') ? "MADIS" : $shortname;
        $this->db->select('*');
        $this->db->from("materias_activas");
        $this->db->where('shortname', $shortname);
        $consulta = $this->db->get();
        return $consulta->result();
    }
}
