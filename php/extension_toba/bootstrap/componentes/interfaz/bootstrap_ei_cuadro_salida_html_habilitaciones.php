<?php
/**
 * Created by PhpStorm.
 * User: ngazcon
 * Date: 05/09/18
 * Time: 14:48
 */

namespace ext_bootstrap\componentes\interfaz;


class bootstrap_ei_cuadro_salida_html_habilitaciones  extends bootstrap_ei_cuadro_salida_html
{
    protected function html_cuadro_inicio()
    {
        echo "<div id='{$this->id_table}' class='table-responsive {$this->profundidad_table}'> \n";
        echo "	<table class='fix-table-habilitaciones table table-condensed table-hover'> \n";
    }

    function generar_layout_fila($columnas, $datos, $id_fila,  $clave_fila, $evt_multiples, $objeto_js, $estilo_fila, $formateo)
    {
        $estilo_seleccion = $this->get_estilo_seleccion($clave_fila);

        //Javascript de seleccion multiple
        $js = $this->get_invocacion_js_eventos_multiples($evt_multiples, $id_fila, $objeto_js);

        echo "<tr>\n"; // Abro tag para la fila

        //---> Creo los EVENTOS de la FILA  previos a las columnas<---
        $this->html_cuadro_celda_evento($id_fila, $clave_fila, true);
        foreach (array_keys($columnas) as $a) {
            if ($a == 'habilitacion_destacada') {
                $this->html_cuadro_celda_evento_especial($id_fila, $clave_fila, false);
            } else {
                //*** 1) Recupero el VALOR
                $valor = "";
                if(isset($columnas[$a]["clave"])) {
                    if(isset($datos[$id_fila][$columnas[$a]["clave"]])) {
                        $valor_real = $datos[$id_fila][$columnas[$a]["clave"]];
                        //-- Hace el saneamiento para evitar inyección XSS
                        if (!isset($columnas[$a]['permitir_html']) || $columnas[$a]['permitir_html'] == 0) {
                            $valor_real = texto_plano($valor_real);
                        }
                    }else{
                        $valor_real = null;
                        //ATENCION!! hay una columna que no esta disponible!
                    }
                    //Hay que formatear?
                    if(isset($columnas[$a]["formateo"])) {
                        $funcion = "formato_" . $columnas[$a]["formateo"];
                        //Formateo el valor
                        $valor = $formateo->$funcion($valor_real);
                    } else {
                        $valor = $valor_real;
                    }
                }

                //*** 2) La celda posee un vinculo??
                if ($columnas[$a]['usar_vinculo'] )  {
                    $valor = $this->get_html_cuadro_celda_vinculo($columnas, $a, $id_fila, $clave_fila, $valor);
                }

                //*** 3) Genero el HTML
                $ancho = "";
                if(isset($columnas[$a]["ancho"])) {
                    $ancho = " width='". $columnas[$a]["ancho"] . "'";
                }

                //Emito el valor de la celda
                echo "<td $js>\n";
                if (trim($valor) !== '') {
                    echo $valor;
                } else {
                    echo '&nbsp;';
                }
                echo "</td>\n";
                //Termino la CELDA
            }
        }
        //---> Creo los EVENTOS de la FILA <---
        $this->html_cuadro_celda_evento($id_fila, $clave_fila, false);
        echo "</tr>\n";
    }

    protected function html_cuadro_celda_evento($id_fila, $clave_fila, $pre_columnas)
    {
        //Si es primera columna los input quedan centrados, caso contrario a derecha
        $clase_evento = "align=".( !$pre_columnas? "'right'":"'center'"  );
        if (count($this->_cuadro->get_eventos_sobre_fila()) > 0){
            $minimo_uno = false;

            foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
                $minimo_uno = $minimo_uno || !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());
            }

            if($minimo_uno){
                echo "<td $clase_evento'>\n";
            }

            foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
                if ($evento->get_id() != 'destacar') {
                    $grafico_evento = !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());        //Decido si se debe graficar el boton en este lugar (logica explicada en html_cuadro_cabecera_columna_evento)
                    if ($grafico_evento) {
                        $parametros = $this->get_parametros_interaccion($id_fila, $clave_fila);
                        $clase_alineamiento = ($evento->es_seleccion_multiple()) ? 'col-cen-s1' : '';    //coloco centrados los checkbox si es multiple

                        if ($evento->posee_accion_respuesta_popup()) {
                            $descripcion_popup = \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila));
                            echo \toba_form::hidden($this->_cuadro->get_id_form() . $id_fila . '_descripcion', \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila)));    //Podemos hacer esto porque no vuelve nada!
                        }
                        echo $this->get_invocacion_evento_fila($evento, $id_fila, $clave_fila, false, $parametros);    //ESto hay que ver como lo modifico para que de bien

                    }
                }
            }
            if($minimo_uno){
                echo "</td>\n";
            }
            //Se agrega la clave a la lista de enviadas
            $this->_cuadro->agregar_clave_enviada($clave_fila);
        }
    }

    protected function html_cuadro_celda_evento_especial($id_fila, $clave_fila, $pre_columnas)
    {
        //Si es primera columna los input quedan centrados, caso contrario a derecha
        //$clase_evento = "align=".( !$pre_columnas? "'right'":"'center'"  );
        $clase_evento = "align = center";
        if (count($this->_cuadro->get_eventos_sobre_fila()) > 0){
            $minimo_uno = false;

            foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
                $minimo_uno = $minimo_uno || !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());
            }

            if($minimo_uno){
                echo "<td $clase_evento'>\n";
            }

            foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
                if ($evento->get_id() == 'destacar') {

                    $grafico_evento = !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());        //Decido si se debe graficar el boton en este lugar (logica explicada en html_cuadro_cabecera_columna_evento)
                    if ($grafico_evento) {
                        $parametros = $this->get_parametros_interaccion($id_fila, $clave_fila);
                        $clase_alineamiento = ($evento->es_seleccion_multiple()) ? 'col-cen-s1' : '';    //coloco centrados los checkbox si es multiple

                        if ($evento->posee_accion_respuesta_popup()) {
                            $descripcion_popup = \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila));
                            echo \toba_form::hidden($this->_cuadro->get_id_form() . $id_fila . '_descripcion', \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila)));    //Podemos hacer esto porque no vuelve nada!
                        }
                        echo $this->get_invocacion_evento_fila($evento, $id_fila, $clave_fila, false, $parametros);    //ESto hay que ver como lo modifico para que de bien

                    }
                }
            }
            if($minimo_uno){
                echo "</td>\n";
            }
            //Se agrega la clave a la lista de enviadas
            $this->_cuadro->agregar_clave_enviada($clave_fila);
        }
    }
}