<?php

require_once('cambio.php');

class cambio_434 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 434: Estructura de tablas para Preguntas Dependientes.';
	}

	function cambiar()
	{
        $sql = "
            CREATE TABLE sge_pregunta_dependencia
            (
                pregunta_dependencia serial NOT NULL,
                encuesta_definicion integer,
                CONSTRAINT sge_pregunta_dependencia_pkey PRIMARY KEY (pregunta_dependencia),
                CONSTRAINT sge_pregunta_dependencia_encuesta_definicion_fkey FOREIGN KEY (encuesta_definicion)
                    REFERENCES kolla.sge_encuesta_definicion (encuesta_definicion) MATCH SIMPLE
                    ON UPDATE NO ACTION ON DELETE CASCADE
            );
            
            CREATE TABLE sge_pregunta_dependencia_definicion
            (
                dependencia_definicion serial NOT NULL,
                pregunta_dependencia integer NOT NULL,
                condicion character varying NOT NULL,
                valor character varying,
                accion character varying NOT NULL,
                bloque integer NOT NULL,
                pregunta integer,
                CONSTRAINT sge_pregunta_dependencia_definicion_pkey PRIMARY KEY (dependencia_definicion),
                CONSTRAINT sge_pregunta_dependencia_definicion_bloque_fkey FOREIGN KEY (bloque)
                    REFERENCES kolla.sge_bloque (bloque) MATCH SIMPLE
                    ON UPDATE NO ACTION ON DELETE NO ACTION,
                CONSTRAINT sge_pregunta_dependencia_definicion_pregunta_dependencia_fkey FOREIGN KEY (pregunta_dependencia)
                    REFERENCES kolla.sge_pregunta_dependencia (pregunta_dependencia) MATCH SIMPLE
                    ON UPDATE NO ACTION ON DELETE CASCADE,
                CONSTRAINT sge_pregunta_dependencia_definicion_pregunta_fkey FOREIGN KEY (pregunta)
                    REFERENCES kolla.sge_pregunta (pregunta) MATCH SIMPLE
                    ON UPDATE NO ACTION ON DELETE NO ACTION
            );
        ";
        
        $this->ejecutar($sql);
	}
}