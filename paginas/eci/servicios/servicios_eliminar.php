<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkForm = $link.'&opcion=eliminar';
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
            <div class="panel panel-default">
                <div class="panel-heading"><? if(!empty($servicio)){ ?>¿ Estas seguro que deseas eliminar el servicio <strong><?=ucfirst($rol)?></strong> ?<? }else{ ?>Servicio borrado correctamente.<? } ?></div>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-lg-6">
                        <? if(!empty($servicio)){ ?>
                        <form action="<?=$SIRAgest->urlAmigas($linkForm)?>" method="post" enctype="multipart/form-data" role="form">
							<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />
							<input name="idReg" id="idReg" type="hidden" value="<?=$idReg?>" />                            
							<div class="form-group text-right"><button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Sí, elminiar</button></div>                            
						</form>
                        <? }else{ ?>
                        <a href="<?=$SIRAgest->urlAmigas($link)?>"><button type="button" class="btn btn-success">Volver</button></a>
                        <? } ?>                        
                        </div>                         
					</div>
                </div>
            </div>            
        </div>        
    </div>    
<?
}
?>