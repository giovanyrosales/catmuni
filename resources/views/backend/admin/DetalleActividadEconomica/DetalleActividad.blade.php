@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" type="text/css" rel="stylesheet" />

    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">

@stop

<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Agregar nueva actividad económica</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>

<!-- Inicia Formulario Actividad Economica-->
<section class="content">
     <div class="container-fluid" style="margin-left: 10px">
        <form class="col-md-10" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Detalle de la actividad económica.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
                    <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                        <label>Limite Inferior:</label>
                        <input type="text" name="limite_inferior" id="limite_inferior" class="form-control" required placeholder="Limite Inferior">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                <!-- /.form-group -->
              
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Fijo:</label>
                          <input type="text" name="fijo" id="fijo" class="form-control" required placeholder="Fijo" >
                  </div></div>

                  
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Categoria:</label>
                          <input type="text" name="categoria" id="categoria" required placeholder="Categoria" class="form-control" >
                  </div>
                 </div>
                

                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Millar:</label>
                    <input type="text" name="millar" id="millar" class="form-control" placeholder="Millar"  >
                  </div>
                 </div>
                </div> 
                <!-- /.form-group -->

                <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($actividadeconomica as $actE)
                                  <option value="{{ $actE->id }}"> {{ $actE->rubro }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
               
                    </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div>
              </div>
            </div>
            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaAct()"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
                </div>
                </div></div>
            </div>
          
            <!-- /.col -->
            </div>
          </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Actividad Economica-->

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>


    function nuevaAct()
      {
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var limite_inferior = document.getElementById('limite_inferior').value;
        var fijo = document.getElementById('fijo').value;
        var categoria = document.getElementById('categoria').value;
        var millar = document.getElementById('millar').value;

        if(limite_inferior === ''){
            toastr.error('El limite inferior es requerido');
            return;
        }
        
        if(fijo === ''){
            toastr.error('Fijo es requerido');
            return;
        }
       
        if(categoria === ''){
            toastr.error('Categoria es requerida');
            return;
        }
        
        if(millar === ''){
            toastr.error('Millar es requerido');
            return;
        }

        
        if(actividad_economica === ''){
            toastr.error('Actividad económica es requerida');
            return;
        }

        openLoading();
      var formData = new FormData();
      formData.append('actividad_economica', actividad_economica);
      formData.append('limite_inferior', limite_inferior);
      formData.append('fijo', fijo);
      formData.append('categoria', categoria);
      formData.append('millar', millar);

      axios.post('/admin/DetalleActividadEconomica/DetalleActividad', formData, {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            toastr.success('Guardado exitosamente');
          }
          else
          {
            toastr.error('¡Error al guardar!');
          }
        })
           .catch((error) => {
              toastr.error('Error al registrar');
               closeLoading();
          });

      }
   
    </script>

  @stop