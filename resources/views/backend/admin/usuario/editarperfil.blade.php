
<script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    
<script type="text/javascript">
  
     function actualizarPerfil(){

       
      var pass = document.getElementById('password').value;
      var pass2 = document.getElementById('password2').value; 

      if (pass === '')
          {
            toastr.error("Ingrese contraseña");
             return;
           } 
      if (pass2 === '')
             {
            toastr.error("Ingrese contraseña 2");
             return;
             }
       if(pass.length > 16){
          toastr.error('máximo 16 caracteres para contraseña actual');
            return;
            }

       if(pass.length < 4){
            toastr.error('mínimo 4 caracteres para contraseña');
             return;
            }

        if(pass.length > 16){
             toastr.error('máximo 16 caracteres para contraseña');
                return;
            }

        if(pass2.length > 16){
            toastr.error('máximo 16 caracteres para contraseña actual');
                return;
            }

       if(pass2.length < 4){
            toastr.error('mínimo 4 caracteres para contraseña');
                return;
            }

        if(pass2.length > 16){
            toastr.error('máximo 16 caracteres para contraseña');
                return;
            }
        
        // contrasena no coincide
        if(pass !== pass2){
            toastr.error("Contraseña no coincide...");
                return;
           }
                
        

      let formData = new FormData();
                formData.append('password', pass);
                
      // GUARDAR DATOS + CONTRASENa

            axios.post('/admin/usuario/editarperfil', formData)
                      .then(function (response) {
            if (response.data.success === 999)
                {
              toastr.error("Contraseña Incorrecta");
               }
               else if (response.data.success === 1){
                    toastr.info("Contraseña Correcta");

                }

              })
                .catch(function (error) {
             toastr.error("Error de Servidor!");
                }); 
                  }

</script>
@extends('backend.menus.Superior')
 
@section('content-admin-css')

<link href="{{ asset('css/backend/adminlte3/adminlte.min.css') }}" type="text/css" rel="stylesheet" /> 
<!-- Toastr -->
<link href="{{ asset('plugins/toastr/toastr.min.css') }}" type="text/css" rel="stylesheet" /> 
@stop
              
</script>

@extends('backend.menus.Superior')
 
<section class="content-header">
    <div class="container-fluid">
        <div class="col-sm-12">
            <h1>Actualizar Contraseña</h1>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid" style="margin-left: 15px">
        <div class="row">
            <div class="col-sm-6">
                <div class="card card-green">
                    <div class="card-header">
                        <h3 class="card-title">Datos del Usuario</h3>
                    </div>
                    <form>
                        <div class="card-body">
            
              <div class="form-group">
                        <label>Nombre Apellido:</label>
                        <input type="text" id="nombre" class="form-control" required placeholder="Nombre"disabled value=" {{$usuario->nombre}} {{$usuario->apellido}} ">
                       
                      </div>

              <!-- /.form-group -->
                <div class="form-group">
                        <label>Usuario:</label>
                        <input type="text" name="usuario" id="usuario" required class="form-control" placeholder="Apellido" disabled value="{{$usuario->usuario}} ">
                      </div>
                <!-- /.form-group -->
              
                <div class="form-group">
                    <label for="exampleInputPassword1">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control"  value="">
                  </div>
                <!-- /.form-group -->    
                <div class="form-group">
                    <label for="exampleInputPassword2">Repetir Contraseña:</label>
                    <input type="password" name="password2" id="password2" class="form-control"  value="">
                  </div>
                <!-- /.form-group -->  
              </div>
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarPerfil()">Actualizar</button>
                  <button type="button" onclick="location.href='{{url('panel')}}'" class="btn btn float-left">Cancelar</button>
                </div>
              </div>
            <!-- /.col -->
            </div>
          <!-- /.row -->
 
           </div>
         <!-- /.card-body -->
        
                <!-- /.card-footer -->
         </div> 
      <!-- /.card -->
      </form>
    </div>
          <!-- /form -->
    <!-- /.container-fluid -->
    </section>
@section('content-admin-js')  

@stop