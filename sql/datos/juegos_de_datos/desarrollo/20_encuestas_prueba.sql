
INSERT INTO sge_pregunta (pregunta, nombre, componente_numero,tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion) VALUES 
( 397, 'El programa de la materia era conocido por los alumnos.', 2, '', NULL, NULL, NULL, NULL, '1' ),
( 398, 'El dictado de las clases cumplió con el cronograma de la materia.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 399, 'El desarrollo de cada uno de los temas fue adecuado.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 400, 'El tiempo asignado a cada uno de los temas fue suficiente.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 401, 'Hubo buena coordinación entre las clases teóricas y las clases de problemas, laboratorios y/o seminarios.',2 , '', NULL, NULL, NULL, NULL , '1'),
( 402, 'Se dieron a conocer las normas de seguridad para el trabajo en el laboratirio.',2 , '', NULL, NULL, NULL, NULL , '1'),
( 403, 'Se cumplieron las normas de seguridad para el trabajo en el laboratorio.',2 , '', NULL, NULL, NULL, NULL , '1'),
( 404, 'El material disponible en el laboratorio fue apropiado y suficiente para el desarrollo de las prácticas.',2 , '', NULL, NULL, NULL, NULL , '1'),
( 405, 'La bibliografía sugerida fue adecuada para comprender los contenidos específicos de la materia.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 406, 'La bibiliografía sugerida sirvió para ampliar y/o profundizar los contenidos de la materia.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 407, 'Los exámenes se ajustaron a los contenidos programados.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 408, 'Las consignas de los exámenes fueron claras y precisas.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 409, 'Las correcciones me orientaron para superar las dificultades.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 410, 'Pude conocer y comentar los criterios de valoración de los exámenes.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 411, 'Las notas con que fui calificado reflejan mi desempeño en los exámenes.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 412, 'Considero que los conocimientos adquiridos en este curso son importantes para mi formación.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 413, 'Asiste normalmente a clases.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 414, 'Cumple con los horarios establecidos.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 415, 'Mantiene un trato adecuado con sus alumnos.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 416, 'Parece dominar la asignatura que imparte.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 417, 'Sus clases están bien organizadas.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 418, 'Explica con claridad.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 419, 'Varía las estrategias de enseñanza para asegurar la comprensión, aclarar dudas o atender necesidades individuales.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 420, 'Presenta un panorama amplio de su asignatura.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 421, 'Responde con exactitud y precisión a las preguntas que le hacen.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 422, 'Intenta que los alumnos participen en las clases.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 423, 'Acepta la crítica fundamentada.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 424, 'Utiliza en clase un material didáctico que ayuda a comprender las explicaciones.',2 , '', NULL, NULL, NULL, NULL, '1' ),
( 425, 'Comentario', 8, '', NULL, NULL, NULL, NULL, '1' );

INSERT INTO sge_respuesta ( respuesta, valor_tabulado , unidad_gestion) VALUES
	 ( 3078, '1', '1' ),
	 ( 3079, '2', '1' ),
	 ( 3080, '3', '1'),
	 ( 3081, '4', '1' ),
	 ( 3082, '5','1' );


INSERT INTO sge_pregunta_respuesta ( pregunta, respuesta, orden ) VALUES
	( 397, 0, 1 ),
	( 397, 3078, 2 ),
	( 397, 3079, 3 ),
	( 397, 3080, 4 ),
	( 397, 3081, 5 ),
	( 397, 3082, 6 ),
	
	( 398, 0, 1 ),
 ( 398, 3078, 2 ),
 ( 398, 3079, 3 ),
 ( 398, 3080, 4 ),
 ( 398, 3081, 5 ),
 ( 398, 3082, 6 ),
                 
 
 ( 399, 0, 1 ),
 ( 399, 3078, 2 ),
 ( 399, 3079, 3 ),
 ( 399, 3080, 4 ),
 ( 399, 3081, 5 ),
 ( 399, 3082, 6 ),

( 400, 0, 1 )	,
( 400, 3078, 2 ),
( 400, 3079, 3 ),
( 400, 3080, 4 ),
( 400, 3081, 5 ),
( 400, 3082, 6 ),

( 401, 0, 1 )	,
( 401, 3078, 2 ),
( 401, 3079, 3 ),
( 401, 3080, 4 ),
( 401, 3081, 5 ),
( 401, 3082, 6 ),
 
( 402, 0, 1 ),		-- No Responde
( 402, 3078, 2 ),
( 402, 3079, 3 ),
( 402, 3080, 4 ),
( 402, 3081, 5 ),
( 402, 3082, 6 ),
( 403, 0, 1 ),		-- No Responde
( 403, 3078, 2 ),
( 403, 3079, 3 ),
( 403, 3080, 4 ),
( 403, 3081, 5 ),
( 403, 3082, 6 ),
( 404, 0, 1 ),		-- No Responde
( 404, 3078, 2 ),
( 404, 3079, 3 ),
( 404, 3080, 4 ),
( 404, 3081, 5 ),
( 404, 3082, 6 ),
 ( 405, 0, 1 ),		-- No Responde
 ( 405, 3078, 2 ),
 ( 405, 3079, 3 ),
 ( 405, 3080, 4 ),
 ( 405, 3081, 5 ),
 ( 405, 3082, 6 ),
 ( 406, 0, 1 ),		-- No Responde
 ( 406, 3078, 2 ),
 ( 406, 3079, 3 ),
 ( 406, 3080, 4 ),
 ( 406, 3081, 5 ),
 ( 406, 3082, 6 ),

 ( 407, 0, 1 ),		-- No Responde
 ( 407, 3078, 2 ),
 ( 407, 3079, 3 ),
 ( 407, 3080, 4 ),
 ( 407, 3081, 5 ),
 ( 407, 3082, 6 ),


 ( 408, 0, 1 ),		-- No Responde
 ( 408, 3078, 2 ),
 ( 408, 3079, 3 ),
 ( 408, 3080, 4 ),
 ( 408, 3081, 5 ),
 ( 408, 3082, 6 ),
 ( 409, 0, 1 ),		-- No Responde
 ( 409, 3078, 2 ),
 ( 409, 3079, 3 ),
 ( 409, 3080, 4 ),
 ( 409, 3081, 5 ),
 ( 409, 3082, 6 ),
 ( 410, 0, 1 ),		-- No Responde
 ( 410, 3078, 2 ),
 ( 410, 3079, 3 ),
 ( 410, 3080, 4 ),
 ( 410, 3081, 5 ),
 ( 410, 3082, 6 ),
 ( 411, 0, 1 ),		-- No Responde
 ( 411, 3078, 2 ),
 ( 411, 3079, 3 ),
 ( 411, 3080, 4 ),
 ( 411, 3081, 5 ),
 ( 411, 3082, 6 ),
 ( 412, 0, 1 ),		-- No Responde
 ( 412, 3078, 2 ),
 ( 412, 3079, 3 ),
 ( 412, 3080, 4 ),
 ( 412, 3081, 5 ),
 ( 412, 3082, 6 ),

 ( 413, 0, 1 ),		-- No Responde
 ( 413, 3078, 2 ),
 ( 413, 3079, 3 ),
 ( 413, 3080, 4 ),
 ( 413, 3081, 5 ),
 ( 413, 3082, 6 ),
 ( 414, 0, 1 ),		-- No Responde
 ( 414, 3078, 2 ),
 ( 414, 3079, 3 ),
 ( 414, 3080, 4 ),
 ( 414, 3081, 5 ),
 ( 414, 3082, 6 ),
 ( 415, 0, 1 ),		-- No Responde
 ( 415, 3078, 2 ),
 ( 415, 3079, 3 ),
 ( 415, 3080, 4 ),
 ( 415, 3081, 5 ),
 ( 415, 3082, 6 ),
 ( 416, 0, 1 ),		-- No Responde
 ( 416, 3078, 2 ),
 ( 416, 3079, 3 ),
 ( 416, 3080, 4 ),
 ( 416, 3081, 5 ),
 ( 416, 3082, 6 ),
 ( 417, 0, 1 ),		-- No Responde
 ( 417, 3078, 2 ),
 ( 417, 3079, 3 ),
 ( 417, 3080, 4 ),
 ( 417, 3081, 5 ),
 ( 417, 3082, 6 ),
 ( 418, 0, 1 ),		-- No Responde
 ( 418, 3078, 2 ),
 ( 418, 3079, 3 ),
 ( 418, 3080, 4 ),
 ( 418, 3081, 5 ),
 ( 418, 3082, 6 ),
 ( 419, 0, 1 ),		-- No Responde
 ( 419, 3078, 2 ),
 ( 419, 3079, 3 ),
 ( 419, 3080, 4 ),
 ( 419, 3081, 5 ),
 ( 419, 3082, 6 ),
 ( 420, 0, 1 ),		-- No Responde
 ( 420, 3078, 2 ),
 ( 420, 3079, 3 ),
 ( 420, 3080, 4 ),
 ( 420, 3081, 5 ),
 ( 420, 3082, 6 ),
 ( 421, 0, 1 ),		-- No Responde
 ( 421, 3078, 2 ),
 ( 421, 3079, 3 ),
 ( 421, 3080, 4 ),
 ( 421, 3081, 5 ),
 ( 421, 3082, 6 ),
 ( 422, 0, 1 ),		-- No Responde
 ( 422, 3078, 2 ),
 ( 422, 3079, 3 ),
 ( 422, 3080, 4 ),
 ( 422, 3081, 5 ),
 ( 422, 3082, 6 ),
 ( 423, 0, 1 ),		-- No Responde
 ( 423, 3078, 2 ),
 ( 423, 3079, 3 ),
 ( 423, 3080, 4 ),
 ( 423, 3081, 5 ),
 ( 423, 3082, 6 ),
 ( 424, 0, 1 ),		-- No Responde
 ( 424, 3078, 2 ),
 ( 424, 3079, 3 ),
 ( 424, 3080, 4 ),
 ( 424, 3081, 5 ),
 ( 424, 3082, 6 );

INSERT INTO sge_bloque ( bloque, nombre, descripcion, orden ) VALUES ( 313, 'Materia', 'Materia', 1 );
INSERT INTO sge_bloque ( bloque, nombre, descripcion, orden ) VALUES ( 314, 'Acerca de los docentes', 'Docentes', 1 );
INSERT INTO sge_bloque ( bloque, nombre, descripcion, orden ) VALUES ( 315, 'Comentarios', 'Docentes', 1 );


INSERT INTO sge_encuesta_atributo (encuesta,nombre,descripcion,texto_preliminar, implementada,estado, unidad_gestion) VALUES 
('5','Evaluación de cátedra - Materia','Evaluación de materia',NULL, 'S','A', '1'),
('6','Evaluación de cátedra - Docentes','Evaluación de docentes',NULL,'S','A', '1'),
('7','Evaluación de cátedra - Comentarios','Evaluación de cátedra', NULL, 'S','A', '1');


INSERT INTO sge_encuesta_definicion (encuesta_definicion, encuesta, bloque, orden, pregunta, obligatoria) VALUES 
 (500, 5, 313,   5, 397, 'N' ),
 (501, 5, 313,  10, 398, 'N' ),
 (502, 5, 313,  15, 399, 'N' ),
 (503, 5, 313,  20, 400, 'N' ),
 (504, 5, 313,  25, 401, 'N' ),
 (505, 5, 313,  30, 402, 'N' ),
 (506, 5, 313,  35, 403, 'N' ),
 (507, 5, 313,  40, 404, 'N' ),
 (508, 5, 313,  45, 405, 'N' ),
 (509, 5, 313,  50, 406, 'N' ),
 (510, 5, 313,  55, 407, 'N' ),
 (511, 5, 313,  60, 408, 'N' ),
 (512, 5, 313,  65, 409, 'N' ),
 (513, 5, 313,  70, 410, 'N' ),
 (514, 5, 313,  75, 411, 'N' ),
 (515, 5, 313,  80, 412, 'N' ),
(516, 6, 314,   5, 413, 'N' ),
(517, 6, 314,  10, 414, 'N' ),
(518, 6, 314,  15, 415, 'N' ),
(519, 6, 314,  20, 416, 'N' ),
(520, 6, 314,  25, 417, 'N' ),
(521, 6, 314,  30, 418, 'N' ),
(522, 6, 314,  35, 419, 'N' ),
(523, 6, 314,  40, 420, 'N' ),
(524, 6, 314,  45, 421, 'N' ),
(525, 6, 314,  50, 422, 'N' ),
(526, 6, 314,  55, 423, 'N' ),
(527, 6, 314,  60, 424, 'N' ),
(528, 7, 315,   5, 425, 'N' );