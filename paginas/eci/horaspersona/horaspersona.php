<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion='.$SIRAgest->urlAmigables($seccionConfig['seccion']).'&apartado='.$SIRAgest->urlAmigables($seccionConfig['apartado']);
	$linkNew = $link.'&opcion=nuevo';
	//print_r($horas);
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
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Listado de horas x servício y persona</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Trabajador</th>
                                    <th>Entrada</th>
                                    <th>Gran consumo</th>
                                    <th>Clasificador</th>
                                    <th>Salida</th>
                                    <th>Total horas</th>                                                                        
                                </tr>
                            </thead>
<? if(is_array($horas) && count($horas)>0){ ?>                            
                            <tbody>
	<? 
	foreach($horas as $fecha=>$datos){ 
		if($fecha!='totales' && is_array($datos) && count($datos)>0){	
			foreach($datos as $trabajador){		
	?>   
                                <tr class="odd gradeX">
                                    <td class="text-center"><?=$fecha?></td>
                                    <td><?=$trabajador['nombre']?></td>
                                    <td class="text-center"><?=$trabajador['entrada']?></td>
                                    <td class="text-center"><?=$trabajador['granconsumo']?></td>
                                    <td class="text-center"><?=$trabajador['clasificador']?></td>
                                    <td class="text-center"><?=$trabajador['salida']?></td>
                                    <td class="text-center"><?=$trabajador['horas']?></td>                                                                        
                                </tr>
	<? 
			}
		}
	}
	?>    
                            </tbody>
<? } ?>                        
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center"><?=$horas['totales']['granconsumo']?></th>
                                    <th class="text-center"><?=$horas['totales']['clasificador']?></th>
                                    <th></th>
                                    <th class="text-center"><?=$horas['totales']['horas']?></th>                                                                        
                                </tr>
                            </thead>                                    
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->    
<?
}
?>