<?php
if(isset($_SESSION['usuario'])){
	session_destroy();	
}

?>
<!DOCTYPE html>
<html>
<meta charset="utf8">
<head>
	<title>Prueba Ortografia</title>
	
	<link rel="stylesheet" type="text/css" href="libs/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="libs/css/mdb-fixed.min.css">
	<link rel="stylesheet" type="text/css" href="libs/css/all.css">
	<link rel="stylesheet" type="text/css" href="libs/css/style.css">
	<link rel="stylesheet" type="text/css" href="libs/css/personalizado.css">

	<!--
	<link rel="stylesheet" type="text/css" href="libs/DataTables/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="libs/DataTables/Buttons-1.6.1/css/buttons.dataTables.css">	
	-->
	<style type="text/css">
		.bg-ops-indigo{
			background-color: rgba(9,51,76);
		}
	</style>
</head>
<body>
	<div id="contenido">
		<div class="container container-fluid mt-5">
			
			<div class="row">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
					<form id='FormUsuario'>
						<div class="card">
							<div class="card-header bg-ops-indigo text-white text-center">
								<h1>Ingrese sus datos</h1>
							</div>
							<div class="card-body">
								<input type="hidden" name="request" value="AgregarCandidato" required="">
								<input type="text" id='Form_nombre' name="nombre" class="form-control" placeholder="Su Nombre" required="">
								<br>
								<input type="text" name="identidad" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" placeholder="Numero identidad">
							</div>
							<div class="card-footer text-white">
								<button class="btn btn-success btn-sm float-left" type="submit">Entrar</button>
								<button class="btn btn-info btn-sm float-right" onclick="AdminLogin()" type="button">Administrar</button>
							</div>
						</div>
					</form>			
				</div>
				<div class="col-lg-3"></div>
			</div>

			
		</div>
	</div>

	<!--<script type="text/javascript" src="libs/js/jquery-3.3.1-fixed.min.js"></script>
	<script type="text/javascript" src="libs/js/bootstrap-fixed.min.js"></script>-->
		
	<<script type="text/javascript" src="libs/DataTables/jQuery-3.3.1/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="libs/DataTables/Bootstrap-4-4.1.1/js/bootstrap.min.js"></script>

	<!---- Datatables -->
	<script type="text/javascript" src="libs/DataTables/datatables.js"></script>
	<script type="text/javascript" src="libs/js/addons/datatables.js"></script>
	<script type="text/javascript" src="libs/DataTables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="libs/DataTables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="libs/DataTables/jszip.min.js"></script>
	<script type="text/javascript" src="libs/DataTables/pdfmake.min.js"></script>
	<script type="text/javascript" src="libs/DataTables/vfs_fonts.js"></script>
	<script type="text/javascript" src="libs/DataTables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="libs/DataTables/Buttons-1.6.1/js/buttons.print.min.js"></script>
	<!---- Datatables -->	

	<script type="text/javascript" src="libs/js/mdb-fixed.min.js"></script>

	<script type="text/javascript">
		$("#FormUsuario").on('submit',function(e){
			e.preventDefault();
			$.ajax({
				url:'backend/Controller.php',
				method:'POST',
				data:$(this).serialize(),
				processData:false,
				success:function(response){
					if(response.request=='success'){
						//toastr.success("Bienvenido "+$("#Form_nombre").val());
						$("#contenido").load('views/PruebaOrtografia.php');
						
						toastr.options = {
							  "closeButton": false,
							  "debug": false,
							  "newestOnTop": true,
							  "progressBar": false,
							  "positionClass": "md-toast-top-full-width",
							  "preventDuplicates": false,
							  "onclick": null,
							  "showDuration": 300,
							  "hideDuration": 1000,
							  "timeOut": 5000,
							  "extendedTimeOut": 1000,
							  "showEasing": "swing",
							  "hideEasing": "linear",
							  "showMethod": "fadeIn",
							  "hideMethod": "fadeOut"
							}
						toastr["success"]("Bienvenido "+$("#Form_nombre").val())


					}else{
						console.log(response);
						//toastr.info(response.message);
						toastr.options = {
						  "closeButton": false,
						  "debug": false,
						  "newestOnTop": true,
						  "progressBar": false,
						  "positionClass": "md-toast-top-full-width",
						  "preventDuplicates": false,
						  "onclick": null,
						  "showDuration": 300,
						  "hideDuration": 1000,
						  "timeOut": 5000,
						  "extendedTimeOut": 1000,
						  "showEasing": "swing",
						  "hideEasing": "linear",
						  "showMethod": "fadeIn",
						  "hideMethod": "fadeOut"
						}

						toastr["info"](response.message)

					}
					
				},
				fail:function(error){
					toasr.warning('Error, No se puede ingresar');
					console.log(error);
				},
				statusCode:{
					500:function(ie){
						toastr.error('Error en la aplicacion');
						console.log(ie);
					}
				}
			})
		})
	</script>

	<script type="text/javascript">
		function AdminLogin(){
			$("#contenido").load('views/AdminLogin.php');
		}
	</script>
</body>
</html>