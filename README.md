# Sistema de Prediccion de Baja

Este repositorio contiene el código fuente del proyecto SPB.

## Modelos

### AlumnosModel

El modelo `AlumnosModel` contiene métodos para obtener información relacionada con los alumnos.

#### Método `get_todos_activos`

Este método devuelve todos los alumnos que están activos (is_active=1).

- **Descripción:** Este método realiza una consulta para obtener todos los registros de alumnos que tienen el estado activo (is_active=1).
  
- **Uso:**
  ```php
  $this->load->model('AlumnosModel');
  $alumnos_activos = $this->AlumnosModel->get_todos_activos();
