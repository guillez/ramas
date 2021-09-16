CREATE OR REPLACE VIEW resumen_estado_habilitacion
AS SELECT sh.habilitacion,
    sh.fecha_desde,
    sh.fecha_hasta,
    sh.paginado,
    sh.externa,
    sh.anonima,
    sh.estilo,
    sh.sistema,
    sh.password_se,
    sh.descripcion,
    sh.texto_preliminar,
    sh.archivada,
    sh.destacada,
    sh.descarga_pdf,
    sh.generar_cod_recuperacion,
    sh.imprimir_respuestas_completas,
    sh.mostrar_progreso,
    sh.publica,
    sh.unidad_gestion,
    sh.url_imagenes_base,
    count(DISTINCT srf.respondido_formulario) AS total_respuestas_recibidas_habilitacion
   FROM sge_respondido_formulario srf
     JOIN sge_formulario_habilitado sfh ON sfh.formulario_habilitado = srf.formulario_habilitado
     JOIN sge_grupo_habilitado sgh ON sgh.formulario_habilitado = sfh.formulario_habilitado
     JOIN sge_formulario_habilitado_detalle sfhd ON sfhd.formulario_habilitado = sfh.formulario_habilitado
     JOIN sge_habilitacion sh ON sh.habilitacion = sfh.habilitacion
     JOIN sge_grupo_definicion sgd ON (sgd.grupo = sgh.grupo AND CASE WHEN sgd.externo = 'N'
     								THEN sgd.unidad_gestion::text = sh.unidad_gestion::text
     								ELSE true END) 
  GROUP BY sh.habilitacion;
