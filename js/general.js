var general;
general=$(document);
general.ready(inicializarEventos);

function inicializarEventos(){
	//- CHECKS ACTIVAR/DESACTIVAR
	$('input:checkbox[name="estado[]"]').change(function(){
		$.post("paginas/ajax.php",{ accion:'estado' , seccion:$('#seccion').val() , apartado:$('#apartado').val() , id:$(this).val() });			
	});
}
//---
	
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };

var nav4 = window.Event ? true : false;
function acceptNum(evt,dec){
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
	var key = nav4 ? evt.which : evt.keyCode;
	if(dec != '' && dec != false){
		//alert(Cadena.search("."));
		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
	}else{
		return (key <= 13 || (key >= 48 && key <= 57));				
	}
}	
