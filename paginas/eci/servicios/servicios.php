<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkNew = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="<?=$seccionConfig['seccion_icono']?>"></i> <?=$seccionConfig['seccion']?></li>
    			<li class="active"><i class="<?=$seccionConfig['apartado_icono']?>"></i> <?=$seccionConfig['apartado']?></li>
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($linkNew)?>"><button type="button" class="btn btn-success">Nuevo</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
        	<div class="table-responsive">
				<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />            
				<input name="seccion" id="seccion" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['seccion'])?>" />                
				<input name="apartado" id="apartado" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['apartado'])?>" />                                
                <table class="table table-bordered table-hover table-striped tablesorter">
                	<thead>
                		<tr>
                			<th>Estado <i class="fa fa-sort"></i></th>
               				<th>Fecha <i class="fa fa-sort"></i></th>
                			<th>Código <i class="fa fa-sort"></i></th>
                			<th>Servicio <i class="fa fa-sort"></i></th>
                			<th>Acciones <i class="fa fa-sort"></i></th>                                                        
                		</tr>
                	</thead>
                	<tbody>
<?
	if(is_array($servicios) && count($servicios)>0){
		foreach($servicios as $servicio){
			$checked = !empty($servicio['estado']) ? ' checked' : '';
			$linkEdit = $link.'&opcion=editar&id='.$servicio['idReg'];
			$linkDel = $link.'&opcion=eliminar&id='.$servicio['idReg'];			
?>
                        <tr>
                        	<td class="text-center"><input id="estado[]" name="estado[]" type="checkbox" value="<?=$servicio['id']?>"<?=$checked?>></td>
                        	<td class="text-center"><?=$servicio['fecha']?></td>
                        	<td class="text-center"><?=$servicio['id']?></td>
                        	<td class="text-left"><?=$servicio['servicio']?></td>
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