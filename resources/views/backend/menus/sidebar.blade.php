<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('images/icono-sistema.png') }}" alt="Logo" class="brand-image img-circle elevation-3" >
        <span class="brand-text font-weight" style="color: white">CATASTRO MUNICIPAL</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <!-- Grupo Roles y permisos -->
             <li class="nav-item">

                 <a href="#" class="nav-link">
                    <i class="far fa-edit"></i>
                    <p>
                        Config
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-user-friends"></i>
                            <p>Roles</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.permisos.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-user-shield"></i>
                            <p>Permisos y usuarios</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.ActividadEspecifica.index') }}" target="frameprincipal" class="nav-link">
                        <i class="fas fa-network-wired"></i>
                            <p>Actividades específicas</p>
                        </a>
                    </li>
                                    
                    <li class="nav-item">
                        <a href="{{ route('admin.listarTarifaVariable.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-donate"></i>
                            <p>Tarifa variable</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.TarifaFija.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-percent"></i>
                            <p>Tarifas fijas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.LicenciaMatricula.index') }}" target="frameprincipal" class="nav-link">
                            <i class="far fa-credit-card"></i>
                            <p>Licencia y matriculas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.Multas.index') }}" target="frameprincipal" class="nav-link">
                             <i class="fas fa-exclamation-triangle"></i>
                            <p>Multas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.TasaInteres.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-hand-holding-usd"></i>
                            <p>Tasas de interés</p>
                        </a>
                    </li>
                </ul>
             </li>
            
            <!-- Grupo Empresas -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-building"></i>
                                    <p>Empresas 
                                    <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                <a href="{{ route('admin.crear.empresa.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Agregar nueva empresa</p>
                                    </a>
                                    </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.listarEmpresa.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-list-ol nav-icon"></i>
                                    <p>Listar empresas</p>
                                </a>
                                </li>
                            </ul>
                <!-- Finaliza Grupo Empresas -->
                <!-- Grupo Contribuyentes -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-people-arrows"></i>
                                <p>Contribuyentes <i class="right fas fa-angle-left"></i></p>
                                </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.crear.contribuyentes.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Agregar contribuyente</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.listarContribuyentes.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-id-badge nav-icon"></i>
                                <p>Listar Contribuyentes</p>
                                </a>
                            </li>
                        </ul>
                <!-- Finaliza Grupo Contribuyentes -->

                 <!-- Grupo Rótulos -->
                         <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-sign"></i>
                                <p>Rótulos <i class="right fas fa-angle-left"></i></p>
                                
                                </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.crear.rotulos.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Agregar Rótulo</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.listarRotulos.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-list-ol nav-icon"></i>
                                <p>Listar Rótulos</p>
                                </a>
                            </li>
                        </ul>
                <!-- Finaliza Grupo Rótulos -->
                <!-- Grupo Buses -->
                                <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-bus"></i>
                                <p>Buses <i class="right fas fa-angle-left"></i></p>
                                
                                </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.crear.rotulos.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Agregar Buses</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.listarRotulos.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Listar Buses</p>
                                </a>
                            </li>
                        </ul>
                <!-- Finaliza Grupo Rótulos -->
                    </ul>
                </li>

            </ul>
        </nav>



    </div>
</aside>
