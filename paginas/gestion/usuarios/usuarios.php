<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=usuarios';
	$linkNew = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li class="active"><i class="fa fa-user"></i> Usuarios</li>
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($linkNew)?>"><button type="button" class="btn btn-success">Nuevo</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
        	<div class="table-responsive">
				<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />            
				<input name="seccion" id="seccion" type="hidden" value="<?=strtolower($_GET['seccion'])?>" />                
				<input name="apartado" id="apartado" type="hidden" value="<?=strtolower($_GET['apartado'])?>" />                                
                <table class="table table-bordered table-hover table-striped tablesorter">
                	<thead>
                		<tr>
                			<th>Estado <i class="fa fa-sort"></i></th>
               				<th>Fecha <i class="fa fa-sort"></i></th>
                			<th>Usuario <i class="fa fa-sort"></i></th>
                			<th>Nombre <i class="fa fa-sort"></i></th>                            
                			<th>Tipo <i class="fa fa-sort"></i></th>                            
                			<th>Acciones <i class="fa fa-sort"></i></th>                                                        
                		</tr>
                	</thead>
                	<tbody>
<?
	if(is_array($users['usuarios']) && count($users['usuarios'])>0){
		foreach($users['usuarios'] as $user){
			$checked = !empty($user['estado']) ? ' checked' : '';
			$linkEdit = $link.'&opcion=editar&id='.$user['idReg'];
			$linkDel = $link.'&opcion=eliminar&id='.$user['idReg'];			
?>
                        <tr>
                        	<td class="text-center"><input id="estado[]" name="estado[]" type="checkbox" value="<?=$user['id']?>"<?=$checked?>></td>
                        	<td class="text-center"><?=$user['fecha']?></td>
                        	<td class="text-left"><?=$user['usuario']?></td>
                        	<td><?=$user['nombre']?></td>                            
                        	<td class="text-left"><?=$user['tipo']?></td>                            
                        	<td class="text-center"><a href="<?=$SIRAgest->urlAmigas($linkEdit)?>"><button type="button" class="btn btn-warning"><i class="fa fa-edit"></i></button></a>&nbsp;&nbsp;<a href="<?=$SIRAgest->urlAmigas($linkDel)?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td>                            
                        </tr>
<?			
		}
	}
?>                    
					</tbody>
				</table>
        	</div>
        </div>    
    </div>
<?
}
?>