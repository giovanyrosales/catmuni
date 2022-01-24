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
                            <i class="fas fa-user-friends nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.permisos.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-user-shield nav-icon"></i>
                            <p>Permisos y usuarios</p>
                        </a>
                    </li>
                                       
                    <li class="nav-item">
                        <a href="{{ route('admin.listarDetalleActividadEconomica.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-donate"></i>
                            <p>Actividades económicas</p>
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
                                    <p>Agregar Empresa</p>
                                    </a>
                                    </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.listarEmpresa.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-list-ol nav-icon"></i>
                                    <p>Listar Empresas</p>
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
                    </ul>
                </li>

            </ul>
        </nav>




    </div>
</aside>
