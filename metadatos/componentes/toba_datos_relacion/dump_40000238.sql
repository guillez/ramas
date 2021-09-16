------------------------------------------------------------
--[40000238]--  Habilitación - datos 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 40
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
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
	'Habilitación - datos', --nombre
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
	'2010-05-26 18:41:35', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 40

------------------------------------------------------------
-- apex_objeto_datos_rel
------------------------------------------------------------
INSERT INTO apex_objeto_datos_rel (proyecto, objeto, debug, clave, ap, punto_montaje, ap_clase, ap_archivo, sinc_susp_constraints, sinc_orden_automatico, sinc_lock_optimista) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
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
	'38000720', --dep_id
	'40000238', --objeto_consumidor
	'38000859', --objeto_proveedor
	'formulario_habilitado', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'2'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'kolla', --proyecto
	'38000721', --dep_id
	'40000238', --objeto_consumidor
	'38000860', --objeto_proveedor
	'formulario_habilitado_detalle', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'3'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'kolla', --proyecto
	'38000722', --dep_id
	'40000238', --objeto_consumidor
	'40000239', --objeto_proveedor
	'grupo_habilitado', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'4'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'kolla', --proyecto
	'38000719', --dep_id
	'40000238', --objeto_consumidor
	'200000038', --objeto_proveedor
	'habilitacion', --identificador
	NULL, --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'1'  --orden
);
--- FIN Grupo de desarrollo 38

--- INICIO Grupo de desarrollo 40
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'kolla', --proyecto
	'40000444', --dep_id
	'40000238', --objeto_consumidor
	'40000447', --objeto_proveedor
	'log_formulario_definicion_habilitacion', --identificador
	'', --parametros_a
	'', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'5'  --orden
);
--- FIN Grupo de desarrollo 40

------------------------------------------------------------
-- apex_objeto_datos_rel_asoc
------------------------------------------------------------

--- INICIO Grupo de desarrollo 38
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000079', --asoc_id
	NULL, --identificador
	'kolla', --padre_proyecto
	'200000038', --padre_objeto
	'habilitacion', --padre_id
	NULL, --padre_clave
	'kolla', --hijo_proyecto
	'38000859', --hijo_objeto
	'formulario_habilitado', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'1'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000080', --asoc_id
	NULL, --identificador
	'kolla', --padre_proyecto
	'38000859', --padre_objeto
	'formulario_habilitado', --padre_id
	NULL, --padre_clave
	'kolla', --hijo_proyecto
	'38000860', --hijo_objeto
	'formulario_habilitado_detalle', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'2'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000081', --asoc_id
	NULL, --identificador
	'kolla', --padre_proyecto
	'38000859', --padre_objeto
	'formulario_habilitado', --padre_id
	NULL, --padre_clave
	'kolla', --hijo_proyecto
	'40000239', --hijo_objeto
	'grupo_habilitado', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'3'  --orden
);
--- FIN Grupo de desarrollo 38

--- INICIO Grupo de desarrollo 40
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'40000036', --asoc_id
	NULL, --identificador
	'kolla', --padre_proyecto
	'200000038', --padre_objeto
	'habilitacion', --padre_id
	NULL, --padre_clave
	'kolla', --hijo_proyecto
	'40000447', --hijo_objeto
	'log_formulario_definicion_habilitacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'4'  --orden
);
--- FIN Grupo de desarrollo 40

------------------------------------------------------------
-- apex_objeto_rel_columnas_asoc
------------------------------------------------------------
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000079', --asoc_id
	'200000038', --padre_objeto
	'38000763', --padre_clave
	'38000859', --hijo_objeto
	'38000715'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000080', --asoc_id
	'38000859', --padre_objeto
	'38000714', --padre_clave
	'38000860', --hijo_objeto
	'38000720'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'38000081', --asoc_id
	'38000859', --padre_objeto
	'38000714', --padre_clave
	'40000239', --hijo_objeto
	'38000538'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'kolla', --proyecto
	'40000238', --objeto
	'40000036', --asoc_id
	'200000038', --padre_objeto
	'38000763', --padre_clave
	'40000447', --hijo_objeto
	'40000366'  --hijo_clave
);
