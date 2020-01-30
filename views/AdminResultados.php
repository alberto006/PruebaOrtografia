<?php
session_start();
if(isset($_SESSION['usuario'])){

	?>
	<div class="container container-fluid mt-4">
		<div class="card">
			<div class="card-header bg-ops-indigo text-center text-white">
				<h3>Reporte de resultados</h3>
			</div>
			<div class="card-body">
				
				<!--- Division para mostrar opciones de reporte --->
				<div id="Opciones">

					<div class="row">
						<div class="col-md-12">
								
							<h4>Buscar por:</h4>

							<div class="row">
								
									<div class="form-check form-check-inline">
									  <input type="radio" onclick="showForm(1)" class="form-check-input" id="searchId" name="searchRadio">
									  <label class="form-check-label" for="searchId">Identidad Candidato</label>
									</div>			
								
								
									<div class="form-check form-check-inline">
									  <input type="radio" onclick="showForm(2)" class="form-check-input" id="searchFechas" name="searchRadio">
									  <label class="form-check-label" for="searchFechas">Rango de fechas</label>
									</div>			
								
							</div>
						</div>
					</div>
					
					<hr>

					<div class="row" id="FormIdentidad" style="display: none;">
						<div class="col-md-4">

							<div class="input-group mb-3">
							  <input type="text" class="form-control" id="id_candidato" placeholder="Identidad Candidato" aria-label="Identidad Candidato"
							    aria-describedby="button-addon2">
							  <div class="input-group-append">
							    <button class="btn btn-md btn-success m-0 px-3 py-2 z-depth-0 waves-effect" onclick="reporteIdentidad($('#id_candidato').val())" type="button">Buscar</button>
							  </div>
							</div>
									
						</div>
					</div>

					<div class="row" id="FormFechas" style="display: none;">
						<div class="col-md-12">

							<div class="row">
								<div class="col-md-5">
									<input type="date" name="FechaInicio" class="form-control" id="FechaInicio">
								</div>

								<div class="col-md-5">
									<input type="date" name="FechaFin" class="form-control" id="FechaFin">
								</div>

								<div class="col-md-2 text-center">
									<button class="btn btn-success btn-sm" onclick="reporteFechas()">Generar</button>
								</div>
							</div>
									
						</div>
					</div>

					


				</div>
				<!--- Division para mostrar opciones de reporte --->

				<!--- Division para mostrar info de reporte --->
				<div id="divReporte">
					
				</div>
				<!--- Division para mostrar info de reporte --->

			</div>
			<div class="card-footer">
				
			</div>
		</div>
	</div>



	<script type="text/javascript">
		//-------------------------------------------------------------------------------------------------//
		function showForm(opcion){
			if(opcion === 1){
				$("#FormIdentidad").css('display','block');
				$("#FormFechas").css('display','none');
			}else if(opcion === 2){
				$("#FormIdentidad").css('display','none');
				$("#FormFechas").css('display','block');
			}
		}
		//-------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------//
		function reporteIdentidad(id_candidato){
			
			var request = "reporteIdentidad";
			if(id_candidato === ""){
				toastr.warning('Por favor ingrese una identidad');
			}else{
				$.post('backend/AdminController.php',{
					request:request,
					id_candidato:id_candidato
				}).done(function(response){
					if(response.request == 'success' && response.data[0].length ===1){
						//Variables con datos
						var calificacion = response.data[0];
						var respuestas = response.data[1];

						//Impresion de calificacion
						var content = '<h3 class="text-primary">Calificacion</h3><table class="table table-sm table-bordered table-hover" id="tb_calificacion">'
										+'<thead class="bg-ops-indigo text-white">'
											+'<tr>'
												+'<th>Identidad</th>'
												+'<th>Nombre</th>'
												+'<th>Fecha</th>'
												+'<th>Palabras Contestadas</th>'
												+'<th>Correctas</th>'
												+'<th>Incorrectas</th>'
												+'<th>Porcentaje</th>'
											+'</tr>'	
										+'</thead>'
										+'<tbody>';

						for(var i = 0;i<calificacion.length;i++){
							content = content+'<tr>'
											+'<td>'+calificacion[i].identidad+'</td>'
											+'<td>'+calificacion[i].nombre+'</td>'
											+'<td>'+calificacion[i].fecha+'</td>'
											+'<td>'+calificacion[i].palabras+'</td>'
											+'<td>'+calificacion[i].correctas+'</td>'
											+'<td>'+calificacion[i].incorrectas+'</td>'
											+'<td>'+calificacion[i].porcentaje+'%</td>'
										+'</tr>';
										
						}
						
						content=content+'</tbody></table>';
						
						//Impresion de detalle


						content = content+'<div class="mt-3"><h3 class="text-primary">Detalle respuestas</h3>'
												+'<table class="table table-sm table-bordered table-hover text-center" id="tb_resultados">'
													+'<thead class="bg-ops-indigo text-white">'
														+'<tr>'
															+'<th>Nivel</th>'
															+'<th>Palabra</th>'
															+'<th>Respuesta</th>'
															+'<th>Resultado</th>'															
														+'</tr>'
													+'</thead>'
													+'<tbody>';

						for(var j=0;j<respuestas.length;j++){
							content = content+'<tr>'
												+'<td>'+respuestas[j].nivel+'</td>'
												+'<td>'+respuestas[j].palabra+'</td>'
												+'<td>'+respuestas[j].respuesta+'</td>'
												+'<td>'+respuestas[j].resultado+'</td>'
											+'</tr>'
						}

						content = content+'</tbody></table></div>';
											

						console.log();
						$("#divReporte").html(content);

						
						


					}else{
						toastr.warning('<h6><i>Error:</i> no se encontro el registro<h6>');
						console.log(response);
					}
				}).fail(function(error){
					toastr.error("Error en la aplicacion");
					console.log(error);
				})
			}

		}
		//-------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------//
		function reporteFechas(){
			var FechaInicio = $("#FechaInicio").val();
			var FechaFin = $("#FechaFin").val();
			var request = "reporteFechas";

			if( (FechaInicio === "") || (FechaFin === "")){
				toastr.warning('Por favor seleccione fechas validas');
			}else{
				
				$.post('backend/AdminController.php',{
					request:request,
					FechaInicio:FechaInicio,
					FechaFin:FechaFin
				}).done(function(response){
					if(response.request == 'success' && response.data[2].length>=1){
						//Variables con datos

						var datos = response.data[2];
						var content = '<h3 class="text-primary">Resultados de candidatos</h3>'
										+'<table class="table table-sm table-bordered table-hover text-center" id="tb_resultados">'
											+'<thead class="bg-ops-indigo text-white">'
												+'<th>Identidad</th>'
												+'<th>Nombre</th>'
												+'<th>Fecha</th>'
												+'<th>Palabras</th>'
												+'<th>Correctas</th>'
												+'<th>Incorrectas</th>'
												+'<th>Porcentaje</th>'
												+'<th>Opciones</th>'
											+'<thead>'
											+'<tbody>';
								for(var i = 0;i<datos.length;i++){
									content = content+'<tr>'
														+'<td>'+datos[i].identidad+'</td>'
														+'<td>'+datos[i].nombre+'</td>'
														+'<td>'+datos[i].fecha+'</td>'
														+'<td>'+datos[i].palabras+'</td>'
														+'<td>'+datos[i].correctas+'</td>'
														+'<td>'+datos[i].incorrectas+'</td>'
														+'<td>'+datos[i].porcentaje+'%</td>'
														+'<td>'
															+'<button class="btn btn-outline-primary btn-sm btn-rounded waves-effect"'
																+' onclick="reporteIdentidad(\''+datos[i].identidad+'\')">'
																+'Reporte'
															+'</button>'
														+'</td>'
														
													+'</tr>'
								}

								content = content+'</tbody></table>';

								$("#divReporte").html(content);
									
					}else{
						toastr.info('No se encontro informacion');
						console.log(response);
					}
								
				}).fail(function(error){
					toastr.erro("Error en la aplicacion");
					console.log(error);
				})
			}

		}
		//-------------------------------------------------------------------------------------------------//
	</script>
	
	<?php



}else{
	header('location: ../index.php');
}
?>