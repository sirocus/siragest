<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=roles';
	$linkNew = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li class="active"><i class="fa fa-group"></i> Roles</li>
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
                			<th>Código <i class="fa fa-sort"></i></th>
                			<th>Rol <i class="fa fa-sort"></i></th>
                			<th>Acciones <i class="fa fa-sort"></i></th>                                                        
                		</tr>
                	</thead>
                	<tbody>
<?
	if(is_array($roles) && count($roles)>0){
		foreach($roles as $role){
			$checked = !empty($role['estado']) ? ' checked' : '';
			$linkEdit = $link.'&opcion=editar&id='.$role['idReg'];
			$linkDel = $link.'&opcion=eliminar&id='.$role['idReg'];			
?>
                        <tr>
                        	<td class="text-center"><input id="estado[]" name="estado[]" type="checkbox" value="<?=$role['id']?>"<?=$checked?>></td>
                        	<td class="text-center"><?=$role['fecha']?></td>
                        	<td class="text-center"><?=$role['id']?></td>
                        	<td class="text-left"><?=$role['rol']?></td>
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