<?php
// Verificar si el usuario tiene el rol de Administrador
$esAdministrador = false;
foreach ($sesion['roles'] as $rol) {
    if ($rol->rol === 'Administrador') {
        $esAdministrador = true;
        break; // Salir del bucle una vez encontrado el rol de Administrador
    }
}

?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">
            <img id="logo" src="<?php echo base_url(); ?>assets/img/logo_iexe.webp" width="100%" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="<?php echo base_url(); ?>dashboard">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url(); ?>lista-alumnos">
                        <i class="fa-solid fa-graduation-cap"></i> Alumnos
                    </a>
                </li>
                <?php if ($esAdministrador) { ?>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url(); ?>usuarios">
                            <i class="fa-solid fa-users"></i> Usuarios
                        </a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link" href="lista_alumnos">
                            <i class="fa-solid fa-clipboard"></i> Asignaciones
                        </a>
                    </li> -->

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu4Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-clipboard"></i> Asignaciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menu4Dropdown">
                            <li><a class="dropdown-item" href="asignaciones-consejeras"><i class="fa-solid fa-comments"></i> Consejeras</a></li>
                            <li><a class="dropdown-item" href="asignaciones-financiero"><i class="fa-solid fa-headset"></i> Asesores financiero</a></li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($sesion['correo'] == 'giles.carlos@iexe.edu.mx') { ?>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url(); ?>recordatorio-consejera/campis.sara@iexe.edu.mx">
                            <i class="fa-regular fa-clipboard"></i> Revisión recordatorios
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url(); ?>recordatorio-consejera/<?php echo $sesion['correo']; ?>">
                            <i class="fa-regular fa-clipboard"></i> Revisión recordatorios
                        </a>
                    </li>
                <?php } ?>

            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo $sesion['nombre']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="cerrar-sesion">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>