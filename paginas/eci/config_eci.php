<?
	$javascripts.= '<script src="'.URL.'paginas/eci/js/general.js"></script>'."\n";
	$javascripts.= '<script src="'.URL.'js/dataTables/jquery.dataTables.js"></script>'."\n";
	$javascripts.= '<script src="'.URL.'js/dataTables/dataTables.bootstrap.js"></script>'."\n";	
	$css.= '<link rel="stylesheet" href="'.URL.'js/dataTables/dataTables.bootstrap.css" type="text/css" media="screen" />';		
	/*
	$javascripts.= '<script type="text/javascript" src="'.URL.'paginas/eci/js/REDIPS_drag/header.js"></script>'."\n";
	$javascripts.= '<script type="text/javascript" src="'.URL.'paginas/eci/js/REDIPS_drag/redips-drag-min.js"></script>'."\n";
	$javascripts.= '<script type="text/javascript" src="'.URL.'paginas/eci/js/REDIPS_drag/redips-table-min.js"></script>'."\n";
	$javascripts.= '<script type="text/javascript" src="'.URL.'paginas/eci/js/REDIPS_drag/script.js"></script>'."\n";
	$css.= '<link rel="stylesheet" href="'.URL.'paginas/eci/js/REDIPS_drag/style.css" type="text/css" media="screen" />';
	*/
	
	require_once('paginas/eci/class.eci.php');
	$ECI = new eci();
	switch($apartado){		
		case 'servicios':			
			switch($opcion){
				default:								
					$servicios = $ECI->servicios();
					$pagInclude = $seccionConfig['archivo'];				
					break;
				case 'nuevo':	
				case 'editar':		
					$action = strtolower($_GET['opcion'])=='nuevo' ? $x = empty($_POST['idReg']) ? 'add' : 'update' : 'update';
					$servicios = $ECI->servicios(array('action'=>$action,'data'=>$_POST,'id'=>$_GET['id']));
					$idReg = $servicios['idReg'];
					$servicio = $servicios['servicio'];
					$pagInclude = $seccionConfig['archivo'];									
					break;
				case 'eliminar':	
					$servicios = $ECI->servicios(array('action'=>'del','data'=>$_POST,'id'=>$_GET['id'],'debug'=>true));
					$idReg = $servicios['idReg'];
					$servicio = $servicios['servicio'];
					$pagInclude = $seccionConfig['archivo'];													
					break;														
			}
			break;
		//--
		case 'subservicios':			
			switch($opcion){
				default:								
					$subservicios = $ECI->subservicios();
					$pagInclude = $seccionConfig['archivo'];				
					break;
				case 'nuevo':	
				case 'editar':		
					$action = strtolower($_GET['opcion'])=='nuevo' ? $x = empty($_POST['idReg']) ? 'add' : 'update' : 'update';
					$subservicios = $ECI->subservicios(array('action'=>$action,'data'=>$_POST,'id'=>$_GET['id']));
					$idReg = $subservicios['subservicios']['idReg'];
					$servicio = $subservicios['subservicios']['servicio'];
					$subservicio = $subservicios['subservicios']['subservicio'];					
					$pagInclude = $seccionConfig['archivo'];									
					break;
				case 'eliminar':	
					$subservicios = $ECI->subservicios(array('action'=>'del','data'=>$_POST,'id'=>$_GET['id'],'debug'=>false));
					$idReg = $subservicios['subservicios']['idReg'];
					$subservicio = $subservicios['subservicios']['subservicio'];
					$pagInclude = $seccionConfig['archivo'];													
					break;														
			}
			break;
		//--
		case 'asignaciones':		
			switch($opcion){
				default:								
					$asignaciones = $ECI->asignaciones_naveG();									
					$pagInclude = $seccionConfig['archivo'];				
					break;											
			}
			break;
		//--
		case 'horas-x-servicio-persona':		
			switch($opcion){
				default:								
					$horas = $ECI->horaspersona_naveG();																		
					$pagInclude = $seccionConfig['archivo'];				
					break;											
			}
			break;			

		//--
		case 'horas-x-servicio-grupo':		
			switch($opcion){
				default:			
					$horas = $ECI->trabajadores_naveG();																											
					$pagInclude = $seccionConfig['archivo'];				
					break;											
			}
			break;												
		//--
		/*
		case 'listado-por-dia':		
			switch($opcion){
				default:								
					$clasificacion = $ECI->clasificacion_naveG();									
					$pagInclude = $seccionConfig['archivo'];				
					break;											
			}
			break;
		*/			
	}			
?>