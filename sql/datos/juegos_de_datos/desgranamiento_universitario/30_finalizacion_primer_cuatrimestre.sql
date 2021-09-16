--
-- Creación de Bloques
--

INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (417, '[]', NULL, 1);

--
-- Creación de Encuestas y sus Atributos
--

INSERT INTO sge_encuesta_atributo (encuesta, nombre, descripcion, texto_preliminar, implementada, estado, unidad_gestion) VALUES (10, 'Encuesta de Finalización del Primer Cuatrimestre', 'Estudio de Desgranamiento Universitario', '<p>
	Estimado alumno,<br />
	La encuesta que presentamos, y que agradecemos completes en su totalidad, tiene como objetivo principal relevar datos que constribuyan a que la Universidad que elgiste para continuar tus estudios superiores defina y organice acciones para acompañar tu primer año de estudio.</p>', 'S', 'A', '0');

--
-- Creación de Preguntas
--

INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (659, '¿Cursaste alguna asignatura en el primer semestre?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Cursaste alguna asignatura?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (660, 'Con respecto a la elección de tu carrera:', 3, '', NULL, NULL, NULL, NULL, '0', 'Con respecto a tu elección:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (661, '¿Cuántos exámenes finales rendiste?', 11, '', NULL, NULL, NULL, NULL, '0', '¿Cuántos finales rendiste?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (662, '¿Cuántas materias aprobaste?', 11, '', NULL, NULL, NULL, NULL, '0', '¿Cuántas materias aprobaste?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (663, '¿Qué importancia le das a los aplazos en evaluaciones y/o exámenes finales?', 3, '', NULL, NULL, NULL, NULL, '0', 'Importancia de los aplazos:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (664, '¿Te sentías sobrepasado en la información que te brindaron en clases?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Te sentías sobrepasado?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (665, '¿Estudiar una carrera universitaria te produjo estrés?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Estudiar te produjo estrés?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (666, '¿Cómo es tu relación con tus compañeros?', 3, '', NULL, NULL, NULL, NULL, '0', 'Relación con tus compañeros:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (667, '¿Cómo es tu relación con tus docentes?', 3, '', NULL, NULL, NULL, NULL, '0', 'Relación con tus docentes:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (668, '¿Cómo es tu relación con los ayudantes estudiantiles o tutores?', 3, '', NULL, NULL, NULL, NULL, '0', 'Relación con ayudantes y tut.', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (669, '¿Cómo es tu relación con el personal de apoyo? (Despacho de alumnos, etc.)', 3, '', NULL, NULL, NULL, NULL, '0', 'Relación con personal de apoyo', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (670, '¿Cómo es tu relación con el centro de estudiantes?', 3, '', NULL, NULL, NULL, NULL, '0', 'Relación con centro de estud.', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (671, '¿Cómo te resulta la cantidad de material de estudio?', 3, '', NULL, NULL, NULL, NULL, '0', 'Cantidad de material de estud.', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (672, '¿Cómo considerás el grado de dificultad en los exámenes y/o parciales?', 3, '', NULL, NULL, NULL, NULL, '0', 'Dificultad de exámenes y parc.', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (673, 'En general, ¿cómo te evaluarías como alumno?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Cómo te evaluás como alumno?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (674, '¿Cuántas horas semanales promedio dedicás para estudiar aparte de las cursadas?', 3, '', NULL, NULL, NULL, NULL, '0', 'Horas semanales de estudio:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (675, '¿Qué medio de movilidad utilizabas para ir a clase?', 3, '', NULL, NULL, NULL, NULL, '0', 'Medio de movilidad para cursar', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (676, '¿Te sentías acompañado/contenido por tus padres/familia?', 3, '', NULL, NULL, NULL, NULL, '0', 'Contención de familia:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (677, '¿Trabajabas y estudiabas al mismo tiempo?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Trabajabas y estudiabas?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (678, '¿Cuántas horas semanales dedicabas a tu trabajo?', 3, '', NULL, NULL, NULL, NULL, '0', 'Horas semanales de trabajo:', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (679, '¿Cuáles considerás que son las causas principales por las que dejaste los estudios?', 4, '', NULL, NULL, NULL, NULL, '0', 'Causas por dejar los estudios', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (680, '¿Tenés pensado retomar tus estudios universitarios?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Retomarías tus estudios?', NULL);
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda) VALUES (681, '¿Querés hacer algún comentario u observación?', 8, '', NULL, NULL, NULL, NULL, '0', '¿Comentario u observación?', NULL);

--
-- Definición de la Encuesta
--

INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (762, 10, 417, 659, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (763, 10, 417, 660, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (764, 10, 417, 661, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (765, 10, 417, 662, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (766, 10, 417, 663, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (767, 10, 417, 664, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (768, 10, 417, 665, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (769, 10, 417, 666, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (770, 10, 417, 667, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (771, 10, 417, 668, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (772, 10, 417, 669, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (773, 10, 417, 670, 13, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (774, 10, 417, 671, 14, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (775, 10, 417, 672, 15, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (776, 10, 417, 673, 16, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (777, 10, 417, 674, 17, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (778, 10, 417, 675, 18, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (779, 10, 417, 676, 19, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (780, 10, 417, 677, 20, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (781, 10, 417, 678, 21, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (782, 10, 417, 679, 22, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (783, 10, 417, 680, 23, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (784, 10, 417, 681, 24, 'N');

--
-- Creación de Respuestas
--

INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (771, 'Sí, y continúo cursando', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (772, 'Sí, pero no continúo cursando', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (773, 'No cursé ninguna asignatura', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (774, 'Me siento satisfecho', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (775, 'A veces dudo de la carrera que elegí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (776, 'Siento que me equivoqué en la elección', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (777, 'Me deprimen', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (778, 'Me enojan', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (779, 'No les doy ninguna importancia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (780, 'Los tomo como aprendizaje a partir del error', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (781, 'No tuve aplazos hasta el momento', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (782, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (783, 'Un poco', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (784, 'Mucho', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (785, 'Totalmente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (786, 'Mucho', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (787, 'Moderado', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (788, 'Poco', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (789, 'No pasé situaciones de estrés', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (790, 'Buena', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (791, 'Regular', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (792, 'Mala', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (793, 'No tengo relación', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (764, 'No aplica', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (794, 'Demasiada', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (795, 'Suficiente / adecuada', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (796, 'Insuficiente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (797, 'Muy elevado', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (798, 'Elevado', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (799, 'Término medio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (800, 'Bajo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (801, 'Muy bajo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (802, 'Muy bueno', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (803, 'Bueno', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (804, 'Regular', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (805, 'Malo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (806, 'Menos de 6 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (807, 'De 6 a 10 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (808, 'Más de 10 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (809, 'Caminando', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (810, 'Ómnibus Urbano', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (811, 'Ómnibus Interurbano', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (812, 'Bicicleta', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (813, 'Auto', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (765, 'Ninguno, cursé con Modalidad a distancia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (814, 'Otros', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (815, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (816, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (766, 'Medianamente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (817, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (818, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (819, 'Hasta 20 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (820, 'De 21 a 35 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (821, 'Más de 35 hs.', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (822, 'Una cantidad variable de horas', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (823, 'No supe administrar los horarios', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (824, 'No tenía estrategias de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (825, 'Problemas de salud', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (826, 'Viajé o cambié de residencia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (827, 'Problemas en el trabajo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (828, 'Complejidad en el material de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (829, 'Problemas familiares', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (830, 'Volumen y extensión de los textos de estudio', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (831, 'No sabía cómo estudiar cada materia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (832, 'Cambio de institución', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (833, 'No era mi vocación', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (834, 'Problemas con algún docente', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (835, 'Problemas económicos', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (836, 'Otros', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (837, 'Sí, intentaré seguir con la misma carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (838, 'Sí, intentaré con otra carrera', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (839, 'No lo tengo decidido', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (840, 'Decididamente no los voy a retomar', '0');

--
-- Asociación de Respuestas y Preguntas
--

INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (771, 659, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (772, 659, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (773, 659, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (774, 660, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (775, 660, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (776, 660, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (777, 663, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (778, 663, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (779, 663, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (780, 663, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (781, 663, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (782, 664, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (783, 664, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (784, 664, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (785, 664, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (786, 665, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (787, 665, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (788, 665, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (789, 665, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (790, 666, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (791, 666, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (792, 666, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (793, 666, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (790, 667, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (791, 667, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (792, 667, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (793, 667, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (790, 668, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (791, 668, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (792, 668, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (793, 668, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (764, 668, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (790, 669, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (791, 669, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (792, 669, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (793, 669, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (790, 670, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (791, 670, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (792, 670, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (793, 670, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (764, 670, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (794, 671, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (795, 671, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (796, 671, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (797, 672, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (798, 672, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (799, 672, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (800, 672, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (801, 672, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (802, 673, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (803, 673, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (804, 673, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (805, 673, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (806, 674, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (807, 674, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (808, 674, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (809, 675, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (810, 675, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (811, 675, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (812, 675, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (813, 675, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (765, 675, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (814, 675, 7);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (815, 676, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (816, 676, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (766, 676, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (817, 677, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (818, 677, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (819, 678, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (820, 678, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (821, 678, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (822, 678, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (823, 679, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (824, 679, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (825, 679, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (826, 679, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (827, 679, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (828, 679, 6);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (829, 679, 7);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (830, 679, 8);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (831, 679, 9);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (832, 679, 10);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (833, 679, 11);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (834, 679, 12);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (835, 679, 13);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (836, 679, 14);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (837, 680, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (838, 680, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (839, 680, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (840, 680, 4);

--
-- Asociación de Preguntas y Dependencias
--

INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (32, 762);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (33, 780);

--
-- Creación de Dependencias
--

INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (78, 32, 417, 660, 'es_igual_a', 771, 'mostrar', 763);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (80, 32, 417, 675, 'es_igual_a', 773, 'ocultar', 778);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (83, 32, 417, 679, 'es_igual_a', 771, 'ocultar', 782);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (84, 32, 417, 680, 'es_igual_a', 771, 'ocultar', 783);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (79, 33, 417, 678, 'es_igual_a', 817, 'mostrar', 781);

