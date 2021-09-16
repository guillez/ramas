<?php

/**
 * Esta vista muestra las encuestas en base a Bootstrap.
 * @author demo
 */

include_once('nucleo/formulario/vista_builder.php');

class builder_base implements vista_builder
{
	public $enc_actual_text_p;
	protected $tabs_texto_maxlen = 18;
	protected $inicio_cadena_invisible = '[';
	protected $fin_cadena_invisible    = ']';
	
	//Si es paginada, se pone la encuesta y elemento en cada bloque, por eso se guarda el actual (el ultimo que se pidio)
	protected $tabs = false;
	protected $encuesta_ids = array();
	protected $bloques = array();
	protected $counter = 0;
	protected $flag_counter = 0;
	protected $enc_actual_id; 
	protected $enc_actual_nombre; 
	protected $elem_actual_desc;
	protected $elem_actual_img;
	//fin tabs

	protected $es_editable = true;
	protected $url_action_post; //me lo manda el director, lo "cacheo"
	protected $puede_guardar;
    protected $puede_volver = true;
    protected $plantilla_css;
    
    protected $num_encuesta = 0;
    protected $preg_calculo_anios = array();
    protected $preg_calculo_cp    = array();
    protected $localidad = '';

    const ENCUESTA_PREINSCRIPCION = 8;
    
    public function set_paginacion($paginar, $lista_pags)
	{
		$this->tabs = $paginar;
		$this->bloques = $lista_pags;
	}

	/**
	 * Nota-Implementación. Solo funciona para false. El true
	 * es por defecto, pero no se puede revertir un false.
	 * @param type $boolean
	 */
	public function set_editable($boolean)
	{
		$this->es_editable = $boolean;
		
		if (!$boolean) {
			repositorio_componentes::disable_all_components();
        }
	}
	
    public function set_plantilla_css($plantilla)
    {
        $this->plantilla_css = $plantilla;
    }
	
	public function crear_encabezado_formulario($nombre_form, $texto_preliminar, $url_action_post, $puede_guardar)
	{
		$this->url_action_post  = $url_action_post;
		$this->puede_guardar    = $puede_guardar;
		$clase                  = $this->tabs ? ' formulario-con-tabs' : '';
		
		if (!empty($texto_preliminar)) {
            echo "	<div class='container-fluid$clase'>
                        <div class='row'>
							<div class='col-md-12 cuerpo-formulario'>
                                <div class ='well well-small'>$texto_preliminar</div>
                            </div>
						</div>
					</div>";
		}
		
		echo "	<div class='container-fluid$clase'>
	                <div>
				        <div class=' cuerpo-formulario'>";

		if ($this->mostrar_texto($nombre_form)) {
			echo "<h2 class='formulario-titulo'>$nombre_form</h2>";
		}
		
		echo "<form id='formulario' name='formulario' method='post' class='form-horizontal' action='$url_action_post'>";
		toba_manejador_sesiones::enviar_csrf_hidden();
	}

    public function crear_barra_progreso()
    {
        // Redefinir en las clases que sean convenientes
    }
    
    public function set_mostrar_header()
    {
        // Redefinir en las clases que sean convenientes
    }
	
	public function crear_cierre_formulario()
	{
		if ($this->es_editable) {
			$this->generar_formulario_botones();
		}
		echo '</form>';
		echo '</div></div></div>';
                
		$this->generar_html_componentes_flotantes();
		$this->html_scripts_fin_pagina($this->agregar_validaciones_js());
        $this->html_scripts_preguntas_calculo_anios();
        $this->html_scripts_preguntas_localidad_y_cp();
		$this->html_scripts_preguntas_combo_autocompletado();
        $this->html_scripts_encuesta_preinscripcion();
	}
	        
	protected function generar_formulario_botones()
	{
		if ($this->url_action_post == '') {
			return;
		}
		
		echo "<div class='resumen-errores alert-error-kolla col-md-12' style='text-align: center; margin-bottom: 2px; display: none;'></div>";
		echo "<div class='form-actions'>"; //Abro acciones
		
        if ($this->puede_volver) {
			$this->html_boton_volver(htmlspecialchars($_SERVER['HTTP_REFERER']), 'volver');
		}
        
		if ($this->puede_guardar) {
			$this->html_boton_guardar('return true', 'guardar', 'submit', 'guardar');
		}
        
		echo '&nbsp;&nbsp;&nbsp;';
		$this->html_boton_terminar('return terminar_encuesta()', 'terminar', 'submit', 'terminar');
		echo '</div>'; // cierro Acciones
	}

	public function crear_encabezado_encuesta($id, $nombre, $bloques, $texto_preliminar=null)
	{
		$this->encuesta_ids[$id] = $id;
		
		$this->_html_encabezado_encuesta($id, $nombre, $texto_preliminar);
		
		if ($this->tabs) { //las pongo a nivel de bloque
			
			$this->enc_actual_id = $id;
			$this->enc_actual_nombre = $nombre;
			$this->enc_actual_text_p = $texto_preliminar;
			
			echo "<div class='nav-tabs-custom '>
					<ul id='tabs' class='nav nav-tabs nav-stacked col-md-2'>";
			foreach ($bloques as $key => $b) {
                if (substr($b['nombre'], 0, 1) == '[' && substr($b['nombre'], -1, 1) == ']') {
                    $etiqueta_tab = '';
                } elseif (strlen($b['nombre']) > $this->tabs_texto_maxlen) {
					$etiqueta_tab = substr($b['nombre'], 0, $this->tabs_texto_maxlen - 3).'...';
				} else {
					$etiqueta_tab = $b['nombre'];
				}
                
				$this->counter++;
                
				echo "  <li class='disabled'>
                            <a href='#tab-{$this->counter}' data-toggle='tab' >
                                <span class='glyphicon  text-danger'></span> $etiqueta_tab
                            </a>
                        </li>";//empieza
			}
			$this->counter = $this->flag_counter;
			echo "	</ul>";
			echo "<div class='tab-content col-md-9'>";
		} 
	}
	
	protected function _html_encabezado_encuesta($id, $nombre, $texto_preliminar)
	{
		if (!empty($texto_preliminar)) {
			echo "<div class ='disclaimer_encuesta well well-small'>$texto_preliminar</div>";
		}
		
		if (!$this->tabs) {
			echo "<div id='encuestak_$id' class='encuesta panel'>"; // Encierro a la encuesta completa
		}
        
		echo "<div class='panel col-md-12'>";
		echo "<h3 class='encuesta-titulo ' >";
        
		if ($this->mostrar_texto($nombre)) {
			echo $nombre;
		}

        echo "  <a class='pull-right' data-toggle='collapse' aria-expanded='true' data-target='.multi-collapse' style='text-decoration: none' >
            <span class='glyphicon glyphicon-menu-up collapse in multi-collapse' aria-hidden='true' style='color:white'></span>
            <span class='disable-collapsing glyphicon glyphicon-menu-down collapse multi-collapse' aria-hidden='true' style='color:white'></span>   
        </a>";
		echo "</h3>";
		echo "<div id='encuesta_bloques_{$this->num_encuesta}' aria-expanded='true' class='collapse in multi-collapse'>";
		$this->num_encuesta++;
	}
	
	public function crear_elemento($elemento_desc, $elemento_img)
	{
		if (!$this->tabs) {
			$this->_crear_elemento($elemento_desc, $elemento_img);
		} else {
			$this->elem_actual_desc = $elemento_desc;
			$this->elem_actual_img = $elemento_img;
		}
	}
	
	protected function _crear_elemento($elemento_desc, $elemento_img)
	{
		if ($this->es_texto_vacio($elemento_desc) && $elemento_img == null) {
			return;
		}
	
		if (!$this->mostrar_texto($elemento_desc)) {
			return;
		}
	
		if ($elemento_img == null) { //solo desc, ocupa todo el ancho
			echo "  <div class='encuesta-elemento well well-small'>
                        <h4>$elemento_desc</h4>
                    </div>
                    ";
		} else {
			echo "  <div class='encuesta-elemento well well-small clearfix'>
                        <img alt='' src='$elemento_img' class='pull-left elemento-foto' />
                        <div class='elemento-descripcion-foto pull-left'>
                            <h3>$elemento_desc</h3>
                        </div>
                    </div>
                    ";
		}
	}
	
	public function crear_encabezado_bloque($id, $nombre)
	{
		if ($this->tabs) {
			$this->counter++;
			echo "<div class='tab-pane' id='tab-{$this->counter}'>";
			//$this->_html_encabezado_encuesta($this->enc_actual_id, $this->enc_actual_nombre, $this->enc_actual_text_p);
			$this->_crear_elemento($this->elem_actual_desc, $this->elem_actual_img);
				
		}
        
		echo "<div id='$id' class='panel panel-info'>";
		
		if ($this->mostrar_texto($nombre)) {
			echo "<div class='panel-heading bloque'>$nombre</div>";
		}
        
		echo "<div class='panel-body'>";
	}
	
	public function crear_cierre_encuesta()
	{
		if ($this->tabs) {
			echo "</div>"; // Cierro tab-content
			echo "</div>"; // Cierro tabbable
		} else {
            echo "</div>"; // Cierro el panel que encierra
        }
        
		echo "</div>"; 
		echo "</div>";// cierro el div que encierra los bloques de un encuesta
		
		$this->flag_counter = $this->counter;
	}
	
	public function crear_cierre_bloque()
	{
		echo "</div>"; // Cierro el body
		echo "</div>"; // Cierro el panel
        
		if ($this->tabs) {
			echo "</div>"; // Cierro el tab-pane
		}
	}
	
	public function crear_encabezado_pregunta($id, $for_id, $obligatoria, $texto, $ayuda, $error, $componente = null)
	{
		$error_txt = ($error) ? ' error' : '';
		$mark = $obligatoria ? '<b>*</b>' : '';
		echo "<div class='form-group $error_txt'>";
		echo "<label id='$id' class='control-label col-sm-12' style='text-align: left;' for='$for_id'>";
        
		if (isset($ayuda)) {
			echo "<span class='glyphicon glyphicon-pushpin' data-toggle='tooltip' data-placement='top' title='$ayuda'></span>";
		}
        
		echo "$texto $mark</label>";
        $pregunta_arr = explode('_', $for_id);
        
        if ($componente == 'fecha_calculo_anios') {
            $encuesta_def = toba::consulta_php('co_preguntas')->get_pregunta_dependiente_encuesta($pregunta_arr[2]);
            $componente_cascada = array();
            $componente_cascada['disparadora'] = $for_id;
            $componente_cascada['receptora']   = $pregunta_arr[0].'_pk_'.$encuesta_def['encuesta_definicion'];
            $this->preg_calculo_anios[] = $componente_cascada;
        } elseif ($componente == 'localidad_y_cp') {
            $encuesta_def = toba::consulta_php('co_preguntas')->get_pregunta_dependiente_encuesta($pregunta_arr[2]);
            $componente_cascada = array();
            $componente_cascada['disparadora'] = $for_id;
            $componente_cascada['receptora']   = $pregunta_arr[0].'_pk_'.$encuesta_def['encuesta_definicion'];
            $this->preg_calculo_cp[] = $componente_cascada;
        }
        
        if ($componente == 'fecha_calculo_anios' || toba::consulta_php('co_preguntas')->es_pregunta_dependiente_encuesta_definicion($pregunta_arr[2])) {
            echo '<div class="controls col-sm-3">';
        } else {
            echo '<div class="controls col-sm-12">';
        }
	}
	
	public function crear_cierre_pregunta()
	{
		echo '</div></div>'; 
	}
	
	public function crear_error_label($str)
	{
		if (!empty($str)) {
			echo "<div class='alert-error-kolla'>$str</div>";
		} else {
			echo "<span class='js-error'></span>";
		}
	}

	/* LEGACY */
	public function crear_componente_label($id, $texto)
	{
		echo "<h4 id='$id' class='encuesta-preg-etiq'>$texto</h4>";
	}

    public function crear_componente_subtitulo($id, $texto)
    {
        echo "<h4 id='$id' class='etiqueta-subtitulo etiqueta-subtitulo-color'>$texto</h4>";
    }

    public function crear_componente_titulo($id, $texto)
    {
        echo "<h2 id='$id' class='etiqueta-titulo etiqueta-titulo-color'>$texto</h2>";
    }

    public function crear_componente_texto_enriquecido($id, $texto)
    {
	    echo "<div id='$id' class='etiqueta-texto-enriquecido etiqueta-texto-enriquecido-color'>$texto</div>";
    }
	
	public function crear_respuesta(kolla_comp_encuesta $componente, $componenteid, $opciones_respuesta, $obligatoria, $id_preg, $imprimir_respuestas_completas)
	{
        $id_arreglo   = explode('_', $componenteid);
        $solo_lectura = toba::consulta_php('co_preguntas')->es_pregunta_dependiente_encuesta_definicion($id_arreglo[2]);
        
        if ($componente instanceof kolla_cp_localidad_y_cp) {
            
            //Seteo la localidad para usarla al momento de generar todos sus respectivos codigos postales (que será la pregunta siguiente)
            $this->localidad = $opciones_respuesta[0]['respuesta_valor'];
        }
        
        if ($componente instanceof kolla_cp_combo_dinamico) {
            
            /*
             * Generación de respuesta dinámica: Se envía además la localidad seteada en
             * la respuesta anterior, para obtener asi todos los CPs que le corresponden
             */
            echo $componente->get_html($componenteid, $opciones_respuesta, $obligatoria, $solo_lectura, $this->localidad);
        } else {
            
            //Generación de respuesta estática
            echo $componente->get_html($componenteid, $opciones_respuesta, $obligatoria, $solo_lectura);
        }
	}
	
	public function crear_mensaje($mensaje)
	{
		echo "<div class='encuesta-mensaje'>$mensaje</div>";
	}
	
	protected function html_boton_terminar($onclick, $name, $type, $value)
	{	
		echo "<button id='btn-terminar' class='btn btn-primary' onclick='$onclick' value='$value' name='$name' type='$type'>Terminar</button>";
	}
        	
    protected function html_boton_guardar($onclick, $name, $type, $value)
	{
		echo "<button class='btn cancel' id='btn-guardar' onclick='$onclick' value='$value' name='$name' type='$type'>Guardar</button>";
	}
    
    protected function html_boton_volver($url, $name)
	{
        echo "<a id='btn-volver' class='btn btn-default pull-left' href='$url' name='$name'>Volver</a>";
	}
	
	protected function agregar_validaciones_js()
	{
		$scripts = "<script type='text/javascript' src='js/jquery.validate.min.js'></script>
			        <script  type='text/javascript' src='js/jquery.validate.config.js'></script>
			        <script  type='text/javascript' src='js/MediatorDependientesBarraProgreso.js'></script>
			        <script  type='text/javascript' src='js/encuestas/helper_encuestas.js'></script>
			        <script  type='text/javascript' src='js/encuestas/DeshabilitarUnselectRadio.js'></script>";
        
		if ($this->es_editable) {
			foreach ($this->encuesta_ids as $key => $value) {
                $dependencias = new pregunta_dependencias($key);
                $scripts .= $dependencias->get_dependencias_js();
                $file = toba_proyecto::get_path()."/www/js/encuestas/$key.js";
				
                if (file_exists($file)) {
					$scripts .= "<script type='text/javascript' src='js/encuestas/$key.js'></script>";
				}
			}
		}
        
		return $scripts;
	}
	
	protected function generar_html_componentes_flotantes()
	{
		$comps = repositorio_componentes::get_active_components();
        
		if (isset($comps['localidad']) || isset($comps['localidad_y_cp'])) {
			$form_loc = new formulario_localidad();
			$form_loc->get_html();
			$form_loc->get_javascript();
		}
	}
	
	protected function mostrar_texto($texto)
	{
		$texto = trim($texto);
		$primer_caracter = substr($texto, 0, 1);
		$ultimo_caracter = substr($texto, -1);
		
		return !($primer_caracter == $this->inicio_cadena_invisible && $ultimo_caracter == $this->fin_cadena_invisible);
	}
	
	protected function es_texto_vacio($texto)
	{
		$texto = trim($texto);
		return $texto == null || $texto == $this->inicio_cadena_invisible.$this->fin_cadena_invisible;
	}
        
    protected function html_scripts_preguntas_combo_autocompletado()
    {
        $comps = repositorio_componentes::get_active_components();
        
        if (isset($comps['combo_autocompletado'])) {
            echo "<link href='select2/css/select2.min.css' rel='stylesheet' />
                    <script src='select2/js/select2.min.js'></script>
                    <script src='select2/js/i18n/es.js'></script>
                    ";
            
            echo "  <script type='text/javascript'>
                
                        function matchCustom(params, data) {
                            
                            var valor  = data.text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                            var search = params.term.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                            
                            if (valor.indexOf(search) > -1) {
                                return data;
                            }
                            
                            return null;
                        }
                        
                        $('.select_search').select2({
                            minimumInputLength:3,
                            language: 'es',
                            placeholder: ' ',
                            allowClear: true,
                            selectOnClose: true,
                            width: '100%',
                            matcher: matchCustom
                        });
                    </script>";
        }
    }
    
    protected function html_scripts_fin_pagina($scripts)
	{
  	    echo " <script type='text/javascript' src='assets/jquery/jquery.min.js'></script>
                <script type='text/javascript' src='js/bootstrap.min.js'></script>";
		echo    $scripts;
        echo "  <script>
                    $('#formulario input, #formulario select, #formulario textarea').focusout(function() {
                        $('#formulario').validate().element(this);
                    })
                </script>
            ";
	
		if ($this->tabs) {
			echo "
				<script>
					
				$(document).ready(function() {
	
					$('#btn-terminar').hide();
					$('#tabs li:first').removeClass('disabled');
					$('#tabs a:first').tab('show');
					
					if ($('#tabs a').length == 1) {
						$('#btn-terminar').show();
					}
			
					//habilito el segundo porque el primero esta 'clickeado'
					$('#tabs li:eq(1)').removeClass('disabled');
                    
					$('#tabs a').click(function(event) {
						
						if ($(this).attr('href') == $('li.active > a').attr('href')) {
							return false;
                        }
                        
						if ($(this).parent().hasClass('disabled')) {
							return false;
						}
					
						var actual = $('li.active > a').attr('href');
						actual = actual.slice(actual.indexOf('-') + 1)
						
						var index_tab = $(this).attr('href') ;
						var index = parseInt(index_tab.slice(index_tab.indexOf('-') + 1)) + 1;

						validar_bloque(actual);
                        
						if ($('#tab-'+(actual)+' div .has-error').length  > 0) {
							$('a[href=\"#tab-'+actual+'\"] > span').addClass('glyphicon-exclamation-sign')
						} else {
							$('a[href=\"#tab-'+actual+'\"] > span').removeClass('glyphicon-exclamation-sign')
						}
						
						var next = $('li > a[href=\"#tab-'+index+'\"]');
				
						if ($(next).length) { //no es el ultimo
							next = $(next).closest('li');
							next.removeClass('disabled');
						} else {
							$('#btn-terminar').show();
						}
					});
				});
				</script>
			";
		}                
	}
    
    protected function html_scripts_preguntas_calculo_anios()
    {
        if ($this->preg_calculo_anios != array()) {
            
            echo "  <script>
                        function calcularAniosTranscurridos(fecha)
                        {
                            var array = fecha.split('/');
                            var dia   = parseInt(array[0], 10);
                            var mes   = parseInt(array[1], 10);
                            var anio  = parseInt(array[2], 10);
                            
                            var fecha_actual = new Date();
                            var fecha_desde  = new Date(anio, mes - 1, dia);
                            var fecha_valida = fecha_desde.getFullYear() == anio && fecha_desde.getMonth() + 1 == mes && fecha_desde.getDate() == dia && fecha_desde.getFullYear() >= 1000;

                            if (!fecha_valida || fecha_desde > fecha_actual) {
                                return '';
                            }

                            var anios = fecha_actual.getFullYear() - fecha_desde.getFullYear();
                            var meses = fecha_actual.getMonth() - fecha_desde.getMonth();

                            if (meses < 0 || (meses === 0 && fecha_actual.getDate() < fecha_desde.getDate())) {
                                anios--;
                            }

                            return anios;
                        }
                ";
            
            foreach ($this->preg_calculo_anios as $pregunta) {
                
                $disparadora = $pregunta['disparadora'];
                $receptora   = $pregunta['receptora'];
                        
                echo "  $('#$disparadora').focusout(function() {
                            var fecha = $(this).val();
                            var anios = calcularAniosTranscurridos(fecha);
                            $('#$receptora').val(anios);
                        })
                    ";
            }
            
            echo '</script>';
        }
    }
    
    protected function html_scripts_preguntas_localidad_y_cp()
    {
        foreach ($this->preg_calculo_cp as $pregunta) {

            $disparadora = $pregunta['disparadora'];
            $receptora   = $pregunta['receptora'];

            $url = toba::vinculador()->get_url(null, 38000170, null, array('menu' => 0 , 'celda_memoria' => '0123456'));

            echo "  <script>
                        $('#$disparadora').change(function() {
                            var loc = $(this).val();

                            $.ajax({
                                url : '$url',
                                data : {localidad : loc},
                                type : 'GET',

                                success : function(response) {
                                    $('#$receptora').html(response).fadeIn();
                                },

                                error : function(xhr, status) {
                                    alert('Status Error: ' + status);
                                }
                            });
                        })
                    </script>
                ";
        }
    }
    
    protected function html_scripts_encuesta_preinscripcion()
    {
        if ($this->existe_encuesta_preinscripcion()) {
            $encuesta_preinscripcion = new encuesta_preinscripcion();
			$encuesta_preinscripcion->get_javascript();
        }
    }
    
    protected function existe_encuesta_preinscripcion()
    {
        foreach ($this->encuesta_ids as $key => $value) {
            if ($value == self::ENCUESTA_PREINSCRIPCION) {
                return true;
            }
        }
        
        return false;
    }

    public function mostrar_aviso_guardado()
    {
        echo "<div class='alert alert-success text-center'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    Tus respuestas se guardaron exitosamente.
            </div>";
    }    
}
?>