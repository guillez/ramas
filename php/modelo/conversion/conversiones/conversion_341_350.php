<?php

require_once('conversion.php');

class conversion_341_350 extends conversion
{
    
	protected $cambios = array(
        //de  341 a 342
        '336', // Tabla para almacenar qui�n fue el usuario que respondi� en nombre de otro
        '364', // Incorporar perfiles de datos (tablas nuevas y columnas nuevas en tablas)
        //de 342 a 343
        '368', // Unidades de gesti�n para preguntas
        '369', // Unidades de gesti�n para respuestas
        //de 343 a 344
        '374', // Grupos habilitados para habilitaciones externas
        //de 344 a 345
        '378', // unidad de gesti�n para elementos externos
        '379',  // unidad de gesti�n para conceptos externos
        //de 345 a 346
        '381', // Nuevo reporte x habilitaci�n - x encuestado
        '382', // Nuevo reporte x habilitaci�n - x pregunta
        '383', // Nuevo reporte x habilitaci�n - x respuesta
        '384', // Correcciones en funciones para reportes
        //de 346 a 350
        '385', // Unidad de gesti�n por defecto
        '387', //Administraci�n de tipos de elementos por dependencia
        '388',  //Administraci�n de habilitaciones por dependencia
        '392', //Tipo para los servicios web y UG para los que son tipo rest        
        '395', //Unidad de gesti�n en tablas para datos importados        
        '397' //formulario_habilitado_externo para sge_formulario_habilitado        
	);
}

?>