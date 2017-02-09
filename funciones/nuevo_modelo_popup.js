//Validaciones referentes al popup 'Agregar Modelo'
$(document).ready(function(){
    
	$("#btn_aceptar_popup_2").click(function(){
		
		//Se saca el atributo 'disabled' de los Combobox de categoría y marca para poder enviar los datos del formulario.
		$("#categoria_popup_modelo").removeAttr("disabled");
		$("#marca_popup_modelo").removeAttr("disabled");

		//Se cachea el valor del textbox 'Nombre de modelo'
		var desc_modelo = $("#desc_modelo").val();

		//Se cachea el valor del select 'Marca'
		var marca_mod = $("#marca_popup_modelo").val();

		//Se cachea el valor del select 'Categoría'
		var categoria_mod = $("#categoria_popup_modelo").val();

	   // Validación del campo desc_modelo de cuando se deja el campo vacio.
		if( desc_modelo == "" ){
			$("#dialog-success-popup-2").html("<span>Por favor ingrese un modelo</span>");
			$("#dialog-success-popup-2").dialog({
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
				title: "Mensaje",
				buttons:{
					OK: function(){
						$(this).dialog("close");
					},
				}
			});
			$("#dialog-success-popup-2").dialog("open");
			
            //Se vuelven a deshabilitar los Select que se habilitaron al momento de dar click en el botón Aceptar
			$("#categoria_popup_modelo").attr("disabled", "disabled");
			$("#marca_popup_modelo").attr("disabled", "disabled");
			return false;
		
		}

		
		$.post("nuevo_modelo_modal_ajax.php", {"desc_modelo":desc_modelo, "marca_popup_modelo": marca_mod, "categoria_popup_modelo": categoria_mod}, function(retorno){
			if(retorno == 2)
			{		
				$("#dialog-success-popup-2").html("<span>El modelo ingresado ya existe!</span>");
				$("#dialog-success-popup-2").dialog({
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
					title: "Error",
					buttons:{
						OK: function(){
						   $(this).dialog("close");
						},
				    }
				});
		    	$("#dialog-success-popup-2").dialog("open");

		    	//Se vuelven a deshabilitar los Select que se habilitaron al momento de dar click en el botón Aceptar
				$("#categoria_popup_modelo").attr("disabled", "disabled");
				$("#marca_popup_modelo").attr("disabled", "disabled");

		    }else{	
		    
				$("#dialog-success-popup-2").html("<span>Modelo insertado exitosamente!</span>");
				$("#dialog-success-popup-2").dialog({
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
					title: "Mensaje",
					buttons:{
						OK: function(){
						   //Se recarga el Select 'Modelo' del ABM principal
						   $("#contenedor_modelo").load("rellena_option_modelo_ajax.php",{"cod_marca":marca_mod,"cod_categoria":categoria_mod});
						   //Se cierra el mensaje de éxito
						   $(this).dialog("close");
						   //La ventana modal se vuelve a ocultar
						   $("#back_ventana_modelo").css("visibility","hidden");
						},
				    }
				});
		    	$("#dialog-success-popup-2").dialog("open");

		    }

		});	//Cerradura de $.post    
	
	}); //Cerradura de "#btn_aceptar_popup_2".click
	  

//#######################################################################################################################################################################//

	// Consulta si estás seguro de cancelar la operación
	$("#btn_cancelar_popup_2").click(function(){
			$("#dialog-success-popup-2").html("<span>¿Está seguro que desea cancelar la operación?</span>");
				$("#dialog-success-popup-2").dialog({
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
					title: "Mensaje",
					buttons:{
						Si: function(){
						   $(this).dialog("close");
						   $("#back_ventana_modelo").css("visibility","hidden")
						},
				    	
				    	No: function(){
				    	  $(this).dialog("close");
				    	}   
				    }
				});
		    
		    $("#dialog-success-popup-2").dialog("open");
	});	

}); //Cerradura del $document.ready

			