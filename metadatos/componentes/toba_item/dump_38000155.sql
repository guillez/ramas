------------------------------------------------------------
--[38000155]--  Disparar Proceso 
------------------------------------------------------------

------------------------------------------------------------
-- apex_item
------------------------------------------------------------

--- INICIO Grupo de desarrollo 38
INSERT INTO apex_item (item_id, proyecto, item, padre_id, padre_proyecto, padre, carpeta, nivel_acceso, solicitud_tipo, pagina_tipo_proyecto, pagina_tipo, actividad_buffer_proyecto, actividad_buffer, actividad_patron_proyecto, actividad_patron, nombre, descripcion, punto_montaje, actividad_accion, menu, orden, solicitud_registrar, solicitud_obs_tipo_proyecto, solicitud_obs_tipo, solicitud_observacion, solicitud_registrar_cron, prueba_directorios, zona_proyecto, zona, zona_orden, zona_listar, imagen_recurso_origen, imagen, parametro_a, parametro_b, parametro_c, publico, redirecciona, usuario, exportable, creacion, retrasar_headers) VALUES (
	NULL, --item_id
	'kolla', --proyecto
	'38000155', --item
	NULL, --padre_id
	'kolla', --padre_proyecto
	'38000154', --padre
	'0', --carpeta
	'0', --nivel_acceso
	'consola', --solicitud_tipo
	'toba', --pagina_tipo_proyecto
	'normal', --pagina_tipo
	NULL, --actividad_buffer_proyecto
	NULL, --actividad_buffer
	NULL, --actividad_patron_proyecto
	NULL, --actividad_patron
	'Disparar Proceso', --nombre
	NULL, --descripcion
	'1', --punto_montaje
	'nucleo/lib/procesos_bk/disparar_proceso.php', --actividad_accion
	'0', --menu
	NULL, --orden
	'0', --solicitud_registrar
	NULL, --solicitud_obs_tipo_proyecto
	NULL, --solicitud_obs_tipo
	NULL, --solicitud_observacion
	NULL, --solicitud_registrar_cron
	NULL, --prueba_directorios
	NULL, --zona_proyecto
	NULL, --zona
	NULL, --zona_orden
	'0', --zona_listar
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	'0', --publico
	NULL, --redirecciona
	NULL, --usuario
	'0', --exportable
	'2013-04-03 16:47:33', --creacion
	'0'  --retrasar_headers
);
--- FIN Grupo de desarrollo 38
