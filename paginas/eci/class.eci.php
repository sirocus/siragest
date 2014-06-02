<?
class eci extends SIRAgest{
	var $acciones;
	var $naveG;
	var $granCons;
	var $clasif;
	//-- Constructor. Inicializa las variables a 0
	function eci(){		
		$this->usuarioLogin=0;
		$this->naveG=1;
		$this->granCons=1;
		$this->clasif=2;
		parent::SIRAgest();
	}
	//-
	function servicios($data=false){
		switch($data['action']){
			default:
				$sql = 'SELECT sv_id AS id,MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,"%Y%m%d"),sv_id)) AS idReg,sv_estado AS estado,DATE_FORMAT(sv__fechaRegistro,"%d-%m-%Y") AS fecha,sv_servicio AS servicio FROM eci__servicios';
				$sql.= !empty($data['where']) ? ' WHERE '.$data['where'] : '';
				$sql.= ' ORDER BY sv_id ASC';			
				$sql.= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';	
				$valores = !empty($data['where']) ? array('sql'=>$sql,'asoc'=>$data['asoc'],'conMadre'=>'','debug'=>false) : array('sql'=>$sql,'conMadre'=>'','debug'=>false);
				$salida = $this->consultaSql($valores);	
				$salida = $salida;
				break;
			case 'add':
				if(is_array($data['data']) && count($data['data'])>0){
					$servicio = $this->servicios(array('where'=>'sv_servicio = :servicio','asoc'=>array(':servicio'=>$data['data']['servicio']),'limit'=>1));					
					if(count($servicio)<=0){				
						$sql = "INSERT INTO eci__servicios (sv__fechaRegistro,sv__usuarioRegistro,sv_servicio) VALUES (:sv__fechaRegistro,:sv__usuarioRegistro,:sv_servicio)";					
						$asoc = array(':sv__fechaRegistro'=>date('Y-m-d H:i:s'),':sv__usuarioRegistro'=>$_SESSION["usuarioActivo"]->usuario_Id,':sv_servicio'=>$data['data']['servicio']);													
						$servicio = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));					
						$servicio = $this->servicios(array('where'=>'sv_id = :id','asoc'=>array(':id'=>$servicio),'limit'=>1));
						$salida = array('id'=>$servicio[0]['id'],'idReg'=>$servicio[0]['idReg'],'servicio'=>$servicio[0]['servicio']);						
					}
				}else $salida = false;
				break;
			case 'update':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "UPDATE eci__servicios SET sv__usuarioModificacion = :sv__usuarioModificacion , sv_servicio = :sv_servicio WHERE MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,'%Y%m%d'),sv_id)) = :id LIMIT 1";
					$asoc = array('sv__usuarioModificacion'=>$_SESSION["usuarioActivo"]->usuario_Id,':sv_servicio'=>$data['data']['servicio'],':id'=>$data['data']['idReg']);
					$servicio = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
					$servicio = $this->servicios(array('where'=>"MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,'%Y%m%d'),sv_id)) = :id",'asoc'=>array(':id'=>$data['data']['idReg']),'limit'=>1));									
					$salida = array('id'=>$servicio[0]['id'],'idReg'=>$servicio[0]['idReg'],'servicio'=>$servicio[0]['servicio']);										
				}else{
					$servicio = $this->servicios(array('where'=>'MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,"%Y%m%d"),sv_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$salida = array('id'=>$servicio[0]['id'],'idReg'=>$servicio[0]['idReg'],'servicio'=>$servicio[0]['servicio']);									
				}
				break;
			case 'del':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "DELETE FROM eci__servicios WHERE MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,'%Y%m%d'),sv_id)) = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['idReg']);				
					$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));									
					//-					
				}else{
					$servicio = $this->servicios(array('where'=>'MD5(CONCAT(DATE_FORMAT(sv__fechaRegistro,"%Y%m%d"),sv_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$salida = array('id'=>$servicio[0]['id'],'idReg'=>$servicio[0]['idReg'],'servicio'=>$servicio[0]['servicio']);									
				}
				break;	
			case 'estado':
					$sql = "UPDATE eci__servicios SET sv_estado = IF(sv_estado=0,1,0) WHERE sv_id = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['id']);
					$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>true));							
				break;			
		}
		return $salida;
	}	
	//-
	function subservicios($data=false){
		$servicios = $this->servicios();		
		switch($data['action']){
			default:
				$sql = 'SELECT ss_id AS id,MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,"%Y%m%d"),ss_id)) AS idReg,ss_estado AS estado,DATE_FORMAT(ss__fechaRegistro,"%d-%m-%Y") AS fecha,ss_servicio AS servicio,ss_subservicio AS subservicio FROM eci__subservicios';
				$sql.= !empty($data['where']) ? ' WHERE '.$data['where'] : '';
				$sql.= ' ORDER BY ss_id ASC';			
				$sql.= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';	
				$valores = !empty($data['where']) ? array('sql'=>$sql,'asoc'=>$data['asoc'],'conMadre'=>'','debug'=>$data['debug']) : array('sql'=>$sql,'conMadre'=>'','debug'=>$data['debug']);
				$salida = $this->consultaSql($valores);	
				$salida = $salida;
				break;
			case 'add':
				if(is_array($data['data']) && count($data['data'])>0){
					$subservicio = $this->subservicios(array('where'=>'ss_servicio = :servicio AND ss_subservicio = :subservicio','asoc'=>array(':servicio'=>$data['data']['servicio'],':subservicio'=>$data['data']['subservicio']),'limit'=>1,'debug'=>false));										
					if(count($subservicio['subservicios'])<=0){				
						$sql = "INSERT INTO eci__subservicios (ss__fechaRegistro,ss__usuarioRegistro,ss_servicio,ss_subservicio) VALUES (:ss__fechaRegistro,:ss__usuarioRegistro,:ss_servicio,:ss_subservicio)";					
						$asoc = array(':ss__fechaRegistro'=>date('Y-m-d H:i:s'),':ss__usuarioRegistro'=>$_SESSION["usuarioActivo"]->usuario_Id,':ss_servicio'=>$data['data']['servicio'],':ss_subservicio'=>$data['data']['subservicio']);
						$subservicio = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));					
						$subservicio = $this->subservicios(array('where'=>'ss_id = :id','asoc'=>array(':id'=>$subservicio),'limit'=>1,'debug'=>false));						
						$salida = array('id'=>$subservicio['subservicios'][0]['id'],'idReg'=>$subservicio['subservicios'][0]['idReg'],'servicio'=>$subservicio['subservicios'][0]['servicio'],'subservicio'=>$subservicio['subservicios'][0]['subservicio']);						
					}
				}else $salida = false;
				break;
			case 'update':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "UPDATE eci__subservicios SET ss__usuarioModificacion = :ss__usuarioModificacion , ss_servicio = :ss_servicio, ss_subservicio = :ss_subservicio WHERE MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,'%Y%m%d'),ss_id)) = :id LIMIT 1";
					$asoc = array('ss__usuarioModificacion'=>$_SESSION["usuarioActivo"]->usuario_Id,':ss_servicio'=>$data['data']['servicio'],':ss_subservicio'=>$data['data']['subservicio'],':id'=>$data['data']['idReg']);
					$subservicio = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
					$subservicio = $this->subservicios(array('where'=>"MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,'%Y%m%d'),ss_id)) = :id",'asoc'=>array(':id'=>$data['data']['idReg']),'limit'=>1));									
					$salida = array('id'=>$subservicio['subservicios'][0]['id'],'idReg'=>$subservicio['subservicios'][0]['idReg'],'servicio'=>$subservicio['subservicios'][0]['servicio'],'subservicio'=>$subservicio['subservicios'][0]['subservicio']);									
				}else{
					$subservicio = $this->subservicios(array('where'=>'MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,"%Y%m%d"),ss_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$salida = array('id'=>$subservicio['subservicios'][0]['id'],'idReg'=>$subservicio['subservicios'][0]['idReg'],'servicio'=>$subservicio['subservicios'][0]['servicio'],'subservicio'=>$subservicio['subservicios'][0]['subservicio']);									
				}
				break;
			case 'del':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "DELETE FROM eci__subservicios WHERE MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,'%Y%m%d'),ss_id)) = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['idReg']);				
					$subservicio = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>true));									
					//-					
				}else{
					$subservicio = $this->subservicios(array('where'=>'MD5(CONCAT(DATE_FORMAT(ss__fechaRegistro,"%Y%m%d"),ss_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1,'debug'=>false));
					$salida = array('id'=>$subservicio['subservicios'][0]['id'],'idReg'=>$subservicio['subservicios'][0]['idReg'],'servicio'=>$subservicio['subservicios'][0]['servicio'],'subservicio'=>$subservicio['subservicios'][0]['subservicio']);									
				}
				break;	
			case 'estado':
					$sql = "UPDATE eci__subservicios SET ss_estado = IF(ss_estado=0,1,0) WHERE ss_id = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['id']);
					$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>true));							
				break;			
		}
		return array('subservicios'=>$salida,'servicios'=>$servicios);
	}	
	//-	
	function asignaciones_naveG($data=false){
		if(is_array($data['data']) && count($data['data'])>0){			
			$trabajadores = $this->usuarios(array('where'=>'gu_tipo > :tipo AND gu_estado = :estado AND gu_id = :trabajador','asoc'=>array(':tipo'=>1,':estado'=>1,':trabajador'=>$data['data']['trabajador']),'debug'=>false));
			$sql = 'SELECT fi_id AS id,DATE_FORMAT(fi__fechaRegistro,"%d-%m-%Y") AS entrada,DATE_FORMAT(fi__fechaModificacion,"%d-%m-%Y") AS salida,fi_trabajador AS trabajador FROM eci__fichar';		
			$sql.= ' WHERE DATE(fi__fechaRegistro)=CURDATE() AND fi_trabajador = :trabajador LIMIT 1';
			$asoc = array(':trabajador'=>$data['data']['trabajador']);
			$fichaje = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));				
			if(count($fichaje)==0){
				$sql = "INSERT INTO eci__fichar (fi__fechaRegistro,fi__usuarioRegistro,fi_trabajador) VALUES (:fechaR,:usuarioR,:trabajador)";
				$asoc = array(':fechaR'=>date('Y-m-d H:i:s'),':usuarioR'=>$_SESSION["usuarioActivo"]->usuario_Id,':trabajador'=>$data['data']['trabajador']);
				$fichar = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));									

				$servicio = $this->naveG;
				$subservicio = $data['data']['proyecto']=='granconsumo' ? $this->granCons : $this->clasif;
				$sql = "INSERT INTO eci__asignaciones (ea__fechaRegistro,ea__usuarioRegistro,ea_servicio,ea_subservicio,ea_trabajador) VALUES (:fechaR,:usuarioR,:servicio,:subservicio,:trabajador)";
				$asoc = array(':fechaR'=>date('Y-m-d H:i:s'),':usuarioR'=>$_SESSION["usuarioActivo"]->usuario_Id,':servicio'=>$servicio,':subservicio'=>$subservicio,':trabajador'=>$data['data']['trabajador']);
				$fichar = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));												

				$salida = array('trabajador'=>$trabajadores['usuarios'][0]);								
			}elseif(count($fichaje)>0 && $data['data']['proyecto']=='salir'){
				$sql = "UPDATE eci__fichar SET fi__usuarioModificacion = :usuarioM WHERE fi_trabajador = :trabajador AND DATE(fi__fechaRegistro) = CURDATE() LIMIT 1";
				$asoc = array(':usuarioM'=>$_SESSION["usuarioActivo"]->usuario_Id,':trabajador'=>$data['data']['trabajador']);
				$fichar = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));									

				$salida = array('trabajador'=>$trabajadores['usuarios'][0],'salir'=>true);
			}elseif(count($fichaje)>0 && $data['data']['proyecto']!='salir'){				
				$servicio = $this->naveG;
				$subservicio = $data['data']['proyecto']=='granconsumo' ? $this->granCons : $this->clasif;
				$sql = "INSERT INTO eci__asignaciones (ea__fechaRegistro,ea__usuarioRegistro,ea_servicio,ea_subservicio,ea_trabajador) VALUES (:fechaR,:usuarioR,:servicio,:subservicio,:trabajador)";
				$asoc = array(':fechaR'=>date('Y-m-d H:i:s'),':usuarioR'=>$_SESSION["usuarioActivo"]->usuario_Id,':servicio'=>$servicio,':subservicio'=>$subservicio,':trabajador'=>$data['data']['trabajador']);
				$fichar = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));												
				
				$salida = array('trabajador'=>$trabajadores['usuarios'][0]);				
			}			
		}else{
			$trabajadores = $this->usuarios(array('where'=>'gu_tipo > 1 AND gu_estado = :estado','asoc'=>array(':estado'=>1)));
			if(is_array($trabajadores['usuarios']) && count($trabajadores['usuarios'])>0){
				foreach($trabajadores['usuarios'] as $trabajador){
					$sql = 'SELECT fi_id AS id,DATE_FORMAT(fi__fechaRegistro,"%d-%m-%Y") AS entrada,DATE_FORMAT(fi__fechaModificacion,"%d-%m-%Y") AS salida,fi_trabajador AS trabajador FROM eci__fichar';		
					$sql.= ' WHERE DATE(fi__fechaRegistro)=CURDATE() AND fi_trabajador = :trabajador LIMIT 1';					
					$asoc = array(':trabajador'=>$trabajador['id']);
					$fichaje = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
					$estado = !empty($fichaje[0]['salida']) && $fichaje[0]['salida']>0 ? 'salida' : 'entrada';
					if($estado=='entrada' && !empty($fichaje[0]['entrada']) && $fichaje[0]['entrada']>0){
						$sql = 'SELECT ea_id AS id,DATE_FORMAT(ea__fechaRegistro,"%d-%m-%Y %H:%i:%s") AS fecha,ea_servicio AS servicio,ea_subservicio AS subservicio,ea_trabajador AS trabajador FROM eci__asignaciones';		
						$sql.= ' WHERE DATE(ea__fechaRegistro)=CURDATE() AND ea_trabajador = :trabajador ORDER BY fecha DESC LIMIT 1';					
						$asoc = array(':trabajador'=>$trabajador['id']);
						$proyecto = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
					
						$estado = $proyecto[0]['subservicio']==$this->granCons ? 'granconsumo' : 'clasificador';																					
						
						if($estado=='granconsumo') $granconsumo[] = array('id'=>$trabajador['id'],'idReg'=>$trabajador['idReg'],'nombre'=>$trabajador['nombre'],'estado'=>$estado);							
						else $clasificador[] = array('id'=>$trabajador['id'],'idReg'=>$trabajador['idReg'],'nombre'=>$trabajador['nombre'],'estado'=>$estado);							

					}elseif($estado=='entrada' && $fichaje[0]['entrada']<1){
						$entradas[] = array('id'=>$trabajador['id'],'idReg'=>$trabajador['idReg'],'nombre'=>$trabajador['nombre'],'estado'=>$estado);
					}elseif($estado=='salida') $salidas[] = array('id'=>$trabajador['id'],'idReg'=>$trabajador['idReg'],'nombre'=>$trabajador['nombre'],'estado'=>$estado); 
					$arrayTr[] = array('id'=>$trabajador['id'],'idReg'=>$trabajador['idReg'],'nombre'=>$trabajador['nombre'],'estado'=>$estado);									
				}
			}
			$salida = array('entradas'=>$entradas,'salidas'=>$salidas,'granconsumo'=>$granconsumo,'clasificador'=>$clasificador);
		}
		return $salida;
	}	
	//-
	function clasificacion_naveG($data=false){
		$horarios = 'SELECT DATE_FORMAT(MIN(ea__fechaRegistro),"%Y-%m-%d %H:00:00") AS inicio , MAX(ea__fechaRegistro) AS fin FROM eci__asignaciones WHERE DATE(ea__fechaRegistro)=CURDATE()';		
		$horarios = $this->consultaSql(array('sql'=>$horarios,'conMadre'=>'','debug'=>false));		
		$inicio = date('Y-m-d H:i:s', strtotime($horarios[0]['inicio'].' -30 minute'));			
		$fin = $horarios[0]['fin'];
		do{
			$inicio = date('Y-m-d H:i:s', strtotime($inicio.' +30 minute'));
			$siguiente = date('Y-m-d H:i:s', strtotime($inicio.' +30 minute'));									
			$grancon = "SELECT COUNT(DISTINCT ea_trabajador) AS cuantos FROM eci__asignaciones WHERE (ea_servicio=".$this->naveG." AND ea_subservicio=".$this->granCons.") AND (ea__fechaRegistro BETWEEN '".$inicio."' AND '".$siguiente."') ORDER BY ea__fechaRegistro DESC";							
			$grancon = $this->consultaSql(array('sql'=>$grancon,'conMadre'=>'','debug'=>false));					
			$clasif = "SELECT COUNT(DISTINCT ea_trabajador) AS cuantos FROM eci__asignaciones WHERE (ea_servicio=".$this->naveG." AND ea_subservicio=".$this->clasif.") AND (ea__fechaRegistro BETWEEN '".$inicio."' AND '".$siguiente."') ORDER BY ea__fechaRegistro DESC";										
			$clasif = $this->consultaSql(array('sql'=>$clasif,'conMadre'=>'','debug'=>false));
			$salida[] = array('horario'=>date('H:i', strtotime($inicio)).' - '.date('H:i', strtotime($siguiente)),'granconsumo'=>$grancon[0]['cuantos'],'clasificador'=>$clasif[0]['cuantos']);					
		}while(date('Hi', strtotime($fin))>date('Hi', strtotime($siguiente)));
		return $salida;
	}
	//-
	function tiempo_transcurrido($hora1,$hora2,$devolver='horas'){	
    	$entrada = (int) substr($hora1,0,2) > (int) substr($hora2,0,2) ? explode(':',$hora2) : explode(':',$hora1);
	    $salida = (int) substr($hora1,0,2) < (int) substr($hora2,0,2) ? explode(':',$hora2) : explode(':',$hora1);
		
		$minEntrada = ($entrada[0]*60)+$entrada[1];
		$minSalida = ($salida[0]*60)+$salida[1];		
		$minTotal = $minSalida-$minEntrada;

		if($minTotal<=59) return $salida = $devolver == 'horas' ? $this->formato_horas($minTotal) : $minTotal;
		elseif($minTotal>59) return $salida = $devolver == 'horas' ? $this->formato_horas($minTotal) : $minTotal;			
	}	
	function formato_horas($minutos){
		list($horas,$decimal) = explode('.',($minutos/60));
		$minutos = $minutos%60;
		return str_pad($horas,2,0, STR_PAD_LEFT).":".str_pad($minutos,2,0, STR_PAD_LEFT);
	}
	//-
	function horaspersona_naveG($data=false){
		$sql = 'SELECT fi_id AS id,DATE_FORMAT(fi__fechaRegistro,"%d-%m-%Y") AS fecha,DATE_FORMAT(fi__fechaRegistro,"%H:%i") AS entrada,DATE_FORMAT(fi__fechaModificacion,"%H:%i") AS salida,fi_trabajador AS trabajador,gud_nombre AS nombre FROM eci__fichar AS fi';		
		$sql.= ' INNER JOIN gestion__usuariosdatos AS gd ON(gd.gud_usuario=fi.fi_trabajador) WHERE fi__fechaModificacion>0 ORDER BY DATE(fi.fi__fechaRegistro) DESC,gd.gud_nombre ASC';		
		$fechas = $this->consultaSql(array('sql'=>$sql,'conMadre'=>'','debug'=>false));

		if(is_array($fechas) && count($fechas)>0){
			foreach($fechas as $fecha){
				$sql = "SELECT ea_id AS id,DATE(ea__fechaRegistro) AS fecha,DATE_FORMAT(ea__fechaRegistro,'%H:%i') AS hora,ea_servicio AS servicio,ea_subservicio AS subservicio,ea_trabajador AS trabajador FROM eci__asignaciones AS ea WHERE ea_trabajador = :trabajador AND DATE_FORMAT(ea__fechaRegistro,'%d-%m-%Y')='".$fecha['fecha']."' ORDER BY hora ASC";		
				$asoc = array(':trabajador'=>$fecha['trabajador']);
				$totales = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
				unset($granconsumo); unset($clasificador);
				if(is_array($totales) && count($totales)>0){
					$comparar = $fecha['entrada'];
					foreach($totales as $total){
						$granconsumo+= $total['subservicio'] == $this->clasif ? $this->tiempo_transcurrido($comparar,$total['hora'],'minutos') : 0;
						$clasificador+= $total['subservicio'] == $this->granCons ? $this->tiempo_transcurrido($comparar,$total['hora'],'minutos') : 0;						
						$comparar = $total['hora'];						
					}					
					$granconsumo+= $total['subservicio'] == $this->granCons ? $this->tiempo_transcurrido($comparar,$fecha['salida'],'minutos') : 0;
					$clasificador+= $total['subservicio'] == $this->clasif ? $this->tiempo_transcurrido($comparar,$fecha['salida'],'minutos') : 0;											
					$totalGranConsumo+=$granconsumo;
					$totalClasificador+=$clasificador;					
				}		
				$totalHoras+= $this->tiempo_transcurrido($fecha['entrada'],$fecha['salida'],'minutos');		
				$salida[$fecha['fecha']][] = array('trabajador'=>$fecha['trabajador'],'nombre'=>$fecha['nombre'],'fecha'=>$fecha['fecha'],'entrada'=>$fecha['entrada'],'salida'=>$fecha['salida'],'horas'=>$this->tiempo_transcurrido($fecha['entrada'],$fecha['salida']),'granconsumo'=>$this->formato_horas($granconsumo),'clasificador'=>$this->formato_horas($clasificador));									
			}
			$salida['totales'] = array('granconsumo'=>$this->formato_horas($totalGranConsumo),'clasificador'=>$this->formato_horas($totalClasificador),'horas'=>$this->formato_horas($totalHoras));
			return $salida;
		}return false;
	}
}
?>
