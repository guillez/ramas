
------------------------------------------------------------
-- apex_msg
------------------------------------------------------------

--- INICIO Grupo de desarrollo 38
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000079', --msg
	'importacion_ok', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'importacion_ok', --descripcion_corta
	'La importación de usuarios finalizó correctamente.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000080', --msg
	'reprocesamiento_ok', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'reprocesamiento_ok', --descripcion_corta
	'El reprocesamiento de usuarios finalizó correctamente.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000081', --msg
	'eof_cuadro', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'Cuadro sin datos', --descripcion_corta
	'No se encontraron %1%.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000082', --msg
	'eof_cuadro_filtrado', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'Cuadro filtrado sin datos', --descripcion_corta
	'No se encontraron %1% con el filtro especificado.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000083', --msg
	'eliminar_ok', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'Eliminacion ok', --descripcion_corta
	'Los datos fueron eliminados correctamente.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000084', --msg
	'guardar_ok', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'Guardo ok', --descripcion_corta
	'Los datos fueron guardados correctamente.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000085', --msg
	'eliminar_error', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error al eliminar', --descripcion_corta
	'No se pudo eliminar el registro debido a que esta siendo usado en %1%.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000086', --msg
	'dato_duplicado', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Dato duplicado', --descripcion_corta
	'El nombre ingresado para %1% ya existe o no es válido.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000087', --msg
	'control_fechas', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Control de fechas', --descripcion_corta
	'La fecha %1% no puede ser %2% a la fecha %3%.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000088', --msg
	'fecha_hasta_erronea', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error en la fecha hasta', --descripcion_corta
	'La Fecha Hasta debe ser mayor o igual que la fecha actual.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000089', --msg
	'filas_duplicadas', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Filas duplicadas', --descripcion_corta
	'Existen filas duplicadas.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000090', --msg
	'datos_faltantes_ml', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Datos faltantes', --descripcion_corta
	'Debe ingresar al menos %1%.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000092', --msg
	'existe_usuario', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'existe_usuario', --descripcion_corta
	'El usuario ya existe o existe un usuario con los mismos datos de identificación.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000093', --msg
	'fecha_desde_erronea', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error en la fecha desde', --descripcion_corta
	'La Fecha Desde debe ser mayor o igual que la fecha actual.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000094', --msg
	'control_anio_titulo', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Control del año del titulo', --descripcion_corta
	'El Año para el que se cuenta el título no puede ser menor a la fecha de obtención del mismo.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000095', --msg
	'control_repetidos', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Control de repeticiones', --descripcion_corta
	'¡Atención! Existen %1% repetidos.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000096', --msg
	'filtro_vacio', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'El filtro se encuentra vacio', --descripcion_corta
	'Está a punto de realizar una consulta sobre %1% sin indicar ningún filtro. Esto puede demorar, ¿desea continuar?', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000097', --msg
	'control_indicadores_vacio', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Controla al menos un registro', --descripcion_corta
	'Debe definir al menos un indicador.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000098', --msg
	'institucion_inexistente', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'No existe la institución local', --descripcion_corta
	'Faltan datos de la institución. Debe indicar el código de Araucano.</br>					 Puede cargar estos datos mediante la operación Institución Local en el menú Maestros.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000099', --msg
	'fecha_formato_erroneo', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error en el formato de la fecha.', --descripcion_corta
	'Fecha errónea (debe tener el formato dd/mm/aaaa).', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000100', --msg
	'sexo_erroneo', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error en el sexo', --descripcion_corta
	'Sexo erróneo (debe ser M o F).', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000101', --msg
	'email_erroneo', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Error en el e-mail.', --descripcion_corta
	'E-mail erróneo.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000102', --msg
	'archivo_vacio', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'El archivo se encuentra vacio', --descripcion_corta
	'El archivo no debe estar vacío.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000103', --msg
	'control_reprocesar_persona', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Controla la existencia de registros', --descripcion_corta
	'No existen registros para reprocesar.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'38000105', --msg
	'falta_encuesta', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Controla la existencia de encuestas', --descripcion_corta
	'Debe ingresar al menos una encuesta en la definición del Formulario.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
--- FIN Grupo de desarrollo 38

--- INICIO Grupo de desarrollo 45
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'45000001', --msg
	'importacion_sin_usuarios', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'No existen usuarios para importar.', --descripcion_corta
	'No existen usuarios para importar.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'45000002', --msg
	'exportacion_ok', --indice
	'kolla', --proyecto
	'info', --msg_tipo
	'Exportación OK', --descripcion_corta
	'El archivo con los datos exportados se generó correctamente.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
INSERT INTO apex_msg (msg, indice, proyecto, msg_tipo, descripcion_corta, mensaje_a, mensaje_b, mensaje_c, mensaje_customizable) VALUES (
	'45000003', --msg
	'exportacion_sin_usuarios', --indice
	'kolla', --proyecto
	'error', --msg_tipo
	'Exportación sin usuarios', --descripcion_corta
	'No existen usuarios de ingeniería relevamiento para exportar.', --mensaje_a
	NULL, --mensaje_b
	NULL, --mensaje_c
	NULL  --mensaje_customizable
);
--- FIN Grupo de desarrollo 45
