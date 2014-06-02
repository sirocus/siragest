<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=apps';
	$linkNew = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-12">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li class="active"><i class="fa fa-code-fork"></i> Apps</li>
    		</ol>
	    </div>
    	<? /* ?><div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($linkNew)?>"><button type="button" class="btn btn-success">Nuevo</button></a></div><? */ ?>
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
                			<th>App <i class="fa fa-sort"></i></th>
                			<th>Titulo <i class="fa fa-sort"></i></th>                            
                			<? /* ?><th>Acciones <i class="fa fa-sort"></i></th><? */ ?>
                		</tr>
                	</thead>
                	<tbody>
<?
	if(is_array($apps) && count($apps)>0){
		foreach($apps as $app){
			$checked = !empty($app['estado']) ? ' checked' : '';
			$id = md5(str_replace('-','',$app['fecha']).$app['id']);
			//$linkEdit = $link.'&opcion=editar&id='.$id;
			//$linkDel = $link.'&opcion=eliminar&id='.$id;			
?>
                        <tr>
                        	<td class="text-center"><input id="estado[]" name="estado[]" type="checkbox" value="<?=$role['id']?>"<?=$checked?> disabled></td>
                        	<td class="text-center"><?=$app['fecha']?></td>
                        	<td class="text-center"><?=$app['id']?></td>
                        	<td class="text-left"><?=$app['app']?></td>
                        	<td class="text-left"><?=$app['titulo']?></td>                            
                        	<? /* ?><td class="text-center"><a href="<?=$SIRAgest->urlAmigas($linkEdit)?>"><button type="button" class="btn btn-warning"><i class="fa fa-edit"></i></button></a>&nbsp;&nbsp;<a href="<?=$SIRAgest->urlAmigas($linkDel)?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td><? */ ?>
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