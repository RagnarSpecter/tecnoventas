// Control de Validacion para login al sistema

/*---> Validacion para los datos ingresados en el index.php <---*/
$(document).ready(function(){
	$("#login").submit(function(){
		
		// Guardo en las variables los valores que vienen de cedula y password
		var cedula = $("#cedula").val();
		var password    = $("#password").val();

		// Compruebo los valores de cedula y password
		if( cedula == "" || isNaN(cedula ))
		{
			Sexy.alert("Debe ingresar su numero de cedula");
			$("#cedula").focus();
			return false;			
		}
		
		if( password == "" || /^\s+$/.test(password) )
		{
			Sexy.alert("Debe ingresar su PASSWORD");
			$("#password").focus();
			return false;			
		}

		$("#login").submit();
	})

	var error = $("#error").val();

	if( error == 2 ){
		Sexy.alert( "Password incorrecto" );
	}

	if( error == 1 ){
		Sexy.alert( "Cedula incorrecta" );
	}

})