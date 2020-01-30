<?php
/**
 * Autor: Alberto Soriano
 * Fecha: 25/01/2020
 * Clase controlador para interaccion entre cliente servidor de nivel administrativo
 */
include 'Model.php';
class Controller extends Model
{
	
	//----------------------------------------------------------------------------------------------//
	public function Palabra_Agregar($palabra,$significado,$nivel){	
		$Data = Model::agregarPalabra($palabra,$significado,$nivel);
		return $Data;
		
	}
	//----------------------------------------------------------------------------------------------//	

	//----------------------------------------------------------------------------------------------//	
	public function Palabra_updateArchivo($id,$archivo){
		$Data = Model::updateArchivo($id,$archivo);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//	

	//----------------------------------------------------------------------------------------------//	
	public function Palabras_GetLista($nivel){
		$Data = Model::getListaPalabras($nivel);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Palabras_editarPalabra($id_palabra){
		$Data = Model::getPalabraEdit($id_palabra);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Palabra_Editar($id_palabra,$palabra,$significado,$nivel){
		$Data = Model::updatePalabra($id_palabra,$palabra,$significado,$nivel);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Palabras_Eliminar($id_palabra){
		$Data = Model::deletePalabra($id_palabra);
		return $Data;	
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Reporte_Identidad($id_candidato){
		$Calificacion = Model::reporteIdentidadCalificacion($id_candidato);
		$Respuestas = Model::reporteIdentidadRespuestas($id_candidato);

		$Data = null;
		if($Calificacion[0] == TRUE && $Respuestas[0] == TRUE){
			$Data = [ $Calificacion[2],$Respuestas[2] ];

		}else{
			$Data = [$Calificacion,$Respuestas];
		}

		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Reporte_Fechas($FechaInicio,$FechaFin){
		$Data = Model::reporteFechas($FechaInicio,$FechaFin);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Candidato_Buscar($identidad){
		$Data = Model::buscarCandidato($identidad);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Candidato_Eliminar($identidad){
		$ort_candidatos = Model::deleteFromCandidatos($identidad);
		$ort_candidatos_calificaciones = Model::deleteFromCandidatosCalificaciones($identidad);
		$ort_candidatos_resultados = Model::deleteFromCandidatosResultados($identidad);
		$ort_respuestas = Model::deleteFromRespuestas($identidad);

		$Data = array(
			"result"=>TRUE,
			"candidatos" => $ort_candidatos,
			"calificaciones" => $ort_candidatos_calificaciones,
			"resultados" => $ort_candidatos_resultados,
			"respuestas" => $ort_respuestas
		);

		if($ort_candidatos[0] == TRUE && $ort_candidatos_resultados[0] == TRUE && $ort_candidatos_calificaciones[0] == TRUE && $ort_respuestas[0] == TRUE){
			$Data['result'] == TRUE;
			return $Data;
		}else{
			return $Data;
		}
	}
	//----------------------------------------------------------------------------------------------//

}

//----------------------------------------------------------------------------------------------//
//Manejo de peticiones POST

$response = array(
	"request"=>null,
	"message"=>null,
	"data"=>null
);


if(isset($_POST)){
	header('Content-Type: application/json');
	$Controller = new Controller;
	extract($_POST);

	switch ($request) {
		//----------------------------------------------------------------------------------------------//
		case 'AgregarPalabra':

			$extension = explode('.', $_FILES['archivo']['name']);
			$extensiones_permitidas = [
				'mp3',
				'mp4',
				'wav',
				'wma',
				'm4a'
			];

			if(in_array(strtolower(end($extension)), $extensiones_permitidas)){

				//Agregar registro de la palaba sin la ruta del archivo | debe retornar el ID
				$AgregarPalabra = $Controller->Palabra_Agregar($palabra,$significado,$nivel);

				if($AgregarPalabra[0] == TRUE && sizeof($AgregarPalabra[2]) == 1){
					//Se agrego correctamente el registro
					
					/*Crear directorio en caso de que no esita*/
					$dir_audios = '/var/www/html/PruebaOrtografia/views/audios/';
					if(!file_exists($dir_audios)){
						mkdir($dir_audios,0777);
					}

					$nombre_audio = $AgregarPalabra[2][0]['id'].".".strtolower(end($extension));
					//Carga de archivo de audio
					if(move_uploaded_file($_FILES['archivo']['tmp_name'],$dir_audios.$nombre_audio)){
						

						$updateArchivo = $Controller->Palabra_updateArchivo($AgregarPalabra[2][0]['id'],$nombre_audio);

						if($updateArchivo[0] == TRUE){
							$response['request']='success';
							$response['message']='Se agrego correctamente la palabra';
							$response['data']=$updateArchivo;		
						}else{

							$response['request']='error';
							$response['message']='No se pudo actualizar la ruta del archivo';
							$response['data']=$updateArchivo;			

						}

					}else{
						$response['request']='error';
						$response['message']='No se pudo cargar el archivo de audio';
						$response['data']=$dir_audios.$nombre_audio;		
					}				
					

				}else{
					$response['request']='error';
					$response['message']='No se pudo agregar el registro';
					$response['data']=$AgregarPalabra;	
				}

				


			}else{
				$response['request']='error';
				$response['message']='Tipo de archivo no valido';
				$response['data']=strtolower(end($extension));
			}

			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'EditarPalabra':
			$Data = $Controller->Palabra_Editar($id_palabra,$palabra,$significado,$nivel);
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Registro actualizado';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede actualizar el registro';
				$response['data']=$Data[2];	
			}	
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//

		case 'getPalabraEdit':
			$Data = $Controller->Palabras_editarPalabra($id_palabra);
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Datos obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se pueden obtener los datos';
				$response['data']=$Data;	
			}	

			echo json_encode($response);
		break;

		//----------------------------------------------------------------------------------------------//
		case 'getPalabras':			
			$Data = $Controller->Palabras_GetLista($nivel);
			if($Data[0]==TRUE){
				$response['request']='success';
				$response['message']='Datos obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se pueden obtener los datos';
				$response['data']=$Data;					
			}
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'EliminarPalabra':			
			$Data = $Controller->Palabras_Eliminar($id_palabra);
			if($Data[0]==TRUE){
				$response['request']='success';
				$response['message']='Registro Eliminado';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede eliminar el registro';
				$response['data']=$Data;					
			}
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'reporteIdentidad':
			$Data = $Controller->Reporte_Identidad($id_candidato);
			if($Data[0]==TRUE){
				$response['request']='success';
				$response['message']='Datos Obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede obtener los datos';
				$response['data']=$Data;					
			}
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'reporteFechas':
			$Data = $Controller->Reporte_Fechas($FechaInicio,$FechaFin);
			if($Data[0]==TRUE){
				$response['request']='success';
				$response['message']='Datos Obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede obtener los datos';
				$response['data']=$Data;					
			}
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'EliminarCandidato':
			$Data = $Controller->Candidato_Eliminar($identidad);
			if($Data['result']==TRUE){
				$response['request']='success';
				$response['message']='Datos Obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede obtener los datos';
				$response['data']=$Data;					
			}
			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'buscarCandidato':
			$Data = $Controller->Candidato_Buscar($identidad);
			if($Data[0]==TRUE){
				$response['request']='success';
				$response['message']='Datos Obtenidos con exito';
				$response['data']=$Data;	
			}else{
				$response['request']='error';
				$response['message']='No se puede obtener los datos';
				$response['data']=$Data;					
			}
			echo json_encode($response);
		break;
		//----------------------------------------------------------------------------------------------//		
		
		default:
			$response['request']='error';
			$response['message']="No hay una peticion valida";
			$response['data']=$_POST;
		break;
	}
}
//----------------------------------------------------------------------------------------------//

?>