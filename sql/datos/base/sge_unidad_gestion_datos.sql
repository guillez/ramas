-- UNIDAD DE GESTIÓN GENÉRICA
INSERT INTO sge_unidad_gestion (unidad_gestion, nombre) VALUES (0, 'Unidad de Gestión Predeterminada');

UPDATE sge_encuesta_atributo SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;
UPDATE sge_concepto          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;
UPDATE sge_elemento          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;
UPDATE sge_pregunta          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;
UPDATE sge_respuesta         SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;
UPDATE sge_habilitacion      SET unidad_gestion = 0 WHERE unidad_gestion IS NULL;