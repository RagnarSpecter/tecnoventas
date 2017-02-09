$(document).ready(function(){

	// Accion para que, al seleccionar una de las cuentas del combo box, 
	// cargue el saldo de la cuenta en el input
	$("#cuentas").change(function(){

		var cuenta = $("#cuentas").val();

		if( cuenta != 0 ){
			
			$.post( "saldo_cuenta_ajax.php", {"codigo_cuenta":cuenta}, function( retorno ){
				$("input[name=saldo]").attr({
					value: retorno
				});
			});
		}else{
			$("input[name=saldo]").attr({
				value: ""
			});
		}	
	});

	// Accion para que, al seleccionar la operacion de trasladar saldo entre cuentas, 
	// aparezca la fila donde esta el combo box para elegir a que cuenta se va a ir.
	$("#operaciones").change(function(){

		var operacion = $("#operaciones").val();

		if( operacion == 3 ){
			$("#cuenta_a_transferir").show();
		}else{
			$("#cuenta_a_transferir").hide();
		}
	});


	$("#guardar").click(function(){

		// Variables donde se van a guardar las opciones de los selects e inputs
		var cuenta              = $("#cuentas").val();
		var operacion           = $("#operaciones").val();
		var cuenta_a_transferir = $("#cuentas_a_transferir").val();
		var monto               = $("#monto").val();

		// Como el valor de monto viene con los separadores de miles, debo quitarlos
		// antes de enviar al metodo de php.
		monto = monto.replace(/\./g,'');

		// Verificacion si se ha seleccionado correctamente una cuenta
		if( cuenta == 0 ){
			$( "#alerta" ).dialog({
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
		    	// title: "",
		    	buttons: {
			    	OK: function() {
			        	$( this ).dialog( "close" );
			        }
			    }
			});
			$("#alerta").html("<span>Por favor seleccione una cuenta a ser afectada</span>");
			$("#alerta").dialog("open");
			return false;
		}

		// Verificacion si se ha seleccionado correctamente una operacion
		if( operacion == 0 ){
			$( "#alerta" ).dialog({
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
		    	// title: "",
		    	buttons: {
			    	OK: function() {
			        	$( this ).dialog( "close" );
			        }
			    }
			});
			$("#alerta").html("<span>Por favor seleccione un tipo de operacion a realizar</span>");
			$("#alerta").dialog("open");
			return false;
		}

		// En el caso que la operacion corresponda a una transferencia, entonces se verifica
		// que se seleccione una cuenta a la cual se transferira dinero
		if( operacion == 3 ){
			if( cuenta_a_transferir == 0 ){
				$( "#alerta" ).dialog({
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
			    	// title: "",
			    	buttons: {
				    	OK: function() {
				        	$( this ).dialog( "close" );
				        }
				    }
				});
				$("#alerta").html("<span>Por favor seleccione la cuenta a la cual se realizara la transferencia</span>");
				$("#alerta").dialog("open");
				return false;
			}				
		}

		// Verificacion del campo monto
		if( monto === undefined || monto == 0 ){
			$( "#alerta" ).dialog({
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
		    	// title: "",
		    	buttons: {
			    	OK: function() {
			        	$( this ).dialog( "close" );
			        }
			    }
			});
			$("#alerta").html("<span>Por favor ingrese un monto valido</span>");
			$("#alerta").dialog("open");
			return false;
		}

		$("#contenedor_cargando").fadeIn(1000, function(){
			
			$.post( "procesamiento_modificar_saldos_ajax.php", 
					{	"cod_cuenta" 				: cuenta,
						"cod_operacion" 			: operacion,
						"cod_cuenta_a_transferir" 	: cuenta_a_transferir,
						"monto" 					: monto
					}, 
					function( retorno ){
				$("#contenedor_cargando").fadeOut(1000, function(){
					$( "#alerta" ).dialog({
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
				    	// title: "",
				    	buttons: {
					    	OK: function() {
					        	$( this ).dialog( "close" );
					        	location.reload();
					        }
					    }
					});
					$("#alerta").html("<span>" + retorno + "</span>");
					$("#alerta").dialog("open");
					

					/*// Una vez concluido el proceso de modificacion, se resetean los valores de los campos
					$("#cuentas").val(0);
					$("#saldo").val("");
					$("#operaciones").val(0);
					$("#cuentas_a_transferir").val(0);
					$("#monto").val("");*/			
				});
			});
		});		
	});
});


