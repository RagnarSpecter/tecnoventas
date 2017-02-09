//Validaciones referentes a los 'popup´s'
$(document).ready(function(){
    
	   //Al hacer click en 'Agregar marca' se hace visible el ABM para cargar dinámicamente la marca faltante.	
		$("#container_form").on("click", "#popup", function(){
          	
          	//Se captura en una variable el ID de la categoría seleccionada en el Combobox
          	var valor_cat = $('#categoria').val() 
          	
          	//Se asigna a la ventana modal la categoría elegida en el ABM nuevo_producto
			$('#categoria_popup option[value=' + valor_cat + ']').attr('selected', 'selected')
			
			//Se deshabilita el Combobox de Categoría
			$("#categoria_popup").attr("disabled", "disabled")

			//Se hace visible la ventana modal
			$("#back_ventana").css("visibility","visible")

	    })

	   //Al hacer click en 'Agregar modelo' se hace visible el ABM para cargar dinámicamente el modelo faltante.	
		$("#container_form").on("click", "#popup_modelo", function(){
          	
          	//Se captura en una variable el id de la categoría seleccionada en el Combobox
          	var valor_cat = $('#categoria').val()
          	//Se captura en una variable el id de la marca seleccionada en el Combobox
          	var valor_mar = $('#marca').val()	

          	//Se asigna a la ventana modal la categoría elegida en el ABM nuevo_producto
			$('#categoria_popup_modelo option[value=' + valor_cat + ']').attr('selected', 'selected')
			//Se asigna a la ventana modal la marca elegida en el ABM nuevo_producto
			$('#marca_popup_modelo option[value=' + valor_mar + ']').attr('selected', 'selected')
			
			//Se deshabilita el Combobox de Categoría
			$('#categoria_popup_modelo').attr('disabled', 'disabled')	
			//Se deshabilita el Combobox de Marca 	
			$('#marca_popup_modelo').attr('disabled', 'disabled')
			
			//Se hace visible la ventana modal
			$('#back_ventana_modelo').css('visibility','visible')	
								
		})		    

})

			