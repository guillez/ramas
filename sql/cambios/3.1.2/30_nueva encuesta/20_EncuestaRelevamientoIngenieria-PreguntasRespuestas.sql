-- KOLLA 3 - Sistema de Seguimiento de Graduados  					
-- Version 3.1.2
-- Tabla: -- Datos de preguntas y respuestas para el formulario de relevamiento de Datos Censales										
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 	

--- bloque: 1 = 311 - DATOS CENSALES PRINCIPALES
INSERT INTO sge_bloques ( bloque, nombre, descripcion ) VALUES ( 311, 'Datos Censales Principales', 'Datos Censales Principales' );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 370, 'Situación familiar', 'N', '', 7 );
---Pregunta = 8 'Estado Civil' (Incluye unido de hecho y unido civilmente)
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 371, 'Estado Civil', 'N', 'ing_estado_civil', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 372, 'Cantidad de hijos', 'N', 'ing_cantidad_personas', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 373, 'Cantidad de familiares a cargo', 'N', 'ing_cantidad_personas', 3 );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 374, 'Domicilio durante el período de clases', 'N', '', 7 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 375, 'Calle', 'S', '', 1 );	
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 376, 'Número', 'S', '', 11 );	
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 377, 'Piso', 'S', '', 11 );	
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 378, 'Departamento', 'S', '', 1 );	
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 379, 'Unidad', 'S', '', 1 );	
---Pregunta 207 Localidad
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 380, 'Domicilio de procedencia (donde vive fuera del período de clases)', 'N', '', 7 );
--- Pregunta 375 Calle
--- Pregunta 376 Número
--- Pregunta 377 Piso
--- Pregunta 378 Departamento
--- Pregunta 379 Unidad
--- Pregunta 207 Localidad

--- bloque: 2 = 312 - DATOS ECONOMICOS
INSERT INTO sge_bloques ( bloque, nombre, descripcion ) VALUES ( 312, 'Datos Económicos', 'Datos Económicos' );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 381, 'Fuente de financiamiento de los estudios', 'N', '', 7 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 382, 'Fuente de la beca', 'N', '', 5 );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3069, 'Universidad' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3070, 'Internacional' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3071, 'Nacional' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3072, 'Provincial' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3073, 'Municipal' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3074, 'Otra' );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3069, 1 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3070, 2 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3071, 3 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3072, 4 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3073, 5 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 382, 3074, 6 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 383, 'Tipo de beca', 'N', '', 5 );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3075, 'De contraprestación de servicios' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3076, 'De investigación' );
	INSERT INTO sge_respuestas ( respuesta, valor_tabulado ) VALUES ( 3077, 'De ayuda económica' );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 383, 3075, 1 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 383, 3076, 2 );
	INSERT INTO sge_preguntas_respuestas ( pregunta, respuesta, orden ) VALUES ( 383, 3077, 3 );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 384, 'Situación laboral (no considera becas)', 'N', '', 7 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 385, 'Condición de actividad durante la semana pasada', 'N', 'ing_condicion_actividad', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 386, '¿En ese trabajo es usted?', 'N', 'ing_tipo_trabajo', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 387, '¿Esa ocupación es?', 'N', 'ing_ocupacion', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 388, 'Horas semanales', 'N', 'ing_cantidad_horas_semanales', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 389, 'Relación trabajo carrera', 'N', 'ing_relacion_carrera', 3 );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 390, 'Situación del padre', 'N', '', 7 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 391, 'Máximo nivel de estudios cursados', 'N', 'ing_nivel_instruccion', 3 );	
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 392, '¿Vive?', 'N', 'ing_vive', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 393, '¿En ese trabajo es?', 'N', 'ing_tipo_trabajo', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 394, '¿Esa ocupación es?', 'N', 'ing_ocupacion', 3 );
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 395, 'Si no trabaja y no busca trabajo', 'N', 'ing_no_trabaja_no_busca', 3 );
--- Etiqueta
INSERT INTO sge_preguntas ( pregunta, nombre, admite_valor_libre, tabla_asociada, numero ) VALUES ( 396, 'Situación de la madre', 'N', '', 7 );
--- Pregunta 391
--- Pregunta 392
--- Pregunta 393
--- Pregunta 394
--- Pregunta 395
