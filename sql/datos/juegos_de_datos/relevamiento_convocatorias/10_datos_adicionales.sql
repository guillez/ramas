--
-- Creación de Bloques
--

INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (418, '[]', NULL, 2);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (419, '[]', NULL, 3);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (420, '[]', NULL, 4);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (421, '[]', NULL, 5);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (422, '[]', NULL, 6);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (423, '[]', NULL, 7);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (424, '[]', NULL, 8);
INSERT INTO sge_bloque (bloque, nombre, descripcion, orden) VALUES (425, '[]', NULL, 1);

--
-- Creación de Encuestas y sus Atributos
--

INSERT INTO sge_encuesta_atributo (encuesta, nombre, descripcion, texto_preliminar, implementada, estado, unidad_gestion) VALUES (11, 'Datos adicionales', 'Cuestionario final del proceso de relevamiento de datos para solicitud de beca.', '<p>
	Estimado aspirante,</p>
<p>
	Te invitamos a completar estos datos adicionales para finalizar el proceso de solicitud de beca.</p>', 'N', 'A', '0');

--
-- Creación de Preguntas
--

INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (682, 'Modo de ingreso a la universidad:', 3, '', NULL, NULL, NULL, NULL, '0', 'Modo de ingreso:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (683, '¿En qué año de la carrera te encontrás?', 11, '', NULL, NULL, NULL, NULL, '0', 'Año de carrera:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (684, 'Tipo de beca:', 1, '', NULL, NULL, NULL, NULL, '0', 'Tipo de beca:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (685, 'Fuente de financiamiento:', 1, '', NULL, NULL, NULL, NULL, '0', 'Fuente de financiamiento:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (686, 'Año:', 14, '', NULL, NULL, NULL, NULL, '0', 'Año:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (687, '¿Qué tipo de transporte utilizás para llegar a la universidad?', 5, '', NULL, NULL, NULL, NULL, '0', 'Tipo de transporte:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (688, '¿Recibís ayuda económica de otras personas?', 5, '', NULL, NULL, NULL, NULL, '0', 'Recibe ayuda económica:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (689, 'Especificar:', 1, '', NULL, NULL, NULL, NULL, '0', 'Especificar:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (690, 'Tipo y características de tu vivienda', 7, '', NULL, NULL, NULL, NULL, '0', 'Características de vivienda', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (691, 'Tipo de vivienda:', 3, '', NULL, NULL, NULL, NULL, '0', 'Tipo de vivienda:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (692, 'Tipo de locación:', 3, '', NULL, NULL, NULL, NULL, '0', 'Tipo de locación:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (693, 'Cantidad de personas que la habitan:', 11, '', NULL, NULL, NULL, NULL, '0', 'Cantidad de personas:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (694, 'Tipo de zona de la vivienda:', 3, '', NULL, NULL, NULL, NULL, '0', 'Tipo de zona:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (695, '¿Cuántos?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Cuántos?', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (696, 'Apellido y nombre:', 1, '', NULL, NULL, NULL, NULL, '0', 'Apellido y nombre:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (697, 'Edad:', 13, '', NULL, NULL, NULL, NULL, '0', 'Edad:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (698, 'Vínculo:', 1, '', NULL, NULL, NULL, NULL, '0', 'Vínculo:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (699, 'Ocupación:', 1, '', NULL, NULL, NULL, NULL, '0', 'Ocupación:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (700, '¿Te gustaría hacer algún comentario final?', 8, '', NULL, NULL, NULL, NULL, '0', 'Comentario final:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (701, 'Datos de Familiar 1', 7, '', NULL, NULL, NULL, NULL, '0', 'Datos de Familiar 1', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (702, 'Datos de Familiar 2', 7, '', NULL, NULL, NULL, NULL, '0', 'Datos de Familiar 2', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (703, 'Datos de Familiar 3', 7, '', NULL, NULL, NULL, NULL, '0', 'Datos de Familiar 3', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (704, 'Datos de Familiar 4', 7, '', NULL, NULL, NULL, NULL, '0', 'Datos de Familiar 4', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (705, 'Datos de Familiar 5', 7, '', NULL, NULL, NULL, NULL, '0', 'Datos de Familiar 5', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (706, '¿Tenés personas a cargo?', 3, '', NULL, NULL, NULL, NULL, '0', '¿Tenés personas a cargo?', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (707, '¿Fuiste o sos beneficiario de una beca?', 3, '', NULL, NULL, NULL, NULL, '0', 'Beneficiario:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (708, '¿Sos económicamente independiente?', 3, '', NULL, NULL, NULL, NULL, '0', 'Económicamente indep:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (709, 'Cantidad de ambientes:', 12, '', NULL, NULL, NULL, NULL, '0', 'Cantidad de ambientes:', NULL, 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (710, '¿A qué distancia en kilómetros vivís de la universidad?', 12, '', NULL, NULL, NULL, NULL, '0', 'Distancia:', 'Expresar la distancia en kilómetros', 'N');
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta) VALUES (711, 'Estado civil:', 3, '', NULL, NULL, NULL, NULL, '0', 'Estado civil:', NULL, 'N');

--
-- Definición de la Encuesta
--

INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (899, 11, 425, 682, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (790, 11, 425, 683, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (791, 11, 425, 707, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (792, 11, 425, 684, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (793, 11, 425, 685, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (794, 11, 418, 706, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (795, 11, 418, 695, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (796, 11, 419, 701, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (797, 11, 419, 696, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (798, 11, 419, 697, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (799, 11, 419, 698, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (800, 11, 419, 711, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (801, 11, 419, 699, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (802, 11, 420, 701, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (803, 11, 420, 696, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (804, 11, 420, 697, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (805, 11, 420, 698, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (806, 11, 420, 711, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (807, 11, 420, 699, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (808, 11, 420, 702, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (809, 11, 420, 696, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (810, 11, 420, 697, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (811, 11, 420, 698, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (812, 11, 420, 711, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (813, 11, 420, 699, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (814, 11, 425, 686, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (815, 11, 425, 710, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (816, 11, 425, 687, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (817, 11, 425, 708, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (818, 11, 425, 688, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (819, 11, 425, 689, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (820, 11, 425, 690, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (821, 11, 425, 691, 13, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (822, 11, 425, 692, 14, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (823, 11, 425, 709, 15, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (824, 11, 425, 693, 16, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (825, 11, 425, 694, 17, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (826, 11, 421, 701, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (827, 11, 421, 696, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (828, 11, 421, 697, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (829, 11, 421, 698, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (830, 11, 421, 711, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (831, 11, 421, 699, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (832, 11, 421, 702, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (833, 11, 421, 696, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (834, 11, 421, 697, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (835, 11, 421, 698, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (836, 11, 421, 711, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (837, 11, 421, 699, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (838, 11, 421, 703, 13, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (839, 11, 421, 696, 14, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (840, 11, 421, 697, 15, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (841, 11, 421, 698, 16, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (842, 11, 421, 711, 17, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (843, 11, 421, 699, 18, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (844, 11, 422, 701, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (845, 11, 422, 696, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (846, 11, 422, 697, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (847, 11, 422, 698, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (848, 11, 422, 711, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (849, 11, 422, 699, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (850, 11, 422, 702, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (851, 11, 422, 696, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (852, 11, 422, 697, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (853, 11, 422, 698, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (854, 11, 422, 711, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (855, 11, 422, 699, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (856, 11, 422, 703, 13, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (857, 11, 422, 696, 14, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (858, 11, 422, 697, 15, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (859, 11, 422, 698, 16, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (860, 11, 422, 711, 17, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (861, 11, 422, 699, 18, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (862, 11, 422, 704, 19, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (863, 11, 422, 696, 20, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (864, 11, 422, 697, 21, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (865, 11, 422, 698, 22, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (866, 11, 422, 711, 23, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (867, 11, 422, 699, 24, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (868, 11, 423, 701, 1, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (869, 11, 423, 696, 2, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (870, 11, 423, 697, 3, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (871, 11, 423, 698, 4, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (872, 11, 423, 711, 5, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (873, 11, 423, 699, 6, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (874, 11, 423, 702, 7, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (875, 11, 423, 696, 8, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (876, 11, 423, 697, 9, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (877, 11, 423, 698, 10, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (878, 11, 423, 711, 11, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (879, 11, 423, 699, 12, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (880, 11, 423, 703, 13, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (881, 11, 423, 696, 14, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (882, 11, 423, 697, 15, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (883, 11, 423, 698, 16, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (884, 11, 423, 711, 17, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (885, 11, 423, 699, 18, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (886, 11, 423, 704, 19, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (887, 11, 423, 696, 20, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (888, 11, 423, 697, 21, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (889, 11, 423, 698, 22, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (890, 11, 423, 711, 23, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (891, 11, 423, 699, 24, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (892, 11, 423, 705, 25, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (893, 11, 423, 696, 26, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (894, 11, 423, 697, 27, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (895, 11, 423, 698, 28, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (896, 11, 423, 711, 29, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (897, 11, 423, 699, 30, 'N');
INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, pregunta, orden, obligatoria) VALUES (898, 11, 424, 700, 1, 'N');

--
-- Creación de Respuestas
--

INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (842, 'Secundario/Polimodal completo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (843, 'Falta completar el Secundario/Polimodal', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (844, 'Mayor de 25 años sin Secundario completo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (845, 'Por equivalencias', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (846, 'Otro', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (847, 'Auto', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (848, 'Tren', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (849, 'Colectivo', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (850, 'Bicicleta', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (851, 'A pie', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (852, 'Sí', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (853, 'No', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (854, 'Casa', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (855, 'Departamento', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (856, 'Pensión o Residencia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (857, 'Otro', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (858, 'Alquilada', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (859, 'Cedida', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (860, 'Propia', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (861, 'Hipoteca', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (862, 'Urbana', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (863, 'Rural', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (864, '1', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (865, '2', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (866, '3', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (867, '4', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (868, '5', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (869, 'Alojamiento', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (870, 'Alimentos', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (871, 'Dinero', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (872, 'Otros', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (873, 'Soltero/a', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (874, 'Casado/a', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (875, 'Separado/a', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (876, 'Divorciado/a', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (877, 'Viudo/a', '0');
INSERT INTO sge_respuesta (respuesta, valor_tabulado, unidad_gestion) VALUES (878, 'Unido/a de hecho', '0');

--
-- Asociación de Respuestas y Preguntas
--

INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (842, 682, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (843, 682, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (844, 682, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (845, 682, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (846, 682, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (852, 707, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (853, 707, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (847, 687, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (848, 687, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (849, 687, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (850, 687, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (851, 687, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (852, 708, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (853, 708, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (869, 688, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (870, 688, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (871, 688, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (872, 688, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (854, 691, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (855, 691, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (856, 691, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (857, 691, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (852, 706, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (853, 706, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (864, 695, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (865, 695, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (866, 695, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (867, 695, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (868, 695, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (858, 692, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (859, 692, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (860, 692, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (861, 692, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (862, 694, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (863, 694, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (873, 711, 1);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (874, 711, 2);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (875, 711, 3);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (876, 711, 4);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (877, 711, 5);
INSERT INTO sge_pregunta_respuesta (respuesta, pregunta, orden) VALUES (878, 711, 6);

--
-- Asociación de Preguntas y Dependencias
--

INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (36, 791);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (37, 817);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (38, 818);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (39, 794);
INSERT INTO sge_pregunta_dependencia (pregunta_dependencia, encuesta_definicion) VALUES (40, 795);

--
-- Creación de Dependencias
--

INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (85, 36, 425, 684, 'es_igual_a', '852', 'mostrar', 792);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (86, 36, 425, 685, 'es_igual_a', '852', 'mostrar', 793);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (87, 36, 425, 686, 'es_igual_a', '852', 'mostrar', 814);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (89, 38, 425, 689, 'es_igual_a', '872', 'mostrar', 819);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (90, 39, 418, 695, 'es_igual_a', '852', 'mostrar', 795);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (91, 40, 419, NULL, 'es_igual_a', '864', 'mostrar', NULL);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (92, 40, 420, NULL, 'es_igual_a', '865', 'mostrar', NULL);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (93, 40, 421, NULL, 'es_igual_a', '866', 'mostrar', NULL);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (94, 40, 422, NULL, 'es_igual_a', '867', 'mostrar', NULL);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (95, 40, 423, NULL, 'es_igual_a', '868', 'mostrar', NULL);
INSERT INTO sge_pregunta_dependencia_definicion (dependencia_definicion, pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion) VALUES (88, 37, 425, 688, 'es_igual_a', '853', 'mostrar', 818);
