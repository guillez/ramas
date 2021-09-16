INSERT INTO sge_unidad_gestion(unidad_gestion, nombre)
    VALUES ('1', 'UG Guarani');

INSERT INTO sge_encuestado (encuestado, apellidos, nombres, documento_tipo, documento_numero, email, usuario, clave, documento_pais, sexo, fecha_nacimiento, guest, externo )
 VALUES (2, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, 'ue_guarani', 'f1b5d5bfc2677ab37e47b6d124f60e0a5d6227f0dd22d0f7ef8616cdbd32e2c3', DEFAULT, DEFAULT, DEFAULT, 'S', 'S');

 INSERT INTO sge_sistema_externo ( nombre, usuario, estado )
 VALUES ('guarani', 'ue_guarani', 'A');

INSERT INTO sge_grupo_definicion (grupo, nombre, estado, externo, descripcion )
 VALUES (2, 'g_ug_guarani', 'A', 'S', 'Grupo de encuestados creado para las encuestas de la conexion ug_guarani');

 INSERT INTO sge_grupo_detalle ( grupo, encuestado )
 VALUES ('2', '2');


 SELECT setval('sge_encuestado_seq',(SELECT MAX(encuestado) FROM sge_encuestado));
 SELECT setval('sge_sistema_externo_seq',(SELECT MAX(sistema) FROM sge_sistema_externo));
 SELECT setval('sge_grupo_definicion_seq',(SELECT MAX(grupo) FROM sge_grupo_definicion));
