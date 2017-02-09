//Validaciones referentes al popup 'Agregar Marca'
$(document).ready(function(){
    
	$("#btn_aceptar_popup").click(function(){
		
		//Se saca el atributo 'disabled' del Combobox de categoría para poder enviar los datos del formulario.
		$("#categoria_popup").removeAttr("disabled");

		//Se cachea el valor del textbox 'Nombre de marca'
	    var descripcion = $("#desc_marca").val();

	    //Se cachea el valor del select 'Categoría'
	    var categoria = $("#categoria_popup").val();
		
		// Validación del textbox descripcion de cuando se deja el campo vacio.
		if( descripcion == "" ){
			$("#dialog-success-popup").html("<span>Por favor ingrese una marca</span>");
			$("#dialog-success-popup").dialog({
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
			$("#dialog-success-popup").dialog("open");
			
			//Se vuelve a deshabilitar el Select que se habilitó al momento de dar click en el botón Aceptar
			$("#categoria_popup").attr("disabled", "disabled");
			return false;

		}

		
		$.post("nueva_marca_modal_ajax.php", {"desc_marca":descripcion, "categoria_popup":categoria}, function(retorno){
			if(retorno == 2)
			{
				$("#dialog-success-popup").html("<span>La marca ingresada ya existe!</span>");
				$("#dialog-success-popup").dialog({
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
		        $("#dialog-success-popup").dialog("open");

		        //Se vuelve a deshabilitar el Select que se habilitó al momento de dar click en el botón Aceptar
			    $("#categoria_popup").attr("disabled", "disabled");
			
			}else{


				$("#dialog-success-popup").html("<span>Marca insertada exitosamente!</span>");
				$("#dialog-success-popup").dialog({
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
						   //Se recarga el Select 'Marca' del ABM principal
						   $("#contenedor_marca").load("rellena_option_marca_ajax.php",{"cod_categoria":categoria});
						   //Se cierra el mensaje de éxito
						   $(this).dialog("close");
						   //La ventana modal se vuelve a ocultar
						   $("#back_ventana").css("visibility","hidden");
						},
				    }
				});
		    	$("#dialog-success-popup").dialog("open");

			}

		}); //Cerradura de $.post 
    
    }); //Cerradura de "#btn_aceptar_popup".click
	

//######################################################################################################################################################################//

    // Consulta si estás seguro de cancelar la operación
	 $("#btn_cancelar_popup").click(function(){
			$("#dialog-success-popup").html("<span>¿Está seguro que desea cancelar la operación?</span>");
				$("#dialog-success-popup").dialog({
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
						   $("#back_ventana").css("visibility","hidden")
						},
				    	
				    	No: function(){
				    	  $(this).dialog("close");
				    	}   
				    }
				});
		    
		    $("#dialog-success-popup").dialog("open");		    
	});	

}); //Cerradura del $document.ready

			