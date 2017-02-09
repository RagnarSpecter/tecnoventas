$(document).ready(function(){

	// Evento para que al seleccionar una familia del select, cargue el select de categorias con la lista de 
	// categorias relacionadas con la familia
	$("#familia").change(function() {
		
		// Se captura el valor del select de familia en la variable cod_familia
		var cod_familia = $("#familia").val();
		
		// Envio por post el valor del select familia a una tabla intermedia y de alli, cargo lo devuelto
		// en el span contenedor_select_categoria
		$("#contenedor_select_categoria").load("select_categoria_ajax.php", {"cod_familia":cod_familia});

		// Una vez cargado el select de categoria, dependiente de la familia seleccionada,
		// se aprovecha para mostrar ya una lista completa de los productos pertenecientes
		// a la familia seleccionada.
		$("#contenedor_cargando").show( 'slow', function() {
			$("#contenedor_lista_productos").load( "lista_productos_familia_venta_ajax.php", { "cod_familia" : cod_familia }, function(retorno){
				$("#contenedor_cargando").hide( 'slow');
			});   	
		});
	});

	// Evento para que al seleccionar una CATEGORIA del select, 
	// cargue el select de MARCAS RELACIONADAS a la categoria.
	$("#contenedor_filtro_productos").on( "change", "#categoria", function() {
		
		// Se captura el valor del select de familia en la variable cod_familia
		var cod_categoria = $("#categoria").val();
		
		// Envio por post el valor del select familia a una tabla intermedia y de alli, cargo lo devuelto
		// en el span contenedor_select_categoria
		$("#contenedor_select_marca").load("select_marca_ajax.php", {"cod_categoria":cod_categoria});

		// Una vez cargado el select de categoria, dependiente de la familia seleccionada,
		// se aprovecha para mostrar ya una lista completa de los productos pertenecientes
		// a la familia seleccionada.
		$("#contenedor_cargando").show( 'slow', function() {
			$("#contenedor_lista_productos").load( "lista_productos_categoria_compra_ajax.php", { "cod_categoria" : cod_categoria }, function(retorno){
				$("#contenedor_cargando").hide( 'slow');
			});
		});
	});

	// Evento que se activa al seleccionar una de las MARCAS del select, 
	// cargue el select de MODELOS con aquellos modelos relacionados a la marca
	$("#contenedor_filtro_productos").on( "change", "#marca", function() {
		
		// Se captura el valor del select de familia en la variable cod_familia
		var cod_categoria = $("#categoria").val();
		var cod_marca = $("#categoria").val();
		
		// Envio por post el valor del select familia a una tabla intermedia y de alli, cargo lo devuelto
		// en el span contenedor_select_categoria
		$("#contenedor_select_modelo").load("select_modelo_ajax.php", {"cod_categoria":cod_categoria, "cod_marca":cod_marca});
	});

	// Evento para que al hacer click sobre un checkbox, muestre las opciones de compra,
	// y al mismo tiempo, bloquee la opcion de compra del resto de los productos.
	$( '#contenedor_lista_productos' ).on( 'click', '.check_vender', function() {

		// Guardo el numero de orden del elemento en la lista, almacenado en el nombre del elemento
		var nro_elemento = $(this).attr('name');

		if( $(this).is(':checked') ){
			$(this).toggleClass('check_vender checked');

			$('.check_vender').each(function(i){
				$(this).attr('disabled','true');
			});

			$('#contenedor_costo_y_cantidad_' + nro_elemento).show('slow');
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

		$(this).toggleClass('checked check_vender');
			
		$('#contenedor_costo_y_cantidad_' + nro_elemento).hide();

		$('.check_vender').each(function(i){
			$(this).removeAttr('disabled');
		});
		
	});
});