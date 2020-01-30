<?php
session_start();
if(isset($_SESSION)){
	session_destroy();
}
?>

<!DOCTYPE html>
<html>
<meta charset="utf8">
<head>
	<title>Prueba Ortografia</title>
	
	<link rel="stylesheet" type="text/css" href="libs/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="libs/css/mdb-fixed.min.css">
	<link rel="stylesheet" type="text/css" href="libs/css/all.css">
	<link rel="stylesheet" type="text/css" href="libs/css/style.css">

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
							<div class="card-header bg-success text-white text-center">
								<h2>Ingresar como administrador</h2>
							</div>
							<div class="card-body">
								<input type="hidden" name="request" value="AdminLogin" required="">
								<input type="text" id='Form_Usuario' name="usuario" class="form-control" placeholder="Usuario" required="">
								<br>
								<input type="password" name="clave"  class="form-control" placeholder="Clave de acceso">
							</div>
							<div class="card-footer text-white">
								<button class="btn btn-success btn-sm float-left" type="submit">Entrar</button>
								
							</div>
						</div>
					</form>			
				</div>
				<div class="col-lg-3"></div>
			</div>

			
		</div>
	</div>

	<script type="text/javascript" src="libs/js/jquery-3.3.1-fixed.min.js"></script>
	<script type="text/javascript" src="libs/js/bootstrap-fixed.min.js"></script>
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
						toastr.success("Bienvenido "+$("#Form_Usuario").val());
						$("#contenido").load('views/AdminPreguntas.php');
					}else{
						console.log(response);
						toastr.warning(response.message);
					}
					
				},
				fail:function(error){
					toastr.warning('Error, No se puede ingresar');
					console.log(error);
				},
				statusCode:{
					500:function(ie){
						toastr.warning('Error, No se puede ingresar');
						console.log(ie);
					}
				}
			})
		})
	</script>
</body>
</html>