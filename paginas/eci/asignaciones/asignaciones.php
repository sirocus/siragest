<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkNew = $link.'&opcion=nuevo';
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
                <table class="table table-bordered">
                	<thead>
                		<tr>
               				<th class="text-center thgranc">GRAN CONSUMO</th>
               				<th></th>                            
                			<th class="text-center info">CLASIFICADOR</th>
                		</tr>
                	</thead>
                	<tbody>
                        <tr>
                        	<td class="text-center">
                            <div id="granconsumo">
                            <ul>                            
                            <? 
							if(is_array($asignaciones['granconsumo']) && count($asignaciones['granconsumo'])>0){
                            	foreach($asignaciones['granconsumo'] as $granconsumo){
							?>
								
	                            <li id="granconsumo_<?=$granconsumo['id']?>"><button id="btngranc_<?=$granconsumo['id']?>" type="button" class="btn btn-primary btngranc"><?=ucwords($granconsumo['nombre'])?></button></li>
                            <? 
								} 
							}
							?>                            
                            </ul>                              
                            </div>
                            </td>
                        	<td class="text-center" valign="middle"><button id="envgc" type="button" class="btn btn-success" style="display:none;"><i class="fa fa-arrow-left"></i></button><button id="envcl" type="button" class="btn btn-success" style="display:none;"><i class="fa fa-arrow-right"></i></button><br /><br /><br /><br /><button id="salir" type="button" class="btn btn-danger" style="display:none;"><i class="fa fa-power-off"></i></button></td>
                        	<td class="text-center">
                            <div id="clasificador">
                            <ul>                            
                            <? 
							if(is_array($asignaciones['clasificador']) && count($asignaciones['clasificador'])>0){
                            	foreach($asignaciones['clasificador'] as $clasificador){ 
							?>
	                            <li id="clasificador_<?=$clasificador['id']?>"><button id="btnclasif_<?=$clasificador['id']?>" type="button" class="btn btn-info btnclasif"><?=ucwords($clasificador['nombre'])?></button></li>
                            <? 
								} 
							}
							?>
                            </ul>                              
                            </div>
                            </td>
                        </tr>                                    
					</tbody>
                	<thead>
                		<tr>
               				<th class="text-center success">NO HAN ENTRADO</th>
               				<th></th>                            
                			<th class="text-center danger">HAN SALIDO</th>
                		</tr>
                	</thead>
                	<tbody>
                        <tr>
                        	<td class="text-center">
                            <? if(is_array($asignaciones['entradas']) && count($asignaciones['entradas'])>0){ ?>
                            <ul>
                            <? foreach($asignaciones['entradas'] as $entrada){ ?>
	                            <li id="grupo_<?=$entrada['id']?>"><button id="gc_<?=$entrada['id']?>" type="button" class="btn btn-primary grancon" style="display:none;">Gran cons.</button> <button id="btn_<?=$entrada['id']?>" type="button" class="btn btn-success asignar"><?=ucwords($entrada['nombre'])?></button> <button id="cl_<?=$entrada['id']?>" type="button" class="btn btn-info clasif" style="display:none;">Clasificador</button></div></li>
                            <? } ?>                            
                            </ul>
                            <? } ?>
                            </td>
                        	<td class="text-center" valign="middle"></td>
                        	<td class="text-center">
                            <div id="divsalir">
                            <ul>                            
                            <? 
							if(is_array($asignaciones['salidas']) && count($asignaciones['salidas'])>0){
								foreach($asignaciones['salidas'] as $salida){ ?>
	                            <li><button type="button" class="btn btn-danger disabled"><?=ucwords($salida['nombre'])?></button></li>
                            <? 
								} 
							}
							?>                            
                            </ul>                            
                            </div>
                            </td>
                        </tr>                                    
					</tbody>                    
				</table>
        	</div>
        </div>    
    </div>
<?
}
?>