<?php
/**
 * Autor: Alberto Soriano
 * Fecha: 25/01/2020
 * Clase que maneja las peticiones entre servidor y base de datos
 */
class Model
{
	
	//----------------------------------------------------------------------------------------------//
	private function ConexionPDO(){
		$server='10.8.8.83';
		$user='postgres';
        $password='2019$$resuelva';
        $db = 'resuelva_mecanografia';
		$conexion = new PDO('pgsql:dbname='.$db.';user='.$user.';password='.$password.';host='.$server.' ');
		return $conexion;
	}
	//----------------------------------------------------------------------------------------------//	

	//----------------------------------------------------------------------------------------------//
	protected function insertCandidato($nombre,$identidad){

		$q_validar = "SELECT * FROM ort_candidatos where identidad = ?";
		$q_insert = "INSERT INTO ort_candidatos(nombre,identidad) values(?,?) RETURNING *";
		$q_update = "UPDATE ort_candidatos set intentos=intentos+1 where identidad = ? RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			
			//Validacion de identidad
			$s_validar = $conexion->prepare($q_validar);
			$s_validar->bindParam(1,$identidad);

			if($s_validar->execute()){
				
				$s_validar = $s_validar->fetchAll(PDO::FETCH_ASSOC);

				if(sizeof($s_validar) == 1){
					
					//Acceso varios
					/*$s_update = $conexion->prepare($q_update);
					$s_update->bindParam(1,$identidad);
					if($s_update->execute()){
						return [TRUE,"Se actualizo el numero de intentos del usuario",$s_update->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						return [FALSE,"No se puede ejecutar la consulta",$s_update->errorCode()];
					}*/

					//Acceso unico
					return [FALSE,"Ya hay un usuario registrado con este ID",$s_validar];
				}else{
					$s_insert = $conexion->prepare($q_insert);
					$s_insert->bindParam(1,$nombre);
					$s_insert->bindParam(2,$identidad);
					if($s_insert->execute()){
						return [TRUE,"Se agrego el candidato con exito",$s_insert->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						return [FALSE,"No se puede ejecutar la consulta",$s_insert->errorCode()];
					}
				}


			}else{
				return [FALSE,"No se puede ejecutar la consulta",$s_validar->errorCode()];
			}


		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}

	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function validateUsuario($usuario,$clave){
		$query = "SELECT 
					case 
						when (select count(*) from ort_administradores where usuario = ?) <> 1 then FALSE
						when (select count(*) from ort_administradores where usuario = ? and clave = md5(?)) <> 1 then FALSE
						when (select count(*) from ort_administradores where usuario = ? and clave = md5(?)) = 1 then TRUE
						else FALSE
					end as resultado,
					case 
						when (select count(*) from ort_administradores where usuario = ?) <> 1 then 'El usuario no existe'
						when (select count(*) from ort_administradores where usuario = ? and clave = md5(?)) <> 1 then 'la contraseña es incorrecta'
						when (select count(*) from ort_administradores where usuario = ? and clave = md5(?)) = 1 then 'usuario valido'
						else 'usuario o clave incorrecta'
				end as respuesta";

		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$usuario);
			$sentencia->bindParam(2,$usuario);
			$sentencia->bindParam(3,$clave);
			$sentencia->bindParam(4,$usuario);
			$sentencia->bindParam(5,$clave);

			$sentencia->bindParam(6,$usuario);
			$sentencia->bindParam(7,$usuario);
			$sentencia->bindParam(8,$clave);
			$sentencia->bindParam(9,$usuario);
			$sentencia->bindParam(10,$clave);

			if($sentencia->execute()){
				$result = $sentencia->fetchAll(PDO::FETCH_ASSOC);
				if($result[0]['resultado'] == true){
					return [TRUE,$result[0]['respuesta'],null];
				}else{
					return [FALSE,$result[0]['respuesta'],$result];
				}
			}else{
				return [FALSE,"No se pudo obtener la informacion",$sentencia->errorCode()];
			}

		}catch(PDOException $e){
			return [FALSE,"Error en la consulta de datos",$e->getMessage()];
		}

	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function agregarPalabra($palabra,$significado,$nivel){
		$query = "INSERT INTO ort_palabras(palabra,significado,nivel) values(?,?,?) RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$palabra);
			$sentencia->bindParam(2,$significado);
			$sentencia->bindParam(3,$nivel);
			if($sentencia->execute()){
				return [TRUE,"Registro agregado con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se pudo agregar el registro",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function updateArchivo($id,$archivo){
		$query = "UPDATE ort_palabras SET archivo = ? where id = ? RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$archivo);
			$sentencia->bindParam(2,$id);
			if($sentencia->execute()){
				return [TRUE,"Se actualizo el registro con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se pudo actualizar el archivo",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}	
	}	
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function getListaPalabras($nivel){
		$query = "SELECT * FROM ort_palabras where nivel = ? ";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$nivel);
			if($sentencia->execute()){
				return [TRUE,"Datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se pueden obtener los datos",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function getPalabraEdit($id_palabra){
		$query = "SELECT * FROM ort_palabras where id = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_palabra);
			if($sentencia->execute()){
				return [TRUE,"datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se pueden obtener los datos",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function updatePalabra($id_palabra,$palabra,$significado,$nivel){
		$query = "UPDATE ort_palabras set 
			palabra = ?,
			significado = ?,
			nivel = ?
			where id = ?
			RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$palabra);
			$sentencia->bindParam(2,$significado);
			$sentencia->bindParam(3,$nivel);
			$sentencia->bindParam(4,$id_palabra);
			if($sentencia->execute()){
				return [TRUE,"Registro actualizado con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se puede actualizar el registro",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function deletePalabra($id_palabra){
		$query = "DELETE FROM ort_palabras where id = ? RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);			
			$sentencia->bindParam(1,$id_palabra);
			if($sentencia->execute()){
				return [TRUE,"Registro eliminado con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se puede eliminar el registro",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function firstPalabra(){
		$query = "SELECT * FROM ort_palabras where id > 0 order by id asc limit 1 ";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			
			if($sentencia->execute()){
				return [TRUE,"Datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se puede obtener la informacion",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function nextPalabra($id_actual){
		$query = "SELECT * FROM ort_palabras where id > ? order by id asc limit 1 ";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_actual);
			if($sentencia->execute()){
				return [TRUE,"Datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se puede obtener la informacion",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function insertRespuesta($id_actual,$identidad,$respuesta,$id_candidato){
		$query = "INSERT INTO ort_respuestas(id_palabra,identidad,respuesta,id_candidato) values(?,?,?,?) RETURNING *";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_actual);
			$sentencia->bindParam(2,$identidad);
			$sentencia->bindParam(3,$respuesta);
			$sentencia->bindParam(4,$id_candidato);
			if($sentencia->execute()){
				return [TRUE,"Respuesta agregada con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"No se puede agregar la respuesta: ".$query."|".$id_actual."|".$identidad."|".$respuesta,$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function createCalificacion($id_candidato){
		$query ="INSERT INTO ort_candidatos_calificaciones(id_candidato,identidad,nombre,fecha,palabras,correctas,incorrectas,porcentaje)
									select 
									id_candidato,
									identidad,
									nombre,
									fecha,
									count(*) as palabras,
									count(case when validacion = 1 then 1 end) as correctas,
									count(case when validacion = 0 then 1 end) as incorrectas,
									 round(count(case when validacion = 1 then 1 end)::numeric/count(*),2)*100  as porcentaje
									from ort_candidatos_resultados where id_candidato = ?
									group by id_candidato,identidad,nombre,fecha";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_candidato);
			if($sentencia->execute()){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(PDOException $e){
			return FALSE;
		}
									
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function getCalificacion($id_candidato){
		$query ="SELECT * FROM ort_candidatos_calificaciones where id_candidato = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_candidato);
			if($sentencia->execute()){
				return [TRUE,"ok",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"error",$sentencia->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"error",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function calcularResultados($id_candidato){
		$q_validar = "SELECT * FROM ort_candidatos_resultados where id_candidato = ?";
		try{
			$conexion = Self::ConexionPDO();
			$s_validar = $conexion->prepare($q_validar);
			$s_validar->bindParam(1,$id_candidato);
			if($s_validar->execute()){

				$s_validar = $s_validar->fetchAll(PDO::FETCH_ASSOC);

				if(sizeof($s_validar)>=1){
					//Ya existen resultados previos
					$q_removeData = "DELETE FROM ort_candidatos_resultados where id_candidato = ?";
					$s_removeData = $conexion->prepare($q_removeData);
					$s_removeData->bindParam(1,$q_removeData);
					
					$s_removeData->execute();
				}

				//Detale de calificacion
				$q_resultados  = "INSERT INTO ort_candidatos_resultados(id_candidato,nombre,identidad,fecha,palabra,respuesta,resultado,nivel,validacion)
							select 
							a.id,
							a.nombre,
							a.identidad,
							a.fecha,
							b.palabra,
							c.respuesta,
							case when b.palabra = c.respuesta then 'Correcto' else 'Incorrecto' end as resultado,
							b.nivel,
							case when b.palabra = c.respuesta then 1 else 0 end as validacion
							from ort_candidatos as a, ort_palabras as b,ort_respuestas as c
							where a.identidad = c.identidad
							and b.id = c.id_palabra
							and a.id = ?
							RETURNING *";

				$s_resultados = $conexion->prepare($q_resultados);
				$s_resultados->bindParam(1,$id_candidato);

				
				if($s_resultados->execute()){

					$calificacion = Self::createCalificacion($id_candidato);

					if($calificacion==TRUE){
						$calificacion = Self::getCalificacion($id_candidato);
						if($calificacion[0]==TRUE){
							$detalle = $s_resultados->fetchAll(PDO::FETCH_ASSOC);
							$datos = array(
								"resumen"=>$calificacion[2],
								"detalle"=>$detalle

							);
							return [TRUE,"Datos obtenidos con exito",$datos];
						}else{
							return [FALSE,"error",$calificacion];
						}
					}

				}else{
					return [FALSE,"No se puede validar el id del candidato",$s_resultados->errorCode()];	
				}
				

			}else{
				return [FALSE,"No se puede validar el id del candidato",$s_validar->errorCode()];
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function reporteIdentidadCalificacion($id_candidato){
		$query = "SELECT * FROM ort_candidatos_calificaciones WHERE identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_candidato);
			if($sentencia->execute()){
				$datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
				if(sizeof($datos)>=1){
					return [TRUE,"Datos obtenidos con exito",$datos];
				}else{
					return [FALSE,"Error al obtener los datos",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
				}
			}else{
					return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];
				}
			}catch(PDOException $e){
				return [FALSE,"Error en la ejecucion",$e->getMessage()];
			}
		
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function reporteIdentidadRespuestas($id_candidato){
		$query = "SELECT * FROM ort_candidatos_resultados WHERE identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$id_candidato);
			if($sentencia->execute()){
				$datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
				if(sizeof($datos)>=1){
					return [TRUE,"Datos obtenidos con exito",$datos];
				}else{
					return [FALSE,"Error al obtener los datos",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
				}
			}else{
					return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];
				}
			}catch(PDOException $e){
				return [FALSE,"Error en la ejecucion",$e->getMessage()];
			}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function reporteFechas($FechaInicio,$FechaFin){
		$query = "SELECT * FROM ort_candidatos_calificaciones where fecha between ? and ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$FechaInicio);
			$sentencia->bindParam(2,$FechaFin);
			if($sentencia->execute()){
				return [TRUE,"datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function buscarCandidato($identidad){
		$query = "SELECT * FROM ort_candidatos where identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$identidad);
			if($sentencia->execute()){
				return [TRUE,"datos obtenidos con exito",$sentencia->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
			
		
	}
	//----------------------------------------------------------------------------------------------//


	//----------------------------------------------------------------------------------------------//
	protected function deleteFromCandidatos($identidad){
		$query = "DELETE FROM ort_candidatos where identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$identidad);
			if($sentencia->execute()){
				return [TRUE,"Registro Eliminado con exito",null];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function deleteFromCandidatosCalificaciones($identidad){
		$query = "DELETE FROM ort_candidatos_calificaciones where identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$identidad);
			if($sentencia->execute()){
				return [TRUE,"Registro Eliminado con exito",null];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function deleteFromCandidatosResultados($identidad){
		$query = "DELETE FROM ort_candidatos_resultados where identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$identidad);
			if($sentencia->execute()){
				return [TRUE,"Registro Eliminado con exito",null];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------//
	protected function deleteFromRespuestas($identidad){
		$query = "DELETE FROM ort_respuestas where identidad = ?";
		try{
			$conexion = Self::ConexionPDO();
			$sentencia = $conexion->prepare($query);
			$sentencia->bindParam(1,$identidad);
			if($sentencia->execute()){
				return [TRUE,"Registro Eliminado con exito",null];
			}else{
				return [FALSE,"Error al obtener los datos",$sentencia->errorCode()];	
			}
		}catch(PDOException $e){
			return [FALSE,"Error en la ejecucion",$e->getMessage()];
		}
	}
	//----------------------------------------------------------------------------------------------//


	
}
?>