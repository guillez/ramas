-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3 
-- Tabla: sge_puntaje_aplicacion
-- Indice: ix_evaluacion_formhab_formhabdet_puntaje
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_evaluacion_formhab_formhabdet_puntaje;
CREATE UNIQUE INDEX ix_evaluacion_formhab_formhabdet_puntaje ON sge_puntaje_aplicacion (evaluacion,formulario_habilitado,formulario_habilitado_detalle,puntaje);


