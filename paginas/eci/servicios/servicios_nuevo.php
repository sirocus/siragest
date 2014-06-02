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
                    		<div class="form-group"><label>Servicio</label><input id="servicio" name="servicio" class="form-control" value="<?=$servicio?>" placeholder="Nombre servicio" required autofocus></div>
							<div class="form-group text-right"><button type="submit" class="btn btn-default">Guardar</button></div>                            
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