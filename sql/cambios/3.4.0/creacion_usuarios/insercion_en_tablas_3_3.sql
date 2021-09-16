
----------------------------------------------------------------
-------------------------- MIGRACION ---------------------------
----------------------------------------------------------------

INSERT INTO	kolla_new.sge_encuestado (encuestado, apellidos, nombres, documento_pais, documento_tipo, documento_numero, email, sexo, fecha_nacimiento, usuario, clave, guest, externo)
SELECT 		encuestado, apellidos, nombres, documento_pais, documento_tipo, documento_numero, email, sexo, fecha_nacimiento, usuario, clave, guest, externo
FROM 		kolla.sge_encuestados;

INSERT INTO	kolla_new.sge_grupo_definicion (grupo, nombre, estado, externo, descripcion)
SELECT 		grupo_encuestado, nombre, estado, externo, descripcion
FROM 		kolla.sge_grupos_encuestados;

INSERT INTO	kolla_new.sge_grupo_detalle (grupo, encuestado)
SELECT 		grupo_encuestado, encuestado
FROM 		kolla.sge_encuestados_grupos;

/* -- Especifico de 3.1 o 3.3 - Lo migramos ahi.
INSERT INTO	kolla_new.sge_grupo_habilitado (grupo, formulario_habilitado)
SELECT 		grupo_encuestado, formulario_habilitado
FROM 		sge_grupos_encuestas;
*/

/*INSERT INTO	kolla_new.sge_documento_tipo (documento_tipo, descripcion)
SELECT  documento_tipo, descripcion
FROM kolla.sge_documentos_tipos;
--Se incluye en datos base
*/