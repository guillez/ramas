// Variable Global que juega de intermediaro con la BarraProgreso/EstadoPreguntas
var mediator = new MediatorDependientesBarraProgreso();

    function validar_bloque(id){
        $("#tab-"+id+" .form-control ").each(function(){
                $("#formulario").validate().element(this);
        })
    }

    function _hacer_obligatoria(objeto)
    {
        var etiqueta = objeto.innerHTML;
        if (etiqueta.indexOf("<b>*</b>")<0)
        {
           etiqueta +="<b>*</b>"; 
        }
        objeto.innerHTML = etiqueta;
    }

    function _sacar_obligatoria(objeto)
    {
        var etiqueta = objeto.innerHTML;
        etiqueta = (etiqueta.replace("<b>*</b>", ""));
        objeto.innerHTML = etiqueta;
    }

    function _es_obligatoria(objeto)
    {
        return objeto.innerHTML.endsWith("<b>*</b>");
    }
    
    function _deshabilitar_elemento(objeto)
    {
        if (objeto.type != 'radio' && objeto.type != "checkbox" )
        {
            objeto.value = null;
        }
        if (objeto.nodeName === "SELECT"){
                _deshabilitar_select_options(objeto);
        }
            
        objeto.checked = false;
        objeto.disabled = true;
    }
    
    function _deshabilitar_select_options(objeto)
    {
        // Cross-browser solution for unselect options
        $("#"+objeto.id).val([]);
    }

    function _habilitar_elemento(objeto)
    {
        objeto.disabled = false;
    }	

    function _limitar_selecciones(elemento, maximo)
    {
        var cantidad = 0;
        for (var i = 0; i < elemento.options.length; i++) {
            if (elemento.options[i].selected) {
                cantidad++;
                if (cantidad > maximo) {
                    elemento.options[i].selected = false;
                }
            }
        }
    }
    
    function _limitar_selecciones_checkbox(elemento, maximo)
    {
        var cantidad = 0;
        for (var i = 0; i < elemento.children.length; i++) {
            if (elemento.children[i].checked != 'undefined') {
                if (elemento.children[i].checked) {
                    cantidad++;
                    if (cantidad > maximo) {
                        //alert('No es posible seleccionar mas de '+maximo+' opciones.');
                        elemento.children[i].checked = false;
                    }
                }
            }
        }
    }
    
    function get_preguntas_radio(id)
    {
        var id_preg = 'pk_'+id;
        return $("[name$='"+id_preg+"']");
    }
    
    function get_preguntas_localidad(id)
    {
        var id_preg = 'c_pk_'+id;
        return $("[id^='"+id_preg+"']");
    }

    function get_preguntas(id)
    {
        var id_preg = 'pk_'+id;
        return $("[id$='"+id_preg+"']");
    }

    function get_pregunta_encuesta(id_fuente, id_destino)
    {
        var prefijo = (id_fuente).split('_pk_')[0];
        var pregunta = document.getElementById(prefijo+'_pk_'+id_destino);
        
        if ( pregunta ) {
            return pregunta;
        } else { // Fallback para preguntas de tipo radio
            return get_etiqueta_encuesta(id_fuente, id_destino);
        }
    }

    function get_opcion_check(id_fuente, id_destino_pregunta, id_destino_opcion)
    {
        var prefijo = (id_fuente.id).split('_pk_')[0]; 
        return document.getElementById(prefijo+'_pk_'+id_destino_pregunta+'.'+id_destino_opcion);
    }
    
    function get_etiqueta_encuesta(id_fuente, id_destino)
    {
        var prefijo = (id_fuente).split('_pk_')[0];
        prefijo = prefijo.replace('c', 'd');
        return document.getElementById(prefijo+'_lk_'+id_destino);
    }

    function hacer_obligatoria(fuente, id_objeto)
    {
        var pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        _hacer_obligatoria(pregunta);
        pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).addClass('required');
        $(pregunta).attr('required', 'required');
        
        //si es checkbox se hace item por item
        hacer_obligatoria_elemento_checkbox(id_objeto);
        
        //si es radio se hace item por item
        hacer_obligatoria_elemento_radio(id_objeto);
    }
    
    function hacer_obligatoria_elemento_checkbox(id_objeto)
    {
        var pregunta = $("[name$='_pk_"+id_objeto+"[]']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {
            $(pregunta[i]).attr('required', 'required');
        }
    }
    
    function hacer_obligatoria_elemento_radio(id_objeto)
    {
        var pregunta = $("[name$='_pk_"+id_objeto+"']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {
            $(pregunta[i]).attr('required', 'required');
        }
    }

    function es_obligatoria(fuente, id_objeto)
    {
        var pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        return _es_obligatoria(pregunta);
    }
    
    function sacar_obligatoria(fuente, id_objeto)
    {
        var pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        _sacar_obligatoria(pregunta);
        pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).removeClass('required');
        $(pregunta).removeAttr('required');        
        
        //si es checkbox se hace item por item
        sacar_obligatoria_elemento_checkbox(id_objeto)
        
        //si es radio se hace item por item
        sacar_obligatoria_elemento_radio(id_objeto)
    }

    function sacar_obligatoria_elemento_checkbox(id_objeto)
    {
        var pregunta = $("[name$='_pk_"+id_objeto+"[]']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {
            $(pregunta[i]).removeAttr('required');
        }
    }
    
    function sacar_obligatoria_elemento_radio(id_objeto)
    {
        var pregunta = $("[name$='_pk_"+id_objeto+"']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {
            $(pregunta[i]).removeAttr('required');
        }
    }

    function colapsar_elemento(fuente, id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).parents('.form-group').slideUp('slow');
        
        if ( !pregunta ) {
            return;
        }
        if( typeof pregunta.type === "undefined"){
            deshabilitar_elemento_checkbox(fuente,id_objeto);
            //mediator.actualizarAlDeshabilitarElemento("[name$='_pk_"+id_objeto+"[]']");
            return;
        }

        _deshabilitar_elemento(pregunta);
        mediator.actualizarAlDeshabilitarElemento("[id=c_pk_"+id_objeto+"]");
        return;
    }
    
    function colapsar_elemento_radio(fuente,id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).parents('.form-group').slideUp('slow');
        deshabilitar_elemento_radio(fuente,id_objeto);
    }
    
    function descolapsar_elemento_radio(fuente,id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).parents('.form-group').slideDown('slow');
        habilitar_elemento_radio(fuente,id_objeto);
    }
    
    function _colapsar_elemento(objeto)
    {
        $(objeto).parents('.form-group').slideUp('slow');
        _deshabilitar_elemento(objeto);
    }

    function descolapsar_elemento(fuente, id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        $(pregunta).parents('.form-group').slideDown('slow');
        
        if ( !pregunta ) {
            return;
        }
        if( typeof pregunta.type === "undefined"){
            habilitar_elemento_checkbox(fuente,id_objeto);
            return;
        }

        habilitar_elemento(fuente,id_objeto);
        return;
    }
    
    function _descolapsar_elemento(objeto)
    {
        $(objeto).parents('.form-group').slideDown('slow');
    }

    function deshabilitar_elemento_localidad(fuente, id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        var localidad_display = $(pregunta).next('input');

        //por alguna razón no es está llegando a partir de pregunta al elemento que contiene el link
        //se obtiene acá mismo el elemento a partir del id específico de ese botón
        var prefijo = (fuente.id).split('_pk_')[0];
        var id = 'boton_'+prefijo+'_pk_'+id_objeto;
        var localidad = document.getElementById(id);
        $(localidad).hide();

        _deshabilitar_elemento(pregunta);
        _deshabilitar_elemento(localidad_display);
        localidad_display.val(""); // VER SI ES SEGURO USAR ESTA LINEA DENTRO DE _deshabilitar_elemento(localidad_display)

        mediator.actualizarAlDeshabilitarElemento("[id=c_pk_"+id_objeto+"]");
    }

    function deshabilitar_elemento(fuente, id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        
        if ( pregunta === null ) {
            pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        } else {
            _deshabilitar_elemento(pregunta);
            mediator.actualizarAlDeshabilitarElemento("[id=c_pk_"+id_objeto+"]");
        }
    }
    
    function deshabilitar_elemento_radio(fuente, id_objeto, id_elto)
    {
        var pregunta = $("[name$='c"+id_elto+'_pk_'+id_objeto+"']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {       
            _deshabilitar_elemento(pregunta[i]);
        }

        mediator.actualizarAlDeshabilitarElemento("[name$='"+id_objeto+"']");
    }    
    
    function deshabilitar_elemento_checkbox(fuente, id_objeto,id_elto)
    {
        var pregunta = $("[name$='c"+id_elto+"_pk_"+id_objeto+"[]']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {
            _deshabilitar_elemento(pregunta[i]);
        }

        mediator.actualizarAlDeshabilitarElemento("[name$='_pk_"+id_objeto+"[]']");
    }    
    
    function colapsar_bloque(fuente, id_bloque)
    {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);		

        bloque.find('.controls').each(function() {
            _colapsar_elemento(this.children[0]);
        });
        
        $(bloque).slideUp('slow');
    }
    
    function slideup_bloque(fuente, id_bloque) {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);
        $(bloque).slideUp('slow');
    }
    
    function descolapsar_bloque(fuente, id_bloque)
    {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);
        
        $(bloque).slideDown('slow');
        
        bloque.find('.controls').each(function() {
            _descolapsar_elemento(this.children[0]);
        });
    }
    
    function slidedown_bloque(fuente, id_bloque) {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);
        $(bloque).slideDown('slow');
    }

    function deshabilitar_bloque(fuente, id_bloque)
    {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);		

        bloque.find('.controls').each(function() {
            _deshabilitar_elemento(this.children[0]);
        });
    }

    function habilitar_bloque(fuente, id_bloque)
    {
        var id = get_id_bloque(fuente.id, id_bloque);
        var bloque = $('#'+id);

        bloque.find('.controls').each(function() {
            _habilitar_elemento(this.children[0]);
        });
    }

    function get_id_bloque(id_fuente, id_bloque)
    {
        var prefijo = (id_fuente).split('_pk_')[0];
        prefijo = prefijo.replace('c', 'b');
        return prefijo + '_' + id_bloque;
    }
    
    function habilitar_elemento_localidad(fuente, id_objeto)
    {
        /*
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        var localidad = $(pregunta).siblings('a');
        localidad.show();
        */

        //por alguna razón no es está llegando a partir de pregunta al elemento que contiene el link
        //se obtiene acá mismo el elemento a partir del id específico de ese botón
        var prefijo = (fuente.id).split('_pk_')[0];
        var id = 'boton_'+prefijo+'_pk_'+id_objeto;
        var localidad = document.getElementById(id);
        $(localidad).show();

        mediator.actualizarAlHabilitarElemento("[id=c_pk_"+id_objeto+"]");
    }

    function habilitar_elemento(fuente, id_objeto)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        if ( pregunta == null ) {
           pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        } else if ( pregunta.disabled == true ) {
            _habilitar_elemento(pregunta);
            mediator.actualizarAlHabilitarElemento("[id=c_pk_"+id_objeto+"]");
        }
    }

    function habilitar_elemento_radio(fuente, id_objeto, id_elto)
    {
        var pregunta = $("[name$='c"+id_elto+"_pk_"+id_objeto+"']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {       
            _habilitar_elemento(pregunta[i]);
        }

        mediator.actualizarAlHabilitarElemento("[name$='"+id_objeto+"']");
    }

    function habilitar_elemento_checkbox(fuente, id_objeto, id_elto)
    {
        var pregunta = $("[name$='c"+id_elto+"_pk_"+id_objeto+"[]']");
        var cant = pregunta.length;
        for (var i=0; i<cant; i++) {       
            _habilitar_elemento(pregunta[i]);
        }

        mediator.actualizarAlHabilitarElemento("[name$='_pk_"+id_objeto+"[]']");
    }    
    
    function limitar_selecciones(fuente, id_objeto, maximo)
    {
        var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
        _limitar_selecciones(pregunta, maximo);
    }

    /*
     * Todavia no anda!
     */
    function limitar_selecciones_checkbox(fuente, id_objeto, maximo)
	{
		var pregunta = get_pregunta_encuesta(fuente.id, id_objeto);
		_limitar_selecciones_checkbox(pregunta, maximo);
	}
    
    // La eliminación de opciones por posición en el arreglo genera problemas en la recarga de los combos.
    // Se implementa una nueva operación.

    function remover_opcion(objeto, codigo)
    {
        objeto.find('option[value='+codigo+']').remove();
    }

    function colapsar_etiqueta(fuente, id_objeto)
    {
        var pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        $(pregunta).slideUp('slow');
    }
	
    function descolapsar_etiqueta(fuente, id_objeto)
    {
        var pregunta = get_etiqueta_encuesta(fuente.id, id_objeto);
        $(pregunta).slideDown('slow');
    }
    
    function valores_unicos(a, b)
    {
        var c = a.concat(b);
        for(var i=0; i<c.length; ++i) {
            for(var j=i+1; j<c.length; ++j) {
                if(c[i] === c[j])
                    c.splice(j, 1);
            }
        }
        return c;
    }
    
    function intersect(a, b)
    {
        if (a && b) {
            var t;
            if (b.length > a.length) t = b, b = a, a = t; // indexOf to loop over shorter
            return a.filter(function (e) {
                if (b.indexOf(e) !== -1) return true;
            });
        } else {
            return [];
        }
    }
    
    function terminar_encuesta() 
    {        
        if ( confirm("El formulario será registrado como definitivo, si Termina ya no podrá cambiar los datos ingresados. ¿Desea continuar?") == true ) {
            return true;
        } else {
            return false;
        }
    }

    function buscar_elementos_checkbox_seleccionados(id, elemento) {
        var arreglo = [];
        var elementos = $("[name='c"+elemento+"_pk_"+ id +"[]']").filter(':checked');

        elementos.each(function() {
           arreglo.push($(this).val());
        });

        return arreglo;
    }
    
   
