<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class formulario extends bootstrap_formulario
{
	function generar_layout()
	{
        echo '<div class="row">';
            $this->generar_html_ef('clave_actual');
        echo '</div>';
        
        echo '<hr></hr>';
        
        echo '<div class="row">';
            $this->generar_html_ef('clave_nueva');
        echo '</div>';
	}
	
}