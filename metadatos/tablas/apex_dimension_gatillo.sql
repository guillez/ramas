
------------------------------------------------------------
-- apex_dimension_gatillo
------------------------------------------------------------

--- INICIO Grupo de desarrollo 40
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000001', --gatillo
	'indirecto', --tipo
	'2', --orden
	'sge_formulario_habilitado', --tabla_rel_dim
	NULL, --columnas_rel_dim
	'sge_encuesta_atributo', --tabla_gatillo
	'sge_formulario_habilitado_detalle'  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000002', --gatillo
	'directo', --tipo
	'7', --orden
	'sge_concepto', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000003', --gatillo
	'directo', --tipo
	'6', --orden
	'sge_elemento', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000004', --gatillo
	'directo', --tipo
	'8', --orden
	'sge_tipo_elemento', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000005', --gatillo
	'directo', --tipo
	'2', --orden
	'sge_habilitacion', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'40000006', --gatillo
	'directo', --tipo
	'9', --orden
	'sge_grupo_definicion', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
--- FIN Grupo de desarrollo 40

--- INICIO Grupo de desarrollo 45
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'45000001', --gatillo
	'directo', --tipo
	'3', --orden
	'sge_encuesta_atributo', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'45000002', --gatillo
	'directo', --tipo
	'1', --orden
	'sge_unidad_gestion', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'45000003', --gatillo
	'indirecto', --tipo
	'1', --orden
	'sge_formulario_atributo', --tabla_rel_dim
	NULL, --columnas_rel_dim
	'sge_encuesta_atributo', --tabla_gatillo
	'sge_formulario_definicion'  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'45000004', --gatillo
	'directo', --tipo
	'4', --orden
	'sge_pregunta', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
INSERT INTO apex_dimension_gatillo (proyecto, dimension, gatillo, tipo, orden, tabla_rel_dim, columnas_rel_dim, tabla_gatillo, ruta_tabla_rel_dim) VALUES (
	'kolla', --proyecto
	'45000001', --dimension
	'45000005', --gatillo
	'directo', --tipo
	'5', --orden
	'sge_respuesta', --tabla_rel_dim
	'unidad_gestion', --columnas_rel_dim
	NULL, --tabla_gatillo
	NULL  --ruta_tabla_rel_dim
);
--- FIN Grupo de desarrollo 45
