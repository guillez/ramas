<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class formulario extends bootstrap_formulario
{
	function generar_layout()
	{
        echo '<div class="container">';
        
            echo '<div class="row">';
                $this->generar_html_ef('unidad_gestion');
            echo '</div>';

            echo '<div class="row">';
                $this->generar_html_ef('nombre');
            echo '</div>';
            
        echo '</div>';
        
        if ($this->controlador()->tiene_datos()) {
            echo '<a class="btn btn-default pull-right" href="'.URL_TOBA_USUARIOS.'" role="button" target="_blank">Perfiles de Datos</a>';
        }
	}
	
}