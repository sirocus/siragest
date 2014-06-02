<?
	$ajax = true;
	include("../includes/config.php");
	switch($_POST['accion']){
		case 'estado':
			switch($_POST['seccion']){
				case 'gestion':
					switch($_POST['apartado']){				
						case 'usuarios':
							$SIRAgest->usuarios(array('action'=>'estado','data'=>$_POST));				
							break;					
						case 'roles':
							$SIRAgest->roles(array('action'=>'estado','data'=>$_POST));											
							break;
					}
					break;						
			}
			break;
	}
?>