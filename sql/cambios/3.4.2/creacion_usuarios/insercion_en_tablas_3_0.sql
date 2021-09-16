----------------------------------------------------------------
-------------------------- MIGRACION ---------------------------
----------------------------------------------------------------

INSERT INTO	kolla_new.sge_encuestado (encuestado, apellidos, nombres, documento_pais, documento_tipo, documento_numero, email, sexo, fecha_nacimiento, usuario, clave, guest, externo)
SELECT 		encuestado, apellidos, nombres, documento_pais, documento_tipo, documento_numero, email, sexo, fecha_nacimiento, usuario, clave, guest, 'N'
FROM 		kolla.sge_encuestados;

INSERT INTO	kolla_new.sge_grupo_definicion (grupo, nombre, estado, externo, descripcion)
SELECT 		grupo_encuestado, nombre, estado, 'N', nombre
FROM 		kolla.sge_grupos_encuestados;

INSERT INTO	kolla_new.sge_grupo_detalle (grupo, encuestado)
SELECT 		grupo_encuestado, encuestado
FROM 		kolla.sge_encuestados_grupos;

----------------------------------------------------------------
-- Se migran los logs de envíos de mail
----------------------------------------------------------------

INSERT INTO kolla_new.mgn_mail(mail, asunto, contenido, nombre, hora_envio, fecha_envio, formulario_habilitado)
SELECT mail, asunto, contenido, nombre, hora_envio, fecha_envio, habilitacion 
FROM kolla.mgn_mails;

INSERT INTO kolla_new.mgn_log_envio (log, mail, encuestado, mensaje, hash)
SELECT log, mail, encuestado, mensaje, hash
FROM kolla.mgn_logs_envio;

----------------------------------------------------------------
-- Se migran los datos de tablas int_guarani_
----------------------------------------------------------------

INSERT INTO kolla_new.int_guarani_car_tit(fecha_proceso, ra_codigo, carrera_codigo, titulo_codigo)
SELECT fecha_proceso, ra_codigo, carrera_codigo, titulo_codigo
FROM kolla.int_guarani_car_tit;

INSERT INTO kolla_new.int_guarani_carrera(fecha_proceso, carrera_nombre, carrera_codigo, carrera_estado)
SELECT fecha_proceso, carrera_nombre, carrera_codigo, carrera_estado
FROM kolla.int_guarani_carrera;

INSERT INTO kolla_new.int_guarani_instit(fecha_proceso, institucion_nombre, institucion_codigo, institucion_araucano)
SELECT fecha_proceso, institucion_nombre, institucion_codigo, institucion_araucano
FROM kolla.int_guarani_instit;

INSERT INTO kolla_new.int_guarani_persona(fecha_proceso, usuario, clave, ra_codigo, nro_inscripcion, apellido, nombres, pais_documento,
  tipo_documento, nro_documento, sexo, fecha_nacimiento, email, titulo_codigo, colacion_codigo,
  colacion_fecha, resultado_proceso, resultado_descripcion)
SELECT fecha_proceso, usuario, clave, ra_codigo, nro_inscripcion, apellido, nombres, pais_documento,
  tipo_documento, nro_documento, sexo, fecha_nac, email, titulo_codigo, colacion_codigo,
  colacion_fecha, resultado_proceso, resultado_descripcion
FROM kolla.int_guarani_persona;

INSERT INTO kolla_new.int_guarani_ra(fecha_proceso, ra_nombre, ra_codigo, ra_tipo, 
ra_institucion, ra_localidad, ra_calle, ra_numero, ra_cp, ra_telefono, ra_fax, ra_mail)
SELECT fecha_proceso, ra_nombre, ra_codigo, ra_tipo, 
ra_institucion, ra_localidad, ra_calle, ra_numero, ra_cp, ra_telefono, ra_fax, ra_mail
FROM kolla.int_guarani_ra;

INSERT INTO kolla_new.int_guarani_ra_car(fecha_proceso, ra_codigo, carrera_codigo)
SELECT fecha_proceso, ra_codigo, carrera_codigo
FROM kolla.int_guarani_ra_car;

INSERT INTO kolla_new.int_guarani_ra_tit(fecha_proceso,  ra_codigo,  titulo_codigo)
SELECT fecha_proceso,  ra_codigo,  titulo_codigo
FROM kolla.int_guarani_ra_tit;

INSERT INTO kolla_new.int_guarani_titulos(fecha_proceso, titulo_nombre, titulo_nombre_femenino, titulo_codigo, titulo_araucano, titulo_estado)
SELECT fecha_proceso, titulo_nombre, titulo_nombre_femenino, titulo_codigo, titulo_araucano, titulo_estado
FROM kolla.int_guarani_titulos;

/* APARECE EN 3.1.2
INSERT INTO kolla_new.int_ingenieria_relevamiento(tipo_documento, numero_documento, pais_documento, usuario, clave, arau_ua_nombre, arau_ua, arau_titulo_nombre,
  arau_titulo, apellidos, nombres, fecha_nacimiento, email, genero, anio_ingreso, cant_total_mat_aprob, cant_mat_regul,
  cant_mat_plan_estu, cant_mat_aprob, fecha_ult_act_acad, importado, resultado_proceso, resultado_descripcion)
SELECT tipo_documento, numero_documento, pais_documento, usuario, clave, arau_ua_nombre, arau_ua, arau_titulo_nombre,
  arau_titulo, apellidos, nombres, fecha_nacimiento, email, genero, anio_ingreso, cant_total_mat_aprob, cant_mat_regul,
  cant_mat_plan_estu, cant_mat_aprob, fecha_ult_act_acad, importado, resultado_proceso, resultado_descripcion
FROM kolla.int_ingenieria_relevamiento;
*/

----------------------------------------------------------------
-- Se migran los datos de tablas mgi_
----------------------------------------------------------------

INSERT INTO kolla_new.mgi_institucion(institucion, nombre, nombre_abreviado, tipo_institucion, 
localidad, calle, numero, codigo_postal, telefono, fax, email, institucion_araucano)
SELECT institucion, nombre, nombre_abreviado, tipo_institucion, 
localidad, calle, numero, codigo_postal, telefono, fax, email, institucion_araucano
FROM kolla.mgi_instituciones;


INSERT INTO kolla_new.mgi_institucion_tipo(tipo_institucion, nombre, descripcion)
SELECT tipo_institucion, nombre, descripcion
FROM kolla.mgi_instituciones_tipos;


INSERT INTO kolla_new.mgi_propuesta(propuesta, nombre, codigo, estado)
SELECT propuesta, nombre, codigo, estado
FROM kolla.mgi_propuestas;


INSERT INTO kolla_new.mgi_propuesta_ra(responsable_academica, propuesta)
SELECT responsable_academica, propuesta
FROM kolla.mgi_propuestas_ra;


INSERT INTO kolla_new.mgi_responsable_academica(responsable_academica, nombre, codigo, 
tipo_responsable_academica, institucion, ra_araucano, localidad, calle, numero, codigo_postal, telefono, fax, email)
SELECT responsable_academica, nombre, codigo, 
tipo_responsable_academica, institucion, ra_araucano, localidad, calle, numero, codigo_postal, telefono, fax, email
FROM kolla.mgi_responsables_academicas;


INSERT INTO kolla_new.mgi_responsable_academica_tipo(tipo_responsable_academica, nombre, descripcion)
SELECT tipo_responsable_academica, nombre, descripcion
FROM kolla.mgi_responsables_academicas_tipos;


INSERT INTO kolla_new.mgi_titulo(titulo, nombre, nombre_femenino, codigo, titulo_araucano, estado)
SELECT titulo, nombre, nombre_femenino, codigo, titulo_araucano, estado
FROM kolla.mgi_titulos;


INSERT INTO kolla_new.mgi_titulo_propuesta(propuesta, titulo)
SELECT propuesta, titulo
FROM kolla.mgi_titulos_propuestas;


INSERT INTO kolla_new.mgi_titulo_ra(responsable_academica, titulo)
SELECT responsable_academica, titulo
FROM kolla.mgi_titulos_ra;

----------------------------------------------------------------
-- Se migran los datos de encuestados 
----------------------------------------------------------------
--sge_encuestados_titulos
INSERT INTO kolla_new.sge_encuestado_titulo(encuestado, titulo, anio, fecha)
SELECT encuestado, titulo, anio, fecha
FROM kolla.sge_encuestados_titulos;

----------------------------------------------------------------
-- Se migran otros datos base que pueden tener modificaciones en la base local
----------------------------------------------------------------

INSERT INTO kolla_new.sge_institucion(codigo, nombre)
SELECT codigo, nombre 
FROM kolla.sge_instituciones;

--migrar estilos definidos por el usuario, aquells con id > 3
--luego se actualizarán los utilizados para que apunten a los correctos
INSERT INTO kolla_new.sge_encuesta_estilo(estilo, nombre, descripcion, archivo)
SELECT estilo, nombre, descripcion, archivo   
FROM kolla.sge_encuestas_estilos
WHERE estilo > 3;