CREATE OR REPLACE VIEW obtener_respondidos
AS SELECT srf.respondido_formulario,
    sfh.habilitacion,
    srd.encuesta_definicion,
    srd.respuesta_codigo
   FROM sge_respondido_formulario srf
     JOIN sge_formulario_habilitado sfh ON sfh.formulario_habilitado = srf.formulario_habilitado
     JOIN sge_respondido_encuesta sre ON sre.respondido_formulario = srf.respondido_formulario
     JOIN sge_respondido_detalle srd ON srd.respondido_encuesta = sre.respondido_encuesta;
