-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-KOLLA 3 - Sistema de Seguimiento de Graduados
-- Version 3.1.X
-- Tabla: ing_relacion_carrera
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE ing_relacion_carrera;

CREATE TABLE ing_relacion_carrera
(
  codigo integer NOT NULL,
  nombre character varying(100),
  CONSTRAINT ing_relacion_carrera_pkey PRIMARY KEY (codigo)
)
WITH (OIDS=TRUE);
ALTER TABLE ing_relacion_carrera OWNER TO postgres;
GRANT ALL ON TABLE ing_relacion_carrera TO postgres;
COMMENT ON TABLE ing_relacion_carrera IS 'Tabla asociada - Datos Censales de Ingenierías';

