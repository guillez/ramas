CREATE OR REPLACE VIEW respuestas_view AS
SELECT 
  re.respondido_formulario,
  fhd.formulario_habilitado, 
  fhd.encuesta, 
  fhd.elemento, 
  fhd.orden, 
  fhd.formulario_habilitado_detalle,
  rd.encuesta_definicion,
  ed.bloque, 
  rd.respuesta_codigo, 
  rd.respuesta_valor

FROM 
  sge_formulario_habilitado_detalle fhd, 
  sge_respondido_detalle rd, 
  sge_respondido_encuesta re,
  sge_encuesta_definicion ed
WHERE 
  re.respondido_encuesta = rd.respondido_encuesta AND
  re.formulario_habilitado_detalle = fhd.formulario_habilitado_detalle AND
  ed.encuesta_definicion = rd.encuesta_definicion;
