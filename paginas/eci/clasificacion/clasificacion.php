<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkNew = $link.'&opcion=nuevo';
	//print_r($clasificacion);
?>
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
        	<div class="table-responsive">
				<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />            
				<input name="seccion" id="seccion" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['seccion'])?>" />                
				<input name="apartado" id="apartado" type="hidden" value="<?=$SIRAgest->urlAmigables($seccionConfig['apartado'])?>" />                                
                <table class="table table-bordered table-hover table-striped">
                	<thead>
                		<tr>
               				<th class="text-center">HORARIO</th>
               				<th class="text-center">GRAN CONSUMO</th>                           
                			<th class="text-center">CLASIFICADOR</th>
                		</tr>
                	</thead>
<?
	if(is_array($clasificacion) && count($clasificacion)>0){
?>                    
                	<tbody>
<?
		foreach($clasificacion as $label){
?>                       
                        <tr>
                        	<td class="text-center"><?=$label['horario']?></td>
                        	<td class="text-center"><?=$label['granconsumo']?></td>
                        	<td class="text-center"><?=$label['clasificador']?></td>    
                        </tr>                                    
<?
		}
?>                        
					</tbody>                  
<?
	}
?>                    
				</table>
        	</div>
        </div>    
    </div>
<?
}
?>