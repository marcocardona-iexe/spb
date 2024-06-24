<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AlumnosModel extends CI_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
        // Cargar la base de datos
        $this->load->database();
        $this->table = 'alumnos';
    }

    /**
     * Obtiene todos los alumnos activos
     */
    public function get_todos_activos()
    {
        // Construye la consulta SELECT para seleccionar todas las columnas de la tabla
        // donde el campo 'is_active' es igual a 1 (activo)
        $query = $this->db->select('*')
            ->where("is_active", 1)
            ->get($this->table);

        // Ejecuta la consulta y devuelve todos los resultados como un array de objetos
        return $query->result();
    }


    /**
     * Edita un registro por su ID en la tabla especificada.
     *
     * @param int $id ID del registro que se va a editar.
     * @param array $data Datos actualizados que se van a aplicar al registro.
     * @return bool Retorna true si la actualización fue exitosa, false en caso contrario.
     */
    public function editar_por_id($id, $data)
    {
        // Usa el método where para especificar el ID y el método update para actualizar los datos
        $this->db->where('id', $id);
        $updated = $this->db->update($this->table, $data);

        // Imprime la consulta SQL ejecutada (opcional para propósitos de depuración)
        // echo $this->db->last_query();

        // Devuelve true si la actualización fue exitosa, false en caso contrario
        return $updated;
    }



    /**
     * Obtiene registros de alumnos según la condición especificada.
     *
     * @param mixed $where Condición para filtrar los registros de alumnos.
     *                     Puede ser un array para condiciones estructuradas o una cadena para condiciones SQL sin procesar.
     * @return array Retorna un array de objetos que representan los registros de alumnos que cumplen con la condición.
     */
    public function get_alumnos_where($where)
    {
        // Construir la consulta SQL para seleccionar registros de la tabla especificada
        $this->db->select('*');
        $this->db->from($this->table);

        // Verificar el tipo de $where para aplicar la condición adecuada
        if (is_array($where)) {
            // Si $where es un array, se usa para condiciones estructuradas
            $this->db->where($where);
        } else {
            // Si $where no es un array, se asume que es una condición SQL sin procesar y se agrega sin escaparla
            $this->db->where($where, null, false); // Para condiciones WHERE sin procesar
        }

        // Aplicar filtros adicionales según el rol del usuario (supongo que es una función del modelo)
        $this->filtro_rol();

        // Ejecutar la consulta y obtener el resultado
        $query = $this->db->get();

        // Retornar el resultado como un array de objetos que representan registros de alumnos
        return $query->result();
    }



    private function ordenamiento($order_column, $order_dir)
    {
        // Lista de columnas válidas para ordenar
        $valid_columns = array(
            'nombre',              // Columna 0: nombre del estudiante
            'periodo',             // Columna 1: periodo académico
            'programa',            // Columna 2: programa educativo
            'periodo_mensual',     // Columna 3: periodo mensual
            'matricula',           // Columna 4: matrícula del estudiante
            'correo',              // Columna 5: correo electrónico
            'probabilidad_baja',   // Columna 6: probabilidad de baja
            'estatus_plataforma',  // Columna 7: estatus en la plataforma
            'nombre_consejera',    // Columna 8: nombre de la consejera
            'nombre_financiero',   // Columna 9: nombre del asesor financiero
        );

        // Validar y obtener la columna de orden
        // Si $order_column es un índice válido en $valid_columns, usar esa columna; de lo contrario, usar 'nombre' (índice 0)
        $order = isset($valid_columns[$order_column]) ? $valid_columns[$order_column] : $valid_columns[0];

        // Validar la dirección de orden
        // Si $order_dir es 'asc' o 'desc', usarlo; de lo contrario, establecer 'asc' como valor por defecto
        $order_dir = ($order_dir === 'asc' || $order_dir === 'desc') ? $order_dir : 'asc';

        // Aplicar la ordenación en la consulta de la base de datos utilizando la columna y dirección validadas
        return $this->db->order_by($order, $order_dir);
    }

    private function filtro_rol()
    {
        // Verificar si el usuario es Consejera o Asesor Financiero
        $sesion = $this->session->userdata('seguimiento_iexe');

        $es_consejera = false;
        $es_asesor_financiero = false;
        $es_administrador = false;
        foreach ($sesion['roles'] as $rol) {
            if ($rol->rol === 'Consejera') {
                $es_consejera = true;
            } elseif ($rol->rol === 'Asesor Financiero') {
                $es_asesor_financiero = true;
            } elseif ($rol->rol === 'Administrador') {
                $es_administrador = true;
            }
        }


        // Imprimir un mensaje dependiendo del rol del usuario
        if ($es_administrador) {
            return;
        } else {
            if ($es_consejera) {
                return $this->db->where("alumnos.consejera", $sesion['idusuario']);
            } elseif ($es_asesor_financiero) {
                return $this->db->where("alumnos.financiero", $sesion['idusuario']);
            }
        }
    }


    private function query_base()
    {
        // Selección común para ambos casos
        $this->db->select('
            DISTINCT alumnos.id,
            CONCAT(alumnos.nombre, " ", alumnos.apellidos) AS nombre,
            alumnos.periodo,
            alumnos.programa,
            alumnos.periodo_mensual,
            UCASE(alumnos.matricula) AS matricula,
            alumnos.correo,
            alumnos.estatus_plataforma,
            alumnos.ultimo_acceso,
            alumnos.telefono,
            CASE
                WHEN variable_academica = 1 AND variable_financiera = 1 THEN "Alta R1"
                WHEN variable_academica = 0 AND variable_financiera = 1 THEN "Media R2"
                WHEN variable_academica = 1 AND variable_financiera = 0 THEN "Baja R3"
                WHEN variable_academica = 0 AND variable_financiera = 0 THEN "Baja R3"
                ELSE "Desconocida"
            END AS probabilidad_baja,
            CONCAT(consejera.nombre, " ", consejera.apellidos) AS nombre_consejera,
            CONCAT(financiero.nombre, " ", financiero.apellidos) AS nombre_financiero
        ', FALSE);

        $this->db->from($this->table);
        $this->db->join('usuarios AS consejera', 'alumnos.consejera = consejera.id', 'left');
        $this->db->join('usuarios AS financiero', 'alumnos.financiero = financiero.id', 'left');
    }

    public function no_alumnos_bloqueados()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from($this->table);
        $this->db->where('estatus_plataforma', 'Bloqueado');
        $this->filtro_rol();
        $query = $this->db->get();
        $resultado = $query->row(); // Usamos row() porque esperamos un único resultado
        if ($resultado) {
            return $resultado->count;
        } else {
            return "err";
        }
    }


    public function get_bloqueados($start, $length, $order_column, $order_dir, $where, $use_where)
    {
        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();

        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();

        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        $this->ordenamiento($order_column, $order_dir);

        // Verifica si se debe usar el parámetro $where para agregar condiciones a la consulta.
        if ($use_where) {
            // Si $where es un array, utiliza el método where de CodeIgniter para agregar condiciones escapadas.
            if (is_array($where)) {
                $this->db->where($where);
            }
            // Si $where no es un array, asume que es una condición SQL sin procesar y la agrega sin escaparla.
            else {
                $this->db->where($where, null, false); // Para condiciones WHERE sin procesar
            }
        }

        // Aplica el límite a la consulta para paginación.
        // $length es el número de registros a devolver y $start es el desplazamiento (offset).
        $this->db->limit($length, $start);

        // Ejecuta la consulta y obtiene el resultado.
        $query = $this->db->get();

        // Descomentar la línea siguiente para imprimir la última consulta ejecutada.
        // Esto es útil para depuración.
        // echo $this->db->last_query();

        // Devuelve el resultado de la consulta como un array de objetos.
        return $query->result();
    }


    // Método para obtener los alumnos con probabilidad de baja paginados y según el nivel de probabilidad
    public function get_por_probabilidad_baja($start, $length,  $order_column, $order_dir, $where, $use_where)
    {

        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();

        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();

        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        $this->ordenamiento($order_column, $order_dir);

        // Verifica si se debe usar el parámetro $where para agregar condiciones a la consulta.
        if ($use_where) {
            // Si $where es un array, utiliza el método where de CodeIgniter para agregar condiciones escapadas.
            if (is_array($where)) {
                $this->db->where($where);
            }
            // Si $where no es un array, asume que es una condición SQL sin procesar y la agrega sin escaparla.
            else {
                $this->db->where($where, null, false); // Para condiciones WHERE sin procesar
            }
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function get_alumnos_por_seguimiento($start, $length, $order_column, $order_dir, $where, $use_where)
    {

        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();
        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();
        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        $this->ordenamiento($order_column, $order_dir);
        // Aplicar la lógica específica según el tipo de seguimiento
        if ($where == 'Abierto') {
            $this->db->join('seguimientos', 'alumnos.id = seguimientos.idalumno');
            $this->db->where('seguimientos.estatus', 'abierto');
        } elseif ($where == 'Cerrado') {
            $this->db->join('(SELECT idalumno FROM seguimientos WHERE estatus = "cerrado") AS seguimientos_cerrados', 'alumnos.id = seguimientos_cerrados.idalumno', 'left');
            $this->db->join('seguimientos', 'alumnos.id = seguimientos.idalumno', 'left');
            $this->db->where('(seguimientos_cerrados.idalumno IS NOT NULL OR seguimientos.idalumno IS NULL)', NULL, FALSE);
        }
        $this->db->limit($length, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


    public function get_total_por_seguimiento($where)
    {

        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();
        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();
        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        // Aplicar la lógica específica según el tipo de seguimiento
        if ($where == 'Abierto') {
            $this->db->join('seguimientos', 'alumnos.id = seguimientos.idalumno');
            $this->db->where('seguimientos.estatus', 'abierto');
        } elseif ($where == 'Cerrado') {
            $this->db->join('(SELECT idalumno FROM seguimientos WHERE estatus = "cerrado") AS seguimientos_cerrados', 'alumnos.id = seguimientos_cerrados.idalumno', 'left');
            $this->db->join('seguimientos', 'alumnos.id = seguimientos.idalumno', 'left');
            $this->db->where('(seguimientos_cerrados.idalumno IS NOT NULL OR seguimientos.idalumno IS NULL)', NULL, FALSE);
        }
        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }






    public function busuqeda_avanzada($start, $length, $order_column, $order_dir, $where, $use_where)
    {

        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();

        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();

        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        $this->ordenamiento($order_column, $order_dir);

        // Verifica si se debe usar el parámetro $where para agregar condiciones a la consulta.
        if ($use_where) {
            // Si $where es un array, utiliza el método where de CodeIgniter para agregar condiciones escapadas.
            if (is_array($where)) {
                $this->db->where($where);
            }
            // Si $where no es un array, asume que es una condición SQL sin procesar y la agrega sin escaparla.
            else {
                $this->db->where($where, null, false); // Para condiciones WHERE sin procesar
            }
        }

        // Aplica el límite a la consulta para paginación.
        // $length es el número de registros a devolver y $start es el desplazamiento (offset).
        $this->db->limit($length, $start);

        // Ejecuta la consulta y obtiene el resultado.
        $query = $this->db->get();

        // Descomentar la línea siguiente para imprimir la última consulta ejecutada.
        // Esto es útil para depuración.
        //echo $this->db->last_query();

        // Devuelve el resultado de la consulta como un array de objetos.
        return $query->result();
    }


    public function total_busuqeda_avanzada($where)
    {
        $this->query_base();
        $this->filtro_rol();
        if (is_array($where)) {
            $this->db->where($where);
        } else {
            $this->db->where($where, null, false); // Para condiciones WHERE sin procesar
        }
        $query = $this->db->get();
        return $query->result();
    }


    // Método para contar el total de alumnos con seguimiento cerrado
    public function obtener_alumnos_sin_seguimiento_cerrado()
    {
        // Selecciona el conteo de todos los alumnos como total_alumnos
        $this->db->select('COUNT(*) AS total_alumnos');

        // Establece la tabla desde la cual se va a realizar la consulta
        $this->db->from('alumnos');

        // Añade una condición WHERE sin escapar. La condición verifica que:
        // 1. El id del alumno no está en los seguimientos con estatus "Cerrado"
        // 2. O que el conteo de seguimientos para un alumno específico es igual a 0 (no hay seguimientos)
        $this->db->where('(
        alumnos.id NOT IN (SELECT idalumno FROM seguimientos WHERE estatus = "Cerrado") 
            OR (SELECT COUNT(*) FROM seguimientos WHERE seguimientos.idalumno = alumnos.id) = 0
        )', NULL, FALSE);

        // Aplica filtros adicionales según el rol del usuario actual.
        // Es probable que esta función agregue condiciones WHERE adicionales.
        $this->filtro_rol();

        // Ejecuta la consulta y obtiene el resultado
        $query = $this->db->get();

        // Obtiene una sola fila del resultado como un objeto
        $result = $query->row();

        // Devuelve el total de alumnos sin seguimiento cerrado
        return $result->total_alumnos;
    }

    // Método para contar el total de alumnos con seguimiento abierto
    public function obtener_total_alumnos_seguimiento_abierto()
    {
        // Selecciona el conteo de alumnos distintos que tienen seguimientos abiertos
        $this->db->select('COUNT(DISTINCT alumnos.id) AS total_abiertos');

        // Establece la tabla principal desde la cual se realizará la consulta
        $this->db->from('alumnos');

        // Realiza una unión izquierda con la tabla 'seguimientos' basada en la igualdad de ids
        $this->db->join('seguimientos', 'alumnos.id = seguimientos.idalumno', 'left');

        // Agrega una condición WHERE para filtrar los seguimientos que están 'Abiertos'
        $this->db->where('seguimientos.estatus', 'Abierto');

        // Aplica filtros adicionales basados en el rol actual del usuario
        $this->filtro_rol();

        // Ejecuta la consulta y obtiene el resultado
        $query = $this->db->get();

        // Devuelve el valor de 'total_abiertos' obtenido de la fila del resultado
        return $query->row()->total_abiertos;
    }

    // Método para contar el total de alumnos según el nivel de probabilidad
    public function no_alumnos_por_probabilidad($probabilidad_baja)
    {
        // Selecciona la cantidad de filas y las nombra 'numrows'
        $this->db->select('COUNT(*) AS numrows');
        // Especifica la tabla 'alumnos' como fuente de datos
        $this->db->from('alumnos');
        // Aplica filtros de rol según la función definida (asumiendo que la función filtro_rol() agrega condiciones adicionales)
        $this->filtro_rol();

        // Verifica el valor de $probabilidad_baja y aplica las condiciones correspondientes
        if ($probabilidad_baja == 'r1') {
            // Si la probabilidad es 'r1', filtra donde variable_academica = 1 y variable_financiera = 1
            $this->db->where('variable_academica', 1);
            $this->db->where('variable_financiera', 1);
        } elseif ($probabilidad_baja == 'r2') {
            // Si la probabilidad es 'r2', filtra donde variable_academica = 0 y variable_financiera = 1
            $this->db->where('variable_academica', 0);
            $this->db->where('variable_financiera', 1);
        } elseif ($probabilidad_baja == 'r3') {
            // Si la probabilidad es 'r3', aplica una condición más compleja:
            // Filtra donde (variable_academica = 1 y variable_financiera = 0) 
            // o (variable_academica = 0 y variable_financiera = 0)
            $this->db->group_start(); // Abre un grupo de condiciones
            $this->db->group_start(); // Abre un subgrupo
            $this->db->where('variable_academica', 1);
            $this->db->where('variable_financiera', 0);
            $this->db->group_end(); // Cierra el subgrupo
            $this->db->or_group_start(); // Abre otro subgrupo con OR
            $this->db->where('variable_academica', 0);
            $this->db->where('variable_financiera', 0);
            $this->db->group_end(); // Cierra el segundo subgrupo
            $this->db->group_end(); // Cierra el grupo principal
        } else {
            // Si el valor de $probabilidad_baja no es válido, retorna null
            return null;
        }

        // Ejecuta la consulta
        $query = $this->db->get();
        // Obtiene el resultado de la consulta como una fila
        $result = $query->row();
        // Retorna el número de filas contadas
        return $result->numrows;
    }








    public function get_limit_alumnos($start, $length, $order_column, $order_dir, $where, $user_where)
    {
        // Llama a la función query_base() para establecer la base de la consulta.
        $this->query_base();

        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();

        // Llama a la función ordenamiento() para aplicar el ordenamiento a la consulta.
        $this->ordenamiento($order_column, $order_dir);


        $this->db->limit($length, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


    public function get_todos_alumnos()
    {
        $this->query_base();
        // Llama a la función filtro_rol() para aplicar filtros basados en el rol del usuario.
        $this->filtro_rol();
        $query = $this->db->get();
        return $query->result();
    }





    public function reporte_excel_todos()
    {
        $this->db->select('
            alumnos.id,
            CONCAT(alumnos.nombre, " ", alumnos.apellidos) AS nombre,
            alumnos.matricula,
            CASE
                WHEN variable_academica = 1 AND variable_financiera = 1 THEN "Baja R1"
                WHEN variable_academica = 0 AND variable_financiera = 1 THEN "Baja R2"
                WHEN variable_academica = 1 AND variable_financiera = 0 THEN "Baja R3"
                WHEN variable_academica = 0 AND variable_financiera = 0 THEN "Baja R3"
                ELSE "Desconocida"
            END AS probabilidad_baja,
            alumnos.periodo,
            alumnos.programa,
            alumnos.periodo_mensual,
            alumnos.correo,
            alumnos.estatus_plataforma,
            alumnos.variable_academica,
            alumnos.variable_financiera,
            CONCAT(consejera.nombre, " ", consejera.apellidos) AS nombre_consejera,
            CONCAT(financiero.nombre, " ", financiero.apellidos) AS nombre_financiero
        ', FALSE);
        $this->db->from($this->table);
        $this->db->join('usuarios AS consejera', 'alumnos.consejera = consejera.id', 'left');
        $this->db->join('usuarios AS financiero', 'alumnos.financiero = financiero.id', 'left');
        $this->filtro_rol();
        $query = $this->db->get();
        return $query->result();
    }




    function obtener_todos_alumnos_in_array()
    {
        $query = $this->db->select('id')->get($this->table);
        return array_column($query->result_array(), 'id');
    }

    public function get_all_alumnos()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_all_alumnos_where($where)
    {
        $query = $this->db->where($where)->get($this->table);
        return $query->result();
    }

    public function alumno_activo_programa_academico($programa, $hoy)
    {
        $query = $this->db->query("SELECT * FROM alumnos WHERE is_active=1 AND update_cron_academico!='" . $hoy . "'  AND programa='" . $programa . "'");

        return $query->result();
    }


    public function alumno_activo_programa_financiero($programa, $hoy)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where('is_active', 1)
            ->where('update_cron_financiero !=', $hoy)
            ->where('programa', $programa)
            ->get();
        return $query->result();
    }

    public function insert_batch($dataInsert)
    {
        $this->db->insert_batch($this->table, $dataInsert);
    }


    public function update_batch($dataUpdate)
    {
        $this->db->update_batch($this->table, $dataUpdate, 'id');
        //echo $this->db->last_query();
    }

    public function desactivar_batch($excluded_ids)
    {
        // Establecer is_active a 0 para todos los registros cuyo id no está en $excluded_ids
        $this->db->where_not_in('id', $excluded_ids);
        $this->db->update($this->table, ['is_active' => 0]);
    }


    public function obtener_periodos_activos_consejeras($programa)
    {
        $this->db->distinct();
        $this->db->select('periodo');
        $this->db->from($this->table);
        $this->db->where('programa', $programa);
        $this->db->where('consejera IS NULL', NULL, FALSE); // Para manejar IS NULL
        $this->db->order_by('periodo');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtener_periodos_activos($programa)
    {
        $this->db->distinct();
        $this->db->select('periodo');
        $this->db->from($this->table);
        $this->db->where('programa', $programa);
        $this->db->order_by('periodo');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function obtener_periodos_mensuales_activos($programa, $periodo)
    {
        $this->db->distinct();
        $this->db->select('periodo_mensual');
        $this->db->from($this->table);
        $this->db->where('programa', $programa);
        $this->db->where('periodo', $periodo);
        $this->db->order_by('periodo');
        $query = $this->db->get();
        return $query->result_array();
    }




    // Función para verificar si existe un alumno con el id del consejero
    public function existe_alumno_con_consejera($id_consejera, $programa, $periodo)
    {
        // Construir la consulta
        $this->db->select('1'); // Solo seleccionamos 1 porque solo queremos saber si existe
        $this->db->from($this->table);
        $this->db->where('consejera', $id_consejera);
        $this->db->where('programa', $programa);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();

        // Verificar si hay algún resultado
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Función para actualizar el campo idusuario
    public function asigna_alumno_consejera($consejera, $periodo, $programa)
    {
        // Configurar los datos de actualización
        $data = array(
            'consejera' => $consejera
        );

        // Aplicar las condiciones
        $this->db->where('periodo', $periodo);
        $this->db->where('programa', $programa);

        // Realizar la actualización
        $this->db->update($this->table,  $data);

        // Verificar si hubo filas afectadas
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function asigna_alumno_financiero($financiero, $matricula)
    {
        // Configurar los datos de actualización
        $data = array(
            'financiero' => $financiero
        );

        // Aplicar las condiciones
        $this->db->where('matricula', $matricula);
        // Realizar la actualización
        $this->db->update($this->table,  $data);

        // Verificar si hubo filas afectadas
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }




    // Función para verificar si existe un alumno con el id del consejero
    public function existe_alumno_con_financiero($matricula)
    {
        $this->db->select('1');
        $this->db->from('alumnos');
        $this->db->where('matricula', $matricula);
        $this->db->where('(financiero IS NULL OR financiero = "")');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return 0; // No tiene asesor financiero asignado
        } else {
            return 1; // Ya tiene un asesor financiero asignado
        }
    }


    public function get_count_alumnos_por_programa($programa)
    {
        $this->db->like('matricula', $programa, 'after');
        $this->db->from('alumnos');
        return $this->db->count_all_results();
    }




    public function update_by_matricula($matricula, $data)
    {
        $this->db->where('matricula', $matricula);
        return $this->db->update('alumnos', $data);
    }

    public function programas_periodos_activos()
    {
        $query = $this->db->query("
            SELECT DISTINCT
                CASE 
                    WHEN matricula LIKE 'MGPM%' THEN 'Maestría en Gestión Pública Municipal'
                    WHEN matricula LIKE 'MFP%' THEN 'Maestría en Finanzas Públicas'
                    WHEN matricula LIKE 'DPP%' THEN 'Doctorado en Políticas Públicas'
                    WHEN matricula LIKE 'MEPP%' THEN 'Maestría en Evaluación de Políticas Públicas'
                    WHEN matricula LIKE 'MCD%' OR matricula LIKE 'MCDIA%' THEN 'Maestría en Ciencias de Datos'
                    WHEN matricula LIKE 'MAN%' THEN 'Maestría en Administración de Negocios'
                    WHEN matricula LIKE 'MAPP%' THEN 'Maestría en Administración y Políticas Públicas'
                    WHEN matricula LIKE 'MAG%' THEN 'Maestría en Auditoría Gubernamental'
                    WHEN matricula LIKE 'MAIS%' THEN 'Maestría en Instituciones de Salud'
                    WHEN matricula LIKE 'LSP%' THEN 'Licenciatura en Seguridad Pública'
                    WHEN matricula LIKE 'LD%' THEN 'Licenciatura en Derecho'
                    WHEN matricula LIKE 'LCPAP%' THEN 'Licenciatura en Ciencias Políticas y Administración Pública'
                    WHEN matricula LIKE 'LCE%' THEN 'Licenciatura en Ciencias de la Educación'
                    WHEN matricula LIKE 'LAE%' THEN 'Licenciatura en Administración de Empresas'
                    WHEN matricula LIKE 'DSP%' THEN 'Doctorado en Seguridad Pública'
                    WHEN matricula LIKE 'MIGE%' THEN 'Maestría en Innovación y Gestión Educativa'
                    WHEN matricula LIKE 'MITI%' THEN 'Maestría en Innovación y Gestión Educativa'
                    WHEN matricula LIKE 'MMPOP%' THEN 'Maestría en Marketing Político y Opinión Pública'
                    WHEN matricula LIKE 'MSPAJO%' THEN 'Maestría en Sistema Penal Acusatorio y Juicio Oral'
                    WHEN matricula LIKE 'MSPP%' THEN 'Maestría en Seguridad Pública y Políticas Públicas'
                    ELSE 'Otro Programa'
                END AS nombre_programa,
                periodo,
                COALESCE(CONCAT(u.nombre,' ',u.apellidos), 'Sin asignar') AS consejera
            FROM
                alumnos a
            LEFT JOIN
                usuarios u ON a.consejera = u.id
        ");
        return $query->result();
    }
}
