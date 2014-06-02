<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=usuarios';
	$linkForm = $link.'&opcion=eliminar';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li><i class="fa fa-user"></i> Usuarios</li>
    			<li class="active"><i class="fa fa-trash-o"></i> Eliminar</li>                
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($link)?>"><button type="button" class="btn btn-success">Volver</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><? if(!empty($usuario)){ ?>¿ Estas seguro que deseas eliminar el usuario <strong><?=ucfirst($usuario)?></strong> ?<? }else{ ?>Usuario borrado correctamente.<? } ?></div>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-lg-6">
                        <? if(!empty($usuario)){ ?>
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