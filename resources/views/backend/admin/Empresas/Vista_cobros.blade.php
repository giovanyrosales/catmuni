@extends('backend.menus.superior')



@section('content-admin-css')

  <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
  <!--Finaliza el select live search -->

    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet" />
  
<style>

        #tres {
            overflow: hidden;
              }
        #tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tabla td{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }

        th{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: left;
            background-color: #F8F9F9;
            color: #1E1E1E;
        }

        #tbempresa{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: left;
            background-color: #F2F2F2;
            color: #1E1E1E;
        }

        #tabla th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #1E1E1E;
            color: #ddd;
            text-align: center;
            font-size: 16px;
        }

        .texto{
            margin-left: 12px;
            display: block;
            margin: 2px 0 0 0;
            font-size: small;
        }
        #uno{
                font-size: 15px;
        }
        #dos{
                font-size: 13px;
        }
        #especial{
            background-color: #E9F1FF;
            color: #1E1E1E;
        }
        #especial2{
            background-color: #E2FFED;
            color: #1E1E1E;
        }

        .badge-inverse {
        background-color: #0FC2EE;
        color: #ffffff;

        }

        .badge-inverse:hover {
        background-color: #108F27;
        color: #ffffff;
        }

/** CSS para btn flotante */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
#btn-mas{
    display: none;
}
#contenedor{
    position: fixed;
    bottom: 20px;
    right: 20px;
    float: left;
}
.redes a, .btn-mas label{
    display: block;
    text-decoration: none;
    background: #08BE4D;
    color: #fff;
    width: 55px;
    height: 55px;
    line-height: 55px;
    text-align: center;
    border-radius: 50%;
    box-shadow: 0px 1px 10px rgba(0,0,0,0.4);
    transition: all 500ms ease;
}
.redes a:hover{
    background: #fff;
    color: #C20E0E;
}
.redes a{
    margin-bottom: -15px;
    opacity: 0;
    visibility: hidden;
}
#btn-mas:checked~ .redes a{
    margin-bottom: 10px;
    opacity: 1;
    visibility: visible;
}
.btn-mas label{
    cursor: pointer;
    background: #118EE5; /** Color del botón */
    font-size: 23px;
}
#btn-mas:checked ~ .btn-mas label{
    transform: rotate(135deg);
    font-size: 25px;
}
       
</style>
@stop    

<!-- Inicia content-wrapper-->
<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                      <h5 class="modal-title"><i class="fas fa-search-dollar"></i>&nbsp;Cobrar obligaciones tributarias.</span>
                      </h5>
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Buscador de  empresas.</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!--Inicia card-projectcard-project-->
<div class="card card-projectcard-project" style="width: 98%; height:25%; margin: 0 auto; -webkit-border-radius: 5px 5px 5px 5px;border-radius: 5px 5px 5px 5px;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: 25%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
  <div class="card-body">

    <!-- Inicia Contenido-->
    <div class="col-md-6" style="width: 95%; height:50%; margin: 0 auto;">
        <div class="input-group mb-3">
            <select 
            required 
            onchange="buscar_empresa_a_cobrar()" 
            class="form-control selectpicker show-tick" 
            data-style="btn btn-outline-success"  
            data-show-subtext="false" 
            data-live-search="true" 
            id="select-empresa" 
            title="Buscar una empresa."
            >
                @foreach($ConsultaEmpresa as $empresas)
                  <option value="{{ $empresas->id }}"> {{ $empresas->nombre }} &nbsp;({{ $empresas->num_tarjeta }})</option>
                @endforeach 
            </select> 
          <div class="input-group-append">
            <label class="input-group-text"  for="inputGroupSelect02"><i class="fas fa-search"></i>&nbsp;Buscar </label>
          </div>
        </div>
    </div><!-- /.col-md-6 -->

  </div>
</div>
 <!-- Finaliza Contenido card-project-->

<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 5 auto;width: 98%;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body"  >
        <!-- Inicia contenido-->  
            <img src="{{ asset('/img/cobros.png') }}" id="img_cobros" style="display: block;margin: 0px auto;width: 25%; height:25%;" >                
        <!-- Finaliza contenido-->
        </div>
      </div>
    </div>
<!-- Finaliza Contenido IMG-->

<!-- Cerrando el content-wrapper-->
      </div>
      </div>
       </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario -->



@extends('backend.menus.footerjs')

@section('archivos-js')

<!-- Para el select live search -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<!-- Finaliza el select live search -->

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>



<script type="text/javascript">

$(document).ready(function(){
  document.getElementById("divcontenedor").style.display = "block"; 

  //** Tooltips de botón flotante */
  $('[data-toggle="tooltip"]').tooltip();

 });

function buscar_empresa_a_cobrar(){

            var id = document.getElementById('select-empresa').value;
            openLoading();
            
            
            var formData = new FormData();
            formData.append('id', id);
          
          axios.post('/admin/buscar/obligaciones_tributarias/calificadas', formData, {
           })
         .then((response) => {

        if(response.data.success === 3)
        {
            Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Esta empresa se encuentra cerrada, cambie su estado para generar un cobro',                                 
                                  showConfirmButton: true, 
                                  
                                }).then((result) => {
                                    if (result.isConfirmed) 
                                    {
                                        window.location.href="{{ url('/admin/empresas/show') }}/"+id;
                                    }
                                }); 
        }
        
        if(response.data.success === 2)
        {
            Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Esta empresa no cuenta con una calificación, registre una para poder generar un cobro',                                 
                                  showConfirmButton: true, 
                                  
                                }).then((result) => {
                                    if (result.isConfirmed) 
                                    {
                                        window.location.href="{{ url('/admin/empresas/show') }}/"+id;
                                    }
                                }); 
        }

        if(response.data.success === 1)
                {
                    
                    Swal.fire({
                          position:'top-end',
                          icon: 'success',
                          title: '¡Información encontrada!',
                          showConfirmButton: false,                     
                        })
                        window.location.href="{{ url('/admin/empresas/cobros') }}/"+id;
                }
              
            })
            .catch((error) =>{
                                toastr.error('Error al buscar la obligación triburaria');
                            });                 
    }


function VerEmpresa(id){
        openLoading();
        window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }


function modalMensaje(titulo, mensaje)
{
   Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {

                }
            });           
}

</script>

@endsection 