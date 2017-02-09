$(document).ready(function(){

	$("#btn_compra_insumos").click(function(){
		$("#btn_compra_productos").fadeOut('slow', function(){
			$("#btn_compra_insumos").css("margin-right", "0px");
		});
	});

	$("#btn_compra_productos").click(function(){
		$("#btn_compra_insumos").fadeOut('slow');
	});

	$("#categoria").change(function(){

		var categoria = $('#categoria').val();
		
		if( categoria != 0 ){
			$("#nueva_categoria").fadeOut('slow');
		}else{
			$("#nueva_categoria").fadeIn('slow');
		}
	});

	$("input[name=nueva_categoria]").keypress(function(e) {
    	if(e.which == 13){
    		
    		var nueva_categoria = $("input[name=nueva_categoria]").val().toUpperCase();
    		
    		
    		$("#dialog-confirm").html("<span>Esta seguro que desea insertar la nueva categoria " + nueva_categoria + "?</span>");
    		$( "#dialog-confirm" ).dialog({
				autoOpen: false,
		    	resizable: false,
		    	height: "auto",
		    	width: 400,
		    	modal: true,
		    	show:{
		        	effect: "blind",
			        duration: 1000
			    },
			    hide:{
			        effect: "fade",
			        duration: 1000
			    },
		    	title: "Guardar Nueva Categoria",
		    	buttons: {
			    	OK: function() {
			        	alert("Ha hecho click en OK");
			        	$( this ).dialog( "close" );
			        },
			    	Cancel: function() {
			        	$( this ).dialog( "close" );
			        }
			    }
			});
    		$("#dialog-confirm").dialog("open");
    	}
    });

	$("#marca").change(function(){

		var marca = $('#marca').val();
		
		if( marca != 0 ){
			$("#nueva_marca").fadeOut('slow');
		}else{
			$("#nueva_marca").fadeIn('slow');
		}
	});
});

