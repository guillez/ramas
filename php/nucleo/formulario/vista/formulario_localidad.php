
<?php
/**
 *Maneja html, y procesamiento del ajax de ese html. Esta en sincronia con localidades.js 
 */
class formulario_localidad
{
	protected function get_url_post_ajax()
    {
		$op_ajax = 46000021;
		return toba::vinculador()->get_url(null, $op_ajax , null, array('menu'=>0 , 'celda_memoria' => '0123456')); //por ahora es un op de Toba
	}
	
	function ajax_request()
    {	
		if (isset($_POST['cc_par'])) $a = (int)$_POST['cc_par'];
		switch ($_POST['get_ajax']){
			case 'paises':
				$res = catalogo::consultar(dao_encuestas::instancia(), 'get_paises'); break;
			case 'provincias':
				$res = catalogo::consultar(dao_encuestas::instancia(), 'get_provincias', array($a)); break;
			case 'departamentos':
				$res = catalogo::consultar(dao_encuestas::instancia(), 'get_departamentos', array($a)); break;
			case 'localidades':
				$res = catalogo::consultar(dao_encuestas::instancia(), 'get_localidades', array($a)); break;
			default : return;
		}
		echo $this->json_encode($res);//derivar esto al formulario_localidad
		return;
	}

	function json_encode($array_to_encode)
	{
		$array_to_encode = $this->utf8_encode_fields($array_to_encode);
		return json_encode($array_to_encode);
	}
		
	protected function utf8_encode_fields($elements)
	{
		foreach ($elements as $key => $element) {
			if (is_array($element)) {
				$elements[$key] = $this->utf8_encode_fields($element);
			} else {
				$elements[$key] = utf8_encode($element);
			}
		}
		
		return $elements;
	}
		
	function get_html() {
?>
	
<!-- Modal -->

<div class="modal fade" id="form_localidades" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
	  
		  <div class="modal-title">
		    <button type="button" class="close" onclick="f_localidad.cancelar()" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Seleccionar Localidad</h3>
		  </div>
		  
		  <div class="modal-body">
		     <form>
		     	<?php toba_manejador_sesiones::enviar_csrf_hidden(); ?>
				<div class="form-group">
					<label class="control-label" for="fl_pais">Pais</label>
					<select id="fl_pais" onchange="f_localidad.load_provincias(this.value)" class="form-control">
						<option value=-1>--Seleccionar--</option>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label" for="fl_provincia">Provincia</label>
					<select id="fl_provincia" onchange="f_localidad.load_departamentos(this.value)" class="form-control">
						<option value=-1>--Seleccionar--</option>
					</select>
				</div>
				 <div class="form-group">
					<label class="control-label" for="fl_departamento">Departamento</label>
					<select id="fl_departamento" onchange="f_localidad.load_localidades(this.value)" class="form-control"> 
						<option value=-1>--Seleccionar--</option>
					</select>
				</div>
				 <div class="form-group">
					<label class="control-label" for="fl_localidad">Localidad</label>
					<select id="fl_localidad" class="form-control" >
						<option value=-1>--Seleccionar--</option>
					</select>
				</div>
			</form>
		  </div>
		  
		  <div class="modal-footer">
		    <button class="btn" onclick="f_localidad.cancelar()">Cancelar</button>
		    <button class="btn btn-primary" onclick="f_localidad.guardar()">Aceptar</button>
		  </div>
		</div>
	</div>
</div>
<?php 
}
	
	function get_javascript() {
		$url = $this->get_url_post_ajax();
		echo "	<script>
				post_url = '$url';
				</script>";
		echo "<script type='text/javascript' src='js/localidades.js'></script>";
	}

}
?>
