<?php
/**
 * Autor: Alberto Soriano
 * Fecha: 25/01/2020
 * Clase controlador para interaccion entre cliente servidor
 */
include 'Model.php';
class Controller extends Model
{
	
	//----------------------------------------------------------------------------------------------//
	public function Candidato_insert($nombre,$identidad){	
		$Data = Model::insertCandidato($nombre,$identidad);
		return $Data;
		
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Admin_Login($usuario,$clave){
		$Data = Model::validateUsuario($usuario,$clave);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Prueba_getFirstPalabra(){
		$Data = Model::firstPalabra();
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Prueba_getNextPalabra($id_actual){
		$Data = Model::nextPalabra($id_actual);
		return $Data;
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Prueba_agregarRespuesta($id_actual,$identidad,$respuesta,$id_candidato){
		$Data = Model::insertRespuesta($id_actual,$identidad,$respuesta,$id_candidato);
		return $Data;	
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	public function Prueba_CalcularResultados($id_candidato){
		$Data = Model::calcularResultados($id_candidato);
		return $Data;
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
		case 'AgregarCandidato':
			$AgregarCandidato = $Controller->Candidato_insert($nombre,$identidad);
			if($AgregarCandidato[0] == TRUE){
				$response['request']='success';
				$response['message']=$AgregarCandidato[1];
				$response['data']=$AgregarCandidato;
			}else{
				$response['request']='error';
				$response['message']=$AgregarCandidato[1];
				$response['data']=$AgregarCandidato[2];
			}
			session_start();
			$_SESSION['usuario'] = $nombre;
			$_SESSION['identidad'] =$identidad;
			$_SESSION['id_candidato'] = $AgregarCandidato[2][0]['id'];
			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'AdminLogin':
			$Request = $Controller->Admin_Login($usuario,$clave);
			if($Request[0] == TRUE){
				$response['request']='success';
				$response['message']='Consulta correcta';
				$response['data']=$Request;
				session_start();
				$_SESSION['usuario'] = $usuario;
				
			}else{
				$response['request']='error';
				$response['message']=$Request[1];
				$response['data']=$Request;
			}
			
			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'getFirstPalabra':
			$Data = $Controller->Prueba_getFirstPalabra();
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Consulta correcta';
				$response['data']=$Data;			
				
			}else{
				$response['request']='error';
				$response['message']=$Data[1];
				$response['data']=$Data;
			}
			
			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'getNextPalabra':
			$Data = $Controller->Prueba_getNextPalabra($id_actual);
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Consulta correcta';
				$response['data']=$Data;			
				
			}else{
				$response['request']='error';
				$response['message']=$Data[1];
				$response['data']=$Data;
			}
			
			echo json_encode($response);

		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'agregarRespuesta':
			$Data = $Controller->Prueba_agregarRespuesta($id_palabra,$identidad,$respuesta,$id_candidato);
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Respuesta Agregada';
				$response['data']=$Data;			
				
			}else{
				$response['request']='error';
				$response['message']=$Data[1];
				$response['data']=$Data;
			}
			
			echo json_encode($response);		
		break;
		//----------------------------------------------------------------------------------------------//

		//----------------------------------------------------------------------------------------------//
		case 'generarResultados':
			$Data = $Controller->Prueba_CalcularResultados($id_candidato);
			if($Data[0] == TRUE){
				$response['request']='success';
				$response['message']='Resultados Listos';
				$response['data']=$Data;			
				
			}else{
				$response['request']='error';
				$response['message']=$Data[1];
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