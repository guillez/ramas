<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class ei_abm_preguntas extends bootstrap_formulario
{
	function extender_objeto_js()
	{
		$mensaje_obligatoriedad = 'es obligatorio.';
		
		echo "
		    
		    //Uso esta variable para el control de actualizaciones al cambiar estado del componente numero
		    //respecto al texto enriquecido (ya que al usar este se oculta un ef y se muestra otro).
		    var estado_combo_anterior = 1;
		    
            //---- Procesamiento de EFs --------------------------------
		
            {$this->objeto_js}.evt__tabla_asociada_codigo__procesar = function(es_inicial)
            {
                var tabla_asociada = this.ef('tabla_asociada').get_estado();
                
                if (tabla_asociada.slice(0, 3) == 'ta_') {
                    
                    //Se accede a través del DOM porque el set_estado() del combo dispara un ciclo recursivo
                    document.getElementById('ef_form_200000013_formulariotabla_asociada_codigo').value = 'codigo';
                    this.ef('tabla_asociada_codigo').set_solo_lectura(true);
                }
            }

            {$this->objeto_js}.evt__tabla_asociada_descripcion__procesar = function(es_inicial)
            {
                var tabla_asociada = this.ef('tabla_asociada').get_estado();
                
                if (tabla_asociada.slice(0, 3) == 'ta_') {
                        
                    //Se accede a través del DOM porque el set_estado() del combo dispara un ciclo recursivo
                    document.getElementById('ef_form_200000013_formulariotabla_asociada_descripcion').value = 'descripcion';
                    this.ef('tabla_asociada_descripcion').set_solo_lectura(true);
                }
            }
        
			{$this->objeto_js}.evt__componente_numero__procesar = function(es_inicial)
			{
				var estilo  = this.ef('componente_numero').get_estado();
				var estilos_tablas_asociadas = [2,3,4,5,17];    //Que permiten tablas asociadas
		        var estilos_visualizacion_horizontal = [2,5];   //Que permiten visualización horizontal
		
				if (in_array(estilo, estilos_tablas_asociadas)) {
					this.ef('barra_datos_externos').mostrar();
					this.ef('tabla_asociada').mostrar();
				} else {
					this.ef('barra_datos_externos').ocultar();
					this.ef('tabla_asociada').ocultar(true);
				}
		                
                if (in_array(estilo, estilos_visualizacion_horizontal)) {
                    this.ef('visualizacion_horizontal').mostrar();
				} else {
                    this.ef('visualizacion_horizontal').ocultar(true);
				}
		                
                if (estilo == 16) {
                    this.ef('pregunta_calculo_anios').mostrar();
                } else {
                    this.ef('pregunta_calculo_anios').ocultar();
                }

                if (estilo == 22) {
                    // Seteo lo que hay en el ef de pregunta al ef de texto enriquecido
                    if (!es_inicial) {
                        this.ef('texto_enriquecido').set_estado(this.ef('nombre').get_estado());
                    }

                    // Hago el cambio de efs
                    this.ef('nombre').ocultar();
                    this.ef('texto_enriquecido').mostrar();
                } else {
                    // Si el anterior era el texto enriquecido, entonces tengo que pasar el
                    // contenido del ef para que se mantenga entre los dos ef.
                    if (estado_combo_anterior == 22) {
                        this.ef('nombre').set_estado(this.ef('texto_enriquecido').get_estado());
                    }

                    // Hago el cambio de efs
                    this.ef('texto_enriquecido').ocultar();
                    this.ef('nombre').mostrar();
                }
		        
		        // Actualizo cual es el ultimo estado del combo.    
		        estado_combo_anterior = this.ef('componente_numero').get_estado();
			}
			
			{$this->objeto_js}.evt__tabla_asociada__procesar = function(es_inicial)
			{
				if (this.ef('tabla_asociada').tiene_estado()) {
					this.ef('tabla_asociada_codigo').mostrar();
					this.ef('tabla_asociada_descripcion').mostrar();
					this.ef('tabla_asociada_orden_campo').mostrar();
					this.ef('tabla_asociada_orden_tipo').mostrar();
				} else {
					this.ef('tabla_asociada_codigo').ocultar(true);
					this.ef('tabla_asociada_descripcion').ocultar(true);
					this.ef('tabla_asociada_orden_campo').ocultar(true);
					this.ef('tabla_asociada_orden_tipo').ocultar(true);
				}
			}
			
			//---- Validacion de EFs -----------------------------------
			
			{$this->objeto_js}.evt__tabla_asociada_codigo__validar = function()
			{
				var es_oculto = this.ef('tabla_asociada_codigo').es_oculto();
				var tiene_codigo = this.ef('tabla_asociada_codigo').tiene_estado();
				var tabla_asociada = this.ef('tabla_asociada').get_estado();
                
                if (tabla_asociada.slice(0, 3) == 'ta_') {
                    return true;
                }
                
				if (!es_oculto && !tiene_codigo) {
				    this.ef('tabla_asociada_codigo').set_error('$mensaje_obligatoriedad');
					return false;
				}
				
				return true;
			}
			
			{$this->objeto_js}.evt__tabla_asociada_descripcion__validar = function()
			{
				var es_oculto = this.ef('tabla_asociada_descripcion').es_oculto();
				var tiene_descripcion = this.ef('tabla_asociada_descripcion').tiene_estado();
				var tabla_asociada = this.ef('tabla_asociada').get_estado();
                
                if (tabla_asociada.slice(0, 3) == 'ta_') {
                    return true;
                }
                
				if (!es_oculto && !tiene_descripcion) {
					this.ef('tabla_asociada_descripcion').set_error('$mensaje_obligatoriedad');
					return false;
				}
				
				return true;
			}
			
            //---- Procesamiento de EFs --------------------------------

            {$this->objeto_js}.evt__nombre__procesar = function(es_inicial)
            {
                var texto_pregunta = this.ef('nombre').get_estado();
                var resumida = this.ef('descripcion_resumida').get_estado();

                if (resumida == '') {
                    this.ef('descripcion_resumida').set_estado(texto_pregunta.substr(0,30));
                }

                return true;
            }
		
		";
	}

}
?>