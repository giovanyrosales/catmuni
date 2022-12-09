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
                        <a href="{{ route('admin.ActividadEspecifica.index') }}" target="frameprincipal" class="nav-link">
                        <i class="fas fa-network-wired nav-icon"></i>
                            <p>Actividades específicas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.listarTarifaVariable.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-donate nav-icon"></i>
                            <p>Tarifa variable</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.TarifaFija.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-percent nav-icon"></i>
                            <p>Tarifas fijas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.LicenciaMatricula.index') }}" target="frameprincipal" class="nav-link">
                            <i class="far fa-credit-card nav-icon"></i>
                            <p>Licencia y matriculas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.Multas.index') }}" target="frameprincipal" class="nav-link">
                             <i class="fas fa-exclamation-triangle nav-icon"></i>
                            <p>Multas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.TasaInteres.index') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-hand-holding-usd nav-icon"></i>
                            <p>Tasas de interés</p>
                        </a>
                    </li>
                </ul>
             </li>


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
                                <p>Listar contribuyentes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.cobrar.empresa.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-coins nav-icon"></i>
                                <p>Obligaciones tributarias</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.historico.solvencias.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-history nav-icon"></i>
                                <p>Histórico Solvencias</p>
                                </a>
                            </li>
                        </ul>
                <!-- Finaliza Grupo Contribuyentes -->

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
                                <li class="nav-item">
                                    <a href="{{ route('admin.cobros.empresa.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-coins nav-icon"></i>
                                    <p>Cobros</p>
                                </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.historico.avisos.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-history nav-icon"></i>
                                    <p>Historial notificaciones</p>
                                </a>
                                </li>
                            </ul>
                <!-- Finaliza Grupo Empresas -->

                <!-- Grupo Rótulos -->
                         <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-sign"></i>
                                <p>Rótulos <i class="right fas fa-angle-left"></i></p>
                                </a>
                       
                <!-- NUEVO MÓDULO DE RÓTULOS DETALLE -->
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.crear.rotulos.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Agregar Rótulo</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.listarRotulosDetalle.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-list-ol nav-icon"></i>
                                <p>Listar Rótulos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.historico.bus.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-history nav-icon"></i>
                                <p>Historial notificaciones</p>
                                </a>
                            </li>
                        </ul>
                    <!-- TERMINA NUEVO MÓDULO DE RÓTULOS DETALLE -->

                <!-- Grupo Buses -->
                                <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-bus"></i>
                                <p>Buses <i class="right fas fa-angle-left"></i></p>

                                </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.crear.buses.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Agregar Buses</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.listarBuses.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Listar Buses</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.historico.bus.index') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-history nav-icon"></i>
                                <p>Historial notificaciones</p>
                                </a>
                            </li>
                        </ul>
                <!-- Finaliza Grupo Rótulos -->
                <!-- Grupo Buses -->
                <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="fas fa-file"></i>
                                <p>Reportes <i class="right fas fa-angle-left"></i></p>

                                </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.mora_tributaria') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>Mora tributaria global</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.mora_tributaria_periodica') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-calendar-alt nav-icon"></i>
                                <p>Mora tributaria periódica</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.reporte_cobros') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Ingresos global</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.cobros_diarios') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-funnel-dollar nav-icon"></i>
                                <p>Cobros diarios</p>
                                </a>
                            </li>
                       
                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.actividad.economica') }}" target="frameprincipal" class="nav-link">
                                    <i class="fas fa-cubes nav-icon"></i>
                                    <p>Actividad Económica</p>
                                </a>
                            </li>
                        
                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.contribuyentes') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-id-badge nav-icon"></i>
                                    <p>Contribuyentes</p>
                                </a>
                            </li>
                       <!--     
                            <li class="nav-item">
                                <a href="{{ route('admin.sidebar.reporte.empresas.prueba') }}" target="frameprincipal" class="nav-link">
                                <i class="fas fa-hotel nav-icon"></i>
                                    <p>Empresas Prueba</p>
                                </a>
                            </li>
                        -->
                        </ul>
                <!-- Finaliza Grupo Rótulos -->

                    </ul>
                </li>

            </ul>
        </nav>



    </div>
</aside>
