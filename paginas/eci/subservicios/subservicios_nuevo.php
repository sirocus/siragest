<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkForm = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="<?=$seccionConfig['seccion_icono']?>"></i> <?=$seccionConfig['seccion']?></li>
    			<li><i class="<?=$seccionConfig['apartado_icono']?>"></i> <?=$seccionConfig['apartado']?></li>
    			<li class="active"><i class="<?=$seccionConfig['opcion_icono']?>"></i> <?=$seccionConfig['opcion']?></li>                
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($link)?>"><button type="button" class="btn btn-success">Volver</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Nuevo servicio</div>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-lg-6">
                        <form action="<?=$SIRAgest->urlAmigas($linkForm)?>" method="post" enctype="multipart/form-data" role="form">
							<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />
							<input name="idReg" id="idReg" type="hidden" value="<?=$idReg?>" />                            
                    		<div class="form-group">
                            	<label>Servicio</label>
								<select id="servicio" name="servicio" class="form-control">
                                <? 
                                    if(is_array($subservicios['servicios']) && count($subservicios['servicios'])>0){ 
                                        foreach($subservicios['servicios'] as $service){
                                            $selected = $service['id']==$servicio ? ' selected' : '';
                                ?>
                                    <option value="<?=$service['id']?>"<?=$selected?>><?=$service['servicio']?></option>                            
                                <?
                                        }
                                    }
                                ?>
                                </select>                                
                            </div>
                    		<div class="form-group"><label>Subservicio</label><input id="subservicio" name="subservicio" class="form-control" value="<?=$subservicio?>" placeholder="Nombre subservicio" required autofocus></div>                            
							<div class="form-group text-right">
							<?
								if(is_array($subservicios['servicios']) && count($subservicios['servicios'])>0){ 
							?>                        
							<div class="form-group text-right"><button type="submit" class="btn btn-default">Guardar</button></div>                            
							<?
								}else{
							?>
                            <div class="alert alert-danger">No existen servicios, para poder crear un usuario debes crear antes los <a href="<?=$SIRAgest->urlAmigas(URL.'index.php?seccion=eci&apartado=servicios')?>" class="alert-link"><strong>Servicios</strong></a>.</div>                                                        
                            <?
								}
                            ?>                            
                            </div>                            
						</form>
                        </div>
					</div>
                </div>
            </div>            
        </div>        
    </div>    
<?
}
?>