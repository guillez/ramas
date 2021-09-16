<?php
/**
* @todo cambiar por namespace de alguna manera
*/
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';


class form_totales extends bootstrap_formulario
{
	/*
	function generar_layout()
	{
		
		echo '<table cellspacing="1" cellpadding="1" border="0" align="left">';
			echo'<tbody>'; 
				echo '<tr>';
					echo '<td colspan="2">'; 
					$this->generar_html_ef('cant_nuevos');
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="2">';
					$this->generar_html_ef('cant_actualizados');
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
                    echo '<td colspan="2">';
					$this->generar_html_ef('cant_agregados_grupo');
					echo '</td>';
				echo '</tr>';
                echo '<tr>';
                    echo '<td>';
					$this->generar_html_ef('cant_error_datos');
					echo '</td>';
                    echo '<td>';
					$this->generar_boton('reprocesar', false);
					echo '</td>';
				echo '</tr>';
                echo '<tr>';
					echo '<td colspan="2">'; 
					$this->generar_html_ef('cant_error_registro');
					echo '</td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
	}*/
	
}
?>