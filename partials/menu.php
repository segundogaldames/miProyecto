<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>">MiProyecto</a>
                <!-- boton de contraccion en dispositivo movil -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contacto</a>
                        </li>
                        <!-- dropdown de administracion del sistema -->
                        <?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Administrar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Clientes</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Marcas</a></li>
                                    <li><a class="dropdown-item" href="#">Productos</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'comunas/' ?>">Comunas</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'regiones/' ?>">Regiones</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'personas/' ?>">Personas</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'roles/' ?>">Roles</a></li>
                                </ul>
                            </li>
                            <!-- fin dropdown -->
                        <?php endif; ?>

                        <?php if(!isset($_SESSION['autenticado'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL . 'usuarios/login.php' ?>">Login</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL . 'usuarios/cerrar.php' ?>">Cerrar Sesión</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- formulario de busqueda -->
                    <!-- action = indica la direccion donde se envia el formulario. Si accion esta vacio, significa que procesaremos la informacion dentro de la misma pagina -->
                    <!-- ******************** -->
                    <!-- method = indica como enviaremos los datos al servidor
                    GET = los datos se enviaran a traves de la url de la pagina
                    POST = los datos se enviaran de manera interna, no visible para la url -->
                    <form class="d-flex" action="" method="POST">
                        <input class="form-control me-2" type="search" placeholder="Busque su producto" aria-label="Search" name="producto" required>
                        <input type="hidden" name="confirm" value="1">
                        <button class="btn btn-outline-dark" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </nav>