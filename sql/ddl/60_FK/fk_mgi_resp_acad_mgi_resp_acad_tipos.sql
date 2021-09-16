-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_responsable_academica
-- FK: fk_mgi_resp_acad_mgi_resp_acad_tipos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mgi_resp_acad_mgi_resp_acad_tipos;
CREATE INDEX ifk_mgi_resp_acad_mgi_resp_acad_tipos ON  mgi_responsable_academica (tipo_responsable_academica);

-- ALTER TABLE mgi_responsable_academica DROP CONSTRAINT fk_mgi_resp_acad_mgi_resp_acad_tipos; 
ALTER TABLE mgi_responsable_academica 
	ADD CONSTRAINT fk_mgi_resp_acad_mgi_resp_acad_tipos FOREIGN KEY (tipo_responsable_academica) 
	REFERENCES mgi_responsable_academica_tipo (tipo_responsable_academica) deferrable;


