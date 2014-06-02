<?
	function consultaBBDD($campos,$tabla=0,$inner=0,$filtro=0,$order=0,$group=0,$limit=0,$conMadre=false,$debugar=false){
		$camposPartes = explode(",",$campos);	
		$sql = (empty($inner)) ? "SELECT ".$campos." FROM ".$tabla : "SELECT ".$campos." FROM ".$tabla.$inner;		
		$sql.= (!empty($filtro)) ? " WHERE ".$filtro : "";
		$sql.= (!empty($group)) ? " GROUP BY ".$group : "";		
		$sql.= (!empty($order)) ? " ORDER BY ".$order : "";
		$sql.= (!empty($limit)) ? " LIMIT ".$limit : "";		
		$datos = consultaSql($sql,$conMadre,$debugar);
		if(count(explode(",",$campos))<=2) $salida[]=array("valor"=>0,"label"=>"Selecciona");	
		if(is_array($datos) && count($datos)>0){	
			foreach($datos as $val){
				$item = 1;
				foreach($camposPartes as $campo){
					$keyCamp = explode(" as ",strtolower($campo));										
					if($item==1){
						if(empty($keyCamp[1])) $array['valor'] = $val[$campo]; else $array[trim($keyCamp[1])] = $val[trim($keyCamp[1])];
					}elseif($item==2){
						if(empty($keyCamp[1])){ $array['label'] = $val[$campo]; }else{ $array[trim($keyCamp[1])] = $val[trim($keyCamp[1])]; }
					}elseif($item>2){
						if(empty($keyCamp[1])){ $array[$campo] = $val[$campo]; }else{ $array[trim($keyCamp[1])] = $val[trim($keyCamp[1])]; }
					}
					$item++;
				}			
				$salida[]=$array;				
			}
			if(!empty($debugar)) print_r($salida);
		}
		return $salida;	
	}
	//-	
	function formateaCampos($campos,$valores,$separador=":"){
		$separado = ($separador != ":") ? $separador : ":";
		$conta=0;
		foreach($campos as $campo){
			if(!empty($valores[$conta])){
				if($conta>0) $salida.= ",";
				$valores[$conta] = ($valores[$conta]!='[x]') ? $valores[$conta] : '';
				$salida.= $campo.$separador."'".$valores[$conta]."'";
			}
			$conta+=1;
		}	
		return $salida;	
	}	
/*	
	//-- Acciones New,Update,Delete
	//accionesNud(tabla,valores(nombre:'valor',talla:'valor'),accion[*no obligatorio],id[*solo update,delete],duplicados[*solo add],comparador[*solo add con duplicados, nombre_tabla)
	function accionesNUD($tabla,$datos="",$action="add",$duplicados=true,$comp="",$conMadre=false,$debug=false){
		$campos = array(); $valores = array();
		$conta=0; $debugExit="";
		//-			
		if(is_array($datos)){
			$campos = $datos;
		}else{
			preg_match_all("/\b([\w-]+)\s*:\s*('?)([^']+?)\\2(?=\s*,\s*[\w-]+:|$)/", $datos, $coincidencias, PREG_SET_ORDER);
			foreach($coincidencias as $coincide){ $camposEnv[] = $coincide[0]; }
			//-
			foreach($camposEnv as $campoList){
				list($pCampo,$pValor,$pExtra) = explode(":",$campoList);
				$pValor = (!empty($pExtra)) ? $pValor.':'.$pExtra : $pValor;
				$campos[$pCampo] = (substr(trim($pValor),0,1)=="'") ? "'".str_replace("'","",$pValor)."'" : $pValor;			
			}
		}
		//-
		if(empty($duplicados)){
			if(is_array($comp)){
				foreach($comp as $key=>$label){
					$comparar.= (strlen($comparar)<=0) ? $key.'='.$label : ' AND '.$key.'='.$label;
				}
			}else{
				$arrayComp = array();
				//--			
				preg_match_all("/\b([\w-]+)\s*:\s*('?)([^']+?)\\2(?=\s*,\s*[\w-]+:|$)/", $comp, $coincidencias, PREG_SET_ORDER);
				foreach($coincidencias as $coincide){ $camposEnv[] = $coincide[0]; }								
				//$camposEnv = explode("',",trim($comp));
				foreach($camposEnv as $campoList){
					list($pCampo,$pValor,$pExtra) = explode(":",$campoList);
					$pValor = (!empty($pExtra)) ? $pValor.':'.$pExtra : $pValor;				
					$arrayComp[$pCampo] = (substr(trim($pValor),0,1)=="'") ? "'".str_replace("'","",$pValor)."'" : $pValor;
				}
				foreach($arrayComp as $key=>$label){
					$comparar.= (strlen($comparar)<=0) ? $key.'='.$label : ' AND '.$key.'='.$label;
				}
			}
		}		
		//-
		$camposInsert = implode("`,`", array_keys($campos));
		$valoresInsert = implode(', ',$campos);	
		//---	
		switch($action){
			case 'add':			
				if(empty($duplicados)){			
					$sql_dup = "SELECT * FROM ".$tabla." WHERE (".$comparar.") LIMIT 1";
					$duplicados = consultaSql($sql_dup,$conMadre,$debug);
					if(count($duplicados)==0){
						$sql_ins = "INSERT INTO ".$tabla." (`".$camposInsert."`) VALUES(".$valoresInsert.")";			
						$insertar = consultaSql($sql_ins,$conMadre,$debug);
					}
				}else{
					$sql_ins = "INSERT INTO ".$tabla." (`".$camposInsert."`) VALUES(".$valoresInsert.")";			
					$insertar = consultaSql($sql_ins,$conMadre,$debug);					
				}
				$debugExit.= $sql_ins;
				$sqlExit = empty($duplicados) || !is_array($insertar) ? "SELECT * FROM ".$tabla." WHERE (".$comparar.") LIMIT 1" : "SELECT * FROM ".$tabla." WHERE ".$comp."='".$insertar[0]['id']."' LIMIT 1";				
				$pedAddInf = consultaSql($sqlExit,$conMadre,$debug);				
				$salida['info'] = $pedAddInf[0];				
				if(is_array($duplicados)){ $salida['duplicado'] = true; }else{ $salida['duplicado'] = false; }				
				break;
			case 'update':
				unset($datos);
				foreach($campos as $key=>$label){
					$datos.= (strlen($datos)<=0) ? '`'.$key."`=".$label : ',`'.$key."`=".$label;
				}
		
				$sql_upd = (!empty($duplicados)) ? "UPDATE ".$tabla." SET ".$datos." WHERE (".$comparar.")" : "UPDATE ".$tabla." SET ".$datos." WHERE (".$comparar.") LIMIT 1";
				$update = consultaSql($sql_upd,$conMadre,$debugar);
				
				$debugExit.= $sql_upd;
				$pedUpdInf = consultaSql("SELECT * FROM ".$tabla." WHERE (".$comparar.") LIMIT 1",$conMadre,$debugar);
				if(is_array($pedUpdInf)){				
					$salida['info'] = $pedUpdInf[0];																			
				}else{
					$salida="";
				}
				break;
			case 'delete':
				$sql_del = "DELETE FROM ".$tabla." WHERE (".$comparar.")";			
				$debugExit.= $sql_del;
				$salida = consultaSql($sql_del,$conMadre,$debugar);		
				break;
		}

		if($debug==true){
			echo '<div style="border:#ccc 1px solid;background:#eeeeee;padding:7px;margin-bottom:10px;">'.$debugExit.'</div>';
			//echo "<br>----<br>tabla -> ".$tabla." <br>datos -> ".$datos." <br>accion -> ".$action." <br>duplicados -> ".$duplicados." <br>comparar -> ".$comp."<br>----<br>";
			//echo "<br>[sql_duplicados]<br><br>".$sql_dup."<br>---------------<br>";
			//echo "<br>[okRegistro]<br><br>".print_r($noError)."<br>---------------<br>";
		}
		return $salida;		
	}	
*/	
	//-
	function insertSelect($valores){
		if(!empty($valores['comparar'])){	
			if(!is_array($valores['comparar']))	$compara = str_replace(array(":",","),array("="," AND "),$valores['comparar']);
			else{
				foreach($valores['comparar'] as $key=>$label){
					$compara = strlen($compara)>0 ? " AND `".$key."`='".$label."'" : "`".$key."`=".$label;
				}
			}
			$sql = "SELECT * FROM ".$valores['tabla']." WHERE ".$compara." LIMIT 1";
			$comparar = consultaSql($sql,$valores['conMadre'],$valores['debugar']);
		}
		if(!is_array($comparar)){		
			if(!is_array($valores['campos'])){
				$valores['campos'] = trim($valores['campos']);
				if(!empty($valores['campos'])) $items = explode(",",$valores['campos']);
				if(is_array($items) && count($items)>0){
					foreach($items as $valor){
						$partes = explode(":",$valor);
						$sqlCampos[] = $partes[0];
						$sqlValores[]= trim($partes[1]);
					}
				}
			}else{
				foreach($valores['campos'] as $key=>$label){
					$sqlCampos[] = $key;
					$sqlValores[]= trim($label);					
				}
			}
			
			$SQL = "INSERT INTO ".$valores['tabla']."(".implode(",",$sqlCampos).")";
			$SQL.= " SELECT ".implode(",",$sqlValores)." FROM ".$valores['tablaSelect'];
			$SQL.= (!empty($valores['compararSelect'])) ? " WHERE ".str_replace(array(":",","),array("="," AND "),$valores['compararSelect']) : '';
			$SQL.= " LIMIT 1;";
			if(!empty($valores['debugar'])) echo '<div id="debugar">'.$SQL.'</div>';
			return $guardar = consultaSql($SQL,$valores['conMadre'],$valores['debugar']);
		}else return false;
	}			
	//-
	function obtenerId($id,$tabla,$ac,$md5=true,$debug=false){
		$sql = "SELECT * FROM ".$tabla." WHERE";
		if($md5==true){ $sql.= " md5(".$ac."id)='".$id."' LIMIT 1"; }else{ $sql.= " ".$ac."id='".$id."' LIMIT 1"; }
		$idResultado = consultaSql($sql,'',$debug);
		if($debug==true){
			echo $debugExit = '<div style="border:#ccc 1px solid;background:#eee;padding:5px;margin-bottom:10px;"><strong>MD5</strong>[ '.$id.' ] <strong>ID</strong>[ '.$idResultado[0][$ac.'id'].' ]</div>';
		}
		return $idResultado[0][$ac.'id'];		
	}		
	//-
	function randomPassword($length) {
		$pattern = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for($i=0;$i<$length;$i++) {
		  $key .= $pattern{rand(0,35)};
		}
		return $key;
	}	
	//-
	function paginador($sql,$pagina,$nProds=25,$iPag=10,$style="default",$class="",$classAct="",$url,$debugar=false){	
		$claseDef = "width:25px;height:25;border:#999 1px solid;text-align:center;line-height:23px;text-decoration:none;color:#666;float:left;margin:10px 10px 0px 0px;cursor:pointer;";
		$claseDefAct = "width:25px;height:25;border:#999 1px solid;background:#666;text-align:center;line-height:23px;text-decoration:none;color:#fff;float:left;margin:10px 10px 0px 0px;cursor:pointer;";		
		$partes = explode("FROM",$sql);
		$partes = explode("GROUP",$partes[1]);
		$partes = explode("ORDER",$partes[0]);
		$sql = "SELECT COUNT(*) AS items FROM ".$partes[0];
		$paginas = consultaSql($sql,"",$debugar);		
		$cuantosRegistros = $paginas[0]['items'];
		if(is_array($paginas) && count($paginas) > 0){
			$numPaginas = $cuantosRegistros / $nProds;
			$numPaginas = ($numPaginas < 1) ? 1 : $numPaginas = is_float($numPaginas) ? $numPaginas+=1 : $numPaginas;
			settype($numPaginas,"integer");		
			//--
			$paginaAnterior = $pagina-1;
			$paginaSiguiente = $pagina+1;
			$itemDe = (($pagina-1) * $nProds) + 1;
			$itemA = ($itemDe + $cuantosRegistros)-1;		
		}
		switch(strlen($pagina)){
			case 1:
				$paginaDesde = 1;
				$paginaHasta = $paginaDesde + ($iPag-1);
				break;
			default:
			case 2:
				if($pagina % $iPag == 0){ 
					$paginaDesde = ($pagina-$iPag)+1;
				}else{
					$paginaDesde = ($pagina - substr($pagina,strlen($pagina)-1,1))+1;
				}
				break;
		
		}		
		$paginaHasta = ($numPaginas > ($paginaDesde + ($iPag-1)) ) ? $paginaDesde + ($iPag-1) : $numPaginas;
	//--		
	//echo "pagina [".$pagina."] iPaginas [".$iPag."] numPags [".$numPaginas."] paginaDesde [".$paginaDesde."] paginaHasta [".$paginaHasta."]<br>";
	//--
		switch($style){
			case 'todas':
			case 'complet':
				for($i = 1; $i <= $numPaginas; $i++){
					$linkPag = $url."&pagina=".$i;				
					$salida.='<a href="'.$linkPag.'"><div';
					$salida.= ($class!="") ? 
						$clase = ($i == $pagina) ? ' class="'.$classAct.'"' : ' class="'.$class.'"'
						: $clase = ($i == $pagina) ? ' style="'.$claseDefAct.'"' : ' style="'.$claseDef.'"';
					$salida.='>'.$i.'</div></a>';
				}			
				break;
			default:
				if($numPaginas > 1){
					if($numPaginas > $iPag && ($pagina-100) > $iPag){
						$linkPag = $url."&pagina=".($paginaDesde-101);
						$salida.='<a href="'.$linkPag.'"><div';
						$salida.= ($class!="") ? ' class="'.$class.'"' : ' style="'.$claseDef.'"';		
						$salida.='><<</div></a>';						
					}			
					if($numPaginas > $iPag && $pagina > $iPag){
						$linkPag = $url."&pagina=".($paginaDesde-1);
						$salida.='<a href="'.$linkPag.'"><div';
						$salida.= ($class!="") ? ' class="'.$class.'"' : ' style="'.$claseDef.'"';		
						$salida.='><</div></a><div style="'.$claseDef.'border:none;">...</div>';						
					}
					for($i = $paginaDesde; $i <= $paginaHasta; $i++){
						$linkPag = $url."&pagina=".$i;				
						$salida.='<a href="'.$linkPag.'"><div';
						$salida.= ($class!="") ? 
							$clase = ($i == $pagina) ? ' class="'.$classAct.'"' : ' class="'.$class.'"'
							: $clase = ($i == $pagina) ? ' style="'.$claseDefAct.'"' : ' style="'.$claseDef.'"';
						$salida.='>'.str_pad($i,strlen($numPaginas),"0",STR_PAD_LEFT).'</div></a>';
					}
					if($numPaginas > $iPag && $paginaHasta < $numPaginas){
						$linkPag = $url."&pagina=".($paginaHasta+1);
						$salida.='<div style="'.$claseDef.'border:none;">...</div><a href="'.$linkPag.'"><div';
						$salida.= ($class!="") ? ' class="'.$class.'"' : ' style="'.$claseDef.'"';		
						$salida.='>></div></a>';						
					}
					if($numPaginas > $iPag && ($paginaHasta+100) < $numPaginas){
						$linkPag = $url."&pagina=".($paginaHasta+101);
						$salida.='<a href="'.$linkPag.'"><div';
						$salida.= ($class!="") ? ' class="'.$class.'"' : ' style="'.$claseDef.'"';		
						$salida.='>>></div></a>';						
					}	
				}
				break;
		}
		$salida.='<div style="clear:both;"></div>';
		$salida = array("paginador"=>$salida,"numregs"=>$cuantosRegistros);
		return $salida;
	}
	//-
	function urlAmigables($texto) {
		$s = trim($texto);
		$s = strtolower($s);
		$s = ereg_replace("[ ]+","-",$s);
		$s = ereg_replace("ç","c",$s);
		$s = ereg_replace("ñ","n",$s);
		$s = ereg_replace("á|à|â|ã|ä|â|ª|Á","a",$s);
		$s = ereg_replace("í|ì|î|ï|Í","i",$s);
		$s = ereg_replace("é|è|ê|ë|É","e",$s);
		$s = ereg_replace("ó|ò|ô|õ|ö|º|Ó","o",$s);
		$s = ereg_replace("ú|ù|û|ü|Ú","u",$s);
		$s = ereg_replace("[^a-z0-9_-]",'',$s);
		return $s; //substr($s, 0, 40);
	}	
	//-
	function formatNum($numero,$separador=",",$decimales=2){
		$partes = explode(".",$numero);
		if(count($partes) > 1){
			$entero = $partes[0];
			$decimal = $partes[1];
			$resultado = strrev(wordwrap(strrev($entero),3,".",1)).$separador.str_pad(substr($decimal,0,2), 2, "0", STR_PAD_RIGHT);
		}else{
			$resultado = $numero.$separador."00";
		}
		return $resultado;
	}		
	//-
    function calcularFecha($fechaIni,$dias){ 
		$fechaComparacion = strtotime($fechaIni);
		$calculo= strtotime("$dias days", $fechaComparacion);
    	return date("Y-m-d", $calculo);
    }	
	//-
	function getMonthDays($Month, $Year){
	   if( is_callable("cal_days_in_month")){ return cal_days_in_month(CAL_GREGORIAN, $Month, $Year); }else{ return date("d",mktime(0,0,0,$Month+1,0,$Year)); }
	}
	//-
	function enviaEmail($para,$de='',$asunto='',$mensaje){	
		$from = empty($de) ? $_SERVER['HTTP_HOST'] : $de;
		$to = $para;
		$subject = $asunto;			
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
		$headers .= "From: ".$_SERVER['HTTP_HOST']." <".$_SERVER['HTTP_HOST'].">\r\n"; 
		$headers .= "Reply-To: info@".str_replace("www.","",$_SERVER['HTTP_HOST'])."\r\n"; 
		return mail($to,$subject,$mensaje,$headers);		
	}
	//-
	function creaCampos($valores,$tipo=0,$valorIni=0,$idReg=0,$extras=0){
		//fecha //fechahora //txt //bolean //estado //list //tags //file //money //txtarea //oculto //cache //opciones
		$altoCamposNoEdit = 20;
		if(is_array($valores) && count($valores)>0){
			$tipo = (empty($tipo)) ? ($tipoArr = (empty($valores['tipo'])) ? 'txt' : $valores['tipo']) : $tipo;			
		}else{
			$tipo = (empty($tipo)) ? "txt" : $tipo;	
			$valores = array("titulo"=>$valores,"tipo"=>$tipo);		
		}
		$campoN = (empty($extras['array'])) ? urlAmigables($valores['titulo']) : urlAmigables($valores['titulo']).'[]';
		$campoI = urlAmigables($valores['titulo']);
		$clase = (!empty($valores['class'])) ? 'class="'.$valores['class'].'"' : 'class="inputInfo"';			
		$orden = (!empty($valores['orden'])) ? 'tabindex="'.$valores['orden'].'" ' : '';
		
		//echo "valIni[".$valorIni."] valoresValor[".$valores['valor']."]<br>";
		if(empty($valorIni) && empty($valores['valor'])){
			$valor = '';
		}elseif(empty($valorIni) && !empty($valores['valor'])){
			$valor = $valores['valor'];
		}else{
			$valor = ($valores['tipo']=='fecha') ? substr($valorIni,0,10) : $valorIni;
			//$valor = (!empty($valorIni)) ? $valor = ($valores['tipo']=='fecha') ? substr($valorIni,0,10) : $valorIni : ''; 						
		}
		
		$max = (!empty($valores['max'])) ? 'maxlength="'.$valores['max'].'"' : '';
		$rows = (!empty($valores['rows']) && $tipo=='txtarea') ? 'style="height:'.(20*$valores['rows']).'px;"' : 'style="height:80px;"';
		$style = (!empty($valores['style'])) ? 'text-transform:'.$valores['style'].';' : '';
		//print_r($valores);
		//echo "<br>valores[$valores] tipo[$tipo] valor[$valor] valorIni[$valorIni] idReg[$idReg] extras[$extras]<br>";
				
		switch($tipo){
			default:
			case 'fecha':
			case 'fechahora':			
			case 'txt':
				if(!empty($valores['editable']) && $valores['editable']!="no"){
					if($valores['editable']!='nodata'){
						$salida = '<input name="'.$campoN.'" id="'.$campoI.'" '.$clase.' '.$orden.' value="'.$valor.'" '.$max.'>';
					}else{
						$valor = ($valor=='0000-00-00') ? '' : $valor;						
						if(empty($valor)){
							$salida = '<input name="'.$campoN.'" id="'.$campoI.'" '.$clase.' '.$orden.' value="'.$valor.'" '.$max.'>';
						}else{	
							$salida = '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$valor.'</div>';												
						}
					}
					if($tipo=='fecha'){
						$salida.= "\n".'<script>'."\n".'<!--'."\n";
						$salida.= '$("#'.$campoI.'").datepicker({'."\n";
						$salida.= 'showOn: "focus",'."\n";
						$salida.= 'buttonImage: "img/calendar.gif",'."\n";
						$salida.= 'buttonImageOnly: false,'."\n";
						$salida.= 'dateFormat: "yy-mm-dd",'."\n";
						$salida.= 'dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],'."\n";
						$salida.= 'firstDay: 1,'."\n";
						//$salida.= 'beforeShowDay: $.datepicker.noWeekends,'."\n";
						$salida.= 'minDate:  new Date('.date('Y').','.(date("m")-9).', 1),'."\n";
						$salida.= 'maxDate: new Date('.date('Y').','.(date("m")-1).', '.getMonthDays(date('m'),date('Y')).')'."\n";
						$salida.= '});'."\n";
						$salida.= '$.datepicker.noWeekends;'."\n";
						$salida.= '-->'."\n".'</script>'."\n";					
					}
				}else{
					if($tipo!='txt'){ 
						list($ano,$mes,$dia,$hora,$minutos) = preg_split("[-|\s|:]", $valor);
						switch($tipo){
							default:
								$fechaData = $dia."-".$mes."-".$ano."&nbsp;&nbsp;&nbsp;&nbsp;".$hora.":".$minutos;							
								break;
							case 'fecha':
								$fechaData = $dia."-".$mes."-".$ano;														
								break;
							case 'hora':
								$fechaData = $hora.":".$minutos;							
								break;
						}
						//$fechaData = ($tipo=='fecha') ? $fecha[2]."-".$fecha[1]."-".$fecha[0] : $fecha[2]."-".$fecha[1]."-".$fecha[0]."&nbsp;&nbsp;&nbsp;&nbsp;".$fecha[3].":".$fecha[4];
					}	
					$txtValor = (!empty($valor)) ? $txtValor = ($tipo!='txt') ? $fechaData : $valor : "&nbsp;";					
					if(!empty($valores['infoVinculada'])){ 
						list($sql_buscar,$sql_var,$sql_tabla,$sql_inner,$sql_inneron) =  preg_split("[@|:|&|-]", $valores['infoVinculada']);										
						$hayDatos = explode("=",$sql_var);
						$sql_var = (count($hayDatos)==1) ? $sql_var : $sql_buscar;
						$txtValor = (count($hayDatos)==1) ? $txtValor : $sql_var;						
						$sql = "SELECT ".$sql_buscar." as valor FROM ".$sql_tabla;
						$sql.= !empty($sql_inner) ? " LEFT JOIN ".$sql_inner." ON(".$sql_inneron.")" : '';
						$sql.= " WHERE ".$sql_var."='".$txtValor."' LIMIT 1";
						$sql = consultaSql($sql,"",$debugar);
						$txtValor = $sql[0]['valor']; 
					}					
					if(!empty($valores['recorta']) && is_numeric($valores['recorta'])){
						$txtValor = substr($txtValor,0,$valores['recorta']);
					}elseif(!empty($valores['recorta']) && is_string($valores['recorta'])){
						$recortes = preg_split("/[\s,;\.:\-_]+/",$valores['recorta']);
						$txtValor = substr($txtValor,$recortes[0],$recortes[1]);				
					}else{
						$txtValor = $txtValor;					
					}												
					$salida = ($extras!="modoVista") ? '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$txtValor.'</div>' : $txtValor;
				}
				break;
			case 'bolean':
				$partesSql =  preg_split("[@|:]", $valores['campo']);
				if(count($partesSql)>1){
					$sql = consultaSql("SELECT ".$partesSql[0]." as valor FROM ".$partesSql[2]." WHERE ".$partesSql[1]."='".$idReg."' LIMIT 1","",DEBUG);
					$valSalida = $sql[0]['valor'];								
				}else{
					$valSalida = $valor;
				}
				$salida = (!empty($valSalida) && ($valSalida=='si' || $valSalida==1 || $valSalida=='true')) ? '<span style="color:#009900;">Sí</span>' : '<span style="color:#FF0000;">No</span>';
				break;				
			case 'estado':
				$check = ($valor == 1) ? 'checked' : '';
				$enlaza=false;
				$salida = '<input name="activades[]" type="checkbox" id="activades[]" value="'.$idReg.'" '.$check.' />';
				break;
			case 'check':
				$check = (!empty($valor)) ? 'checked' : '';
				$enlaza=false;
				$salida = '<input name="'.$campoN.'" type="checkbox" id="'.$campoI.'" value="'.$idReg.'" '.$check.' />';
				break;								
			case 'list':
				if(!empty($valores['editable']) && $valores['editable']!="no"){	
					if($valores['editable']!='nodata'){		
						$salida = '<select id="'.$campoI.'" name="'.$campoN.'" '.$clase.' '.$orden.'>';
						//$salida.= '<option value="0">Selecciona</option>';									
						foreach($valores['elementos'] as $option){
							$selected = (!empty($valorIni) && $valorIni==$option['valor']) ? " selected" : "";					
							$salida.= '<option value="'.$option['valor'].'"'.$selected.'>'.$option['label'].'</option>';					
						}
						$salida.= '</select>';
					}else{
						if(empty($valor)){
							$salida = '<select id="'.$campoI.'" name="'.$campoN.'" '.$clase.' '.$orden.'>';
							foreach($valores['elementos'] as $option){
								$selected = (!empty($valorIni) && $valorIni==$option['valor']) ? " selected" : "";					
								$salida.= '<option value="'.$option['valor'].'"'.$selected.'>'.$option['label'].'</option>';					
							}
							$salida.= '</select>';
						}else{	
							foreach($valores['elementos'] as $item){
								if($item['valor']==$valor) break;
							}
							$salida = '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$item['label'].'</div>';
						}												
					}
				}else{
					foreach($valores['elementos'] as $item){
						if($item['valor']==$valor) break;
					}
					if(!empty($valores['recorta']) && is_numeric($valores['recorta'])){
						$txtValor = substr($item['label'],0,$valores['recorta']);
					}elseif(!empty($valores['recorta']) && is_string($valores['recorta'])){
						$recortes = preg_split("/[\s,;\.:\-_]+/",$valores['recorta']);
						$txtValor = substr($item['label'],$recortes[0],$recortes[1]);				
					}else{
						$txtValor = $item['label'];					
					}					
					$salida = ($extras!="modoVista") ? '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$txtValor.'</div>' : $txtValor;					
				}
				break;
			case 'checklist':
				$checks = explode("|",$valorIni);
				$selecteds = array_merge(array('null'),$checks);
				if(is_array($valores['elementos']) && count($valores['elementos'])){
					$salida = '<ul>';					
					foreach($valores['elementos'] as $item){
						//print_r($item);
						$existe = array_search($item['submenu_id'], $selecteds);
						$check = !empty($existe) ? 'checked' : '';
						$salida.= $oldMenu!=$item['menu_id'] ? "\n".'<li><u>'.$item['menu_nombre'].'</u></li>' : '';											
						$salida.= "\n".'<li><input name="'.$campoN.'[]" type="checkbox" id="'.$campoI.'[]" value="'.$item['submenu_id'].'" '.$check.' />&nbsp;'.$item['submenu_nombre'].'</li>';
						$oldMenu=$item['menu_id'];
					}
					$salida.= "\n".'<ul>';										
				}
				break;
			/*case 'tags':
				foreach($valores['elementos'] as $item){
					if($item['valor']==$value[$valores['campo']]) break;
				}
				unset($salida);
				$listaTags = explode(",",$item['label']);
				foreach($listaTags as $val){
					$partesSql =  preg_split("[@|:]", $valores['infoVinculada']);
					$sql = consultaSql("SELECT ".$partesSql[0]." as valor FROM ".$partesSql[2]." WHERE ".$partesSql[1]."='".$val."' LIMIT 1","",DEBUG);
					$valSalida = $sql[0]['valor'];
					if(!empty($valores['recorta']) && is_numeric($valores['recorta'])){
						$valSalida = substr($valSalida,0,$valores['recorta']);
					}elseif(!empty($valores['recorta']) && is_string($valores['recorta'])){
						$recortes = preg_split("/[\s,;\.:\-_]+/",$valores['recorta']);
						$valSalida = substr($value[$valores['campo']],$recortes[0],$recortes[1]);
					}else{
						$valSalida = $valSalida;				
					}					
					$salida.= '<div'.$style.'>'.$valSalida."</div>";
				}						
				break;*/				
			case 'file':				
				$valorIni = (file_exists($valorIni)) ? $valorIni : '';
				if(empty($valorIni) && (!empty($valores['editable']) && $valores['editable']!="no") ){
					$salida = '<input id="'.$campoI.'" name="'.$campoN.'" type="file" '.$clase.' '.$orden.'>';
				}else{
					if(file_exists($valorIni)){
						$linkVw = $valorIni;
						$linkDel = '';
						$linkDel = URL."index.php?seccion=".$_GET['seccion']."&apartado=".$_GET['apartado']."&opcion=editar";
						$existeId = strpos($urlEnv, "id");
						$linkDel = (!empty($existeId)) ? $linkDel."&del=".$campoN : $linkDel.'&id='.md5($idReg)."&del=".$campoN;
						switch(substr($valorIni,strlen($valorIni)-3,3)){
							case 'jpg':
							case 'gif':
							case 'png':
							case 'tif':							
								$salida = '<div style="width:60%;">';
								$salida.= '<img src="'.$valorIni.'" style="width:85%;float:left;margin-right:10px;">';
								if(!empty($valores['editable']) && $valores['editable']=='si'){ $salida.= '<div style="float:left;"><a href="'.$linkDel.'" style="color:#DF1717;font-weight:bold;">[X]</a></div>'; }
								$salida.= '<div style="clear:both"></div>';
								$salida.= '</div>';
								break;
							case 'pdf':
							case 'doc':
							case 'ocx':								
								$valorIniExt = explode("/",$valorIni);
								$salida = '<div style="width:90%;">';
								$salida.= '<a href="'.$linkVw.'" target="_blank"><img src="img/pdfdoc.png" style="float:left;margin-right:10px;"><div style="float:left;margin-right:10px;">'.$valorIniExt[count($valorIniExt)-1].'</div></a>';
								if(!empty($valores['editable']) && $valores['editable']=='si'){ $salida.= '<div style="float:left;"><a href="'.$linkDel.'" style="color:#DF1717;font-weight:bold;">[X]</a></div>'; }
								$salida.= '<div style="clear:both"></div>';
								$salida.= '</div>';
								break;							
						}
					}else{
						$salida = '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$valor.'</div>';
					}
					//$salida.= ' <--> '.$valorIni;
				}
				break;
			case 'money':
				if(!empty($valores['editable']) && $valores['editable']!="no" && $valores['editable']!="nodata" ){	
					$salida = '<input name="'.$campoN.'" id="'.$campoI.'" '.$clase.' '.$orden.' value="'.$valor.'" '.$max.' onkeypress="return acceptNum(event,true)">';			
				}elseif(!empty($valores['editable']) && $valores['editable']=="nodata" && ((is_numeric($valor) && $valor==0) || empty($valor)) ){
					$salida = '<input name="'.$campoN.'" id="'.$campoI.'" '.$clase.' '.$orden.' value="" '.$max.' onkeypress="return acceptNum(event,true)">';								
				}else{
					$txtValor = (is_numeric($valor) && $valor > 0) ? formatNum($valor).' &euro;' : '';					
					$salida = '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$txtValor.'</div>';										
				}
				break;
			case 'txtarea':
				if(!empty($valores['editable']) && $valores['editable']!="no"){			
					$salida = '<textarea id="'.$campoI.'" name="'.$campoN.'" '.$clase.' '.$orden.''.$rows.'>'.$valor.'</textarea>';
				}else{
					$valor = str_replace("\n","<br>",$valor);
					$salida = $valor;								
				}
				
				break;
			case 'oculto':	
				if(!empty($valores['visible']) && $valores['visible']!="no"){								
					$salida = '<div style="height:'.$altoCamposNoEdit.'px;'.$style.'">'.$valor.'</div>';												
				}else{
					$salida = '<input type="hidden" id="'.$campoI.'" name="'.$campoN.'" value="'.$valor.'" />';
				}
				break;
			case 'cache':
				$txtValor = $valor; //$value[$valores['campo']];				
				$cacheParts = explode(",",$txtValor);
				$cacheParts = array_reverse($cacheParts);
				unset($txtValor);
				unset($salidaMin);				
				$conta = 0;
				foreach($cacheParts as $parts){
					$partP = preg_split("[-|=>]", $parts);
					$txtValor.= '<span style="font-size:9px;">['.$partP[2].'-'.$partP[1].'-'.$partP[0].']</span> <span style="font-size:10px;">'.ucfirst(str_replace($ac,"",$partP[3])).'</span><br>';
					if($conta < 2){  //> count($cacheParts)-3){
						$salidaMin.= ' <span style="font-size:9px;">['.$partP[2].'-'.$partP[1].'-'.$partP[0].']</span> <span style="font-size:10px;">'.ucfirst(str_replace($ac,"",$partP[3])).'</span><br>';
					}
					$conta+= 1;					
				}
				if($extras!="modoJs"){
					$salida = "\n".'<div id="cachemax_'.$idReg.'" style="display:none;">'.$txtValor.'</div>';				
					$salida.= "\n".'<div id="cachemin_'.$idReg.'">'.$salidaMin.'</div>';
					$salida.= "\n".'<script>'."\n".'<!--'."\n";
					$salida.= "\t".'$("#cachemin_'.$idReg.'").click(function(){'."\n";								
					$salida.= "\t\t".'$("#cachemax_'.$idReg.'").slideDown();'."\n";
					$salida.= "\t\t".'$("#cachemin_'.$idReg.'").css("display","none");'."\n";								
					$salida.= "\t\t".'return false;'."\n";
					$salida.= "\t".'});';
					$salida.= "\n".'-->'."\n".'</script>'."\n";	
				}else{
					$salida.= '<div id="cachemin_'.$idReg.'">'.$salidaMin.'</div>';					
				}
				break;				
			case 'opciones':			
				$linkE = URL.'index.php?seccion='.$_GET['seccion'].'&apartado='.$_GET['apartado'].'&opcion=eliminar&id='.md5($idReg);
				$link = URL.'index.php?seccion='.$_GET['seccion'].'&apartado='.$_GET['apartado'].'&opcion=editar&id='.md5($idReg);
				if($usuarioDatos['tipo'] == 0){
					$salida = '<a href="'.$linkE.'" title="Eliminar" class="letraMarcador"><img src="'.URL.'img/delete.png" alt="Eliminar" width="15" class="flotaDcha" /></a>';
				}
				$salida.= '<a href="'.$link.'" title="Editar" class="letraMarcador"><img src="'.URL.'img/edit.png" alt="Editar" width="15" /></a>';
				break;				
		}	
		return $salida;
	}		
	//-
	function campoForm($tipo,$valores,$nombre,$sel=false,$clase=false,$estilo=false,$debugar=false){
		$class = (!empty($clase)) ? ' class="'.$clase.'"' : '';
		$style = (!empty($estilo)) ? ' style="'.$estilo.'"' : '';		
		switch($tipo){
			case 'input':
				$salida = "\n".'<input type="text" name="'.$nombre.'" id="'.$nombre.'"'.$class.$style.$sel.' value="'.$valores.'">'."\n";			
				break;
			case 'password':
				$salida = "\n".'<input type="password" name="'.$nombre.'" id="'.$nombre.'"'.$class.$style.$sel.' value="'.$valores.'">'."\n";			
				break;				
			case 'txtarea':
				$salida = "\n".'<textarea name="'.$nombre.'" id="'.$nombre.'" rows="'.$sel.'"'.$class.$style.'>'.$valores.'</textarea>'."\n";
				break;
			case 'list':
			case 'lista':			
			case 'select':							
				$salida = "\n".'<select name="'.$nombre.'" id="'.$nombre.'"'.$class.$style.'>';
	            $salida.= "\n\t".'<option value="0" selected>Selecciona</option>';			
				if(is_array($valores) && count($valores)>0){
					foreach($valores as $key=>$label){
						if(!is_array($label)){
							$selected = ($key==$sel || count($valores)==1) ? ' selected' : '';
							$salida.= "\n\t".'<option value="'.$key.'"'.$selected.'>'.$label.'</option>';
						}else{
							$id = (empty($label['id'])) ? $label['valor'] : $label['id'];
							$selected = ($id==$sel) ? ' selected' : '';							
							$salida.= (empty($id) || !empty($label['optgroup'])) ? "\n\t".'<optgroup label="'.$label['label'].'">' : "\n\t".'<option value="'.$id.'"'.$selected.'>'.utf8_encode($label['label']).'</option>';							
						}
					}
				}
                $salida.= "\n".'</select>';
				break;
			case 'listchecks':
                    if(is_array($valores) && count($valores)){
						$salida = "\n".'<ul>';
                        foreach($valores as $item){
		                    $salida.= !empty($item['id']) ? '<li style="margin-bottom:10px;line-height:6px;"><label><input name="escoba[]" type="checkbox" value="'.$item['id'].'" class="ck" />&nbsp;'.ucwords(utf8_encode($item['nombre'])).'</label></li>' : '';
                        }
						$salida.= "\n".'<ul>';                        
                    }		
				break;
			case 'check':
				$checked = !empty($sel) ? ' checked' : '';
				$salida = "\n".'<input type="checkbox" name="'.$nombre.'" id="'.$nombre.'" value="'.$valores.'"'.$checked.' '.$class.' />'."\n";			
				break;
			case 'hidden':
				$salida = "\n".'<input name="'.$nombre.'" id="'.$nombre.'" type="hidden" value="'.$valores.'" />'."\n";			
				break;				
		}
		if(!empty($debugar)){
			print_r($valores);
			echo "<br><br>tipo[$tipo] , nombre[$nombre] , sel[$sel] , clase[$clase] , estilo[$estilo]<br><br>";
		}		
		return $salida;
	}
	//-	
	function is_mobile(){	
		$mobile_browser = '0';
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|ipad)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
		}					
		
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		   $mobile_browser++;
		}    
					 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda ','xda-');
					
		if (in_array($mobile_ua,$mobile_agents)) {
			$mobile_browser++;
		}
					
		if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
		   $mobile_browser++;
		}
					
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
		   $mobile_browser = 0;
		}
					
		if ($mobile_browser > 0) {
			return true;
		}else{
			return false;      
		}   
	}		
	//-
	function getRealIP(){
	   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
	   {
		  $client_ip =
			 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
				$_SERVER['REMOTE_ADDR']
				:
				( ( !empty($_ENV['REMOTE_ADDR']) ) ?
				   $_ENV['REMOTE_ADDR']
				   :
				   "unknown" );
	
		  // los proxys van añadiendo al final de esta cabecera
		  // las direcciones ip que van "ocultando". Para localizar la ip real
		  // del usuario se comienza a mirar por el principio hasta encontrar
		  // una dirección ip que no sea del rango privado. En caso de no
		  // encontrarse ninguna se toma como valor el REMOTE_ADDR
	
		  $entries = preg_split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
	
		  reset($entries);
		  while (list(, $entry) = each($entries))
		  {
			 $entry = trim($entry);
			 if ( preg_match("/^([0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+)/", $entry, $ip_list) )
			 {
				// http://www.faqs.org/rfcs/rfc1918.html
				$private_ip = array(
					  '/^0\\./',
					  '/^127\\.0\\.0\\.1/',
					  '/^192\\.168\\..*/',
					  '/^172\\.((1[6-9])|(2[0-9])|(3[0-1]))\\..*/',
					  '/^10\\..*/');
	
				$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	
				if ($client_ip != $found_ip)
				{
				   $client_ip = $found_ip;
				   break;
				}
			 }
		  }
	   }
	   else
	   {
		  $client_ip =
			 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
				$_SERVER['REMOTE_ADDR']
				:
				( ( !empty($_ENV['REMOTE_ADDR']) ) ?
				   $_ENV['REMOTE_ADDR']
				   :
				   "unknown" );
	   }
	
	   return $client_ip;
	}		
?>