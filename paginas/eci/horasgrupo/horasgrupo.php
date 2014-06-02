<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkNew = $link.'&opcion=nuevo';
	//print_r($trabajadores);
?>
    <input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />            
    <input name="seccion" id="seccion" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['seccion'])?>" />                
    <input name="apartado" id="apartado" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['apartado'])?>" />                                
    <div class="row">
    	<div class="col-lg-12">
    		<ol class="breadcrumb">
    			<li><i class="<?=$seccionConfig['seccion_icono']?>"></i> <?=$seccionConfig['seccion']?></li>
    			<li class="active"><i class="<?=$seccionConfig['apartado_icono']?>"></i> <?=$seccionConfig['apartado']?></li>
    		</ol>
	    </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
<?
	if(is_array($trabajadores) && count($trabajadores)>0){
		foreach($trabajadores as $key=>$trabajador){
?>        
            <div class="panel panel-primary">
                <button type="button" class="btn panel-heading btn-block" data-toggle="collapse" data-target="#trabajador_<?=$key?>"><?=$trabajador[0]['trabajador']?></button>
                <div id="trabajador_<?=$key?>" class="panel-body collapse">
                    <div class="row">
	                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Entrada</th>  
                                    <th class="text-center">Gran Consumo</th>  
                                    <th class="text-center">Clasificador</th>                                      
                                    <th class="text-center">Entrada</th>                                                                                                   
                                    <th class="text-center">Salida</th>
                                </tr>
                            </thead>
		                	<tbody>
<?
			if(is_array($trabajador) && count($trabajador)>0){
				foreach($trabajador as $dia){
?>
                                <tr>
                                    <td class="text-center"><?=$dia['fecha']?></td>
                                    <td class="text-center"><?=$dia['entrada']?></td>
                                    <td class="text-center"><?=$dia['salida']?></td>    
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
			</div>
<?
		}
	}
?>            
        </div>    
    </div>
<?
}
?>