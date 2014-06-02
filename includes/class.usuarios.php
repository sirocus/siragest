<?
/*
	File name   : class.usuarios.php
	Version     : 1.0
	Begin       : 2014-05-15
	Last Update : 2014-05-15
	Author      : Marc Torrente Cesteros (Mark Sirocus)

	License
	===============================================================================================================
	This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
	To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/.	

	Esta obra está licenciada bajo la Licencia Creative Commons Atribución-NoComercial-CompartirIgual 3.0 Unported. 
	Para ver una copia de esta licencia, visita http://creativecommons.org/licenses/by-nc-sa/3.0/.
	===============================================================================================================		
*/
class usuarios extends SIRAgest{
	//public $acciones;
	var $acciones;
	var $usuarioLogin;
	var $usuario_Id;
	var $usuario_Admin;
	var $usuario_Password;
	var $usuario_Nombre;	
	var $usuario_Email;
	var $usuario_Tipo_Id;
	var $usuario_Tipo_idReg;
	var $usuario_Tipo;
	var $usuario_Estado;
	var $usuario_Apps;
	var $conMadre;
	//-- Constructor. Inicializa las variables a 0
	function usuarios(){
		$this->usuarioLogin=0;
		$this->usuario_Id=0;
		$this->usuario_Admin='';
		$this->usuario_Password='';
		$this->usuario_Email='';		
		$this->usuario_Tipo_Id=0;
		$this->usuario_Tipo_idReg='';		
		$this->usuario_Tipo=0;
		$this->usuario_Tiponame='';
		$this->usuario_Estado=0;
		$this->usuario_Apps='';		
		parent::SIRAgest();				
	}
	//-- Login usuarios
	function comprobarUsuario($adminSend,$pswdSend){	
		if(!$adminSend && !$pswdSend){
			$administrador = $this->usuario_Admin;
			$pswd = $this->usuario_Password;
		}else{
			unset($this->usuario_Admin);
			unset($this->usuario_Password);
				
			$administrador = strtolower($adminSend);
			$pswd = md5($pswdSend);			
			$this->usuario_Admin = $administrador;
			$this->usuario_Password = $pswd;
		}
		//--	
		if(!$administrador || !$pswd) $loginError = true;	
		$where = 'gu_usuario = :gu_usuario AND gu_password = :gu_password AND gu_estado = :gu_estado';
		$asoc = array(':gu_usuario'=>$administrador,':gu_password'=>$pswd,':gu_estado'=>1);
		$existe = parent::usuarios(array('where'=>$where,'asoc'=>$asoc,'limit'=>1,'debug'=>false));					
		$existe = $existe['usuarios'];
		if(is_array($existe) && count($existe) > 0){			
			$this->usuario_Id = $existe[0]['id'];
			$this->usuario_Admin = $existe[0]['usuario'];
			$this->usuario_Password = $existe[0]['password'];
			$this->usuario_Tipo_Id = $existe[0]['tipoid'];			
			$this->usuario_Tipo_idReg = $existe[0]['tipoidReg'];						
			$this->usuario_Tipo = $existe[0]['tipo'];
			$this->usuario_Estado = $existe[0]['estado'];
			$this->usuario_Nombre = $existe[0]['nombre'];					
			$this->usuario_Email = $existe[0]['email'];		
			$this->usuario_Apps = $existe[0]['apps'];					
			//--
			$sql = "UPDATE gestion__usuarios SET gu__ultimaIp = :ip WHERE MD5(CONCAT(DATE_FORMAT(gu__fechaRegistro,'%Y%m%d'),gu_id)) = :id LIMIT 1";
			$asoc = array(':ip'=>getRealIP(),':id'=>$existe['idReg']);
			$usr = $this->consultaSql(array('sql'=>$sql,'asoc'=>$asoc,'conMadre'=>'','debug'=>false));						
			$this->usuarioLogin=true;
			$loginError = false;
		}else{
			$this->usuarioLogin=false;			
			$loginError = true;
			unset($this->usuario_Admin);
			unset($this->usuario_Password);			
		}	
		return $loginError;
	}	
	//-- Información usuarios
	/*-- Ejemplo de uso
		==================================================================================================================================
		$datos[] = array("campo"=>'gud_nombre',"valor"=>'MARCO',"tipo"=>'txt',"tabla"=>'gestion__usuariosDatos',"compara"=>'gud_usuario');
		$datos[] = array("campo"=>'gud_cp',"valor"=>'08018',"tipo"=>'txt',"tabla"=>'gestion__usuariosDatos',"compara"=>'gud_usuario');		
		$usuarioDatos = $_SESSION["usuarioActivo"][0]->datosUsuario($_SESSION["usuarioActivo"][0]->usuario_Id,false,'update',$datos);*/	
	function datosUsuario($id,$md5=false,$action='vw',$datos=false){			
		if($md5 == true){
			$idUsuario = consultaSql("SELECT * FROM gestion__usuarios WHERE MD5(gu_id)='".$id."' LIMIT 1","",DEBUG);
			$id = $idUsuario[0]['gu_id'];
		}
		if($action=='vw'){
			$sql = "SELECT * FROM gestion__usuarios,gestion__usuariosDatos WHERE gu_id=".$id." AND gu_estado=1 AND gu_id=gud_usuario LIMIT 1";
			$datosUsuario = consultaSql($sql,"",DEBUG);	
			foreach(array_keys($datosUsuario[0]) as $campos){
				if(strripos($campos, "__")==''){
					$usuario[$campos]=$datosUsuario[0][$campos];
				}
			}
		}elseif($action=='update' || $action=='add'){	
			foreach($datos as $campos){	
				if(strlen(array_search($campos['campo'],$arrayNum))==0){ $arrayNum[]=$campos['campo']; }
				if(strlen(array_search($campos['tabla'],$tablas))==0){ $tablas[]=$campos['tabla']; }											
				if(strlen(array_search($campos['compara'],$comp))==0){ $comp[]=$campos['compara']; }															
				$idValor = array_search($campos['tabla'],$tablas);
				switch($campos['tipo']){
					default:
					case 'txt':
						$campos['valor'] = strtolower($campos['valor']);
						break;
					case 'pass':
						$campos['valor'] = md5($campos['valor']);
						break;												
				}								
				$valores[$idValor].= (strlen($valores[$idValor])>0) ? ",".$campos['campo'].":'".$campos['valor']."'" : $campos['campo'].":'".$campos['valor']."'";				
			}			
			$tabla=0;
			foreach($valores as $campos){
				$update = accionesNUD($tablas[$tabla],$campos,$action,false,$comp[$tabla].":'".$id."'",DEBUG);				
				$tabla+=1;
			}						
		}
		return $usuario;
	}
	//--
	function tipoUsuario($tipoUsuario){
		$sql = consultaSql("SELECT * FROM gestion__tipoUsuarios ORDER BY gt_id ASC","",DEBUG);
		$search[]=0;
		$replace[]='GOD';
		if(is_array($sql) && count($sql)>0){
			foreach($sql as $item){
				$search[]=$item['gt_id'];
				$replace[]=strtoupper($item['gt_tipo']);
			}
		}
		//$search  = array('0', '1', '2');
		//$replace = array('SADMIN', 'ADMIN', 'USER');
		return str_replace($search, $replace, $tipoUsuario);		
	}	
	//--
	function recordarPass($usuario){
		$sql = consultaSql("SELECT gu_id AS id, gu_email AS email FROM gestion__usuarios WHERE LOWER(gu_usuario)='".$usuario."' LIMIT 1","",DEBUG);
		if(is_array($sql) && count($sql)>0){
			$nuevaClave = randomPassword(8);
			$guardarClave = accionesNUD("gestion__usuarios","gu_password:'".md5($nuevaClave)."'","update",false,"gu_id:'".$sql[0]['id']."'",DEBUG);
			if(file_exists("paginas/plantillas/gestion_nuevaClave.php")){
				$plantilla = file_get_contents("paginas/plantillas/gestion_nuevaClave.php");
				$busca  = array('[logo]','[nuevaclave]','[url]','[urlmodificar]');
				$remplaza = array('<img src="'.URL.'img/logo.png" width="140">',$nuevaClave,'<a href="'.URL.'">'.URL.'</a>','<a href="'.URL."index.php?seccion=clavenueva&token=".md5(strtolower($sql[0]['id'].$sql[0]['email'])).'">'.URL."index.php?seccion=clavenueva&token=".md5(strtolower($sql[0]['id'].$sql[0]['email'])).'</a>');
				$plantilla = str_replace($busca,$remplaza,$plantilla);				
			}else{
				$plantilla = 'Este email ha sido generado automáticamente, por favor no responder.'."<br><br>";
				$plantilla.= 'Te hemos asignado esta nueva clave '.$nuevaClave.'. Puedes acceder con ella junto con tu usuario desde:'."<br><br>";
				$plantilla.= URL."<br><br>";				
				$plantilla.= 'Si prefieres modificar la clave puedes hacerlo desde el siguiente enlace:'."<br><br>";
				$plantilla.= URL."index.php?seccion=clavenueva&token=".md5(strtolower($sql[0]['id'].$sql[0]['email']));
			}			
			
			$from="".$_SERVER['HTTP_HOST'];
			$to = $sql[0]['email'];
			$subject = "No-responder: Recordatorio de contraseña ".URL;			
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
			$headers .= "From: ".$_SERVER['HTTP_HOST']." <".$_SERVER['HTTP_HOST'].">\r\n"; 
			$headers .= "Reply-To: info@".str_replace("www.","",$_SERVER['HTTP_HOST'])."\r\n"; 
			return mail($to,$subject,$plantilla,$headers);			
		}
	}
	function guardarClave($token,$nuevaClave){
		$sql = consultaSql("SELECT gu_id AS id FROM gestion__usuarios WHERE MD5(LOWER(CONCAT(gu_id,gu_email)))='".$token."' LIMIT 1","",DEBUG);
		if(is_array($sql) && count($sql)>0){
			$guardarClave = accionesNUD("gestion__usuarios","gu_password:'".md5($nuevaClave)."'","update",false,"gu_id:'".$sql[0]['id']."'",DEBUG);			
		}
	}
	//--
	function crearHtpasswd($user,$pwd,$filepath,$action=0){
		$password = crypt($pwd, base64_encode($pwd));
		if(file_exists($filepath)){
			/*system:IyiDTtrB.nfUQ
			41:1$Opu1jkLwS1I*/
			$contenido = file_get_contents($filepath);			
			$fp= fopen($filepath, "w+");
			$contenido = explode("\n",$contenido);
			$repetido=false;
			foreach($contenido as $items){
				$partes = explode(":",$items);	
				if(is_array($partes) && count($partes)>0){							
					if($partes[0]==$user && (!empty($action) && $action=='del') ){
					}else{
						if(!empty($partes[0]) && !empty($partes[1]) && $partes[0]!=$user){
							fwrite($fp, PHP_EOL.$partes[0].":".$partes[1]);					
							$repetido==true;
						}
					}
				}
			}
			if(empty($action) && $repetido==false){			
				fwrite($fp, PHP_EOL.$user.":".$password);
			}
			fclose($fp);			
		}
	}			
}
?>
