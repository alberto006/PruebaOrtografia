<?php
session_start();
if(isset($_SESSION['usuario'])){

	?>
	<div class="container container-fluid mt-4">
		<div class="card">
			<div class="card-header bg-ops-indigo text-center text-white">
				<h3>Desbloquear candidato</h3>
			</div>
			<div class="card-body">
				
				<div class="input-group mb-3">
				  <input type="text" class="form-control" id="id_candidato" placeholder="Identidad Candidato" aria-label="Identidad Candidato"
				    aria-describedby="button-addon2">
				  <div class="input-group-append">
				    <button class="btn btn-md btn-success m-0 px-3 py-2 z-depth-0 waves-effect" onclick="buscarCandidato($('#id_candidato').val())" type="button">
				    	Buscar
				   	</button>
				  </div>
				</div>


				<div id="divBusqueda"></div>

			</div>			
		</div>
	</div>


	<!-- Modal Editar Palaba -->
		<form id='FormDelete'>
			<div class="modal fade top text-center" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel"
			  aria-hidden="true">
			  <div class="modal-dialog modal-frame modal-top" role="document">
			    <div class="modal-content">
			      <div class="modal-header bg-danger text-center text-white">
			        <h5 class="modal-title " id="ModalDeleteLabel">Eliminar Registro</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      

			      	<input type="hidden" name="request" value="EliminarCandidato" required="">
			      	<input type="hidden" name="identidad" value="" required="" id="FormDelete_identidad">    	
			        
			        
			      
			      <div class="modal-footer">
			        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-success btn-sm">Eliminar</button>
			      </div>
			    </div>
			  </div>
			</div>
		</form>
		<!-- Modal Editar Palaba -->

	<script type="text/javascript">
		//-------------------------------------------------------------------------------------------------------------------------//
		function buscarCandidato(identidad){
			var request = "buscarCandidato";
			if(identidad === ""){
				toastr.warning('Ingrese una identidad valida');
			}else{
				$.post('backend/AdminController.php',{
					request:request,
					identidad:identidad
				}).done(function(response){

					if(response.request == 'success'){

						var data = response.data[2];

						content = '<table class="table table-sm table-bordered table-hover text-center" id="tb_candidato">'
									+'<thead class="bg-ops-indigo text-white">'
										+'<tr>'
											+'<th>Identidad</th>'
											+'<th>Nombre</th>'
											+'<th>Fecha Conexion</th>'
											+'<th>Desbloquear</th>'
										+'</tr>'
									+'</thead>'
									+'<tbody>';
						for(var i = 0;i<data.length;i++){
							content = content+'<tr>'
												+'<td>'+data[i].identidad+'</td>'
												+'<td>'+data[i].nombre+'</td>'
												+'<td>'+data[i].fecha+'</td>'
												+'<td><button class="btn btn-danger btn-sm" onclick="Desbloquear(\''+data[i].identidad+'\')">Desbloquear</button></td>'
											+'</tr>';
						}

						content = content+'</tbody><table>';

						$("#divBusqueda").html(content);


					}else{
						toastr.error(response.message);
						console.log(response);
					}

				}).fail(function(error){
					toastr.error("No se encontro informacion");
					console.log(error);
				})
			}
		}
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//
		function Desbloquear(identidad){
			$("#FormDelete_identidad").val(identidad);
			$("#ModalDelete").modal('show');

		}
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//

		$("#FormDelete").on('submit',function(e){
			e.preventDefault();
			$.ajax({
				url:'backend/AdminController.php',
				method:'POST',
				data:$(this).serialize(),
				processData:false,
				success:function(response){
										
					if(response.request == 'success'){
						toastr.success('Registro eliminado con exito!');
						$("#divBusqueda").empty();
						$("#ModalDelete").modal('hide');
						$(".modal-backdrop").remove();
					}else{
						toastr.error(response.message);
						console.log(response);
					}

				},
				fail:function(fail){
					toastr.error('Error en la aplicacion');
					console.log(fail);
				},
				statusCode:{
					500:function(ie){
						toastr.error('Error en la aplicacion');
						console.log(error);
					}
				}
			})
		})
		//-------------------------------------------------------------------------------------------------------------------------//
	</script>
	<?php

}else{
	header('location: ../index.php');
}	
?>