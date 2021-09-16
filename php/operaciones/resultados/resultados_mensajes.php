<?php

class resultados_mensajes
{

    static function formatear_info_filtros($habilitacion, $filtros, $respuestas_pregunta)
    {
        $mensaje = "Habilitación: <b>" . $habilitacion['descripcion'] . " (" .$habilitacion['habilitacion'].")</b></br>";

        if (isset($filtros['grupo']) && $filtros['grupo'] != '') {
            $grupo = toba::consulta_php('consultas_usuarios')->get_listado(" grupo = " . $filtros['grupo']);
            $mensaje .= "Respuestas del grupo de encuestados: <b>" . $grupo[0]['nombre'] . "</b></br>";
        }

        if (isset($filtros['concepto']) && $filtros['concepto'] != '') {
            $concepto = toba::consulta_php('consultas_formularios')->get_datos_conceptos($filtros['concepto']);
            $mensaje .= "El concepto evaluado: <b>" . $concepto['descripcion'] . "</b></br>";
        }

        if (isset($filtros['elemento']) && $filtros['elemento'] != '') {
            $elemento = toba::consulta_php('consultas_formularios')->get_combo_elementos(" elemento = " . $filtros['elemento']);
            $mensaje .= "El elemento evaluado: <b>" . $elemento[0]['descr'] . "</b></br>";
        }

        if (isset($filtros['encuesta']) && $filtros['encuesta'] != '') {
            $encuesta = toba::consulta_php('consultas_encuestas')->get_encuesta($filtros['encuesta']);
            $mensaje .= "La encuesta: <b>" . $encuesta['nombre'] . "</b></br>";
        }

        if (isset($filtros['pregunta']) && $filtros['pregunta'] != '')
        {
            $pregunta = toba::consulta_php('consultas_encuestas')->get_pregunta_encuesta_definicion($filtros['pregunta']);
            $mensaje .= "Solo la pregunta: <b>" . $pregunta['nombre'] . "</b></br>";
        }

        if (isset($filtros['pregunta_filtro']) && $filtros['pregunta_filtro'] != '')
        {
            $pregunta = toba::consulta_php('consultas_encuestas')->get_pregunta_encuesta_definicion($filtros['pregunta_filtro']);
            $mensaje .= "Solo quienes hayan respondido a la pregunta <b>" .$pregunta['nombre'] . "</b>";
            if (isset($filtros['respuesta']) && $filtros['respuesta'] != '')
            {
                $key = array_search($filtros['respuesta'], array_column($respuestas_pregunta, 'respuesta'));
                $mensaje .= " con la opción <b>" .$respuestas_pregunta[$key]['valor_tabulado'] . "</b></br>";

            }
        }

        switch ($filtros['terminadas']) {
            case 'T':
                $mensaje .= "Todas las respuestas <b>finalizadas</b> y <b>sin finalizar</b> </br>";
                break;
            case 'S':
                $mensaje .= "Solo las respuestas <b>finalizadas</b></br>";
                break;
            case 'N':
                $mensaje .= "Solo las respuestas <b>sin finalizar</b> </br>";
                break;
        }
        $mensaje .= (isset($filtros['codigos']) && $filtros['codigos']) ? "<b>Incluye</b> códigos de respuestas.</br>" : "<b>No incluye</b> códigos de respuestas.</br>";
        $mensaje .= (isset($filtros['respondida_por']) && $filtros['respondida_por']) ? "Se muestra si un gestor respondió por el encuestado.</br>" : '';
        $mensaje .= (isset($filtros['codigos_externos']) && $filtros['codigos_externos']) ? "Se muestran los códigos de origen de los conceptos y elementos evaluados.</br>" : '';
        $mensaje .= (isset($filtros['opciones']) && $filtros['opciones'] == 'E') ? "Se muestran <b>solo las opciones de respuesta elegidas.</b></br>" : 'Se muestran <b>todas</b> las opciones de respuesta disponibles.</br>';

        $mensaje .= (isset($filtros['fecha_desde_respondido']) && $filtros['fecha_desde_respondido'] != '') ? "Se muestran las respondidas a partir de: <b>".$filtros['fecha_desde_respondido']."</b></br>" : '';
        $mensaje .= (isset($filtros['fecha_hasta_respondido']) && $filtros['fecha_hasta_respondido'] != '') ? "Se muestran las respondidas hasta el: <b>".$filtros['fecha_hasta_respondido']."</b></br>" : '';
        return $mensaje;
    }


}
?>