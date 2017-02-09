//JavaScript Document: Validaciones
$(document).ready(function() {
    
	$("#btn_aceptar").click(function(){
		
		var descripcion = $("#desc_plataforma").val();
	
		// Validación del campo descripcion
		if( descripcion == "" ){
			$("#dialog-success").html("<span>Por favor ingrese una plataforma</span>");
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

		$("#form_plataforma").submit();
    });	
	
	  
//###############################################################################################################################//

	  
	var valor = $("#hidden").val();
	
	// Validación de plataforma repetida
	if (valor == 2)
	{
			$("#dialog-success").html("<span>La plataforma ingresada ya existe!</span>");
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
			$("#dialog-success").html("<span>Plataforma insertada exitosamente!</span>");
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

			