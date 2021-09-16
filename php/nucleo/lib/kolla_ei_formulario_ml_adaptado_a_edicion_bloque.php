<?php

/**
 * Esta clase agrega métodos utiles para el uso del formulario multilinea.
 *
 * @author Germán Lodovskis
 * @category Extension Toba
 * @version 1.0.0
 */

class kolla_ei_formulario_ml_adaptado_a_edicion_bloque extends \bootstrap_ml_formulario
{
    /**
     * Genera el Formulario Multilínea adaptado a la pantalla:  Inicio > DefiniciónAdministrar > Encuestas
     * en cuanto a la edición de bloques.
     */
    protected function generar_formulario_encabezado()
    {
        //¿Algún EF tiene etiqueta?
        $alguno_tiene_etiqueta = false;
        foreach ($this->_lista_ef_post as $ef) {
            if ($this->_elemento_formulario[$ef]->get_etiqueta() != '') {
                $alguno_tiene_etiqueta = true;
                break;
            }
        }
        if ($alguno_tiene_etiqueta) {
            echo "<thead id='cabecera_{$this->objeto_js}' >\n";
            //------ TITULOS -----
            echo "<tr>\n";
            $primera = true;
            if ($this->_info_formulario['filas_numerar']) {
                echo "<th class='col-xs-1'>#</th>\n";
            }

            $columna = 2;
            foreach ($this->_lista_ef_post	as	$ef){
                $id_form = $this->_elemento_formulario[$ef]->get_id_form_orig();
                $extra = '';
                if ($primera) {
                    $extra = '';
                }

                // Si es la 3er columna (checkbox obligatorio) lo muestro con col-xs-1
                // si no lo muestro normalmente
                if ($columna == 3) {
                    echo "<th $extra id='nodo_$id_form' class='ei-ml-columna col-xs-1'>\n";
                    $columna++;
                } else {
                    $columna++;
                    echo "<th $extra id='nodo_$id_form' class='ei-ml-columna'>\n";
                }

                if ($this->_elemento_formulario[$ef]->get_toggle()) {
                    $this->_hay_toggle = true;
                    $id_form_toggle = 'toggle_'.$id_form;
                    echo "<input id='$id_form_toggle' type='checkbox' class='ef-checkbox' onclick='{$this->objeto_js}.toggle_checkbox(\"$ef\")' />";
                }
                $this->generar_etiqueta_columna($ef);
                echo "</th>\n";
                $primera = false;
            }
            if ($this->_info_formulario['filas_ordenar'] && $this->_ordenar_en_linea) {
                echo "<th class='ei-ml-columna'>&nbsp;\n";
                echo "</th>\n";
            }
            //-- Eventos sobre fila
            if($this->cant_eventos_sobre_fila() > 0){
                echo "<th class='ei-ml-columna ei-ml-columna-extra'>&nbsp;\n";
                foreach ($this->get_eventos_sobre_fila() as $evento) {
                    if (toba_editor::modo_prueba()) {
                        echo toba_editor::get_vinculo_evento($this->_id, $this->_info['clase_editor_item'], $evento->get_id())."\n";
                    }
                }
                echo "</th>\n";
            }
            if ($this->_info_formulario['filas_agregar'] && $this->_borrar_en_linea) {
                echo "<th class='ei-ml-columna'>&nbsp;\n";
                echo "</th>\n";
            }
            echo "</tr>\n";
            echo "</thead>\n";
        }
    }

}