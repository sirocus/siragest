<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$appsu = str_replace('|',',',$_SESSION["usuarioActivo"]->usuario_Apps);
	$where = empty($_SESSION["usuarioActivo"]->usuario_Apps) ? 'ga_estado = :estado' : 'ga_estado = :estado AND ga_id IN('.$appsu.')';
	$apps = $SIRAgest->apps(array('where'=>$where,'asoc'=>array(':estado'=>1),'debug'=>false));
	//-- SECCION / APARTADO / OPCION
	$seccion = strtolower($_GET['seccion']);
	$apartado = strtolower($_GET['apartado']);
	$opcion = strtolower($_GET['opcion']);		
	//- PERMISOS
	//echo "tiene permisos para $seccion / $apartado / $opcion = ".$permiso;	
	$data['idReg'] = $_SESSION["usuarioActivo"]->usuario_Tipo_idReg;
	$data['campos'] = array('gp_seccion'=>$seccion,'gp_apartado'=>$apartado,'gp_opcion'=>$opcion);						
	$permiso = empty($_SESSION["usuarioActivo"]->usuario_Tipo) ? true : $SIRAgest->roles(array('action'=>'accesos','data'=>$data));													
	$seccion = empty($permiso) ? '' : $seccion;
	//- MENÚ -----------------------
    if(is_array($apps) && count($apps)>0){
		foreach($apps as $app){	
			$menuConfig = $SIRAgest->cargarConfig(array('seccion'=>$app['app'],'debug'=>false));
			$menu[$menuConfig['seccion']]['icono'] = $menuConfig['seccion_icono'];
			$menu[$menuConfig['seccion']]['selected'] = $seccion==$SIRAgest->urlAmigables($app['app']) ? true : false;		
			if(is_array($menuConfig['apartados']) && count($menuConfig['apartados'])>0){
				foreach($menuConfig['apartados'] as $nodo){
					$data['campos'] = array('gp_seccion'=>$SIRAgest->urlAmigables($app['app']),'gp_apartado'=>$SIRAgest->urlAmigables($nodo['nombre']));						
					$activado = empty($_SESSION["usuarioActivo"]->usuario_Tipo) ? true : $SIRAgest->roles(array('action'=>'accesos','data'=>$data));																		
					$selected = $seccion==$SIRAgest->urlAmigables($app['app']) && $apartado==$SIRAgest->urlAmigables($nodo['nombre']) ? true : false;
					$url = 'index.php?seccion='.$SIRAgest->urlAmigables($app['app']).'&apartado='.$SIRAgest->urlAmigables($nodo['nombre']);					
					$menu[$menuConfig['seccion']]['apartados'][$nodo['nombre']] = array('activo'=>$activado,'icono'=>$nodo['icono'],'url'=>$SIRAgest->urlAmigas($url),'selected'=>$selected);
					if(is_array($nodo['operaciones']) && count($nodo['operaciones'])>0){
						foreach($nodo['operaciones'] as $operacion){	
							$data['campos'] = array('gp_seccion'=>$SIRAgest->urlAmigables($app['app']),'gp_apartado'=>$SIRAgest->urlAmigables($nodo['nombre']),'gp_opcion'=>$SIRAgest->urlAmigables($operacion['nombre']));						
							$activado = empty($_SESSION["usuarioActivo"]->usuario_Tipo) ? true : $SIRAgest->roles(array('action'=>'accesos','data'=>$data));																		
							$selected = $seccion==$SIRAgest->urlAmigables($app['app']) && $apartado==$SIRAgest->urlAmigables($nodo['nombre']) && $opcion==$SIRAgest->urlAmigables($operacion['nombre']) ? true : false;						
							$url.= '&opcion='.$SIRAgest->urlAmigables($operacion['nombre']);
							$menu[$menuConfig['seccion']]['apartados'][$nodo['nombre']][$operacion['nombre']] = array('activo'=>$activado,'icono'=>$operacion['icono'],'url'=>$url);										
						}
					}		
				}
			}
		}
	}
	//print_r($menu);	
	//-	
	switch($seccion){
		default:
			if(!empty($permiso)) $pagInclude = 'paginas/portada.php';
			else $pagInclude = 'paginas/error.php';
			break;
		//-- GESTION ----------------------------------------------------------------------------------------------------------------
		case 'gestion':
			switch($apartado){
				case 'usuarios':			
					switch($opcion){
						default:	
							$users = $SIRAgest->usuarios(array('debug'=>$debug));
							$pagInclude = 'paginas/gestion/usuarios/usuarios.php';				
							break;
						case 'nuevo':	
						case 'editar':			
							$action = strtolower($_GET['opcion'])=='nuevo' ? $x = empty($_POST['idReg']) ? 'add' : 'update' : 'update';
														
							$users = $SIRAgest->usuarios(array('action'=>$action,'data'=>$_POST,'id'=>$_GET['id']));
							$idReg = !empty($users['usuarios']['idReg']) ? $users['usuarios']['idReg'] : '';
							$usuario = $users['usuarios']['usuario'];
							$password = $users['usuarios']['password'];				
							$rol = $users['usuarios']['rol'];
							$nombre = $users['usuarios']['nombre'];
							$usrapps = explode("|",$users['usuarios']['apps']);							

							$inputUsuario = empty($users['usuarios']['idReg']) ? ' required autofocus' : 'disabled';						
							$inputPassword = empty($users['usuarios']['idReg']) ? ' required' : ' required autofocus';													
							$pagInclude = 'paginas/gestion/usuarios/usuarios_nuevo.php';				
							break;
						case 'eliminar':			
							$users = $SIRAgest->usuarios(array('action'=>'del','data'=>$_POST,'id'=>$_GET['id'],'debug'=>false));
							$usuario = $users['usuarios']['usuario'];
							$idReg = $users['usuarios']['idReg'];							
							$pagInclude = 'paginas/gestion/usuarios/usuarios_eliminar.php';				
							break;														
					}
					break;
				//-					
				case 'apps':			
					switch($opcion){
						default:								
							$apps = $SIRAgest->apps();
							$pagInclude = 'paginas/gestion/apps/apps.php';				
							break;
						case 'nuevo':	
						case 'editar':			
							$action = strtolower($_GET['opcion'])=='nuevo' ? $x = empty($_POST['idReg']) ? 'add' : 'update' : 'update';
							$roles = $SIRAgest->roles(array('action'=>$action,'data'=>$_POST,'id'=>$_GET['id']));
							$idReg = $roles['id'];
							$rol = $roles['rol'];
							$pagInclude = 'paginas/gestion/roles/roles_nuevo.php';				
							break;
						case 'eliminar':			
							$roles = $SIRAgest->roles(array('action'=>'del','data'=>$_POST,'id'=>$_GET['id'],'debug'=>true));
							$idReg = md5($roles['fecha'].$roles['id']);
							$rol = $roles['rol'];
							$pagInclude = 'paginas/gestion/roles/roles_eliminar.php';				
							break;														
					}
					break;
				//-				
				case 'roles':			
					switch($opcion){
						default:								
							$roles = $SIRAgest->roles();
							$pagInclude = 'paginas/gestion/roles/roles.php';				
							break;
						case 'nuevo':	
						case 'editar':			
							$action = strtolower($_GET['opcion'])=='nuevo' ? $x = empty($_POST['idReg']) ? 'add' : 'update' : 'update';
							$roles = $SIRAgest->roles(array('action'=>$action,'data'=>$_POST,'id'=>$_GET['id']));
							$idReg = $roles['idReg'];
							$rol = $roles['rol'];
							$pagInclude = 'paginas/gestion/roles/roles_nuevo.php';				
							break;
						case 'eliminar':			
							$roles = $SIRAgest->roles(array('action'=>'del','data'=>$_POST,'id'=>$_GET['id'],'debug'=>true));
							$idReg = $roles['idReg'];
							$rol = $roles['rol'];
							$pagInclude = 'paginas/gestion/roles/roles_eliminar.php';				
							break;														
					}
					break;
			}
			break;
			//-- /GESTION ---------------------------------------------------------------------------------------------------------------
			//-- /ECI -------------------------------------------------------------------------------------------------------------------
			case 'eci':
				$seccionConfig = $SIRAgest->cargarConfig(array('seccion'=>$seccion,'apartado'=>$apartado,'opcion'=>$opcion,'debug'=>false));
				//echo "\n---\n"; print_r($seccionConfig); echo "\n---\n";																
				require_once($seccionConfig['config']);
				break;
			//-- /ECI -------------------------------------------------------------------------------------------------------------------
	}
	$pagInclude = empty($pagInclude) ? 'paginas/portada.php' : $pagInclude;
}
?>