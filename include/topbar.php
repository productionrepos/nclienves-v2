<header id="headpage">
    <div class="container">
        <nav class="  row navbar navbar-expand ">
                    <div class="col-2 col-sm-2 col-md-2">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>
                    </div>
                    <div class="col-10 col-sm-10 col-md-10 justify-content-end" >
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse " id="navbarSupportedContent" style="justify-content: end;">
                        <div class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-menu d-flex">
                                    <div class="user-name text-end me-3">
                                        <h6 class="mb-0 text-gray-600"><?php echo $_SESSION['cliente']->nombres_datos_contacto.' '.$_SESSION['cliente']->apellidos_datos_contacto ?></h6>
                                        <!-- <p class="mb-0 text-sm text-gray-600">Administrator</p> -->
                                    </div>
                                    <div class="user-img d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                            <img src="../assets/images/faces/1.jpg">
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                                <li>
                                    <h6 class="dropdown-header">Hola, <?php echo $_SESSION['cliente']->nombres_datos_contacto ?></h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="icon-mid bi bi-person me-2"></i> 
                                        Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="icon-mid bi bi-gear me-2"></i>
                                        Mis Datos
                                    </a>
                                </li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../ws/phplogout.php">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        </nav>
    </div>
                    
</header>