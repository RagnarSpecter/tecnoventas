//JavaScript Document: Validaciones
$(document).ready(function() {
    
	$("#btn_aceptar").click(function(){
		
		var descripcion = $("#desc_modelo").val();
	
		// Validación del campo descripcion
		if( descripcion == "" ){
			$("#dialog-success").html("<span>Por favor ingrese un modelo</span>");
			$("#dialog-success").dialog({
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
			$("#dialog-success").dialog("open");
			return false;
		}

		$("#form_modelo").submit();
    });	
	
	  
//###############################################################################################################################//

	  
	var valor = $("#hidden").val();
	
	// Validación de modelo repetido
	if (valor == 2)
	{
			$("#dialog-success").html("<span>El modelo ingresado ya existe!</span>");
				$("#dialog-success").dialog({
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
		    $("#dialog-success").dialog("open");		
	}	
	
	// Mensaje de éxito tras la inserción
	if (valor == 1)
	{
			$("#dialog-success").html("<span>Modelo insertado exitosamente!</span>");
				$("#dialog-success").dialog({
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
		    $("#dialog-success").dialog("open");		
	}

    // Consulta si estás seguro de cancelar la operación
	$("#btn_cancelar").click(function(){
			$("#dialog-success").html("<span>¿Está seguro que desea cancelar la operación?</span>");
				$("#dialog-success").dialog({
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
						   location.href="inicio.php";
						},
				    	
				    	No: function(){
				    	  $(this).dialog("close");
				    	}   
				    }
				});
		    
		    $("#dialog-success").dialog("open");
	});	

});

			