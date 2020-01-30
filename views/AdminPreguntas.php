<?php
session_start();
if(isset($_SESSION['usuario'])){
	?>
	<!--Navbar -->
	<nav class="mb-1 navbar navbar-expand-lg navbar-dark bg-ops-indigo lighten-1">
	  <a class="navbar-brand" style="text-decoration: none; color:white"  onclick='$("#contenido").load("views/AdminPreguntas.php")'><b>A</b>dmin</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
	    aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarSupportedContent-555">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item">
	        <a class="nav-link" href="#" onclick='$("#contenido").load("views/AdminPreguntas.php")'>Palabras
	          <span class="sr-only"></span>
	        </a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="#" onclick='$("#AdminContenido").load("views/AdminResultados.php")'>Resultados</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="#" onclick='$("#AdminContenido").load("views/AdminDesbloquear.php")'>Desbloquear</a>
	      </li>
	      
	      
	    </ul>
	    <ul class="navbar-nav ml-auto nav-flex-icons">
	      
	      <li class="nav-item avatar dropdown">
	        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
	          aria-haspopup="true" aria-expanded="false">
	          <img src="libs/img/logo_ng.png" class=" z-depth-0"
	            alt="avatar image">
	        </a>
	        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"
	          aria-labelledby="navbarDropdownMenuLink-55">
	          <a class="dropdown-item" href="#">Action</a>
	          <a class="dropdown-item" href="#">Another action</a>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	    </ul>
	  </div>
	</nav>
	<!--/.Navbar -->

	<div id='AdminContenido'>
		
		<div class="container container-fluid mt-4">
			<div class="card card-primary">
				<div class="card-header bg-ops-indigo text-white text-center">
					<h3>Lista de palabras</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<div class="card border-primary mb-3" style="max-width: 20rem;">
							  <div class="card-header bg-ops-indigo text-white text-center" >Niveles de Dificulad</div>
							  <div class="card-body text-primary text-center">
							  	<div class="btn-group-vertical text-center" role="group" style="width: 100%;" aria-label="Vertical button group">
								    <button class="btn btn-success btn-sm" onclick="createTable('Basico')">Basico</button>
								    <button class="btn btn-primary btn-sm" onclick="createTable('Medio')">Medio</button>
								    <button class="btn btn-danger btn-sm" onclick="createTable('Alto')">Alto</button>
							   	</div>
							  </div>
							</div>
						</div>
						<div class="col-lg-9" id='printTables'>
							
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#ModalAgregarPalabra">Agregar</button>
				</div>
			</div>			
		</div>
		

		<!-- Modal Agregar Palaba -->
		<form id='FormAgregarPalabra'>
			<div class="modal fade" id="ModalAgregarPalabra" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarPalabraLabel"
			  aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header bg-ops-indigo text-center text-white">
			        <h5 class="modal-title " id="ModalAgregarPalabraLabel">Agregar Palabra</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">

			      	<input type="hidden" name="request" value="AgregarPalabra" required="">

			      	<label>Nivel</label>
			      	<select name="nivel" class="browser-default custom-select" id="Form_nivel" required="">
			      		<option></option>
			      		<option value="Basico">Basico</option>
			      		<option value="Medio">Medio</option>
			      		<option value="Alto">Alto</option>
			      	</select>
			      	<br>
			        <label>Palabra</label>
			        <input type="text" name="palabra" class="form-control form-control-sm" id='Form_Palabra' required="">
			        <br>
			        <label>Significado</label>
			        <textarea class="form-control form-control-sm" name="significado" id="Form_significado" required=""></textarea>
			        <br>
			        <label>Archivo</label>
			        <input type="file" name="archivo" id="Form_archivo" class="form-control" required="">
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
			      </div>
			    </div>
			  </div>
			</div>
		</form>
		<!-- Modal Agregar Palaba -->

		<!-- Modal Editar Palaba -->
		<form id='FormEditarPalabra'>
			<div class="modal fade" id="ModalEditarPalabra" tabindex="-1" role="dialog" aria-labelledby="ModalEditarPalabraLabel"
			  aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header bg-ops-indigo text-center text-white">
			        <h5 class="modal-title " id="ModalEditarPalabraLabel">Editar Palabra</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">

			      	<input type="hidden" name="request" value="EditarPalabra" required="">
			      	<input type="hidden" name="id_palabra" value="" required="" id="FormEdit_id">

			      	<label>Nivel</label>
			      	<select name="nivel" class="browser-default custom-select" id="FormEdit_nivel" required="">
			      		<option></option>
			      		<option value="Basico">Basico</option>
			      		<option value="Medio">Medio</option>
			      		<option value="Alto">Alto</option>
			      	</select>
			      	<br>
			        <label>Palabra</label>
			        <input type="text" name="palabra" class="form-control form-control-sm" id='FormEdit_Palabra' required="">
			        <br>
			        <label>Significado</label>
			        <textarea class="form-control form-control-sm" name="significado" id="FormEdit_significado" required=""></textarea>
			        
			        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
			      </div>
			    </div>
			  </div>
			</div>
		</form>
		<!-- Modal Editar Palaba -->

		<!-- Modal Eiminar Palaba -->
		<form id='FormEliminarPalabra'>
			<div class="modal fade top text-center" id="ModalEliminarPalabra" tabindex="-1" role="dialog" aria-labelledby="ModalEliminarPalabraLabel"
			  aria-hidden="true">
			  <div class="modal-dialog modal-frame modal-top" role="document">
			    <div class="modal-content">
			      <div class="modal-header bg-danger text-center text-white">
			        <h3 class="modal-title text-center" id="ModalEliminarPalabraLabel">Eliminar Palabra</h3>
			        <button type="button" class="close" data-dismiss="modal" aria-label="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body" style="display: none">

			      	<input type="hidden" name="request" value="EliminarPalabra" required="">
			      	<input type="hidden" name="id_palabra" value="" required="" id="FormDelete_id">
			        
			      </div>
			      <div class="modal-footer text-center">
			        <button type="button" class="btn btn-info " data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-danger">Eliminar</button>
			      </div>
			    </div>
			  </div>
			</div>
		</form>
		<!-- Modal Eliminar Palaba -->

	</div>

	<script type="text/javascript">
		//-----------------------------------------------------------------------------------------//
		function createTable(nivel){
			$.post('backend/AdminController.php',{
				request:"getPalabras",
				nivel:nivel
			}).done(function(response){

				var table = '<table class="table table-condensed table-bordered table-sm text-center" >'
							+'<thead class="bg-ops-indigo text-white text-center">'
								+'<tr>'
									+'<th>Palabra</th>'
									+'<th>Significado</th>'
									+'<th>Archivo</th>'
									+'<th>Opciones</th>'
								+'</tr>'
							+'</thead>'
							+'<tbody>';

							var row = "";
							for(var i = 0; i<response.data[2].length; i++){
								row = 	'<tr>'
											+'<td>'+response.data[2][i].palabra+'</td>'
											+'<td class="text-left">'+response.data[2][i].significado+'</td>'
											+'<td>'
												+'<audio controls> <source src="views/audios/'+response.data[2][i].archivo+'"> </audio>'
											+'</td>'
											+'<td>'
												+'<div class="btn-group text-center">'
													+'<button class="btn btn-sm btn-info"'
													+'onclick="editarPalabra('+response.data[2][i].id+')">'
														+'Editar'
													+'</button>'
													+'<button class="btn btn-sm btn-danger" onclick="deletePalabra('+response.data[2][i].id+')">Eliminar</button>'
												+'</div>'
											+'</td>'
										+'</tr>';
								table = table+row;	
							}

							table = table+'</tbody>'
							+'</table>';

							$("#printTables").html(table);

			}).fail(function(error){
				toastr.error('Error al obtener los datos');
				console.lgo(error);
			})
		}
		//-----------------------------------------------------------------------------------------//
	</script>

	<script type="text/javascript">
		//-----------------------------------------------------------------------------------------//
		function editarPalabra(id_palabra){
			$.post('backend/AdminController.php',{
				request:'getPalabraEdit',
				id_palabra:id_palabra
			}).done(function(response){
				console.log(response);
				if(response.request == 'success'){

					$("#FormEdit_Palabra").val(response.data[2][0].palabra);
					$("#FormEdit_significado").val(response.data[2][0].significado);
					$("#FormEdit_nivel").val(response.data[2][0].nivel);
					$("#FormEdit_id").val(response.data[2][0].id);

					$("#ModalEditarPalabra").modal('show');

				}else{
					toastr.error('No se puede obtener la informacion');
					console.log(error);
				}
			}).fail(function(error){
				toastr.error('No se puede obtener la informacion');
				console.log(error);
			});
		}
		//-----------------------------------------------------------------------------------------//
	</script>

	<script type="text/javascript">
		//-----------------------------------------------------------------------------------------//
		$("#FormAgregarPalabra").on('submit',function(e){
			e.preventDefault();
			$.ajax({
				url:'backend/AdminController.php',
				method:'POST',
				data:new FormData(this),
				contentType:false,
				cache:false,    		
				processData:false,
				success:function(response){
					console.log(response);
					if(response.request == 'success'){

						toastr.success(response.message);
						var nivelSelected = $("#Form_nivel").val();
						$("#Form_Palabra").val('');
						$("#Form_significado").val('');
						$("#Form_nivel option:selected").attr('selected',false);
						$("#Form_archivo").val(null);
						$("#ModalAgregarPalabra").modal('hide');
						$(".modal-backdrop").remove();

						createTable(nivelSelected);


					}else{
						toastr.error(response.message);
					}
					
				},
				fail:function(fail){
					console.log(fail);
					toastr.error('Error al guardar los datos');
				},
				statusCode:{
					500:function(ie){
						toastr.error('Error de la aplicacion');
					}
				}

			})
		})
		//-----------------------------------------------------------------------------------------//

		//-----------------------------------------------------------------------------------------//
		$("#FormEditarPalabra").on('submit',function(e){
			e.preventDefault();
			$.ajax({
				url:'backend/AdminController.php',
				method:'POST',
				data:new FormData(this),
				contentType:false,
				cache:false,    		
				processData:false,
				success:function(response){
					console.log(response);
					if(response.request == 'success'){

						toastr.success(response.message);					
						
						$("#FormEdit_significado").val(response.data[2][0].significado);
						$("#FormEdit_Palabra").val(response.data[2][0].palabra);
						$("#FormEdit_nivel").val(response.data[2][0].nivel);

						createTable(response.data[2][0].nivel);


					}else{
						toastr.error(response.message);
					}
					
				},
				fail:function(fail){
					console.log(fail);
					toastr.error('Error al guardar los datos');
				},
				statusCode:{
					500:function(ie){
						toastr.error('Error de la aplicacion');
					}
				}

			})
		})
		//-----------------------------------------------------------------------------------------//
	</script>

	<script type="text/javascript">
		//-----------------------------------------------------------------------------------------//
		function deletePalabra(id_palabra){
			$("#FormDelete_id").val(id_palabra);
			$("#ModalEliminarPalabra").modal('show');
		}
		//-----------------------------------------------------------------------------------------//

		//-----------------------------------------------------------------------------------------//
		$("#FormEliminarPalabra").on('submit',function(e){
			e.preventDefault();
			$.ajax({
				url:'backend/AdminController.php',
				method:'POST',
				data:$(this).serialize(),
				processData:false,
				success:function(response){
					if(response.request == 'success'){
						toastr.success(response.message);
						console.log(response);

						$("#ModalEliminarPalabra").modal('hide');
						$(".modal-backdrop").remove();
						$("#printTables").empty();

					}else{
						toastr.error(response.message);
						console.log(response);
					}
				},
				fail:function(error){
					console.log(error);
					toastr.error('Error al eliminar el registro');
				},
				statusCode:{
					500:function(ie){
						toastr.error('Error en la aplicacion');
						console.log(ie);
					}
				}
			});
		});
		//-----------------------------------------------------------------------------------------//
	</script>
	
	<?php
}else{
	header('location: ../index.php');
}
?>

