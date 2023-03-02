<div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-center">
                            <a href="./index.php"><img src="../include/img/logo_horizontal.png"></a>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
        
                        <li class="sidebar-item">
                            <a href="../index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item ">
                            <a href="../seleccionBultos.php" class='sidebar-link'>
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Enviar</span>
                            </a>
                        </li>
        
                        <li class="sidebar-item  ">
                            <a href="../Bodegas.php" class='sidebar-link'>
                                <i class="fa-solid fa-warehouse"></i>
                                <span>Mis direcciones</span>
                            </a>
                        </li>
        
                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Mis Envios</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="../PedidosRealizados.php">Envíos Realizados</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="../PedidosPendientes.php">Pendientes</a>
                                </li>
                                
                            </ul>
                        </li>
                        
                        <li class="sidebar-item  ">
                            <a href="../misDatos.php" class='sidebar-link'>
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Mis datos</span>
                            </a>
                        </li>
        
                        <!-- <li class="sidebar-item  ">
                            <a href="../datosComerciales.php" class='sidebar-link'>
                                <i class="fa-solid fa-file-invoice"></i>
                                <span>Datos Comerciales</span>
                            </a>
                        </li> -->

                        <?php
                        $administrador = $_SESSION['cliente']->rol;

                        if($administrador == 1) { ?>

                            <li class="sidebar-item  has-sub">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-gear-wide-connected"></i>
                                    <span>Administración</span>
                                </a>
                                <ul class="submenu ">
                                    <li class="submenu-item ">
                                        <a href="/administracion/pedidos.php">Pedidos</a>
                                    </li>
                                    <li class="submenu-item ">
                                        <a href="../administracion/clientes.php">Clientes</a>
                                    </li>
                                    <li class="submenu-item ">
                                        <a href="../administracion/transacciones.php">Transacciones</a>
                                    </li>
                                    <li class="submenu-item ">
                                        <a href="../administracion/credito.php">Crédito</a>
                                    </li>
                                    <li class="submenu-item ">
                                        <a href="../administracion/cierreMensual.php">Cierrre Mensual Crédito</a>
                                    </li>
                                    
                                </ul>
                            </li>

                        <?php } ?>


                        <li class="sidebar-item  ">
                            <a href="/querytest.php" class='sidebar-link'>
                                <i class="fa-solid fa-warehouse"></i>
                                <span>Test Query</span>
                            </a>
                        </li>
        
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>