------------------------------------------------------------
--[38000905]--  Tablas Externas - datos 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 38
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'kolla', --proyecto
	'38000905', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_relacion', --clase
	'1', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Tablas Externas - datos', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'kolla', --fuente_datos_proyecto
	'kolla', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2019-11-11 11:11:13', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 38

------------------------------------------------------------
-- apex_objeto_datos_rel
------------------------------------------------------------
INSERT INTO apex_objeto_datos_rel (proyecto, objeto, debug, clave, ap, punto_montaje, ap_clase, ap_archivo, sinc_susp_constraints, sinc_orden_automatico, sinc_lock_optimista) VALUES (
	'kolla', --proyecto
	'38000905', --objeto
	'0', --debug
	NULL, --clave
	'2', --ap
	'1', --punto_montaje
	NULL, --ap_clase
	NULL, --ap_archivo
	'0', --sinc_susp_constraints
	'1', --sinc_orden_automatico
	'1'  --sinc_lock_optimista
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 38
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'kolla', --proyecto
	'38000793', --dep_id
	'38000905', --objeto_consumidor
	'38000903', --objeto_proveedor
	'tabla_externa', --identificador
	NULL, --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'1'  --orden
);
--- FIN Grupo de desarrollo 38