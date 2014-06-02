<?
	$ajax = true;
	include("../../includes/config.php");
	include("class.eci.php");
	$ECI = new eci();
	//--
	switch($_POST['accion']){
		case 'estado':
			switch($_POST['seccion']){
				case 'eci':
					switch($_POST['apartado']){				
						case 'servicios':
							$ECI->servicios(array('action'=>'estado','data'=>$_POST));				
							break;					
						case 'subservicios':
							$ECI->subservicios(array('action'=>'estado','data'=>$_POST));											
							break;
					}
					break;						
			}
			break;
		case 'asignar':
			switch($_POST['proyecto']){
				case 'granconsumo':
				case 'clasificador':
				case 'salir':
					$granconsumo = $ECI->asignaciones_naveG(array('data'=>$_POST));				
					echo json_encode($granconsumo);
					break;						
			}
			break;			
	}
?>