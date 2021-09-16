--
-- Creación de Bloques
--

INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (416, 'CURSO DE INGRESO', NULL, 1);

--
-- Creación de Encuestas y sus Atributos
--

INSERT INTO sge_encuesta_atributo (encuesta, nombre, descripcion, texto_preliminar, implementada, estado, unidad_gestion) VALUES (9, 'Encuesta de Finalización del Curso de Ingreso', 'Estudio de Desgranamiento Universitario', '<p>
	Estimado aspirante:</p>
<p>
	La encuesta que presentamos, y que agradecemenos completes en su totalidad tiene como objetivo principal relevar datos que contribuyan a que la Universidad que elegiste para continuar tus estudios superiores defina y organice acciones para acompañar tu ingreso y permanencia.</p>', 'N', 'A', '0');

--
-- Creación de Preguntas
--

INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (646, 'Indicá los motivos por los que no iniciaste', 4, '', NULL, NULL, NULL, NULL, '0', 'Motivos por los que no', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (647, '¿Tenés pensado comenzar esta carrera en otro momento?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Comenzar en otro momento?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (648, '¿Estimás que se te presentará obstáculos frente a las siguientes situaciones durante el cursado?', 4, '', NULL, NULL, NULL, NULL, '0', '¿Serán obstáculos?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (649, '¿Terminaste de cursar?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Terminaste de cursar?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (650, '¿En qué condición?', 3, '', NULL, NULL, NULL, NULL, '0', '¿En qué condición?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (653, '¿Recurrirías a alguno de los beneficios que te puede otorgar la Universidad?', 4, '', NULL, NULL, NULL, NULL, '0', '¿Recurriríras a beneficios?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (654, '¿Cuáles considerás que son las causas principales por las que dejaste los estudios?', 4, '', NULL, NULL, NULL, NULL, '0', 'Causas por dejar los estudios', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (651, '¿A qué área pertenece el/los curso/s pendiente/s?', 4, '', NULL, NULL, NULL, NULL, '0', '¿Cursos pendientes?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (655, '¿Tenés pensado retomar tus estudios universitarios en un futuro?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Retomarías tus estudios?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (656, '¿Querés hacer algún comentario u observación?', 8, '', NULL, NULL, NULL, NULL, '0', '¿Comentario u observación?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (652, 'Indicá qué materias no aprobaste:', 1, '', NULL, NULL, NULL, NULL, '0', 'Materias no aprobadas:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (657, 'En caso de haber seleccionado "Otras" indicar:', 1, '', NULL, NULL, NULL, NULL, '0', 'Indicar:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (645, '¿Iniciaste el curso de ingreso?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Iniciaste el curso?', NULL);


--
-- Definición de la Encuesta
--

INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (748, 9, 416, 645, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (749, 9, 416, 646, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (750, 9, 416, 647, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (751, 9, 416, 648, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (760, 9, 416, 657, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (752, 9, 416, 649, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (753, 9, 416, 650, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (754, 9, 416, 651, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (755, 9, 416, 652, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (756, 9, 416, 653, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (757, 9, 416, 654, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (758, 9, 416, 655, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (759, 9, 416, 656, 13, 'N');

--
-- Creación de Respuestas
--

INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (713, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (714, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (715, 'Comencé a trabajar', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (716, 'Comencé otra carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (717, 'Desistí de la carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (718, 'Económicos', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (719, 'Personales (Salud, familia)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (720, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (721, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (722, 'No lo sé', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (723, 'Económicas', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (724, 'Personales (Salud, familia)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (725, 'Organizacionales (Distancias, horarios)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (726, 'De estudio (Comprensión de los contenidos)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (727, 'Relación con los docentes', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (728, 'Adaptación al ritmo institucional', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (729, 'Otras', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (730, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (731, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (732, 'Aprobado', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (733, 'Aprobado parcialmente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (734, 'No aprobado', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (735, 'Ciencias exactas (Por ejemplo: matemáticas, física, química, etc.)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (736, 'Ciencias naturales (Por ejemplo: biología)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (737, 'Ciencias sociales (Por ejemplo: sociología, psicología)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (738, 'Ciencias humanas (Por ejemplo: historia, idiomas)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (739, 'Ciencias de la salud (Por ejemplo: anatomía)', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (740, 'Programas de Becas', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (741, 'Programas de Tutorías', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (742, 'Programas sobre Actividades Recreativas', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (743, 'Obra Social', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (744, 'Otros beneficios', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (745, 'Desconozco', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (746, 'No supe administrar los horarios', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (747, 'No tenía estrategias de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (748, 'Problemas de salud', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (749, 'Viaje o cambio de residencia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (750, 'Problemas en el trabajo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (751, 'Complejidad en el material de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (752, 'Problemas familiares', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (753, 'Volumen y extensión de los textos de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (754, 'No sabía cómo estudiar cada materia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (755, 'Cambio de institución', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (756, 'No era mi vocación', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (757, 'Problemas con algún docente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (758, 'Problemas económicos', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (759, 'Otros', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (760, 'Sí, intentaré seguir con la misma carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (761, 'Sí, intentaré con otra carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (762, 'No lo tengo decidido', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (763, 'Decididamente no lo voy a retomar', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (841, 'Curso de inducción a los ingresos universitarios', '0');

--
-- Asociación de Respuestas y Preguntas
--

INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (713, 645, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (714, 645, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (715, 646, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (716, 646, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (717, 646, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (718, 646, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (719, 646, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (720, 647, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (721, 647, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (722, 647, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (723, 648, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (724, 648, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (725, 648, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (726, 648, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (727, 648, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (728, 648, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (729, 648, 7);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (730, 649, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (731, 649, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (732, 650, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (733, 650, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (734, 650, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (735, 651, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (736, 651, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (737, 651, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (738, 651, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (739, 651, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (841, 651, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (740, 653, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (741, 653, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (742, 653, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (743, 653, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (744, 653, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (745, 653, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (746, 654, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (747, 654, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (748, 654, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (749, 654, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (750, 654, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (751, 654, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (752, 654, 7);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (753, 654, 8);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (754, 654, 9);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (755, 654, 10);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (756, 654, 11);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (757, 654, 12);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (758, 654, 13);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (759, 654, 14);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (760, 655, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (761, 655, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (762, 655, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (763, 655, 4);

--
-- Asociación de Preguntas y Dependencias
--

INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (27, 748);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (28, 748);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (29, 752);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (30, 753);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (31, 751);

--
-- Creación de Dependencias
--

INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (68, 27, 416, 648, 'es_igual_a', 713, 'mostrar', 751);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (69, 27, 416, 649, 'es_igual_a', 713, 'mostrar', 752);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (70, 28, 416, 646, 'es_igual_a', 714, 'mostrar', 749);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (71, 28, 416, 647, 'es_igual_a', 714, 'mostrar', 750);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (72, 29, 416, 650, 'es_igual_a', 730, 'mostrar', 753);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (73, 29, 416, 654, 'es_igual_a', 731, 'mostrar', 757);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (74, 29, 416, 655, 'es_igual_a', 731, 'mostrar', 758);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (75, 30, 416, 651, 'es_igual_a', 733, 'mostrar', 754);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (76, 30, 416, 652, 'es_igual_a', 733, 'mostrar', 755);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (77, 31, 416, 657, 'es_igual_a', 729, 'mostrar', 760);

