<?php


class informe_resumido
{
    protected $_resumen = '';
    protected $_salto_linea = '<br>';
    protected $_sangria = '&nbsp;&nbsp;&nbsp;&nbsp;';


    public function __construct()
    {
        // Nothing to do here
    }

    public function get_resumen() {
        return $this->_resumen;
    }

    public function limpiar_resumen() {
        $this->_resumen = '';

        return $this;
    }

    public function agregar_titulo($titulo) {
        $this->_resumen .= '<u>' . $titulo . '</u>' . $this->_salto_linea;

        return $this;
    }

    public function agregar_informacion($nombre, $descripcion, $sangria = false) {
        $descripcion = $this->acomodar_descripcion($descripcion);

        if ($sangria) {
            $this->_resumen .= $this->_sangria . '<strong>' . $nombre . '</strong>' . $descripcion . $this->_salto_linea;
        } else {
            $this->_resumen .= '<strong>' . $nombre . '</strong>' . $descripcion . $this->_salto_linea;
        }

        return $this;
    }

    public function mostrar_informacion($nombre, $descripcion, $sangria = false) {

        if ($sangria) {
            $this->_resumen .= $this->_sangria . '<strong>' . $nombre . '</strong>' . $descripcion . $this->_salto_linea;
        } else {
            $this->_resumen .= '<strong>' . $nombre . '</strong>' . $descripcion . $this->_salto_linea;
        }

        return $this;
    }

    public function abrir_tabla() {
        $this->_resumen .= '<table class="table table-achicada table-borderless"><tbody>';

        return $this;
    }

    public function cerrar_tabla() {
        $this->_resumen .= '</tbody></table>';

        return $this;
    }

    public function agregar_fila_doble($nombre_1, $descripcion_1, $nombre_2, $descripcion_2)
    {
        $descripcion_1 = $this->acomodar_descripcion($descripcion_1);
        $descripcion_2 = $this->acomodar_descripcion($descripcion_2);
        $this->_resumen .= '<tr><td><strong>' . $nombre_1 . '</strong>' . $descripcion_1 . '</td><td><strong>' . $nombre_2 . '</strong>' . $descripcion_2 . '</td></tr>';

        return $this;
    }

    public function agregar_fila_completa($nombre, $descripcion)
    {
        $descripcion = $this->acomodar_descripcion($descripcion);
        $this->_resumen .= '<tr><td colspan="2"><strong>' . $nombre . '</strong>' . $descripcion . '</tdcolspan></tr>';

        return $this;
    }

    public function generar_resumen_completo($datos_habilitacion)
    {
        // -- Agrego al resumen todos los datos básicos

        $this->agregar_titulo('Datos habilitación')
            ->abrir_tabla()
            ->mostrar_informacion('ID: ', $datos_habilitacion['habilitacion'])
            ->mostrar_informacion('Descripción: ', $datos_habilitacion['descripcion'])
            ->mostrar_informacion('Texto_preliminar: ', $datos_habilitacion['texto_preliminar'])
            ->mostrar_informacion('Fecha desde: ', $datos_habilitacion['fecha_desde'])
            ->mostrar_informacion('Fecha hasta: ', $datos_habilitacion['fecha_hasta'])
            ->mostrar_informacion('Estilo: ', $datos_habilitacion['estilo_descripcion'])
            ->agregar_informacion('Generada externamente: ', $datos_habilitacion['externa']);

        if($datos_habilitacion['externa'] == 'S') $this->agregar_informacion('Sistema de origen: ', $datos_habilitacion['sistema']);


        $this->agregar_informacion('Anónima: ', $datos_habilitacion['anonima'])
            ->agregar_informacion('Pública: ', $datos_habilitacion['publica'])

            ->agregar_informacion('Paginada: ', $datos_habilitacion['paginado'])
            ->agregar_informacion('Descarga pdf: ', $datos_habilitacion['descarga_pdf'])
            ->agregar_informacion('Pdf muestra respuestas completas: ', $datos_habilitacion['imprimir_respuestas_completas'])
            ->agregar_informacion('Barra de progreso: ', $datos_habilitacion['mostrar_progreso'])
            ->agregar_informacion('Genera código de recuperación: ', $datos_habilitacion['generar_cod_recuperacion'])
        ;

        $this->agregar_titulo('Formularios');

        foreach ($datos_habilitacion['forms'] as $form) {
            //var_dump($form['formulario_concepto_grupo']);
            $this->mostrar_informacion("*", $form['formulario_concepto_grupo']);
        }

        // -- finalizo el reporte
        $this->cerrar_tabla();

        return $this;
    }

    protected function acomodar_descripcion($descripcion) {
        $salida = $descripcion;

        if ($salida == null) {
            $salida = '---';
        } elseif ($salida == 'S' || $salida == 1) {
            $salida = 'Si';
        } elseif ($salida == 'N' || $salida == 0) {
            $salida = 'No';
        }

        return $salida;
    }

}