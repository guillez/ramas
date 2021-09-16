<?php

class importador_institucion_ws extends importador_ws
{
    protected $instituciones = array();
    protected $carreras      = array();
    protected $titulos       = array();
    protected $ras           = array();
            
    function importar()
    {
        $this->_conectar();
        $this->_importar_datos_institucion();
    }
    
    private function _importar_datos_institucion()
    {
        $this->_importar_titulo();
        $this->_importar_carrera();
        $this->_importar_institucion();
        $this->_importar_ra();
        $this->_importar_carrera_titulo();
        $this->_importar_ra_carrera();
        $this->_importar_ra_titulo();
    }
    
    private function _get_datos($metodo)
    {
        if ($this->es_soap()) {
            $metodo = 'ik_'.$metodo;
        } else {
            $metodo = 'get_'.$metodo;
        }
        
        if ($this->es_soap()) {
            $datos = $this->get_cliente()->call($metodo, array());
            $this->_validar_response($datos);
        } else {
            $datos = $this->get_cliente()->$metodo($this->get_ug());
        }
        
        return empty($datos) ? array() : $datos;
    }


    private function _importar_titulo()
    {
        $titulos = $this->_get_datos('titulo');
        $titulos_kolla = kolla::co('consultas_mgi')->get_titulos($this->_get_where());
        $this->titulos = rs_convertir_asociativo($titulos_kolla, array('codigo'), 'titulo');
        
        foreach ($titulos as $titulo) {
            $titulo = $this->_formatear_titulo($titulo);
            $this->_validar_codigo_titulo_araucano($titulo['titulo_araucano']);
            if ($titulo && !array_key_exists($titulo['codigo'], $this->titulos)) {
            	
            	//El título no existe en Kolla
                $sql  = sql_array_a_insert('mgi_titulo', $titulo);
                $sql  = substr($sql, 0, -1);
                $sql .= ' RETURNING titulo';
                $res  = kolla_db::consultar_fila($sql);
                $this->titulos[$titulo['codigo']] = $res['titulo'];
            } else {
            	
            	//El título ya existe y se deben actualizar sus datos
            	$sql = sql_array_a_update('mgi_titulo', $titulo, array('codigo' => $titulo['codigo']));
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_titulo($titulo)
    {
        if (!$this->es_soap()) {
            $titulo = array_values($titulo);
        }
        
        $titulo = array(
            'nombre'            => $titulo[1],
            'nombre_femenino'   => $titulo[2],
            'codigo'            => trim($titulo[3]),
            'titulo_araucano'   => trim($titulo[4]) == '' ? null : $titulo[4],
            'estado'            => $titulo[5]
       );
        
        if (!$this->es_soap()) {
            $titulo['unidad_gestion'] = $this->get_ug();
        }
        
        if ($titulo['codigo'] == '') {
            return false;
        }
        
        return $titulo;
    }
    
    private function _importar_carrera()
    {
        $carreras   = $this->_get_datos('carrera');
        $propuestas = kolla::co('consultas_mgi')->get_propuestas($this->_get_where());
        $this->carreras = rs_convertir_asociativo($propuestas, array('codigo'), 'propuesta');
        
        foreach ($carreras as $carrera) {
            $carrera = $this->_formatear_carrera($carrera);
            if ($carrera && !array_key_exists($carrera['codigo'], $this->carreras)) {
            	
            	//La carrera no existe en Kolla
                $sql  = sql_array_a_insert('mgi_propuesta', $carrera);
                $sql  = substr($sql, 0, -1);
                $sql .= ' RETURNING propuesta';
                $res  = kolla_db::consultar_fila($sql);
                $this->carreras[$carrera['codigo']] = $res['propuesta'];
            } else {
            	
            	//La carrera ya existe y se deben actualizar sus datos
            	$sql = sql_array_a_update('mgi_propuesta', $carrera, array('codigo' => $carrera['codigo']));
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_carrera($carrera)
    {
        if (!$this->es_soap()) {
            $carrera = array_values($carrera);
        }

        $carrera = array(
            'nombre'	=> $carrera[1],
            'codigo'    => $carrera[2],
            'estado'    => $carrera[3]
       		);
        
        if (!$this->es_soap()) {
            $carrera['unidad_gestion'] = $this->get_ug();
        }
        
        if ($carrera['codigo'] == '') {
            return false;
        }
        
        return $carrera;
    }
    
    private function _importar_institucion()
    {
        $insts = $this->_get_datos('institucion');
        $insts_kolla = kolla::co('consultas_mgi')->get_institucion();
        $this->instituciones = rs_convertir_asociativo($insts_kolla, array('nombre_abreviado'), 'institucion');

        foreach ($insts as $inst) {
            $inst = $this->_formatear_institucion($inst);           
            //se valida el código de araucano
            $this->_validar_codigo_institucion_araucano($inst['institucion_araucano']);
            //si es válido se determina si existe o se la debe agregar a kolla
            $existe_inst = abm::existen_registros('mgi_institucion', array('institucion_araucano' => $inst['institucion_araucano']));
            $existe_ra = abm::existen_registros('mgi_responsable_academica', array('ra_araucano' => $inst['institucion_araucano']));
            
            if ($existe_inst) {
                //La institución ya existe y se deben actualizar sus datos
            	$sql = sql_array_a_update('mgi_institucion', $inst, array('nombre_abreviado' => $inst['nombre_abreviado']));
                kolla_db::ejecutar($sql);
            } else {
                if ($existe_ra) {
                    //existe como responsable academica
                    //¿hay algo para actualizar?
                } else {                
                    //es una responsable academica, dar de alta la ra y averiguar los datos de la institucion correspondiente
                    $datos_inst_ra = kolla::co('consultas_mgi')->get_instituciones_araucano_ra($inst['institucion_araucano']);
                    //SE "FABRICA" UN NOMBRE ABREVIADO PARA LA INSTITUCION PORQUE NO SE DE DONDE SACARLO
                    $inst = $this->_formatear_institucion(array('', $datos_inst_ra[0]['nombre'], substr($datos_inst_ra[0]['nombre'], 0, 49) , $datos_inst_ra[0]['institucion_araucano']));
                    $ra = $this->_formatear_ra(array('', $datos_inst_ra[0]['nombre_ra'], $datos_inst_ra[0]['ra_araucano'], '', $datos_inst_ra[0]['institucion_araucano_ra']));
                    $ra['ra_araucano'] = $datos_inst_ra[0]['ra_araucano'];

                    //La institución no existe en Kolla
                    $sql_i  = sql_array_a_insert('mgi_institucion', $inst);
                    $sql_i  = substr($sql_i, 0, -1);
                    $sql_i .= ' RETURNING institucion';
                    $res  = kolla_db::consultar_fila($sql_i);
                    $this->instituciones[$inst['nombre_abreviado']] = $res['institucion'];

                    $sql_r  = sql_array_a_insert('mgi_responsable_academica', $ra);
                    $sql_r  = substr($sql_r, 0, -1);
                    $sql_r .= ' RETURNING responsable_academica';
                    $res  = kolla_db::consultar_fila($sql_r);
                }
            }

        }
    }
    
    private function _formatear_institucion($institucion)
    {
        if (!$this->es_soap()) {
            $institucion = array_values($institucion);
        }

        $institucion = array(
            'nombre'                => $institucion[1],
            'nombre_abreviado'      => $institucion[2],
            'institucion_araucano'  => trim($institucion[3]) == '' ? null : $institucion[3],
            'tipo_institucion'      => 1
       		);
        
        if ($institucion['nombre_abreviado'] == '') {
            return false;
        }
        
        return $institucion;
    }
    
    private function _importar_ra()
    {
        $ras = $this->_get_datos('respacad');
        $ras_kolla = kolla::co('consultas_mgi')->get_responsables_academicas($this->_get_where());
        $this->ras = rs_convertir_asociativo($ras_kolla, array('codigo'), 'responsable_academica');
        
        foreach ($ras as $ra) {
            $ra = $this->_formatear_ra($ra);
            if ($ra && !array_key_exists($ra['codigo'], $this->ras)) {
            	
            	//La ra no existe en Kolla
                $sql  = sql_array_a_insert('mgi_responsable_academica', $ra);
                $sql  = substr($sql, 0, -1);
                $sql .= ' RETURNING responsable_academica';
                $res  = kolla_db::consultar_fila($sql);
                $this->ras[$ra['codigo']] = $res['responsable_academica'];
            } else {
            	
            	//La ra ya existe y se deben actualizar sus datos
            	$sql = sql_array_a_update('mgi_responsable_academica', $ra, array('codigo' => $ra['codigo']));
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_ra($ra)
    {
        if (!$this->es_soap()) {
            $ra = array_values($ra);
        }

        $ra = array(
            'nombre'                        => $ra[1],
            'codigo'                        => $ra[2],
            'tipo_responsable_academica'    => trim($ra[3]) == '' ? 1 : $ra[3], //DEBO ASUMIR EL TIPO DE RA           
            'institucion'                   => $ra[4],
            'localidad'                     => trim($ra[5]) == '' ? null : $ra[5],
            'calle'                         => $ra[6],
            'numero'                        => $ra[7],
            'codigo_postal'                 => $ra[8],
            'telefono'                      => $ra[9],
            'fax'                           => $ra[10],
            'email'                         => $ra[11]
       		);
        
        if (!$this->es_soap()) {
            $ra['unidad_gestion'] = $this->get_ug();
        }
        
        if ($ra['codigo'] != '' && !empty($this->instituciones)) {
        	$ra['institucion'] = current($this->instituciones);
            return $ra;
        }
        
        return false;
    }
    
    private function _importar_carrera_titulo()
    {
        $car_tits = $this->_get_datos('carrera_titulo');
        $car_tits_kolla = kolla::co('consultas_mgi')->get_titulos_propuestas($this->_get_where('t'));
        $car_tits_kolla = rs_convertir_asociativo($car_tits_kolla, array('propuesta', 'titulo'), 'propuesta');
        
        foreach ($car_tits as $car_tit) {
            $car_tit = $this->_formatear_carrera_titulo($car_tit);
            if ($car_tit && !array_key_exists($car_tit['propuesta'].'||'.$car_tit['titulo'], $car_tits_kolla)) {
            	
            	//La carrera - título no existe en Kolla
                $sql = sql_array_a_insert('mgi_titulo_propuesta', $car_tit);
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_carrera_titulo($car_tit)
    {
        if (!$this->es_soap()) {
            $car_tit = array_values($car_tit);
        }
        
        $datos = array(
            'propuesta' => $car_tit[2],
            'titulo'    => $car_tit[3]
       		);
        
        if (array_key_exists($datos['propuesta'], $this->carreras) && array_key_exists($datos['titulo'], $this->titulos)) {
            return array(
                'propuesta' => $this->carreras[$datos['propuesta']],
                'titulo'    => $this->titulos[$datos['titulo']]
           		);
        }
        
        return false;
    }
    
    private function _importar_ra_carrera()
    {
        $ra_carreras = $this->_get_datos('respacad_carrera');
        $ra_carreras_kolla = kolla::co('consultas_mgi')->get_ra_carreras($this->_get_where('mgi_propuesta'));
        $ra_carreras_kolla = rs_convertir_asociativo($ra_carreras_kolla, array('responsable_academica', 'propuesta'), 'propuesta');
        
        foreach ($ra_carreras as $ra_carrera) {
            $ra_carrera = $this->_formatear_ra_carrera($ra_carrera);
            if ($ra_carrera && !array_key_exists($ra_carrera['responsable_academica'].'||'.$ra_carrera['propuesta'], $ra_carreras_kolla)) {
            	
            	//La ra - carrera no existe en Kolla
                $sql = sql_array_a_insert('mgi_propuesta_ra', $ra_carrera);
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_ra_carrera($ra_carrera)
    {
        if (!$this->es_soap()) {
            $ra_carrera = array_values($ra_carrera);
        }
        
        $datos = array(
            'responsable_academica' => $ra_carrera[1],
            'propuesta'             => $ra_carrera[2]
       		);
        
        if (array_key_exists($datos['responsable_academica'], $this->ras) && array_key_exists($datos['propuesta'], $this->carreras)) {
            return array(
                'responsable_academica' => $this->ras[$datos['responsable_academica']],
                'propuesta'             => $this->carreras[$datos['propuesta']]
           		);
        }
        
        return false;
    }
    
    private function _importar_ra_titulo()
    {
        $ra_titulos = $this->_get_datos('respacad_titulo');
        $ra_titulos_kolla = kolla::co('consultas_mgi')->get_ra_titulos($this->_get_where('mgi_titulo'));
        $ra_titulos_kolla = rs_convertir_asociativo($ra_titulos_kolla, array('responsable_academica', 'titulo'), 'titulo');
        
        foreach ($ra_titulos as $ra_titulo) {
            $ra_titulo = $this->_formatear_ra_titulo($ra_titulo);
            if ($ra_titulo && !array_key_exists($ra_titulo['responsable_academica'].'||'.$ra_titulo['titulo'], $ra_titulos_kolla)) {
            	
            	//La ra - titulo no existe en Kolla
                $sql = sql_array_a_insert('mgi_titulo_ra', $ra_titulo);
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _formatear_ra_titulo($ra_titulo)
    {
        if (!$this->es_soap()) {
            $ra_titulo = array_values($ra_titulo);
        }
        
        $datos = array(
            'responsable_academica' => $ra_titulo[1],
            'titulo'                => $ra_titulo[2]
       		);
        
        if (array_key_exists($datos['responsable_academica'], $this->ras) && array_key_exists($datos['titulo'], $this->titulos)) {
            return array(
                'responsable_academica' => $this->ras[$datos['responsable_academica']],
                'titulo'                => $this->titulos[$datos['titulo']]
           		);
        }
        
        return false;
    }
    
    private function _validar_codigo_titulo_araucano($titulo)
    {
        $existe = abm::existen_registros('arau_titulos', array('titulo_araucano' => $titulo));
        
        if (!$existe) {
            throw new toba_error("El código Araucano <strong>$titulo</strong> del título que se quiere importar no existe en la tabla <strong>arau_titulos</strong> de SIU-Kolla. Para continuar debe actualizar la tabla de códigos. Contáctese con el administrador del sistema.");
        }
    }

    private function _validar_codigo_institucion_araucano($codigo)
    {
        /*
         * Momentáneamente se controla que el código araucano de la institución exista
         * dentro de las instituciones araucano, o bien dentro de las responsables académicas
         */
        
        $existe_en_inst = abm::existen_registros('arau_instituciones', array('institucion_araucano' => $codigo));
        $existe_en_ras  = abm::existen_registros('arau_responsables_academicas', array('ra_araucano' => $codigo));
        
        if (!$existe_en_inst && !$existe_en_ras) {
            throw new toba_error("El código Araucano <strong>$codigo</strong> de la institución que se quiere importar no existe en la tabla <strong>arau_instituciones</strong> ni en <strong>arau_responsables_academicas</strong> de SIU-Kolla. Para continuar debe actualizar las tablas de códigos. Contáctese con el administrador del sistema.");
        }
    }
    
    private function _get_where($tabla='')
    {
        $tabla = empty($tabla) ? '' : $tabla.'.';
        
        if ($this->es_soap()) {
            //No requiere que se filtren los datos porque se importan todos
        	return null;
        }
        
        return $tabla.'unidad_gestion = '.kolla_db::quote($this->get_ug());
    }
    
}