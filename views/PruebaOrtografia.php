<?php
session_start();

if(isset($_SESSION['usuario']) && $_SESSION['usuario']!='' && isset($_SESSION['identidad']) && $_SESSION['identidad'] != ''){

	?>
	<!-- Purple Header -->
	<div style="background-color: rgba(210,214,00,0.6)">
		<div class="edge-header bg-ops-indigo"></div>

		<!-- Main Container -->
		<div class="container free-bird" >
		  <div class="row" >
		    <div class="col-md-12 col-lg-12 mx-auto float-none white z-depth-1 py-1 px-1" style="background-color: rgba(102,102,102,1);">


			    <div class="card-header bg-white">
			        <h2 class="h2-responsive"><strong>Prueba de Ortografía</strong></h2>
			        <p class="pb-4"><b class="text-success">Bienvenido</b> <?= $_SESSION['usuario'] ?></p>
			    </div>
		      <!--Naked Form-->
		      <div class="card-body">

		        <div id='contenido-prueba'>
			        <div id="contenido-instrucciones">
				        
				        <div class="row">
				        	<div class="col-lg-12 text-center ">
				        		<h3 class="pb-4 text-primary" style="font-size: 2.5rem;">Instrucciones</h3>
				        		<div class="text-left container">
					        		<p class="pb-4" style="font-size: 1.5rem">
					        			En esta prueba debe de escuchar los audios que aparecerán en cada pregunta y escribir la palabra que escuche, según considere que su escritura, al terminar con cada palabra debe seleccionar el botón de "continuar" para pasar a la siguiente hasta finalizar la prueba.

					        			
					        		</p>

					        		<button class="btn btn-success float-center" onclick="comenzarPrueba()">Comenzar</button>
					        	</div>
				        	</div>
				        </div>
				    </div>
			        <!--Body-->
				</div>

				<div id="divForm" style="display: none">
					<form id="FormPrueba">
						<h2 class="text-primary">Palabra #<span id="palabra_actual"></span></h2>
						<hr>

						<div class="row">
							<div class="col-lg-4">
								<h4>Audio</h4>
								<audio controls id="audio_p"><source  src=""></audio>		
							</div>
							<div class="col-lg-8">
								<h4>Significado:</h4>
								<textarea style="background-color:white;font-size: 1.1em;border:0px;" id="Form_significado" readonly class="form-control text-success"></textarea>		
							</div>
						</div>
						
						<hr>
						<label><b>Respueta:</b></label>
						<input type="hidden" name="request" value="agregarRespuesta">
						<input type="hidden" name="id_palabra" id="Form_idPalabra" value="">
						<input type="hidden" name="identidad" id="Form_identidad" value="<?= $_SESSION['identidad'] ?>"> 
						<input type="hidden" name="id_candidato" id="Form_idCandidato" value="<?= $_SESSION['id_candidato'] ?>"> 

						<input type="text" spellcheck='false' name="respuesta" id="Form_respuesta" class="form-control" required="">
						<br>
						<button class="btn btn-success" type="submit">siguiente</button>

					</form>
				</div>


				<div id="divResultados" style="display: none">
					<h3 class="text-primary">Calificacion:</h3>
					<table class="table table-sm table-condensed table-bordered text-center" id="tb_calificacion">
						<thead class="bg-ops-indigo text-white">
							<tr>
								<th>Nombre</th>
								<th>Palabras Contestadas</th>
								<th>Correctas</th>
								<th>Incorrectas</th>
								<th>Calificacion</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>

					<h3 class="text-primary">Detalle de respuestas:</h3>
					<table class="table table-sm table-condensed table-bordered table-striped text-center" id="tb_detalle">
						<thead class="bg-ops-indigo text-white">
							<tr>
								<th>Palabra</th>
								<th>Respuesta</th>
								<th>Resultado</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>

				</div>
		      </div>
		      <!--Naked Form-->

		    </div>
		  </div>
		</div>
		<!-- /.Main Container -->
		
</div>


	<script type="text/javascript">
		
		var palabra_actual = 1;


		//-------------------------------------------------------------------------------------------------------------------------//
		function comenzarPrueba(){
			$.post('backend/Controller.php',{
				request:'getFirstPalabra'

			}).done(function(response){

				if(response.request == 'success'){
					
					$("#palabra_actual").html(palabra_actual);
					$("#Form_significado").val(response.data[2][0].significado);
					$("#Form_idPalabra").val(response.data[2][0].id);
					
					var file = "views/audios/"+response.data[2][0].archivo; 
					$("#audio_p").attr('src',file );

					$("#divForm").toggle();
					$("#contenido-instrucciones").css('display','none')


					palabra_actual = palabra_actual+1;

					document.getElementById('audio_p').play();

				}else{
					toastr.error('No se puede obtener los datos');
				}

			}).fail(function(error){

			});
		}
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//
		function generarResultados(){
			var id_candidato = "<?= $_SESSION['id_candidato'] ?>";
			var request = "generarResultados";
			$.post('backend/Controller.php',{
				request:request,
				id_candidato:id_candidato
			}).done(function(response){

				if(response.request == 'success'){
					toastr.success('Calificaciones listas');
					console.log(response)

					data = response.data[2];

					var row = '<tr>'
								+'<td>'+data.resumen[0].nombre+'</td>'
								+'<td>'+data.resumen[0].palabras+'</td>'
								+'<td>'+data.resumen[0].correctas+'</td>'
								+'<td>'+data.resumen[0].incorrectas+'</td>'
								+'<td>'+data.resumen[0].porcentaje+'%</td>'
								+'</tr>';
					$("#tb_calificacion>tbody").append(row);

					var rowsDetalle = "";
					var color;
					for(var i = 0;i<data.detalle.length;i++){
						
						color = ( (data.detalle[i].resultado === "Correcto") ? "success" : "danger");

						rowsDetalle = rowsDetalle+
									'<tr>'
										+'<td>'+data.detalle[i].palabra+'</td>'
										+'<td>'+data.detalle[i].respuesta+'</td>'
										+'<td class="bg-"'+(color)+'">'+data.detalle[i].resultado+'</td>'
									+'</tr>';
					}

					$("#tb_detalle>tbody").append(rowsDetalle);

				}else{
					toastr.error('No se puede obtener los resultados')
					console.log(response);
				}

			}).fail(function(error){
				console.log(error);
				toastr.error('Error en la aplicacion');
			})
		}
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//
		function showResultados(){
			generarResultados();
			$("#divForm").css('display','none');
			$("#divResultados").css('display','block');
		}
		
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//
		function siguientePalabra(id_actual){
			$.post('backend/Controller.php',{
				request:'getNextPalabra',
				id_actual:id_actual
			}).done(function(response){

				if(response.request == 'success'){

					if(response.data[2].length == 0){
						

						$("#divForm").empty();
						$("#divForm").html('<div class="alert alert-success text-center"><h2>Prueba finalizada</h2></div><br><button onclick="showResultados()" class="btn btn-info">Ver Resultados</button>');
					}else{
						$("#audio_p").attr('src','views/audios/'+response.data[2][0].archivo);
						$("#Form_idPalabra").val(response.data[2][0].id);
						$("#Form_significado").val(response.data[2][0].significado);
						$("#palabra_actual").html(palabra_actual);
						$("#Form_respuesta").val('');
						palabra_actual = palabra_actual+1;

						document.getElementById('audio_p').play();
					}

					

				}else{
					toastr.error('No se puede obtener los datos');
					console.log(response);
				}

			}).fail(function(error){
				toastr.error('No se puede obtener los datos');
				console.log(error);
			});
		}
		//-------------------------------------------------------------------------------------------------------------------------//

		//-------------------------------------------------------------------------------------------------------------------------//
		$(document).ready(function(){
			$("#FormPrueba").on('submit',function(e){
				e.preventDefault();
				$.ajax({
					url:'backend/Controller.php',
					method:'POST',
					data:$(this).serialize(),
					processData:false,
					success:function(response){
						toastr.info(response.message);
						siguientePalabra(response.data[2][0].id_palabra);
					},
					fail:function(error){
						toastr.error('Error al enviar los datos');
						console.log(error);
					},
					statusCode:{
						500:function(ie){
							toastr.error('Error en la aplicacion');
							console.log(ie);
						}
					}
				});
			});
		});		
		//-------------------------------------------------------------------------------------------------------------------------//
	</script>
	
	<?php

}else{
	?>
	<div class="container container-fluid mt-4">
		<div class="alert alert-danger">
			<h4>Ocurrio un error y no se ha podido iniciar la prueba de ortografia</h4>
		</div>
		<hr>
		<a href="./" class="btn btn-info">Volver</a>
	</div>
	<?php
}

?>