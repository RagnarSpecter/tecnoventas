
$(document).ready(function(){
                
            //Al hacer click en el radiobutton 'generado', el label y textbox de 'Ingrese_aqui_el_codigo' desaparecen. 
            $("#generado").click(function(){

          	 $("#ingrese").css("visibility","hidden")//label
          	 $("#codigo").css("visibility","hidden")//textbox			

            })

            //Al hacer click en el radiobutton 'de barras', el label y textbox de 'Ingrese_aqui_el_codigo' aparecen.
            $("#barras").click(function(){

          	  $("#ingrese").css("visibility","visible")//label
          	  $("#codigo").css("visibility","visible")//textbox			

            })	

            //Al hacer click en el radiobutton 'casual', el label y textbox de 'Stock_mínimo' desaparecen. 
            $("#casual").click(function(){

          	  $("#stock").css("visibility","hidden")//label
          	  $("#stock_min").css("visibility","hidden")//textbox			

            })

            //Al hacer click en el radiobutton 'diaria', el label y textbox de 'Stock_mínimo' aparecen.
            $("#diaria").click(function(){

          	  $("#stock").css("visibility","visible")//label
          	  $("#stock_min").css("visibility","visible")//textbox			

            })	

            $("#container").on("change","#categoria",function(){
			  var categoria = $("#categoria").val()
            
				//Si cod_categoria = 18 (juego) se realizan las modificaciones varias
				if (categoria == 18)
                {
               		$("#contenedor_marca").load("rellena_option_marca_ajax.php",{"cod_categoria":categoria}, function(){ })
                	 
                	//Se cachea el cod_marca = 1 (--NINGUNO--) para pasarle como parámetro a la fn listar_marca_modelo() del archivo 'rellena_option_modelo_ajax.php'
                	var marca = 1;	
					
                	$("#contenedor_modelo").load("rellena_option_modelo_ajax.php",{"cod_marca":marca,"cod_categoria":categoria}, function(){ })
                	
                	//Se visualizan tanto el label como el textbox de Plataforma. 
                	$("#plataforma_label").css("visibility","visible")//label
          			$("#plataforma").css("visibility","visible")//textbox	
                	
                    //Se ocultan tanto el label como el textbox de Color.
                	$("#color_label").css("visibility","hidden")
                	$("#color").css("visibility","hidden")
				
                	//Se ocultan tanto el label como el textbox de Marca.
                	$("#marca_label").css("visibility","hidden")
                	$("#contenedor_marca").css("visibility","hidden")	

                	//Se ocultan tanto el label como el textbox de Modelo.
                	$("#modelo_label").css("visibility","hidden")
                	$("#contenedor_modelo").css("visibility","hidden") 
				
				//Si cod_categoria no es 18 (juego) se vuelven a dejar en su estado original los Textbox y Selects
				}else{

				    //Se ocultan tanto el label como el textbox de Plataforma
				    $("#plataforma_label").css("visibility","hidden")//label
          			$("#plataforma").css("visibility","hidden")//textbox 

          			//Se visualizan tanto el label como el textbox de Color.
                	$("#color_label").css("visibility","visible")
                	$("#color").css("visibility","visible")
				
                	//Se visualizan tanto el label como el textbox de Marca.
                	$("#marca_label").css("visibility","visible")
                	$("#contenedor_marca").css("visibility","visible")	

                	//Se visualizan tanto el label como el textbox de Modelo.
                	$("#modelo_label").css("visibility","visible")
                	$("#contenedor_modelo").css("visibility","visible") 
				}
            })    	
			   
		   //Cuando se elige una opción del Select de familia, se filtran las categorías de dicha familia.
			$("#familia").change(function() {

                var familia = $("#familia").val();				
					$("#contenedor_categoria").load("rellena_option_categoria_ajax.php",{"cod_familia":familia}, function(){
				})
			
			})
			
		   //Cuando se elige una opción del Select de categoría, se filtran las marcas de dicha categoría.			
			$("#container").on("change","#categoria",function(){

                var categoria = $("#categoria").val()			
					$("#contenedor_marca").load("rellena_option_marca_ajax.php",{"cod_categoria":categoria}, function(){
				})
			
			})

			//Cuando se elige una opción del Select de marca, se filtran los modelos de dicha marca.			
			$("#container").on("change","#marca",function(){

                    var marca = $("#marca").val()
                var categoria = $("#categoria").val()			
					
					$("#contenedor_modelo").load("rellena_option_modelo_ajax.php",{"cod_marca":marca,"cod_categoria":categoria}, function(){
				})
			
			})
	
			
	//Se programan las validaciones que se activarán al darle click al botón Aceptar	
	$("#btn_aceptar").click(function(){
		
		// Se asignan los valores de los campos a las variables
		var familia   = $("#familia").val();
		var categoria = $("#categoria").val();
		var marca     = $("#marca").val();
		var modelo    = $("#modelo").val();
		
		// Validación del select Familia
		if( familia == 0 ){
			$("#dialog-success").html("<span>Por favor seleccione una familia de la lista</span>");
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

		// Validación del select Categoría
		if( categoria == 0 ){
			$("#dialog-success").html("<span>Por favor seleccione una categoría de la lista</span>");
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

		// Validación del select Marca
		if( marca == 0 ){
			$("#dialog-success").html("<span>Por favor seleccione una marca de la lista</span>");
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

		// Validación del select Modelo
		if( modelo == 0 ){
			$("#dialog-success").html("<span>Por favor seleccione un modelo de la lista</span>");
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

		$("#form_producto").submit();
	
	}); //Cerradura de "#btn_aceptar".click

	
	//Se cachea el selector hidden para verificar si retornó con valor 1 o 2
	var valor = $("#hidden").val();
			
			// Validación de código de barras repetido
			if (valor == 2)
			{
					$("#dialog-success").html("<span>Código de barras ya existe!</span>");
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
					$("#dialog-success").html("<span>Producto insertado exitosamente!</span>");
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
	
	  
//#######################################################################################################################################################################//

	
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
	   
	})

}) //Cerradura del $document.ready


  
  //Función que aplica los puntos de miles al campo 'Precio de Venta'.			
    function puntos_miles(donde,caracter){
		
		pat = /[\*,\+,\(,\),\?,\,$,\[,\],\^]/
		valor = donde.value
		largo = valor.length
		crtr = true
		if(isNaN(caracter) || pat.test(caracter) == true)
		{
			if (pat.test(caracter)==true)
			{ 
				caracter = "'\'" + caracter
			}
			caracter = new RegExp(caracter,"g")
			valor = valor.replace(caracter,"")
			donde.value = valor
			crtr = false
		
		}else{
			var nums = new Array()
			cont = 0
			for(m=0;m<largo;m++)
			{
				if(valor.charAt(m) == "." || valor.charAt(m) == " ")
				{
					continue;
				}else{
					nums[cont] = valor.charAt(m)
					cont++
				}
			}
		}
		
		var cad1="",cad2="",tres=0
		if(largo > 3 && crtr == true)
		{
			for (k=nums.length-1;k>=0;k--)
			{
				cad1 = nums[k]
				cad2 = cad1 + cad2
				tres++
				if((tres%3) == 0)
				{
					if(k!=0)
					{
						cad2 = "." + cad2
					}
				}
			}
			donde.value = cad2
		}
	}	
	