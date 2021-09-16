<?php
/**
 * Se muestra si hay un error en el pedido. Tiene que llegar por la operacion
 * Si cambia los parametros obviamente llega a kolla. 
 *
 * @author demo
 */
class error_vista
{
	protected $mensaje;
	protected $hashing;
	
	public function __construct($mensaje = null) {
		$this->mensaje = $mensaje;
	}
	
	public function generar_interface (){
		$title = "Error en la operación"; //parametro al header.. mala practica?
		if (empty($this->mensaje)) {
			$this->mensaje = "Se produjo un error al obtener la encuesta";
		}
		include("header.php");
		?>
		
		<div class='container'>
		<div class='row-fluid'>
		<div class='span8 offset2'>
			<p></p>
			<br/>
			<div class="alert alert-error">
				<p style="text-align: center;">
				<?php echo $this->mensaje; ?>
				</p>
			</div>
		</div>
		</div>
		</div>
		<?php
		$scripts = acceso_externo::obtener_script_encuesta_cargada();
		echo $scripts;
		include("footer.php"); 
	}
}

?>
