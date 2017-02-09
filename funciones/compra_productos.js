$(document).ready(function(){

	// Evento donde, al hacer click sobre la opcion INSUMOS BASICOS,
	// este hace desaparecer la opcion PRODUCTOS
	$("#btn_compra_insumos").click(function(){
		$("#btn_compra_productos").fadeOut('slow', function(){
			$("#btn_compra_insumos").css("margin-right", "0px");
			$("#contenedor_detalles_compra_insumos").show('slow');
		});
	});

	// Evento donde, al hacer click sobre la opcion PRODUCTOS,
	// este hace desaparecer la opcion INSUMOS BASICOS
	$("#btn_compra_productos").click(function(){
		$("#btn_compra_insumos").fadeOut('slow', function(){
			$("#contenedor_detalles_compra_productos").show('slow');
		});
	});

	// Evento para que al seleccionar una familia del select, 
	// cargue el select de categorias con la lista de 
	// categorias relacionadas con la familia
	$("#familia").change(function() {
		
		// Se captura el valor del select de familia en la variable cod_familia
		var cod_familia = $(this).val();

		// Condicional para que al volver a dejar en limpio el select FAMILIA,
		// se vacie toda la pantalla de vuelta.
		if(cod_familia != 0){
			
			// Cargo el select de CATEGORIAS que estan relacionadas a la familia seleccionada.
			$("#contenedor_select_categoria").load("select_categoria_ajax.php", {"cod_familia":cod_familia});

			// Cargo la lista de productos que pertenecen a la familia seleccionada.
			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").load( "lista_productos_familia_compra_ajax.php", { "cod_familia" : cod_familia }, function(retorno){
					$("#contenedor_cargando").hide( 'slow');
				});
			});
		}else{
			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").empty();
			});

			$("#contenedor_cargando").hide( 'slow');
		}		
	});

	// Evento para que al seleccionar una CATEGORIA del select, 
	// cargue el select de MARCAS RELACIONADAS a la categoria.
	$("#contenedor_detalles_compra_productos").on( "change", "#categoria", function() {
		
		// Se capturan los valores del select de FAMILIA y de CATEGORIA
		var cod_familia = $("#familia").val();
		var cod_categoria = $(this).val();
		
		// Condicional para que al volver a dejar en limpio el select CATEGORIA,
		// se deje nuevamente la lista de productos de la FAMILIA seleccionada.
		if( cod_categoria != 0 ){

			// Cargo el select de MARCAS relacionadas a la CATEGORIA seleccionada.
			$("#contenedor_select_marca").load("select_marca_ajax.php", {"cod_categoria":cod_categoria});
			
			// Cargo la lista de productos de la CATEGORIA seleccionada
			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").load( "lista_productos_categoria_compra_ajax.php", { "cod_categoria" : cod_categoria }, function(retorno){
					$("#contenedor_cargando").hide( 'slow');
				});
			});
		}else{
			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").load( "lista_productos_familia_compra_ajax.php", { "cod_familia" : cod_familia }, function(retorno){
					$("#contenedor_cargando").hide( 'slow');
				});
			});
		}		
	});

	// Funcion para que al seleccionar una de las MARCAS del select, 
	// cargue el select de MODELOS con
	// aquellos modelos relacionados a la marca
	$("#contenedor_detalles_compra_productos").on( "change", "#marca", function() {
		
		// Se captura el valor del select de familia en la variable cod_familia
		var cod_categoria = $("#categoria").val();
		var cod_marca = $(this).val();
		
		// Condicional para que al volver a dejar en limpio el select MARCA,
		// quede solamente la lista de productos de la CATEGORIA seleccionada.
		if( cod_marca != 0 ){

			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").load( "lista_productos_marca_compra_ajax.php", { "cod_categoria" : cod_categoria, "cod_marca" : cod_marca }, function(retorno){
					$("#contenedor_cargando").hide( 'slow');
				});
			});
		}else{
			$("#contenedor_cargando").show( 'slow', function() {
				$("#contenedor_lista_productos").load( "lista_productos_categoria_compra_ajax.php", { "cod_categoria" : cod_categoria }, function(retorno){
					$("#contenedor_cargando").hide( 'slow');
				});
			});
		}
	});





















































	// Evento para que al hacer click sobre un checkbox, muestre las opciones de compra,
	// y al mismo tiempo, bloquee la opcion de compra del resto de los productos.
	$( '#contenedor_lista_productos' ).on( 'click', '.check_comprar', function() {

		// Guardo el numero de orden del elemento en la lista, almacenado en el nombre del elemento
		var nro_elemento = $(this).attr('name');

		if( $(this).is(':checked') ){
			$(this).toggleClass('check_comprar checked', function(){
				$('.check_comprar').each(function(i){
					$(this).attr('disabled','true');
				});
				
				$('#contenedor_costo_y_cantidad_' + nro_elemento).show('slow');
			});
		}

		// NOTA: toggleClass lo que hace es cambiar el nombre de la clase del elemento, donde 
		// primero se escribe el nombre original de la clase y
		// a continuacion, el nombre nuevo e la clase
	});

	// Evento para que la hacer click sobre un checkbox que esta mostrando las opciones de compra,
	// vuelva a ocultar las opciones y habilite nuevamente el resto de los checkboxes.
	$( '#contenedor_lista_productos' ).on( 'click', '.checked', function() {

		// Guardo el numero de orden del elemento en la lista, almacenado en el nombre del elemento
		var nro_elemento = $(this).attr('name');

		$(this).toggleClass('checked check_comprar', function(){
			
			$('#contenedor_costo_y_cantidad_' + nro_elemento).hide();

			$('.check_comprar').each(function(i){
				$(this).removeAttr('disabled');
			});
		});
	});

	// EVENTO QUE DESENCADENA TODO EL PROCESO DE INSERCION DE DATOS DE UNA COMPRA DE UN PRODUCTO.
	$( '#contenedor_lista_productos' ).on( 'click', '#btn_comprar', function() {

		
		// GUARDO EL NUMERO DE ORDEN DEL ELEMENTO EN LA LISTA, ALMACENADO EN EL NOMBRE DEL ELEMENTO
		var cod_producto = $(this).attr('name');

		// CAPTURO EL VALOR QUE EXISTE EN EL CAMPO COSTO DEL PRODUCTO
		var costo        = $( '#costo_producto_' + cod_producto ).val();
		var cantidad     = $( '#cantidad_' + cod_producto ).val();
		var forma_pago   = $( '#formas_pago_' + cod_producto ).val();

		// VERIFICO QUE SE HAYA CARGADO EFECTIVAMENTE EL VALOR, CASO CONTRARIO, PARO EL SISTEMA.
		if( costo === "" ){
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
		
		// LE SACO LOS SEPARADORES DE MILES A COSTO ANTES DE ENVIAR PARA SU INSERCION
		costo = costo.replace(/\./g,'');

		// ENVIO TODOS LOS DATOS NECESARIOS PARA PROCESAR LA INSERCION DE LA COMPRA
		$.post( "procesar_compra_ajax.php", { "costo":costo, "cantidad":cantidad, "forma_pago":forma_pago, "cod_producto":cod_producto }, function( respuesta ){
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
		    	buttons: {
			    	OK: function() {
			        	$( this ).dialog( "close" );
			        	location.reload("1000");
			        }
			    }
			});
			$("#alerta").html("<span>" + respuesta +"</span>");
			$("#alerta").dialog("open");			
		});
	
	});	
});

