<?
/*
	File name   : class.siragest.php
	Version     : 1.0
	Begin       : 2014-05-01
	Last Update : 2014-05-01
	Author      : Marc Torrente Cesteros (Mark Sirocus)
	Repositorio : https://github.com/sirocus/siragest
	
	License
	===============================================================================================================
	This work is licensed under Apache 2 License. 
	To view a copy of this license, visit https://github.com/sirocus/siragest/blob/master/LICENSE.	
 
	Esta obra está licenciada bajo la Licencia Apache 2. 
	Para ver una copia de esta licencia, visita https://github.com/sirocus/siragest/blob/master/LICENSE.
	===============================================================================================================		
*/
class SIRAgest{
	var $installation;
	var $conMadre;
	var $bbdd;
	//Constructor. Inicializa las variables a 0
	function SIRAgest(){
		global $conMadre;
		global $bbdd;		
		$this->conMadre = is_array($conMadre) ? $conMadre : $bbdd;			
	}
	//-
	public function conectar($data=false){
		$data = !is_array($data) ? $this->conMadre : $data;		
		try{	
			//- Utilizamos PDO para conectarnos a las BBDD's en caso de mostrar un error se deberá activar en php.ini						
			$conection = new PDO('mysql:host='.$data['HOSTNAME'].';dbname='.$data['DATABASE'],$data['USERNAME'],$data['PASSWORD']);		
			return $conection;			
		}catch(PDOException $e){
			echo '<div style="border:#ccc 1px solid;background:#eeeeee;padding:7px;margin-bottom:10px;">'.$e->getMessage().'</div>';
			die();			
		}	
	}	
	//-
	public function consultaSql($data=false){
		$data['sql'].= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';
		if(!is_array($conMadre)) $cnx = $this->conectar();
		else $cnx = $this->conectar($data['conMadre']);		
		try{	
			$resultados = $cnx->prepare($data['sql']);
			$resultados->execute($data['asoc']);
			$resultados = $resultados->fetchAll();	
			$resultados = strtolower(substr($data['sql'],0,6))=='insert' ? $cnx->lastInsertId() : $resultados;					
		}catch(PDOException $e){
			echo '<div style="border:red 1px solid;background:#eeeeee;padding:7px;margin-bottom:10px;">'.$e->getMessage().'</div>';
			die();			
		}	
		if(!empty($data['debug'])){
			echo '<div style="border:#ccc 1px solid;background:#eeeeee;padding:7px;margin-bottom:10px;">'.$data['sql'].'<br>'.print_r($data['asoc']).'</div>';								
		}
		return $resultados;	
	}	
	//-
	public function showTables($data){
		if(is_array($data) && count($data)>0){
			$sql = 'SHOW FULL TABLES';			
			$tables = $this->consultaSql(array('sql'=>$sql,'conMadre'=>$data,'debug'=>false));	
			if(is_array($tables) && count($tables)>0){
				foreach($tables as $table){
					$salida[$table['Tables_in_'.$data['DATABASE']]]=array('tabla'=>$table['Tables_in_'.$data['DATABASE']],'tipo'=>$table['Table_type']);
				}
			}
		}else $salida = false;
		return $salida;
	}	
	//-
	public function urlAmigables($url){	
		$url = trim($url);						
		
		$find = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ü', 'ñ', 'Ñ', 'ç');		
		$repl = array('a', 'a', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u',  'u', 'n', 'n', 'c');		
		$url = str_replace ($find, $repl, $url);	
	
		$find = array(' ', '&', '\r\n', '\n', '+', '/');
		$url = str_replace ($find, '-', $url);			
	
		return stripslashes(strtolower($url)); 	
	}	
	//-
	public function urlAmigas($url){
		$oldurl = $url;
		$url = preg_split('[=|&|/index]',$url);
		if(is_array($url) && count($url)>0){
			foreach($url as $item){
				if($fila%2==0) $salida.= strlen($salida)>0 ? '/'.$item : $item;
				$fila++;				
			}
		}
		$salida.= '.html';	
		$salida = $oldurl;	
		return $salida;
	}
	//-
	function usuarios($data=false){
		$roles = $this->roles(array('where'=>'gr_estado = :gr_estado AND gr_tipo >= :gr_tipo','asoc'=>array(':gr_estado'=>1,':gr_tipo'=>2)));
		$apps = $this->apps();
		switch($data['action']){
			default:
				$sql = 'SELECT gu_id AS id,MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,"%Y%m%d"),gu_id)) AS idReg,gu_estado AS estado,DATE_FORMAT(gu__fechaRegistro,"%d-%m-%Y") AS fecha,gu_ip AS ip,gu_usuario AS usuario,gu_password AS password,gr_id AS rol,gr_id AS tipoid,MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,"%Y%m%d"),gr_id)) AS tipoidReg,gr_tipo AS tipo';
				$sql.= ',gud_nombre AS nombre,gud_apps AS apps';				
				$sql.= ' FROM gestion__usuarios AS a';
				$sql.= ' LEFT JOIN gestion__roles AS b ON(gu_tipo=gr_id)';				
				$sql.= ' LEFT JOIN gestion__usuariosdatos AS c ON(gud_usuario=gu_id)';								
				$sql.= !empty($data['join']) ? ' '.$data['join'] : '';								
				$sql.= empty($data['where']) ? ' WHERE gu_tipo > :rol ' : ' WHERE '.$data['where'];				
				$sql.= ' ORDER BY gu_tipo ASC,gu_id ASC';			
				$sql.= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';							
				$rol = empty($_SESSION["usuarioActivo"]->usuario_Tipo_Id) ? 0 : $_SESSION["usuarioActivo"]->usuario_Tipo_Id;
				$asoc = empty($data['where']) ? array(":rol"=>$rol) : $data['asoc'];
				$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>$data['debug']));			
				$salida = $usr;
				break;
			case 'add':
				if(is_array($data['data']) && count($data['data'])>0){
					$usr = $this->usuarios(array('where'=>'gu_usuario = :user','asoc'=>array(':user'=>strtolower($data['data']['usuario'])),'limit'=>1));				
					if(count($usr['usuarios'])<=0){
						$sql = "INSERT INTO gestion__usuarios (gu__fechaRegistro,gu__usuarioRegistro,gu_ip,gu_usuario,gu_password,gu_email,gu_tipo,gu_estado) VALUES (:gu__fechaRegistro,:gu__usuarioRegistro,:gu_ip,:gu_usuario,:gu_password,:gu_email,:gu_tipo,:gu_estado)";					
						$asoc = array(':gu__fechaRegistro'=>date('Y-m-d H:i:s'),':gu__usuarioRegistro'=>$_SESSION["usuarioActivo"]->usuario_Id,':gu_ip'=>getRealIP(),':gu_usuario'=>strtolower($data['data']['usuario']),':gu_password'=>md5($data['data']['password']),':gu_email'=>strtolower($data['data']['usuario']),':gu_tipo'=>$data['data']['rol'],':gu_estado'=>1);
						$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));																	
						$usr = $this->usuarios(array('where'=>'gu_id = :id','asoc'=>array(':id'=>$usr),'limit'=>1));
						
						$sql = "INSERT INTO gestion__usuariosdatos (gud_idReg,gud_usuario,gud_nombre,gud_apps) VALUES (:gud_idReg,:gud_usuario,:gud_nombre,:gud_apps)";
						$asoc = array('gud_idReg'=>$usr['usuarios'][0]['idReg'],':gud_usuario'=>$usr['usuarios'][0]['id'],':gud_nombre'=>$data['data']['nombre'],':gud_apps'=>implode('|',$data['data']['apps']));
						$usrd = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));	
						$usr = $this->usuarios(array('where'=>'gu_id = :id','asoc'=>array(':id'=>$usr['usuarios'][0]['id']),'limit'=>1,'debug'=>false));												

						$salida = array('id'=>$usr['usuarios'][0]['id'],'idReg'=>$usr['usuarios'][0]['idReg'],'usuario'=>$usr['usuarios'][0]['usuario'],'password'=>$usr['usuarios'][0]['password'],'rol'=>$usr['usuarios'][0]['rol'],'nombre'=>$usr['usuarios'][0]['nombre'],'apps'=>$usr['usuarios'][0]['apps']);																
					}
				}
				break;
			case 'update':
				if(is_array($data['data']) && count($data['data'])>0){	
					$sql = strlen($data['data']['password'])==32 ? "UPDATE gestion__usuarios SET gu__usuarioModificacion = :gu__usuarioModificacion , gu_tipo = :gu_tipo WHERE MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,'%Y%m%d'),gu_id)) = :id LIMIT 1"
																: "UPDATE gestion__usuarios SET gu__usuarioModificacion = :gu__usuarioModificacion , gu_password = :gu_password , gu_tipo = :gu_tipo WHERE MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,'%Y%m%d'),gu_id)) = :id LIMIT 1";
					$asoc = strlen($data['data']['password'])==32 ? 	array('gu__usuarioModificacion'=>$_SESSION["usuarioActivo"]->usuario_Id,':gu_tipo'=>$data['data']['rol'],':id'=>$data['data']['idReg'])
																: 	array('gu__usuarioModificacion'=>$_SESSION["usuarioActivo"]->usuario_Id,':gu_password'=>md5($data['data']['password']),':gu_tipo'=>$data['data']['rol'],':id'=>$data['data']['idReg']);
					$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));

					$sql = "UPDATE gestion__usuariosdatos SET gud_nombre = :gud_nombre , gud_apps = :gud_apps WHERE gud_idReg = :id LIMIT 1";
					$asoc = array('gud_nombre'=>$data['data']['nombre'],':gud_apps'=>implode('|',$data['data']['apps']),':id'=>$data['data']['idReg']);
					$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));					
					//-					
					$usr = $this->usuarios(array('where'=>"MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,'%Y%m%d'),gu_id)) = :id",'asoc'=>array(':id'=>$data['data']['idReg']),'limit'=>1));					
					$salida = array('id'=>$usr['usuarios'][0]['id'],'idReg'=>$usr['usuarios'][0]['idReg'],'usuario'=>$usr['usuarios'][0]['usuario'],'password'=>$usr['usuarios'][0]['password'],'rol'=>$usr['usuarios'][0]['rol'],'nombre'=>$usr['usuarios'][0]['nombre'],'apps'=>$usr['usuarios'][0]['apps']);								
				}else{
					$usr = $this->usuarios(array('where'=>'MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,"%Y%m%d"),gu_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1,'debug'=>false));				
					$salida = array('id'=>$usr['usuarios'][0]['id'],'idReg'=>$usr['usuarios'][0]['idReg'],'usuario'=>$usr['usuarios'][0]['usuario'],'password'=>$usr['usuarios'][0]['password'],'rol'=>$usr['usuarios'][0]['rol'],'nombre'=>$usr['usuarios'][0]['nombre'],'apps'=>$usr['usuarios'][0]['apps']);				
				}
				break;
			case 'del':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "DELETE FROM gestion__usuarios WHERE MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,'%Y%m%d'),gu_id)) = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['idReg']);				
					$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));									
					//-					
				}else{
					$usr = $this->usuarios(array('where'=>'MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,"%Y%m%d"),gu_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$salida = array('id'=>$usr['usuarios'][0]['id'],'idReg'=>$usr['usuarios'][0]['idReg'],'usuario'=>$usr['usuarios'][0]['usuario'],'password'=>$usr['usuarios'][0]['password'],'rol'=>$usr['usuarios'][0]['rol']);				
				}
				break;	
			case 'estado':
					$sql = "UPDATE gestion__usuarios SET gu_estado = IF(gu_estado=0,1,0) WHERE gu_id = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['id']);
					$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>true));							
				break;			
		}
		return array('usuarios'=>$salida,'roles'=>$roles,'apps'=>$apps);
	}	
	//-
	function apps($data=false){
		switch($data['action']){
			default:
				$sql = 'SELECT ga_id AS id,ga_estado AS estado,DATE_FORMAT(ga__fechaRegistro,"%d-%m-%Y") AS fecha,ga_app AS app,ga_titulo AS titulo FROM gestion__apps';
				$sql.= !empty($data['where']) ? ' WHERE '.$data['where'] : '';
				$sql.= ' ORDER BY ga_id';			
				$sql.= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';		
				$asoc = $data['asoc'];									
				$app = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>$data['debug']));	
				
				$salida = $app;
				break;			
		}
		return $salida;
	}
	//-	
	function leerConfig($data=false){
		$file = 'paginas/'.$data['dir'].'/config.xml';
		if(file_exists($file)){
			$xml = file_get_contents($file);
			$menu = new SimpleXMLElement($xml);
			$xml = json_decode(json_encode((array) simplexml_load_string($xml)), 1);
			//-
			if(is_array($xml['lvl2']) && count($xml['lvl2'])>0) $salida = $xml['lvl2'];
			else $salida = false;
		}else $salida = false;
		return $salida;
	}
	function cargarConfig($data=false){		
		$file = 'paginas/'.$data['seccion'].'/config.xml';
		if(file_exists($file)){
			$xml = file_get_contents($file);
			$menu = new SimpleXMLElement($xml);
			$xml = json_decode(json_encode((array) simplexml_load_string($xml)), 1);
			if(!empty($data['debug'])){ echo "\n---\n"; print_r($xml); echo "\n---\n"; }					
			if(is_array($xml['lvl2']) && count($xml['lvl2'])>0){
				$apartados = $xml['lvl2'];
				foreach($xml['lvl2'] as $lvl){
					if($data['apartado']==$this->urlAmigables($lvl['nombre'])) break;
				}
				$archivo = $lvl['archivo'];
				if(!empty($data['opcion']) && (is_array($lvl['operaciones']) && count($lvl['operaciones'])>0)){															
					$keys = array_keys($lvl['operaciones']);
					if(in_array('nombre',$keys)) $lvl['operaciones'][]=$lvl['operaciones'];
					foreach($lvl['operaciones'] as $operacion){
						if($data['opcion']==$this->urlAmigables($operacion['nombre'])) break;						
					}
					$archivo = $operacion['archivo'];
				}
			}
			$salida = array('seccion'=>$xml['nombre'],'seccion_icono'=>$xml['icono'],'apartado'=>$lvl['nombre'],'apartado_icono'=>$lvl['icono'],'opcion'=>$operacion['nombre'],'opcion_icono'=>$operacion['icono'],'config'=>$xml['config'],'archivo'=>$archivo,'apartados'=>$apartados);							
			return $salida;
		}else return false;
	}			
	//-
	function roles($data=false){
		switch($data['action']){
			default:
				$sql = 'SELECT gr_id AS id,MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,"%Y%m%d"),gr_id)) AS idReg,gr_estado AS estado,DATE_FORMAT(gr__fechaRegistro,"%d-%m-%Y") AS fecha,gr_tipo AS rol FROM gestion__roles';
				$sql.= !empty($data['where']) ? ' WHERE '.$data['where'] : '';
				$sql.= ' ORDER BY gr_id ASC';			
				$sql.= !empty($data['limit']) ? ' LIMIT '.$data['limit'] : '';	
				$valores = !empty($data['where']) ? array('sql'=>$sql,'asoc'=>$data['asoc'],'conMadre'=>'','debug'=>false) : array('sql'=>$sql,'conMadre'=>'','debug'=>false);
				$usr = $this->consultaSql($valores);	
				$salida = $usr;
				break;
			case 'accesos':
				$sql = 'SELECT gp_estado AS estado FROM gestion__roles_permisos WHERE gp_idReg = :id';
				$asoc[':id'] = $data['data']['idReg'];				
				if(is_array($data['data']['campos']) && count($data['data']['campos'])>0){
					foreach($data['data']['campos'] as $key=>$value){
						$sql.= ' AND '.$key." = :".$key;
						$asoc[':'.$key] = $value;
					}
				}
				$sql.= ' LIMIT 1';
				$estado = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));											
				$estado = empty($estado) ? 0 : $estado[0]['estado'];
				return $estado;
				break;
			case 'permisos':	
				switch($data['data']['action']){
					default:
						$apps = $this->apps();
						if(is_array($apps) && count($apps)>0){	
							foreach($apps as $app){
								$config = $this->cargarConfig(array('seccion'=>$app['app']));
								//-
								$data['campos'] = array('gp_seccion'=>$this->urlAmigables($config['seccion']));							
								$estado = $this->roles(array('action'=>'accesos','data'=>$data));													
								//-
								$salida[] = array('seccion'=>$this->urlAmigables($config['seccion']),'estado'=>$estado);								
								if(is_array($config['apartados']) && count($config['apartados'])>0){
									foreach($config['apartados'] as $apartado){
										//-
										$data['campos'] = array('gp_seccion'=>$this->urlAmigables($config['seccion']),'gp_apartado'=>$this->urlAmigables($apartado['nombre']));							
										$estado = $this->roles(array('action'=>'accesos','data'=>$data));													
										//-										
										$salida[] = array('seccion'=>$this->urlAmigables($config['seccion']),'apartado'=>$this->urlAmigables($apartado['nombre']),'estado'=>$estado);								
										if(is_array($apartado['operaciones']) && count($apartado['operaciones'])>0){
											foreach($apartado['operaciones'] as $operacion){
												//-
												$data['campos'] = array('gp_seccion'=>$this->urlAmigables($config['seccion']),'gp_apartado'=>$this->urlAmigables($apartado['nombre']),'gp_opcion'=>$this->urlAmigables($operacion['nombre']));							
												$estado = $this->roles(array('action'=>'accesos','data'=>$data));													
												//-													
												$salida[] = array('seccion'=>$this->urlAmigables($config['seccion']),'apartado'=>$this->urlAmigables($apartado['nombre']),'opcion'=>$this->urlAmigables($operacion['nombre']),'estado'=>$estado);																				
											}
										}
									}
								}								
							}
						}					
						break;
					case 'save':			
						$estructura = array('gp_seccion','gp_apartado','gp_opcion');
						//-
						if(is_array($data['data']['permisos']) && count($data['data']['permisos'])>0){
							$sql = "UPDATE gestion__roles_permisos SET gp_estado = :estado WHERE gp_idReg = :id";
							$asoc = array(':id'=>$data['data']['idReg'],':estado'=>0);
							$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));												
							foreach($data['data']['permisos'] as $permisos){
								$partes = explode("|",$permisos);
								if(is_array($partes) && count($partes)>0){
									unset($sql_campos);
									$sql = "SELECT gp_id AS id FROM gestion__roles_permisos WHERE gp_idReg = :id";
									//-
									$asoc = array(':id'=>$data['data']['idReg']);									
									$campos = 'gp_idReg,gp_estado';
									$valores = ':id,:estado';									
									$i=0;
									foreach($partes as $campo){ 
										$sql_campos.= ' AND '.$estructura[$i].'=:'.str_replace('gp_','',$estructura[$i]);
										$campos.= ','.$estructura[$i];
										$valores.= ',:'.str_replace('gp_','',$estructura[$i]);
										$asoc[':'.str_replace('gp_','',$estructura[$i])] = $campo;										
										$i++;
									}
									//-									
									$sql.= $sql_campos." LIMIT 1";
									$existe = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
									$asoc[':estado'] = 1;									
									if(is_array($existe) && count($existe)>0) $sql = 'UPDATE gestion__roles_permisos SET gp_estado = :estado WHERE gp_idReg = :id'.$sql_campos.' LIMIT 1';
									else $sql = 'INSERT INTO gestion__roles_permisos ('.$campos.') VALUES ('.$valores.')';
									//-
									$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
								}
							}
						}
						break;
				}
				break;
			case 'add':
				if(is_array($data['data']) && count($data['data'])>0){
					$rol = $this->roles(array('where'=>'gr_tipo = :tipo','asoc'=>array(':tipo'=>$data['data']['rol']),'limit'=>1));					
					if(count($rol)<=0){					
						$sql = "INSERT INTO gestion__roles (gr__fechaRegistro,gr__usuarioRegistro,gr_tipo) VALUES (:gr__fechaRegistro,:gr__usuarioRegistro,:gr_tipo)";					
						$asoc = array(':gr__fechaRegistro'=>date('Y-m-d H:i:s'),':gr__usuarioRegistro'=>$_SESSION["usuarioActivo"]->usuario_Id,':gr_tipo'=>$data['data']['rol']);													
						$rol = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));					
						$rol = $this->roles(array('where'=>'gr_id = :id','asoc'=>array(':id'=>$rol),'limit'=>1));
						$estructura = $this->roles(array('action'=>'permisos','idReg'=>$rol[0]['idReg']));						
						$salida = array('id'=>$rol[0]['id'],'idReg'=>$rol[0]['idReg'],'rol'=>$rol[0]['rol'],'permisos-estructura'=>$estructura);						
					}
				}else $salida = false;
				break;
			case 'update':
				if(is_array($data['data']) && count($data['data'])>0){		
			
					$sql = "UPDATE gestion__roles SET gr__usuarioModificacion = :gr__usuarioModificacion , gr_tipo = :gr_tipo WHERE MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,'%Y%m%d'),gr_id)) = :id LIMIT 1";
					$asoc = array('gr__usuarioModificacion'=>$_SESSION["usuarioActivo"]->usuario_Id,':gr_tipo'=>$data['data']['rol'],':id'=>$data['data']['idReg']);
					$rol = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));
					$rol = $this->roles(array('where'=>"MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,'%Y%m%d'),gr_id)) = :id",'asoc'=>array(':id'=>$data['data']['idReg']),'limit'=>1));					
					//-
					$data['data']['action'] = 'save';
					$permisos = $this->roles(array('action'=>'permisos','data'=>$data['data']));					
					//-
					$estructura = $this->roles(array('action'=>'permisos','idReg'=>$rol[0]['idReg']));
					$salida = array('id'=>$rol[0]['id'],'idReg'=>$rol[0]['idReg'],'rol'=>$rol[0]['rol'],'permisos-estructura'=>$estructura);										
				}else{
					$rol = $this->roles(array('where'=>'MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,"%Y%m%d"),gr_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$estructura = $this->roles(array('action'=>'permisos','idReg'=>$rol[0]['idReg']));
					$salida = array('id'=>$rol[0]['id'],'idReg'=>$rol[0]['idReg'],'rol'=>$rol[0]['rol'],'permisos-estructura'=>$estructura);									
				}
				break;
			case 'del':
				if(is_array($data['data']) && count($data['data'])>0){			
					$sql = "DELETE FROM gestion__roles WHERE MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,'%Y%m%d'),gr_id)) = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['idReg']);				
					$rol = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));									
					$sql = "DELETE FROM gestion__roles_permisos WHERE gp_idReg = :id";
					$asoc = array(':id'=>$data['data']['idReg']);				
					$rol = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));														
					//-					
				}else{
					$rol = $this->roles(array('where'=>'MD5(CONCAT(DATE_FORMAT(gr__fechaRegistro,"%Y%m%d"),gr_id)) = :id','asoc'=>array(':id'=>$data['id']),'limit'=>1));
					$salida = array('id'=>$rol[0]['id'],'idReg'=>$rol[0]['idReg'],'rol'=>$rol[0]['rol']);				
				}
				break;	
			case 'estado':
					$sql = "UPDATE gestion__roles SET gr_estado = IF(gr_estado=0,1,0) WHERE gr_id = :id LIMIT 1";
					$asoc = array(':id'=>$data['data']['id']);
					$this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>true));							
				break;			
		}
		return $salida;
	}
	//-	
	function testInstall($data=false){
		if(!is_array($this->conMadre) && is_array($data) && count($data)>0) $this->conMadre=array('HOSTNAME'=>$data['hostname'],'USERNAME'=>$data['username'],'PASSWORD'=>$data['password'],'DATABASE'=>$data['database']);						
		switch($data['step']){
			default:								
				$components = array('gestion__apps','gestion__roles','gestion__usuarios','gestion__usuariosdatos');
				$tablesDB = $this->showTables($this->conMadre);				
				$headers = is_array($tablesDB) && count($tablesDB)>0 ? array_keys($tablesDB) : '';
				foreach($components as $item){
					if((is_array($headers) && !in_array($item,$headers)) || (!is_array($headers) && is_array($this->conMadre))){
						$this->createTable(array('table'=>$item));
						$newItems[] = $item;
					}
				}												
				$sql = 'SELECT * FROM gestion__usuarios WHERE gu_tipo < :gu_tipo AND gu_estado = :gu_estado';			
				$adm = $this->consultaSql(array('sql'=>$sql,'asoc'=>array(':gu_tipo'=>1,':gu_estado'=>1),'conMadre'=>$data,'debug'=>false));			
				$step = count($tablesDB)>1 && count($adm)<1 ? 1 : $x = count($adm)>0 ? 2 : 0;									
				return $exit = array('nitems'=>count($newItems),'items'=>$newItems,'step'=>$step);
				break;
			case 1:
				$sql = "INSERT INTO gestion__usuarios (gu__fechaRegistro,gu_usuario,gu_password,gu_email,gu_tipo,gu_estado) VALUES (:gu__fechaRegistro,:gu_usuario,:gu_password,:gu_email,:gu_tipo,:gu_estado)";					
				$asoc = array(':gu__fechaRegistro'=>date('Y-m-d H:i:s'),':gu_usuario'=>strtolower($data['username']),':gu_password'=>md5($data['password']),':gu_email'=>strtolower($data['email']),':gu_tipo'=>0,':gu_estado'=>1);																	
				$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));								
				return $exit = empty($usr) ? array('step'=>1) : array('step'=>2);			
				break;
			case 2:
				//- BUSCAR ACTUALIZACIONES
				break;
		}
	}
	//-
	function createTable($data){
		$sql = file_exists(HOMEDIR.'/install/sql/'.$data['table'].'.sql') ?  file_get_contents(HOMEDIR.'/install/sql/'.$data['table'].'.sql') : '';
		if(!empty($sql)) $salida = $this->consultaSql(array('sql'=>$sql,'conMadre'=>$this->conMadre,'debugar'=>false));		
	}
	//-	
}
//-
$SIRAgest = new SIRAgest();
?>