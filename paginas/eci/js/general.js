/*var general;
general=$(document);
*/
general.ready(javascripteci);

function javascripteci(){
	var btnid;
	
    $('#dataTables-example').dataTable({
		"paging":false,"language":{"search": "Filtrar ","info": "Página _PAGE_ de _PAGES_","infoFiltered": "(Filtrado de _MAX_ registros)","infoEmpty": "Mostrando 0 de 0 de 0 entradas","zeroRecords": "No se han encontrado resultados",}
		}
	);		
	//- CHECKS ACTIVAR/DESACTIVAR
	$('input:checkbox[name="estado[]"]').change(function(){
		$.post("paginas/eci/ajax.php",{ accion:'estado' , seccion:$('#seccion').val() , apartado:$('#apartado').val() , id:$(this).val() });			
	});

	function ocultaBtns(a){
		$('.grancon').each(function(){
			var btn = $(this).attr('id').split('_');				
			if(a!=btn[1]){
				$('#gc_'+btn[1]).css("display","none");	
				$('#cl_'+btn[1]).css("display","none");					
			}
		});			
	}
	
	$('.asignar').click(function(){
		var info = $(this).attr('id').split('_');	
		$('#envcl').css("display","none");
		$('#envgc').css("display","none");							
		$('#salir').css("display","none");							
		ocultaBtns(info[1]);			

		$('#gc_'+info[1]).css("display","inline");
		$('#cl_'+info[1]).css("display","inline");		
	});
	//-	
	$('.grancon').click(function(){
		var info = $(this).attr('id').split('_');
		$.post("paginas/eci/ajax.php",{accion:'asignar' , proyecto:'granconsumo' , trabajador:info[1]}, 
				function(data){
					$('#grupo_'+info[1]).remove();														
					$('#granconsumo ul').append('<li id="granconsumo_'+data.trabajador.id+'"><button id="btngranc_'+data.trabajador.id+'" type="button" class="btn btn-primary btngranc">'+data.trabajador.nombre+'</button></li>');
				}
				,"json");
	});	
	
	$('.clasif').click(function(){
		var info = $(this).attr('id').split('_');
		$.post("paginas/eci/ajax.php",{accion:'asignar' , proyecto:'clasificador' , trabajador:info[1]}, 
				function(data){
					$('#grupo_'+info[1]).remove();								
					$('#clasificador ul').append('<li id="clasificador_'+data.trabajador.id+'"><button id="btnclasif_'+data.trabajador.id+'" type="button" class="btn btn-info btnclasif">'+data.trabajador.nombre+'</button></li>');
				}
				,"json");
	});		
	
	$("#granconsumo").on("click", ".btngranc", function(){
		btnid = $(this).attr('id').split('_');
		$('#envgc').css("display","none");				
		$('#envcl').css("display","inline");		
		$('#salir').css("display","inline");
		ocultaBtns();					
	});
	$("#clasificador").on("click", ".btnclasif", function(){
		btnid = $(this).attr('id').split('_');
		$('#envgc').css("display","inline");		
		$('#envcl').css("display","none");				
		$('#salir').css("display","inline");			
		ocultaBtns();							
	});	

	$('#envgc').click(function(){
		$.post("paginas/eci/ajax.php",{accion:'asignar' , proyecto:'granconsumo' , trabajador:btnid[1]}, 
				function(data){				
					$('#btnclasif_'+data.trabajador.id).remove();
					$('#envcl').css("display","none");
					$('#envgc').css("display","none");							
					$('#salir').css("display","none");
					$('#granconsumo ul').append('<li id="granconsumo_'+data.trabajador.id+'"><button id="btngranc_'+data.trabajador.id+'" type="button" class="btn btn-primary btngranc">'+data.trabajador.nombre+'</button></li>');
				}
				,"json");		
	});
	$('#envcl').click(function(){
		$.post("paginas/eci/ajax.php",{accion:'asignar' , proyecto:'clasificador' , trabajador:btnid[1]}, 
				function(data){				
					$('#btngranc_'+data.trabajador.id).remove();	
					$('#envcl').css("display","none");
					$('#envgc').css("display","none");							
					$('#salir').css("display","none");
					$('#clasificador ul').append('<li id="clasificador_'+data.trabajador.id+'"><button id="btnclasif_'+data.trabajador.id+'" type="button" class="btn btn-info btnclasif">'+data.trabajador.nombre+'</button></li>');
				}
				,"json");		
	});	
	
	$('#salir').click(function(){
		$.post("paginas/eci/ajax.php",{accion:'asignar' , proyecto:'salir' , trabajador:btnid[1]}, 
				function(data){				
					$('#btngranc_'+data.trabajador.id).remove();
					$('#btnclasif_'+data.trabajador.id).remove();
					$('#envcl').css("display","none");
					$('#envgc').css("display","none");							
					$('#salir').css("display","none");
					$('#divsalir ul').append('<li><button id="btnsalir_'+data.trabajador.id+'" type="button" class="btn btn-danger disabled">'+data.trabajador.nombre+'</button></li>');
				}
				,"json");		
	});
	//--
	
}