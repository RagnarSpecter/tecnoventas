//JavaScript Document: Validaciones
$(document).ready(function() {
    
	$("#btn_aceptar").click(function(){
		
		// Se asignan los valores de los campos a las variables
		
		//Primero se captura el contenido de la cedula con los puntos
		var cedula = $("#cedula").val();
		// Luego se le quita los puntos para trabajar con el valor
		cedula = cedula.replace(/\./g,'') 

		var nombres   = $("#nombres").val();
		var apellidos = $("#apellidos").val();
		var celular   = $("#cel_nro").val();
		var fecha     = $("#fecha_alta").val();
		
		// Validación del campo cedula
		if( cedula == "" || isNaN( cedula ) ){
			$("#dialog-success").html("<span>Por favor ingrese un número de cédula correcto</span>");
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

		// Validación del campo nombres
		if( nombres == "" || /^[a-zA-Z]*/.test(nombres) == false ){
			$("#dialog-success").html("<span>Por favor ingrese un nombre válido</span>");
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

		// Validación del campo apellidos
		if( apellidos == "" || /^[a-zA-Z]*/.test(apellidos) == false ){
			$("#dialog-success").html("<span>Por favor ingrese un apellido válido</span>");
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

		// Validación del campo celular
		if( celular == "" || !(/^\d{4}-\d{6}$/.test(celular)) ){
			$("#dialog-success").html("<span>Por favor ingrese un número de celular con formato válido</span>");
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

		// Validación del campo fecha
		if( fecha == "" ){
			$("#dialog-success").html("<span>Por favor ingrese una fecha</span>");
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
		
		$("#form_cliente").submit();
	});
	
	  
//###############################################################################################################################//

	  
	var valor = $("#hidden").val();
	
	// Validación de cédula repetida
	if (valor == 2)
	{
			$("#dialog-success").html("<span>Número de cédula ya existe!</span>");
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
			$("#dialog-success").html("<span>Cliente insertado exitosamente!</span>");
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

			