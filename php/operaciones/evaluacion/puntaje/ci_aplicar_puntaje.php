<?php
class ci_aplicar_puntaje extends ci_navegacion
{
        protected $s__habilitacion;
        protected $s__encuesta;
        protected $s__puntaje;
        protected $s__nuevo_puntaje;
        protected $s__form;

        protected $s__datos_evaluacion;
        protected $s__seleccion_puntajes ;
        
	//-----------------------------------------------------------------------------------
	//---- formulario_evaluacion --------------------------------------------------------
	//-----------------------------------------------------------------------------------
		
	function conf__formulario_evaluacion(bootstrap_formulario $form)
	{   
            $evaluacion = $this->controlador()->get_evaluacion();
            
            if (isset($evaluacion)) {
                //es una edición
                $r = $this->controlador()->get_relacion_evaluacion();
                $this->s__datos_evaluacion = $r->tabla('sge_evaluacion')->get();
            }
            
            if (isset($this->s__datos_evaluacion)) {
                $form->set_datos($this->s__datos_evaluacion);
                $form->set_solo_lectura(array('cerrada'));
            } 
	}

	function evt__formulario_evaluacion__modificacion($datos)
	{
            $this->s__datos_evaluacion['nombre'] = $datos['nombre'];
            $this->s__datos_evaluacion['cerrada'] = $datos['cerrada'];
            $this->s__datos_evaluacion['habilitacion'] = $this->controlador()->get_habilitacion();
            
            $r = $this->controlador()->get_relacion_evaluacion();
            $t = $r->tabla('sge_evaluacion');
            if (!$t->esta_cargada()) {
                try {
                    $id_nuevo = $t->nueva_fila($this->s__datos_evaluacion);
                    $r->sincronizar();
                    $datos = $t->get_fila($id_nuevo);
                    $t->cargar(array('evaluacion' => $datos['evaluacion']));
                } catch (toba_error_db $e) {
                    toba::notificacion()->error($e);
                } 
            }
	}        

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(bootstrap_formulario $form)
	{
            if (isset($this->s__form)) 
            {
                $form->set_datos($this->s__form);
            }
	}
		
        function get_encuestas_habilitacion ()
        {
            $hab = $this->controlador()->get_habilitacion();
            if (isset($hab)) {
                return toba::consulta_php('consultas_encuestas')->get_encuestas_de_habilitacion($hab);
            }
        }

        function get_puntajes_encuesta_habilitada($encuesta)
        {   //obtener puntajes definidos para la encuesta 
            return toba::consulta_php('consultas_evaluacion')->get_puntajes_encuesta($encuesta);
        }

        function get_puntajes_a_aplicar()
        {
            if (isset($this->s__encuesta)) {
                return $this->get_puntajes_encuesta_habilitada($this->s__encuesta);
            }
        }

        function evt__form__filtrar($datos)
	{
            $this->s__form = $datos;
            $this->s__encuesta = $datos['encuesta'];
            $this->s__puntaje = $datos['puntaje'];
	}
        
        function evt__form__cancelar($datos)
	{
            unset($this->s__form);
            unset($this->s__encuesta);
            unset($this->s__puntaje);
	}        
		
	//-----------------------------------------------------------------------------------
	//---- puntaje ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__puntaje(toba_ei_formulario $form)
	{
            if (isset($this->s__encuesta)) {
                $puntajes = toba::consulta_php('consultas_evaluacion')->get_puntajes_encuesta($this->s__encuesta);
                $form->set_datos($puntajes);
            }            
	}

	function evt__puntaje__modificacion($datos)
	{
            $this->s__nuevo_puntaje = $datos['puntaje'];
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_puntajes --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_puntajes(cuadro_seleccion_multiple $cuadro)
	{            
            if (isset($this->s__encuesta)) {

                $evaluacion = $this->controlador()->get_evaluacion();
                $habilitacion = $this->controlador()->get_habilitacion();
                
                $datos = $this->formularios_habilitados($habilitacion, $this->s__encuesta, $evaluacion, $this->s__puntaje);
                $cuadro->set_datos($datos);
            }            
	}
        /*
        function formularios_habilitados_puntaje($habilitacion) {
            
            $r = $this->controlador()->get_relacion_evaluacion();
            $filas = $r->tabla('sge_puntaje_aplicacion')->get_filas();
            
            //Formularios habilitados detalle que tienen puntaje asociado en esta evaluación
            $fhds = array_column($filas, 'formulario_habilitado', 'formulario_habilitado_detalle');
            
            //Formularios habilitados detalle de la habilitación
            $sql = "SELECT DISTINCT sfh.formulario_habilitado, sfhd.formulario_habilitado_detalle, sea.encuesta
                        FROM sge_encuesta_atributo sea INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta) 
                            INNER JOIN sge_formulario_habilitado sfh ON (sfh.formulario_habilitado = sfhd.formulario_habilitado) 
                            WHERE TRUE AND sfh.habilitacion = $habilitacion 
                        ORDER BY 2";
            $res = kolla_db::consultar($sql);
            $todos = array_column($res, 'formulario_habilitado', 'formulario_habilitado_detalle');
            
            //Formularios habilitados detalle que no tienen puntaje 
            $filtrado = array_diff_key($todos, $fhds);
            $listado = implode(",", array_unique(array_values($filtrado)));
        }                
	*/
        function formularios_habilitados($habilitacion, $encuesta, $evaluacion, $puntaje = null) 
        {
            $r = $this->controlador()->get_relacion_evaluacion();
            $filas = $r->tabla('sge_puntaje_aplicacion')->get_filas();
            
            //Formularios habilitados detalle que tienen puntaje asociado en esta evaluación
            $fhds = array_column($filas, 'formulario_habilitado_detalle');
                       
            $listado = implode(",", $fhds);
            
            $select = " ";
            $from = " ";
            $where = " ";
            $group_by = "";
                        
            if (isset($evaluacion)) {
                $where .= " AND se.evaluacion = $evaluacion ";
                if (isset($puntaje)) {
                    $select = ", sp.puntaje
                            , CASE 
                                    WHEN (sp.nombre IS NOT NULL) THEN sp.nombre 
                                    ELSE 'Sin puntaje' END AS puntaje_nombre ";
                    $from = " INNER JOIN sge_puntaje sp ON (sp.puntaje = spa.puntaje AND sp.encuesta = sea.encuesta AND sp.puntaje = $puntaje )";
                    $group_by = ", sp.puntaje ";
                } else {
                    //se busca los que en esta evaluación no tienen puntaje
                    $where .= count($fhds) > 0 ? " AND sfhd.formulario_habilitado_detalle NOT IN ( $listado ) " : "";
                }                
            } else {
                //es una evaluación nueva, no habrá ningún puntaje asociado
                $where .= isset($puntaje) ? " AND FALSE " : "  ";
            }

            $sql = "SELECT DISTINCT                        
                        CASE 
                            WHEN (sc.concepto IS NOT NULL) THEN sc.concepto 
                            ELSE -1 END AS concepto
                        , CASE 
                            WHEN (sect.tipo_elemento IS NOT NULL) THEN sect.tipo_elemento 
                            ELSE -1 END AS tipo_elemento                             
                        , CASE 
                            WHEN (sc.concepto IS NOT NULL) THEN sc.descripcion 
                            ELSE 'Sin concepto' END AS concepto_nombre 
                        , CASE 
                            WHEN (sect.tipo_elemento IS NOT NULL) THEN ste.descripcion 
                            ELSE 'Sin tipo de elemento' END AS tipo_elemento_nombre
                        , CASE 
                                WHEN (sect.tipo_elemento IS NOT NULL AND sc.concepto IS NOT NULL) THEN sc.concepto || '_'  || sect.tipo_elemento
                                ELSE '0_0' END AS concepto_elemento
                        , CASE 
                                WHEN (sect.tipo_elemento IS NOT NULL AND sc.concepto IS NOT NULL) THEN sc.descripcion || ' - '  || ste.descripcion 
                                ELSE 'Sin tipo de elemento' END AS concepto_elemento_descripcion
                        , sfh.formulario_habilitado    
                        , sea.encuesta 
                        , sea.nombre as encuesta_nombre
                        $select
                        , se.evaluacion
                        , se.nombre as evaluacion_nombre
                    FROM sge_encuesta_atributo sea 
                            INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta)
                            INNER JOIN sge_formulario_habilitado sfh ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)    
                            LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
                            LEFT JOIN sge_tipo_elemento ste ON (ste.tipo_elemento = sfhd.tipo_elemento)
                            LEFT JOIN sge_elemento_concepto_tipo sect ON (sect.concepto = sc.concepto AND sect.tipo_elemento = ste.tipo_elemento)
                            
                            INNER JOIN sge_evaluacion se ON (se.habilitacion = sfh.habilitacion)
                            LEFT JOIN sge_puntaje_aplicacion spa ON (spa.evaluacion = se.evaluacion 
                                                                    AND spa.formulario_habilitado = sfh.formulario_habilitado 
                                                                    AND spa.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
                            $from
                    WHERE sea.encuesta = $encuesta 
                        AND sfh.habilitacion = $habilitacion
                        $where 
                    GROUP BY sc.concepto, sect.tipo_elemento, ste.descripcion, sc.descripcion, sfh.formulario_habilitado, sea.encuesta
                                    , spa.puntaje_aplicacion, se.evaluacion $group_by
                    ORDER BY 7
                    ; ";
            return kolla_db::consultar($sql);
        }                
        
	function evt__cuadro_puntajes__seleccion_multiple($datos)
	{
            $this->s__seleccion_puntajes = $datos;
	}

	function evt__cuadro_puntajes__aplicar()
	{
            //se reciben los ids de formularios_habilitados + tipo_elemento 
            //a los que se desea aplicar el puntaje elegido           
            $hab = $this->controlador()->get_habilitacion();
            $rel = $this->controlador()->get_relacion_evaluacion();
            
            foreach ($this->s__seleccion_puntajes as $elegido) {
                $fh = $elegido['formulario_habilitado'];
                $conc =  ($elegido['concepto'] != '-1') ? $elegido['concepto'] : null;
                $t_elto =  ($elegido['tipo_elemento'] != '-1') ? $elegido['tipo_elemento'] : null;   
                
                //se debe averiguar primero todos los formulario_habilitado_detalle que correspoden
                //a formulario_habilitado+concepto+tipo_elemento indicados
                $res = $this->get_formularios_detalle($hab, $fh, $conc, $this->s__encuesta, $t_elto);
                foreach ($res as $fhd) {
                    $fila['formulario_habilitado'] = $fh;
                    $fila['formulario_habilitado_detalle'] = $fhd['formulario_habilitado_detalle'];
                    $fila['puntaje'] = $this->s__nuevo_puntaje;
                    $this->upsert_puntaje_aplicacion($fh, $fhd['formulario_habilitado_detalle'], $this->s__nuevo_puntaje);
                }
            }
            $rel->sincronizar();
	}

        function get_formularios_detalle($habilitacion, $formulario_habilitado, $concepto, $encuesta, $tipo_elemento) 
        {
            $where = " AND sfh.habilitacion = $habilitacion ";
            $where .= " AND sfh.formulario_habilitado = $formulario_habilitado ";
            $where .= " AND sfhd.encuesta = $encuesta ";
            $where .= (isset($concepto)) ? " AND sfh.concepto = $concepto " : " AND sfh.concepto IS NULL ";
            $where .= (isset($tipo_elemento)) ? " AND sfhd.tipo_elemento = $tipo_elemento " : " AND sfhd.tipo_elemento IS NULL ";
            
            $sql = "SELECT 
                        sfh.formulario_habilitado ,sfh.habilitacion ,sfh.concepto
                        ,sfhd.formulario_habilitado_detalle ,sfhd.encuesta ,sfhd.elemento ,sfhd.tipo_elemento
                        FROM sge_formulario_habilitado sfh 
                                INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado)
                                LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
                                LEFT JOIN sge_tipo_elemento ste ON (ste.tipo_elemento = sfhd.tipo_elemento)
                        WHERE TRUE $where
                        ORDER BY sfh.formulario_habilitado, sfhd.orden";
            return kolla_db::consultar($sql);
        }

        function upsert_puntaje_aplicacion($formulario_habilitado, $formulario_habilitado_detalle, $puntaje) {
            $fila['formulario_habilitado'] = $formulario_habilitado;
            $fila['formulario_habilitado_detalle'] = $formulario_habilitado_detalle;
            $fila['puntaje'] = $puntaje;
                                   
            $r = $this->controlador()->get_relacion_evaluacion();
            $t = $r->tabla('sge_puntaje_aplicacion');
            $condiciones = $fila;
            unset($condiciones['puntaje']);            
            $id = $t->get_id_fila_condicion($condiciones);
            
            count($id)>0 
                ? $t->set_fila_columna_valor($id[0], "puntaje", $puntaje) 
                : $t->nueva_fila($fila);
        }

        //-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cerrar()
	{
            
            $evaluacion = $this->controlador()->get_evaluacion();
            $puntajes = toba::consulta_php('consultas_evaluacion')->get_puntajes_evaluacion($evaluacion);
            
            //comenzar transacción
            kolla::db()->abrir_transaccion();
            //marcar como implementados los puntajes involucrados
            foreach($puntajes as $p) {
                $this->marcar_implementado($p['puntaje']);
            }
            
            //marcar como cerrada la evaluación
            $r = $this->controlador()->get_relacion_evaluacion();
            $r->tabla('sge_evaluacion')->set_columna_valor('cerrada', 'S', true);
            $r->sincronizar();
            //terminar transaccion
            kolla::db()->cerrar_transaccion();
	}
        
        function marcar_implementado($puntaje) 
        {
            $sql = "UPDATE sge_puntaje 
                        SET implementado = 'S' 
                        WHERE puntaje = $puntaje ;";
            kolla_db::consultar($sql);
        }

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__atributos(toba_ei_pantalla $pantalla)
	{
            $t = $this->controlador()->get_tabla_evaluacion();
            $cargada = $t->esta_cargada();
                    
            //si no hay evaluación (es alta)
            if (!isset($t) || (isset($t) && !$cargada)) {
                $pantalla->eliminar_evento('cerrar');
            }
            else {
                //o si hay evaluación y ya está cerrada 
                $f = $t->get();
                if ($f['cerrada'] == 'S') {
                    $pantalla->eliminar_evento('cerrar');
                }
            } 
	}
        
	function conf__datos(toba_ei_pantalla $pantalla)
	{
            $fila = $this->controlador()->get_tabla_evaluacion()->get();
            if ($fila['cerrada'] == 'S') {
                $this->dep('cuadro_puntajes')->eliminar_evento('aplicar');
                $this->dep('puntaje')->colapsar();
            }                        
	}
        
}
?>