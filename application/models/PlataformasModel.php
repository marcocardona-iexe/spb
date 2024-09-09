<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PlataformasModel extends CI_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        // Cargar la base de datos
        $this->load->database();
    }




    function obtener_alumnos_todas_plataformas()
    {
        #MAPP	
        $DBMAPP          = $this->load->database('mapp', TRUE);
        #MEPP
        $DBMEPP          = $this->load->database('mepp', TRUE);
        #MSPP
        $DBMSPP          = $this->load->database('mspp', TRUE);
        #LICENCIATURAS
        $DBLICENCIATURAS = $this->load->database('lic', TRUE);
        #MAESTRIAS
        $DBMAESTRIAS     = $this->load->database('maestria', TRUE);
        #DOCTORADOS
        $DBDOC           = $this->load->database('doctorado', TRUE);
        #MIGE
        $DBMIGE          = $this->load->database('mige', TRUE);
        #IEXETEC
        $DBIEXETEC          = $this->load->database('iexetec', TRUE);
        #MASTERS
        $DBMASTERS       = $this->load->database('masters', TRUE);


        $arr_alumnos_activos = $this->usuarios_activosv2();



        $resultmapp      = array();
        $resultmepp      = array();
        $resultmspp      = array();
        $resultlic       = array();
        $resultmae       = array();
        $resultdoc       = array();
        $resultiexetec   = array();
        $resultmige      = array();
        $resultiexetec   = array();
        $resultmasters    = array();


        $str_query = "SELECT 
            mdl_user.id,
            firstname,
            lastname,
            username,
            email,
            suspended,
            deleted,
            firstaccess,
            lastaccess,
            lastlogin,
            auth,
            ui1.data AS perfil,
            ui2.data AS estatus,
            ui3.data AS trimestre,
            ui4.data AS cuatrimestre,
            ui5.data AS periodo,
            ui6.data AS sexo,
            ui7.data AS mes
            FROM mdl_user
            LEFT JOIN mdl_user_info_data AS ui1 ON ui1.userid = mdl_user.id AND ui1.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'Perfil')
            LEFT JOIN mdl_user_info_data AS ui2 ON ui2.userid = mdl_user.id AND ui2.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'estatus')
            LEFT JOIN mdl_user_info_data AS ui3 ON ui3.userid = mdl_user.id AND ui3.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'trimestre')
            LEFT JOIN mdl_user_info_data AS ui4 ON ui4.userid = mdl_user.id AND ui4.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'cuatrimestre')
            LEFT JOIN mdl_user_info_data AS ui5 ON ui5.userid = mdl_user.id AND ui5.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'periodo')
            LEFT JOIN mdl_user_info_data AS ui6 ON ui6.userid = mdl_user.id AND ui6.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'sexo')
            LEFT JOIN mdl_user_info_data AS ui7 ON ui7.userid = mdl_user.id AND ui7.fieldid = (SELECT id FROM mdl_user_info_field WHERE shortname = 'mes')";

        $sqlmapp = $DBMAPP->query($str_query . " WHERE username LIKE 'mapp%'  AND mdl_user.id IN(" . implode(',', $arr_alumnos_activos['mapp']) . ") ORDER BY periodo");


        if ($sqlmapp->num_rows() > 0) {
            foreach ($sqlmapp->result() as $value) {
                $row = new stdClass();
                $row->moodleid    = $value->id;
                $row->username    = $value->username;
                $row->programa    = "MAPP";
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['mapp'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "MAPP";
                $row->conexion    = "mapp";
                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmapp[] = $row;
            }
        }



        $sqlmepp = $DBMEPP->query($str_query . " WHERE username LIKE 'mepp%'  AND mdl_user.id IN(" . implode($arr_alumnos_activos['mepp'], ',') . ") ORDER BY periodo");
        if ($sqlmepp->num_rows() > 0) {
            foreach ($sqlmepp->result() as $value) {
                $row = new stdClass();
                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = "MEPP";
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['mepp'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "MEPP";
                $row->conexion    = "mepp";
                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmepp[] = $row;
            }
        }



        $sqlmspp = $DBMSPP->query($str_query . " WHERE username LIKE 'mspp%'  AND mdl_user.id IN(" . implode($arr_alumnos_activos['mspp'], ',') . ") ORDER BY periodo");
        if ($sqlmspp->num_rows() > 0) {
            foreach ($sqlmspp->result() as $value) {
                $row = new stdClass();

                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = "MSPP";
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma     = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['mspp'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "MSPP";
                $row->conexion    = "mspp";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmspp[] = $row;
            }
        }



        $sqllicenciaturas = $DBLICENCIATURAS->query($str_query . " WHERE (username LIKE 'lae%' OR username LIKE 'ld%' OR username LIKE 'lsp%' OR username LIKE 'lce%' OR username LIKE 'lcpap%') AND mdl_user.id IN(" . implode($arr_alumnos_activos['lic'], ',') . ") ORDER BY periodo");

        if ($sqllicenciaturas->num_rows() > 0) {
            foreach ($sqllicenciaturas->result() as $value) {
                $programa = "";
                if (stristr($value->username, "lae")) {
                    $programa = "LAE";
                } else if (stristr($value->username, "ld")) {
                    $programa = "LD";
                } else if (stristr($value->username, "lsp")) {
                    $programa = "LSP";
                } else if (stristr($value->username, "lcpap")) {
                    $programa = "LCPAP";
                } else if (stristr($value->username, "lce")) {
                    $programa = "LCE";
                }

                $row = new stdClass();

                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->cuatrimestre = $value->cuatrimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['lic'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "LIC";
                $row->conexion    = "lic";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultlic[] = $row;
            }
        }


        $sqlmaestrias = $DBMAESTRIAS->query($str_query . " WHERE (username LIKE 'mfp%' OR username LIKE 'man%') AND mdl_user.id IN(" . implode($arr_alumnos_activos['maestria'], ',') . ") ORDER BY periodo");

        if ($sqlmaestrias->num_rows() > 0) {
            foreach ($sqlmaestrias->result() as $value) {
                $programa = "";
                if (stristr($value->username, "mfp")) {
                    $programa = "MFP";
                } else if (stristr($value->username, "man")) {
                    $programa = "MAN";
                }

                $row = new stdClass();
                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['maestria'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "MAESTRIA";
                $row->conexion    = "mae";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmae[] = $row;
            }
        }

        $sqlmaestrias_mige = $DBMIGE->query($str_query . " WHERE username LIKE 'mige%' AND mdl_user.id IN(" . implode($arr_alumnos_activos['mige'], ',') . ") ORDER BY periodo");

        if ($sqlmaestrias_mige->num_rows() > 0) {
            foreach ($sqlmaestrias_mige->result() as $value) {

                $programa = "";
                if (stristr($value->username, "mige")) {
                    $programa = "MIGE";
                }

                $row = new stdClass();
                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (in_array($value->id, $arr_alumnos_activos['mige'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "MIGE";
                $row->conexion    = "mige";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmige[] = $row;
            }
        }

        $sqldoctorados = $DBDOC->query($str_query . " WHERE (username LIKE 'dpp%' OR username LIKE 'dsp%') AND mdl_user.id IN(" . implode($arr_alumnos_activos['doctorado'], ',') . ") ORDER BY periodo");

        if ($sqldoctorados->num_rows() > 0) {

            foreach ($sqldoctorados->result() as $value) {
                $programa = "";
                if (stristr($value->username, "dpp")) {
                    $programa = "DPP";
                } else if (stristr($value->username, "dsp")) {
                    $programa = "DSP";
                }

                $row = new stdClass();
                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->cuatrimestre   = $value->cuatrimestre; //bug detectado, de $value->trimestre a $value->cuatrimestre 
                $row->periodo     = $value->periodo;
                $row->is_active   = (isset($arr_alumnos_activos['doc']) && in_array($value->id, $arr_alumnos_activos['doctorado'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "Doctorado";
                $row->conexion    = "doc";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultdoc[] = $row;
            }
        }


        $sqlmasters = $DBMASTERS->query($str_query . " WHERE (username LIKE 'mais%' OR username LIKE 'mag%' OR username LIKE 'mgpm%' OR username LIKE 'mspajo%' OR username LIKE 'mmpop%') AND mdl_user.id IN(" . implode($arr_alumnos_activos['masters'], ',') . ") ORDER BY periodo");

        if ($sqlmasters->num_rows() > 0) {


            foreach ($sqlmasters->result() as $value) {
                $programa = "";
                if (stristr($value->username, "mais")) {
                    $programa = "MAIS";
                } else if (stristr($value->username, "mag")) {
                    $programa = "MAG";
                } else if (stristr($value->username, "mgpm")) {
                    $programa = "MGPM";
                } else if (stristr($value->username, "mspajo")) {
                    $programa = "MSPAJO";
                } else if (stristr($value->username, "mmpop")) {
                    $programa = "MMPOP";
                }
                $row = new stdClass();
                $row->moodleid    = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma    = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (isset($arr_alumnos_activos['masters']) && in_array($value->id, $arr_alumnos_activos['masters'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "Masters";
                $row->conexion    = "masters";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultmasters[] = $row;
            }
        }


        $sqlmaestrias_iexetec = $DBIEXETEC->query($str_query . " WHERE (username LIKE 'man%' OR username LIKE 'mcd%' OR username LIKE 'mcdia%' OR username LIKE 'miti%') AND mdl_user.id IN(" . implode($arr_alumnos_activos['iexetec'], ',') . ") ORDER BY periodo");

        if ($sqlmaestrias_iexetec->num_rows() > 0) {
            foreach ($sqlmaestrias_iexetec->result() as $value) {
                if (stristr($value->username, "man")) {
                    $programa = "MAN";
                } else if (stristr($value->username, "mcdia")) {
                    $programa = "MCDIA";
                } else if (stristr($value->username, "mcd")) {
                    $programa = "MCD";
                } else if (stristr($value->username, "miti")) {
                    $programa = "MITI";
                }
                $row = new stdClass();
                $row->moodleid = $value->id;
                $row->username    = $value->username;
                $row->programa    = $programa;
                $row->deleted     = $value->deleted;
                $row->email       = $value->email;
                $row->firstname   = $value->firstname;
                $row->lastname    = $value->lastname;
                $row->firstaccess = $value->firstaccess;
                $row->lastaccess  = $value->lastaccess;
                $row->lastlogin   = $value->lastlogin;
                $row->perfil      = $value->perfil;
                $row->estatus_plataforma      = $value->estatus;
                $row->trimestre   = $value->trimestre;
                $row->periodo     = $value->periodo;
                $row->is_active   = (isset($arr_alumnos_activos['iexetec']) && in_array($value->id, $arr_alumnos_activos['iexetec'])) ? true : 0;
                $row->auth        = "manual";
                $row->plataforma  = "IEXETEC";
                $row->conexion    = "iexetec";

                $row->sexo           = $value->sexo;
                $row->mes          = $value->mes;
                $resultiexetec[] = $row;
            }
        }

        $resultado = array_merge($resultmapp, $resultmepp, $resultmspp, $resultlic, $resultmae, $resultdoc, $resultmige, $resultiexetec, $resultmasters);
        return $resultado;
    }


    public function usuarios_activosv2()
    {
        $arrconexiones       = array("mapp", "mepp", "mspp", "lic", "maestria", "doctorado", "mige", "iexetec", "masters");
        $id_materias_activas = $this->local_materias_activas();
        $resultado_final     = array();

        foreach ($arrconexiones as $keyconexion => $conexion) {
            //echo $id_materias_activas[$conexion] . "<br><br>";
            if (!empty($id_materias_activas[$conexion])) {
                $DB = $this->load->database($conexion, TRUE);
                $materias_activas = $id_materias_activas[$conexion];
                //condicional agregada el 30022022, alumnos con matriculas inactivos no entran en el conteo.
                $consulta = $DB->query("
					SELECT distinct(a.id) as userid, a.username,a.firstname
					from mdl_user a 
					inner join mdl_role_assignments b on a.id=b.userid		
					inner join mdl_context c on b.contextid=c.id 		
					inner join mdl_user_info_data d on d.userid=a.id		
					inner join mdl_user_info_field e on  e.id=d.fieldid
					where c.contextlevel = 50 
                    and ((e.shortname='trimestre' and d.data NOT LIKE 'Baja%') OR (e.shortname='cuatrimestre' and d.data NOT LIKE 'Baja%')) 
					and (c.instanceid in(" . $materias_activas . ")) 
					and b.roleid = 5");
                    // echo "
					// SELECT distinct(a.id) as userid, a.username,a.firstname
					// from mdl_user a 
					// inner join mdl_role_assignments b on a.id=b.userid		
					// inner join mdl_context c on b.contextid=c.id 		
					// inner join mdl_user_info_data d on d.userid=a.id		
					// inner join mdl_user_info_field e on  e.id=d.fieldid
					// where c.contextlevel = 50 
                    // and ((e.shortname='trimestre' and d.data NOT LIKE 'Baja%') OR (e.shortname='cuatrimestre' and d.data NOT LIKE 'Baja%')) 
					// and (c.instanceid in(" . $materias_activas . ")) 
					// and b.roleid = 5";
                ///*AND a.username NOT LIKE 'inactivo%'*/
                $ids = array();

                foreach ($consulta->result() as $key => $row) {
                    $ids[$row->userid] = $row->userid;
                }

                $resultado_final[$conexion] = $ids;
            }
        }
        //die;
        return $resultado_final;
    }


    public function local_materias_activas()
    {
        //$materias_activas = $this->prediccion_model->get_config_materiasactivas();//trae todas las materias activas actuales.	
        $materias_activas = $this->db->get("materias_activas")->result();
        ///colocar en la variable $arrconexiones todas las conexiones de las plataformas/////
        $arrconexiones   = array(
            "mapp",
            "mepp",
            "mspp",
            "lic",
            "maestria",
            "doctorado",
            "mige",
            "iexetec",
            "masters"
        );

        $arr_resultado = array();
        $arrfinal      = array();

        foreach ($arrconexiones as $keyconexion => $value_conexion) {
            foreach ($materias_activas as $keymateria => $value_materia_activa) {
                if ($value_materia_activa->conexion == $value_conexion && $value_materia_activa->idsmaterias != '') {
                    $idsmaterias = explode(",", $value_materia_activa->idsmaterias);
                    foreach ($idsmaterias as $keyids => $valueids) {
                        $arr_resultado[$value_conexion][] = str_replace(' ', '', $valueids);
                    }
                }
            }
        }

        foreach ($arrconexiones as $keyconexion => $value_conexion) {
            $arrfinal[$value_conexion] = (!empty($arr_resultado[$value_conexion])) ? implode(",", $arr_resultado[$value_conexion]) : array();
        }

        return $arrfinal;
    }



    function get_materia_activa($userid, $dbconnection, $in)
    {
        $DB    = $this->load->database(strtolower($dbconnection), TRUE);


        $query = $DB->query("
		SELECT 
		c.id,
		c.fullname, 
		c.shortname, 
		c.summary, 
		c.idnumber,
		c.category,
		c.startdate,
		c.visible,
		(SELECT id FROM mdl_grade_items WHERE courseid=c.id AND itemtype='course') as id_gi,
		(SELECT finalgrade FROM `mdl_grade_grades` WHERE `itemid` = id_gi AND `userid` = {$userid}) as finalgrade
		FROM mdl_course c 
		JOIN mdl_enrol en ON en.courseid = c.id 
		JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
		WHERE c.id IN(" . $in . ") 
		AND  ue.userid = {$userid} 
		AND c.visible = 1 
		AND '" . date("Y-m-d") . "' BETWEEN DATE(FROM_UNIXTIME(c.startdate)) AND DATE(FROM_UNIXTIME(c.enddate))");


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function get_materia_activas($userid, $dbconnection)
    {
        $DB    = $this->load->database(strtolower($dbconnection), TRUE);


        $query = $DB->query("
		SELECT 
		c.id,
		c.fullname, 
		c.shortname, 
		c.summary, 
		c.idnumber,
		c.category,
		c.startdate,
		c.visible,
        DATE(FROM_UNIXTIME(c.startdate)) AS inicio_materia,
        DATE(FROM_UNIXTIME(c.enddate)) AS fin_materia,
		(SELECT id FROM mdl_grade_items WHERE courseid=c.id AND itemtype='course') as id_gi,
		(SELECT finalgrade FROM `mdl_grade_grades` WHERE `itemid` = id_gi AND `userid` = {$userid}) as finalgrade
		FROM mdl_course c 
		JOIN mdl_enrol en ON en.courseid = c.id 
		JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
		WHERE ue.userid = {$userid} ");


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    public function get_info_actividades($server, $in)
    {

        $DB = $this->load->database($server, TRUE);
        //$query = $dbreg->query("SELECT matricula,pais,telefono FROM registro WHERE matricula <> ''");
        $query = $DB->query("
				SELECT
			gi.id AS grade_item_id,
			gi.courseid,
			gi.itemname,
			gi.itemmodule,
			DATE(FROM_UNIXTIME(cm.completionexpected)) AS finalizacion,
			DATE(CASE
				WHEN gi.itemmodule = 'assign' THEN FROM_UNIXTIME(a.duedate)
				WHEN gi.itemmodule = 'quiz' THEN FROM_UNIXTIME(q.timeclose)
				WHEN gi.itemmodule = 'forum' THEN FROM_UNIXTIME(f.cutoffdate)
				ELSE NULL
			END) AS fecha_valida
		FROM
			mdl_grade_items gi
			LEFT JOIN mdl_course_modules cm ON cm.instance = gi.iteminstance AND cm.module = (
				SELECT id FROM mdl_modules WHERE name = gi.itemmodule
			)
			LEFT JOIN mdl_assign a ON gi.itemmodule = 'assign' AND gi.iteminstance = a.id
			LEFT JOIN mdl_quiz q ON gi.itemmodule = 'quiz' AND gi.iteminstance = q.id
			LEFT JOIN mdl_forum f ON gi.itemmodule = 'forum' AND gi.iteminstance = f.id
		WHERE
			gi.id IN (
						" . $in . "
			)
		ORDER BY
			gi.courseid,
			gi.id");

        //echo $DB->last_query();
        return $query->result();
    }

    public function obtiene_participacion($conexion, $idactividad, $idAlumno)
    {
        $DB = $this->load->database($conexion, TRUE);

        $query = $DB->query("SELECT
    		gi.id AS grade_item_id,
	    	gi.courseid,
		    gi.itemname,
		    gi.itemmodule,
		    CASE
			    WHEN gi.itemmodule = 'assign' THEN (
				    SELECT COUNT(*)
				        FROM mdl_assign_submission s
				        WHERE s.assignment = gi.iteminstance
				        AND s.userid = " . $idAlumno . "
			        )
			    WHEN gi.itemmodule = 'quiz' THEN (
				    SELECT COUNT(*)
				        FROM mdl_quiz_attempts qa
				        WHERE qa.quiz = gi.iteminstance
				        AND qa.userid = " . $idAlumno . "
			        )
			    WHEN gi.itemmodule = 'forum' THEN (
				    SELECT COUNT(*)
				        FROM mdl_forum_posts fp
				        WHERE fp.discussion IN (
					    SELECT id
					        FROM mdl_forum_discussions
					        WHERE forum = gi.iteminstance
				        )
				    AND fp.userid = " . $idAlumno . "
			    )
			    ELSE 0
		    END AS participation
	        FROM
		        mdl_grade_items gi
	        WHERE
		        gi.id = " . $idactividad);
        return $query->result();
    }


    function formula_materia($idcourse, $dbconnection, $codigo)
    {
        $prefix = substr($dbconnection, 0, 1);
        $seminario_pia = ["MAPP51", "MEPP52", "MSP53", "MFP51", "MIGE601", "MBA51", "MMPOP600", "MADS601", "MAG502", "MAG600", "MGPM501", "MGPM600", "MSPAJO502", "MSPAJO600"];

        if ($prefix === 'l') {
            $query = "SELECT * FROM mdl_grade_items WHERE courseid = ? AND idnumber = 'tcn'";
        } elseif ($prefix === 'm' && in_array($codigo, $seminario_pia)) {
            $query = "SELECT * FROM mdl_grade_items WHERE courseid = ? AND itemtype = 'course'";
        } elseif (in_array($prefix, ['d', 'i'])) {
            $query = "SELECT * FROM mdl_grade_items WHERE courseid = ? AND itemtype = 'course'";
        } else {
            $query = "SELECT * FROM mdl_grade_items WHERE courseid = ? AND idnumber = 'tn'";
        }

        $DB = $this->load->database($dbconnection, TRUE);
        $result = $DB->query($query, [$idcourse]);

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            // Ejecutar consulta alternativa si no hay resultados
            $query = "SELECT * FROM mdl_grade_items WHERE courseid = ? AND itemtype = 'course'";
            $result = $DB->query($query, [$idcourse]);

            if ($result->num_rows() > 0) {
                return $result->row();
            } else {
                return null; // O cualquier otro valor que quieras devolver si no hay resultados
            }
        }
    }



    function formula_materia_respaldo($idcourse, $dbconnection, $codigo)
    {

        switch (substr($dbconnection, 0, 1)) {
            case 'l':
                $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND idnumber = 'tcn'";
                break;
            case 'm':
                $seminario_pia = array("MAPP51", "MEPP52", "MSP53", "MFP51", "MIGE601", "MBA51", "MMPOP600", "MADS601", "MAG502", "MAG600", "MGPM501", "MGPM600", "MSPAJO502", "MSPAJO600");
                if (in_array($codigo, $seminario_pia)) {
                    $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND itemtype = 'course'";
                } else {
                    $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND idnumber = 'tn'";
                }
                break;
            case 'd':
                $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND itemtype = 'course'";
                break;
            case 'i':
                $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND itemtype = 'course'";

                break;
        }
        //echo $query . "<br>";
        //$query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND itemtype = 'course'";

        $DB    = $this->load->database($dbconnection, TRUE);


        $query = $DB->query($query);
        return $query->row();
    }


    function formula_materia_detalle($idcourse, $dbconnection)
    {


        $query = "SELECT * FROM mdl_grade_items where courseid = '" . $idcourse . "' AND itemtype = 'course'";

        $DB    = $this->load->database($dbconnection, TRUE);

        $query = $DB->query($query);
        return $query->row();
    }


    function pago_del_mes($mes, $anio, $matricula)
    {

        $dbpagos = $this->load->database("pagos", true);
        $query = $dbpagos->query("
			SELECT
                c.producto AS Descripcion,
                c.pago AS Importe,
                ( SELECT d.nombre FROM catalogos_formas AS d WHERE d.idForma = c.idForma ) AS FormaPago,
                DATE ( c.fecha ) AS f_pago,
                EXTRACT( MONTH FROM c.fecha ) AS mes,
                EXTRACT( YEAR FROM c.fecha ) AS anio,
                matricula 
            FROM
                clientes AS a
                INNER JOIN clientes_academicos AS b ON a.idCliente = b.idCliente
                INNER JOIN catalogos_ingresos AS c ON c.idCliente = a.idCliente
                INNER JOIN cuentas AS d ON d.idCuenta = c.idCuenta
                INNER JOIN bancos AS e ON e.idBanco = d.idBanco
                INNER JOIN clientes AS f ON f.idCliente = c.idCliente 
            WHERE
                length( a.nombre )> 0 
                AND matricula = '" . $matricula . "'
                AND c.producto LIKE '%Colegiatura%' 
                AND c.producto LIKE '%" . $mes . "%'
                AND c.producto LIKE '%" . $anio . "%'
            ORDER BY
                matricula ASC,
                fecha DESC");
        return $query->result();
    }


    /**
     * Obtiene los pagos CRM según la matrícula del cliente.
     *
     * @param string $matricula Matrícula del cliente para filtrar los pagos.
     * @return array Resultados de los pagos como array de objetos.
     */
    public function obtenerPagosCRM($matricula)
    {
        // Selecciona los campos necesarios para la consulta
        $dbpagos = $this->load->database("pagos", true);

        $dbpagos->select("
            c.producto AS Descripcion,
            c.pago AS Importe,
            e.nombre as banco,
            (SELECT d.nombre FROM catalogos_formas AS d WHERE d.idForma = c.idForma) AS FormaPago,
            DATE(c.fecha) AS f_pago,
            EXTRACT(MONTH FROM c.fecha) AS mes,
            EXTRACT(YEAR FROM c.fecha) AS anio,
            matricula
        ");

        // Tabla principal y joins necesarios para obtener los datos
        $dbpagos->from('clientes AS a');
        $dbpagos->join('clientes_academicos AS b', 'a.idCliente = b.idCliente');
        $dbpagos->join('catalogos_ingresos AS c', 'c.idCliente = a.idCliente');
        $dbpagos->join('cuentas AS d', 'd.idCuenta = c.idCuenta');
        $dbpagos->join('bancos AS e', 'e.idBanco = d.idBanco');
        $dbpagos->join('clientes AS f', 'f.idCliente = c.idCliente');

        // Condiciones de filtrado
        $dbpagos->where('LENGTH(a.nombre) >', 0); // Filtra clientes con nombre no vacío
        $dbpagos->where('matricula', $matricula); // Filtra por matrícula específica
        $dbpagos->like('c.producto', 'Colegiatura', 'both'); // Filtra productos que contengan 'Colegiatura'

        // Ordenamiento de los resultados
        $dbpagos->order_by('matricula', 'ASC'); // Ordena por matrícula ascendente
        $dbpagos->order_by('fecha', 'DESC'); // Luego por fecha descendente

        // Ejecuta la consulta y obtiene los resultados
        $query = $dbpagos->get();

        // Retorna los resultados como un array de objetos
        return $query->result();
    }



    public function obtener_calificacion_por_actividad($idActividad, $idmoodle, $server)
    {
        $DB = $this->load->database($server, TRUE); // Carga la base de datos especificada
        $sql = "
            SELECT
    gi.id AS grade_item_id,
    gi.courseid,
    gi.itemname,
    gi.itemmodule,
    DATE(FROM_UNIXTIME(cm.completionexpected)) AS finalizacion,
    DATE(
        CASE
            WHEN gi.itemmodule = 'assign' THEN FROM_UNIXTIME(a.duedate)
            WHEN gi.itemmodule = 'quiz' THEN FROM_UNIXTIME(q.timeclose)
            WHEN gi.itemmodule = 'forum' THEN FROM_UNIXTIME(f.cutoffdate)
            ELSE NULL
        END
    ) AS fecha_valida,
    gg.userid AS student_id,
    gg.finalgrade AS calificacion_obtenida,
    gi.grademax AS calificacion_maxima,
    (COALESCE(gg.finalgrade, 0) / gi.grademax) * 100 AS porcentaje_calificacion 
FROM
    mdl_grade_items gi
    LEFT JOIN mdl_course_modules cm ON cm.instance = gi.iteminstance 
    AND cm.module = (SELECT id FROM mdl_modules WHERE NAME = gi.itemmodule)
    LEFT JOIN mdl_assign a ON gi.itemmodule = 'assign' 
    AND gi.iteminstance = a.id
    LEFT JOIN mdl_quiz q ON gi.itemmodule = 'quiz' 
    AND gi.iteminstance = q.id
    LEFT JOIN mdl_forum f ON gi.itemmodule = 'forum' 
    AND gi.iteminstance = f.id
    LEFT JOIN mdl_grade_grades gg ON gi.id = gg.itemid 
    AND gg.userid = ?  -- Mover esta condición aquí para que la unión sea opcional
WHERE
    gi.id = ?
ORDER BY
    gi.courseid,
    gi.id
        ";

        // Ejecutar la consulta con los parámetros proporcionados
        $query =  $DB->query($sql, array($idmoodle, $idActividad,));

        // Devolver los resultados como un array
        return $query->result();
    }

    public function get_alumnos_por_matricula_registro($matricula)
    {

        $registro = $this->load->database('registro', TRUE);
        $query = $registro->select('*')->where("usuario", $matricula)->get('registro');
        return $query->result();
    }

    public function get_kardex($plataforma, $matricula, $calificacion)
    {
        $DB = $this->load->database($plataforma, TRUE); // Carga la base de datos especificada
        $sql = '
        SELECT
	SUBSTRING_INDEX( c.fullname, " - ", 1 ) AS materia_clave,
	c.fullname,
		gg.finalgrade ,
	DATE (
	FROM_UNIXTIME( c.enddate )) calificacionFecha
FROM
	mdl_user_enrolments ue
	LEFT JOIN mdl_enrol e ON ue.enrolid = e.id
	LEFT JOIN mdl_user u ON u.id = ue.userid
	LEFT JOIN mdl_course c ON e.courseid = c.id
	LEFT JOIN mdl_course_categories cat ON c.category = cat.id
	LEFT JOIN mdl_grade_items gi ON c.id = gi.courseid 
	AND gi.itemtype = "course"
	LEFT JOIN mdl_grade_grades gg ON gi.id = gg.itemid 
	AND gg.userid = ue.userid 
WHERE
	username = "' . $matricula . '"
	AND c.fullname NOT LIKE "%inducción%" 
	AND c.fullname NOT LIKE "%Extraordinario%" 
	AND c.fullname NOT LIKE "%Seminario%" 
	AND c.fullname NOT LIKE "%Ordinario%" 
	AND c.fullname NOT LIKE "%Título de suficiencia%" 
	AND cat.NAME NOT LIKE "%gratuitos%" 
	AND cat.NAME NOT LIKE "%Propedéutico%" 
	AND cat.NAME NOT LIKE "%Prórroga%" 
	AND cat.NAME NOT LIKE "%Curso%" 
	AND gg.finalgrade >= "' . $calificacion . '" 
ORDER BY
	c.fullname ASC';

        // Ejecutar la consulta con los parámetros proporcionados
        $query =  $DB->query($sql);

        // Devolver los resultados como un array
        return $query->result();
    }

    public function insert_data_kardex($data)
    {
        return $this->db->insert('kardex', $data);
    }


    public function data_encuesta()
    {
        // Cargar la base de datos 'registro'
        $DBREGISTRO = $this->load->database('registro', TRUE);

        // Ejecutar la consulta SQL directamente
        $sql = $DBREGISTRO->query("SELECT matricula, COALESCE(recomendarias_iexe, 'No') AS recomendarias_iexe FROM registro WHERE matricula != '' AND matricula NOT LIKE '%inactivo%'");

        // Convertir el resultado en un array con 'matricula' como índice
        $result = $sql->result_array();
        $data = [];

        foreach ($result as $row) {
            $data[strtoupper($row['matricula'])] = strtoupper($row['recomendarias_iexe']);
        }

        return $data;
    }

    public function get_promotores_crm($matriculas)
    {
        // Cargar la base de datos "pagos"
        $dbpagos = $this->load->database("pagos", true);

        $result = [];
        $chunkSize = 1000; // Tamaño del fragmento
        $chunks = array_chunk($matriculas, $chunkSize); // Divide el array en fragmentos de tamaño $chunkSize

        foreach ($chunks as $chunk) {
            // Selecciona los campos necesarios
            $dbpagos->select('a.idUsuario, CONCAT(a.nombre, " ", a.apellidoPaterno, " ", a.apellidoMaterno) AS promotor, a.correo, c.matricula');
            $dbpagos->from('usuarios a');
            $dbpagos->join('clientes b', 'a.idUsuario = b.idPromotor');
            $dbpagos->join('clientes_academicos c', 'b.idCliente = c.idCliente');

            // Usa where_in para filtrar por las matrículas en el fragmento actual
            $dbpagos->where_in('c.matricula', $chunk);

            // Ejecuta la consulta
            $query = $dbpagos->get();
            $rows = $query->result_array(); // Obtiene los resultados como array asociativo

            // Agrega los resultados al array result usando la matrícula como índice
            foreach ($rows as $row) {
                $result[$row['matricula']] = $row;
            }
        }

        return $result;
    }
}
