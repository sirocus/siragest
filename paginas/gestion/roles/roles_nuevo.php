<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=roles';
	$linkForm = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li><i class="fa fa-group"></i> Roles</li>
    			<li class="active"><i class="fa fa-plus-square"></i> Nuevo</li>                
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($link)?>"><button type="button" class="btn btn-success">Volver</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Nuevo rol</div>
                <div class="panel-body">
                    <div class="row">
                    <form action="<?=$SIRAgest->urlAmigas($linkForm)?>" method="post" enctype="multipart/form-data" role="form">
                        <input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />
                        <input name="idReg" id="idReg" type="hidden" value="<?=$idReg?>" />                                                
                    	<div class="col-lg-6">
                    		<div class="form-group"><label>Rol</label><input id="rol" name="rol" class="form-control" value="<?=$rol?>" placeholder="Nombre rol" required autofocus></div>
                        </div> 
                    	<div class="col-lg-6">
                    		<div class="form-group"><label>Permisos</label></div>
                            <?
							//print_r($roles['permisos-estructura']);
							if(is_array($roles['permisos-estructura']) && count($roles['permisos-estructura'])>0){
								foreach($roles['permisos-estructura'] as $item){
									$checked = !empty($item['estado']) ? ' checked' : '';
									switch($item){
										case empty($item['opcion']) && empty($item['apartado']):
							?>
<div class="checkbox"><label><input id="permisos[]" name="permisos[]" type="checkbox" value="<?=$item['seccion']?>"<?=$checked?>><?=$item['seccion']?></label></div>                            
                            <?
											break;
										case empty($item['opcion']):
							?>
<div style="padding-left:20px;"><div class="checkbox"><label><input id="permisos[]" name="permisos[]"  type="checkbox" value="<?=$item['seccion']?>|<?=$item['apartado']?>"<?=$checked?>><?=$item['apartado']?></label></div></div>                            
                            <?
											break;
										default:
							?>
<div style="padding-left:40px;"><div class="checkbox"><label><input id="permisos[]" name="permisos[]"  type="checkbox" value="<?=$item['seccion']?>|<?=$item['apartado']?>|<?=$item['opcion']?>"<?=$checked?>><?=$item['opcion']?></label></div></div>
                            <?
											break;											
									}
								}
							}
                            ?>
<div class="form-group text-right"><button type="submit" class="btn btn-default">Guardar</button></div>                            
                        </div>                                                 
                    </form>                        
					</div>
                </div>
            </div>            
        </div>        
    </div>    
<?
}
?>