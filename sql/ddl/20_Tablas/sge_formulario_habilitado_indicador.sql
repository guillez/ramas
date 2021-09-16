-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_indicador
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS sge_formulario_habilitado_indicador;
CREATE  TABLE sge_formulario_habilitado_indicador
(
	encuesta_definicion Integer NOT NULL,
	formulario_habilitado_detalle Integer NOT NULL,
	formulario_habilitado Integer NOT NULL
);

-- ALTER TABLE sge_formulario_habilitado_indicador DROP CONSTRAINT pk_sge_formulario_habilitado_indicador;
ALTER TABLE sge_formulario_habilitado_indicador ADD CONSTRAINT pk_sge_formulario_habilitado_indicador PRIMARY KEY (encuesta_definicion,formulario_habilitado_detalle,formulario_habilitado);
-- ++++++++++++++++++++++++++ Fin tabla sge_formulario_habilitado_indicador +++++++++++++++++++++++++++++

