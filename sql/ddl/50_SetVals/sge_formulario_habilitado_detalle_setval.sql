-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_formulario_habilitado_detalle
-- Actualizacion Nro de Secuencia: sge_formulario_habilitado_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_formulario_habilitado_detalle_seq',(SELECT MAX(formulario_habilitado_detalle) FROM sge_formulario_habilitado_detalle));


