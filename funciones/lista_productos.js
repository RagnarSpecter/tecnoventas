// FUNCION PARA VER LA COLUMNA "PRECIO DE COSTO" AL HACER CLICK SOBRE
// EL CHECKBOX "EXTENDER"
$(document).ready(function(){
	$("#ver_costo").click(function() {  
        if($("#ver_costo").is(':checked')) {  
            $(".costo").show();  
        } else {  
            $(".costo").hide();  
        }  
    });  
});

//FUNCION PARA CARGAR LA LISTA DE PRODUCTOS POR FAMILIA SELECCIONADA
//ADEMAS BLOQUEA EL CAMPO BUSCAR MIENTRAS HAYA OPCION SELECCIONADA.
$(document).ready(function(){
	$("#familia").change(function(){

		var cod_familia = $('#familia').val();

		if(cod_familia != 0){
			
			// BLOQUEO EL CAMPO "BUSCAR"			
			$("#buscar").prop('disabled', true);
			
			// HAGO APARECER LA IMAGEN DE CARGANDO
			$("#contenedor_cargando").fadeIn(1000, function(){
				// CARGO LA LISTA DE PRODUCTOS EN BASE A LA FAMILIA SELECCIONADA
				$("#contenedor_lista_productos").load("lista_productos_por_familia_ajax.php", {"cod_familia":cod_familia}, function(){
					// CARGO EL SELECT DE CATEGORIAS DEPENDIENTES DE LA FAMILIA SELECCIONADA
					$("#contenedor_select_categoria").load("select_categoria_ajax.php", {"cod_familia":cod_familia}, function(){
						// HAGO DESAPARECER LA IMAGEN DE CARGANDO
						$("#contenedor_cargando").fadeOut(1);
					});
				});
			});
		}else{
			// DESBLOQUEO EL CAMPO "BUSCAR"
			$("#buscar").prop('disabled', false);

			// HAGO APARECER LA IMAGEN DE "CARGANDO"
			$("#contenedor_cargando").fadeIn(1000, function(){
				// CARGO LA LISTA COMPLETA DE PRODUCTOS
				$("#contenedor_lista_productos").load("lista_productos_ajax.php", function(){
					// INSERTO UN SELECT VACIO
					$("#contenedor_select_categoria").html('<select name="categoria"><option value=""> -Elegir una Categoria- </option></select>');
					// HAGO DESAPARECER LA IMAGEN DE "CARGANDO"
					$("#contenedor_cargando").fadeOut(1);		
				});
			});
		}
	});
});

//FUNCION PARA CARGAR LA LISTA DE PRODUCTOS POR CATEGORIA SELECCIONADA
//ADEMAS BLOQUEA EL CAMPO BUSCAR MIENTRAS HAYA OPCION SELECCIONADA.
$(document).ready(function(){
	$("#contenedor_opciones").on( "change", "#categoria", function() {

		// CAPTURO EL VALOR DEL SELECT "FAMILIA"
		var cod_familia = $('#familia').val();

		// CAPTURO EL VALOR DEL SELECT "CATEGORIA"
		var cod_categoria = $('#categoria').val();

		// SI EL VALOR CAPTURADO ES DISTINTO DE 0
		if( cod_categoria != 0 ){
			
			// BLOQUEO EL CAMPO BUSCAR
			/*$("#buscar").prop('disabled', true);*/

			// HAGO APARECER LA IMAGEN DE CARGANDO
			$("#contenedor_cargando").fadeIn(1000, function(){
				// CARGO LA LISTA DE PRODUCTOS EN BASE A LA CATEGORIA SELECCIONADA
				$("#contenedor_lista_productos").load("lista_productos_por_categoria_ajax.php", {"cod_categoria":cod_categoria}, function(){
					// HAGO DESAPARECER LA IMAGEN DE CARGANDO
					$("#contenedor_cargando").fadeOut(1);
			});
		});
		}else{
			// HAGO APARECER LA IMAGEN DE CARGANDO
			$("#contenedor_cargando").fadeIn(1000, function(){
				// CARGO LA LISTA DE PRODUCTOS EN BASE A LA CATEGORIA SELECCIONADA
				$("#contenedor_lista_productos").load("lista_productos_por_familia_ajax.php", {"cod_familia":cod_familia}, function(){
					// HAGO DESAPARECER LA IMAGEN DE CARGANDO
					$("#contenedor_cargando").fadeOut(1);
				});
			});
		}

		
	});
});

// FUNCION PARA HABILITAR O DESHABILITAR EL SELECT DE CATEGORIA
// DE ACUERDO A SI SE UTILIZA O NO EL CAMPO BUSCAR
$(document).ready(function(){
	$("#buscar").focus(function(){
		$("#categoria").prop('disabled', true);
	});	
});

// FUNCION PARA VOLVER A HABILITAR EL SELECT "CATEGORIA" AL SALIR DE BUSCAR
$(document).ready(function(){
	$("#buscar").focusout(function(){
		$("#categoria").prop('disabled', false);
	});	
});

// FUNCION PARA BUSQUEDA EN TIEMPO REAL DE TEXTO INGRESADO EN EL
// CAMPO BUSCAR
$(document).ready(function(){
	$("#buscar").keyup(function(){

		var texto = $("#buscar").val();
		
		$("#contenedor_lista_productos").load("lista_productos_busqueda_ajax.php", {"texto":texto}, function(){
			$("#contenedor_lista_productos").highlight(texto);
		});
	});
});



























// FUNCION PARA ABRIR EL POPUP AL HACER CLICK SOBRE EL MODELO DEL PRODUCTO
$(document).ready(function(){
	$("#contenedor_lista_productos").on("click", ".mostrar_foto", function(){

		var direccion = $(this).attr("name");
		direccion = direccion.substring(28);
		direccion = direccion.replace(/\\/g,"/");
		
		$('#popup').css('background-image', 'url(' + direccion + ')');
		$('#popup').css('background-size', 'contain' );
		$('#popup').css('background-repeat', 'no-repeat' );
		// $('#popup').html(direccion); 
		$('#popup').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        // $('.popup-overlay').height($(window).height());

	});
});

// FUNCION PARA CERRAR EL POPUP AL TERMINAR DE MIRAR LA IMAGEN
$(document).ready(function(){
	$("html").on("click", ".popup-overlay", function(){

		$('#popup').fadeOut('slow');
        $('.popup-overlay').fadeOut('slow');
        // return false;

	});
});