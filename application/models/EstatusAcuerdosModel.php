<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstatusAcuerdosModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_acuerdo_por_rol($id_rol)
    {
        if ($id_rol != 0) {
            $this->db->where('id_rol', $id_rol);
        }
        $query = $this->db->get('estatus_acuerdo');
        return $query->result();
    }
}
