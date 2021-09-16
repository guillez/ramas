<?php

class kolla_migracion_4_2_0 extends kolla_migracion
{
    function negocio__18316()
    {
        $sql = "INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES (21, 'etiqueta_titulo', 'Etiqueta Título', 'E');
                
                ALTER TABLE sge_componente_pregunta ALTER COLUMN componente TYPE varchar(35);
                
                INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES (22, 'etiqueta_texto_enriquecido', 'Etiqueta Texto Enriquecido ', 'E');

                UPDATE sge_componente_pregunta
                SET descripcion = 'Etiqueta Sub-Título'
                WHERE numero = 7;

                ALTER TABLE sge_pregunta ALTER COLUMN nombre TYPE character varying(4096);
                ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__18193()
    {
        $sql = "ALTER TABLE sge_habilitacion 
                ADD COLUMN destacada character(1) NOT NULL DEFAULT 'N';
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__18192()
    {
        $sql = "ALTER TABLE sge_habilitacion 
                ADD COLUMN archivada character(1) NOT NULL DEFAULT 'N';
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__17315()
    {
        $sql = "INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES (20, 'texto_numerotelefono', 'Número de teléfono con código internacional y regional', 'A');
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__17977()
    {
        //Agregar nuevo atributo a la habilitación
        $sqls = array();
        $sqls[] = " ALTER TABLE sge_habilitacion
                    ADD COLUMN publica character(1) NOT NULL DEFAULT 'N';
                    ";
        $sqls[] = " ALTER TABLE sge_grupo_definicion
                    DROP CONSTRAINT ck_sge_grupo_definicion_estado,
                    ADD  CONSTRAINT ck_sge_grupo_definicion_estado 
                    CHECK (estado = ANY (ARRAY['A'::bpchar, 'B'::bpchar, 'O'::bpchar]));
                  ";
        $this->get_db()->ejecutar($sqls);

        //Crear usuario invitado y grupo para usar en habilitaciones públicas
        $this->crear_usuario_invitado();
        $this->crear_encuestado_invitado();
        $this->crear_grupo_invitado();
    }

    function negocio__17968()
    {
        $sql = "ALTER TABLE sge_habilitacion 
                ADD COLUMN mostrar_progreso character(1) NOT NULL DEFAULT 'N';
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__17982()
    {
        $sql = "UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 ANA SILVEIRA DE DI FRANCESCO' WHERE cue=60545900;
                    UPDATE mdi_primario SET nombre='ESCUELA DE ADULTOS Nº703 FRAGATA PRESIDENTE SARMIENTO' WHERE cue=61059500;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 MERCEDES DE SAN MARTIN' WHERE cue=60333600;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº505 GENERAL MANUEL BELGRANO' WHERE cue=61306600;
                    UPDATE mdi_primario SET nombre='ESCUELA DE EDUCACIÓN PRIMARIA Nº 25 BARTOLOME MITRE' WHERE cue=60825200;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº503 PAUL HARRIS' WHERE cue=60238000;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 JORGE LUIS BORDON' WHERE cue=61100400;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 5 DE JULIO DE 1910' WHERE cue=61254000;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 GRAL. MARIANO NECOCHEA' WHERE cue=60326800;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 PINCELES DE TERNURA' WHERE cue=60711400;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 IGNACIO CANAL' WHERE cue=60630800;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº503 CAROLINA TOBAR GARCIA' WHERE cue=60849100;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº503 PARA SORDOS HIPOACUSICOS' WHERE cue=60752600;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº507 DR. ARMANDO G. COTONE' WHERE cue=61053800;
                    UPDATE mdi_primario SET nombre='ESCUELA DE ADULTOS Nº704 EVA DUARTE DE PERON' WHERE cue=61409200;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 CEFERINO NAMUNCURA' WHERE cue=60457800;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 PADRE CARLOS CAJADE' WHERE cue=60883000;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 HENA YANZON' WHERE cue=60595300;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº514 MADRE TERESA DE CALCUTA' WHERE cue=61304400;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 DR. OSCAR SANCHEZ SAMBUCETTI' WHERE cue=60414500;
                    UPDATE mdi_primario SET nombre='ESCUELA PRIMARIA Nº20 MARTIN MIGUEL DE GÜEMES' WHERE cue=60197600;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 RENE FAVALORO' WHERE cue=61177500;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº503 HELEN KELLER' WHERE cue=61542600;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº503 MIRTA AMALIA SOSA' WHERE cue=60341400;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº515 LIC.ELINA TEJERINA DE WALSH' WHERE cue=60290700;
                    UPDATE mdi_primario SET nombre='ESCUELA PRIMARIA Nº26 EL SANTO DE LA ESPADA' WHERE cue=60119200;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº527 BARBARA GONZALEZ DE SOTO' WHERE cue=60883700;
                    UPDATE mdi_primario SET nombre='CENTRO DE ADULTOS Nº742/12 PEDRO BENOIT' WHERE cue=60897903;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº510 DOCTOR SIXTO LASPIUR' WHERE cue=60788300;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 WOLT SCHCOLNIK' WHERE cue=60972500;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 DR. EDMUNDO DANTE LUCIANI' WHERE cue=60935700;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº506 GRAL. DON JOSÉ DE SAN MARTÍN' WHERE cue=60743000;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº510 AMERICA LATINA' WHERE cue=60716100;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº502 PARA SORDOS E HIPOACUSICOS' WHERE cue=60995600;
                    UPDATE mdi_primario SET nombre='ESCUELA DE ADULTOS Nº707 EVARISTO IGLESIAS' WHERE cue=61365300;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 DR. RICARDO GUTIERREZ' WHERE cue=60224100;
                    UPDATE mdi_primario SET nombre='CENTRO DE ADULTOS Nº705/12 CORONEL MANUEL DORREGO' WHERE cue=60897901;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 DRA. MARIA MONTESSORI' WHERE cue=60419500;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 ANGEL I. MURGA' WHERE cue=60587200;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 HELEN KELLER' WHERE cue=60351900;
                    UPDATE mdi_primario SET nombre='ESCUELA ESPECIAL Nº501 ADOLFO G. CHAVES' WHERE cue=60660900;";
        $this->get_db()->ejecutar($sql);

        $sql = "UPDATE mdi_secundario SET nombre='ESCUELA DE EDUCACIÓN TÉCNICA Nº2 RODOLFO WALSH' WHERE cue=60843800;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 ANA SILVEIRA DE DI FRANCESCO' WHERE cue=60545900;
                UPDATE mdi_secundario SET nombre='ESCUELA DE ADULTOS Nº703 FRAGATA PRESIDENTE SARMIENTO' WHERE cue=61059500;
                UPDATE mdi_secundario SET nombre='ESCUELA DE EDUCACIÓN SECUNDARIA Nº4 REVOLUCIÓN FRANCESA' WHERE cue=60810500;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 MERCEDES DE SAN MARTIN' WHERE cue=60333600;
                UPDATE mdi_secundario SET nombre='CENTRO EDUCATIVO NIVEL SECUNDARIO Nº453 RAFAEL OBLIGADO' WHERE cue=61709400;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº515 LIC.ELINA TEJERINA DE WALSH' WHERE cue=60290700;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº503 PAUL HARRIS' WHERE cue=60238000;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº527 BARBARA GONZALEZ DE SOTO' WHERE cue=60883700;
                UPDATE mdi_secundario SET nombre='CENTRO DE ADULTOS Nº742/12 PEDRO BENOIT' WHERE cue=60897903;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 JORGE LUIS BORDON' WHERE cue=61100400;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 5 DE JULIO DE 1910' WHERE cue=61254000;
                UPDATE mdi_secundario SET nombre='LICEO MILITAR GENERAL SAN MARTÍN' WHERE cue=69000600;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 GRAL. MARIANO NECOCHEA' WHERE cue=60326800;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 PINCELES DE TERNURA' WHERE cue=60711400;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 WOLT SCHCOLNIK' WHERE cue=60972500;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 DR. EDMUNDO DANTE LUCIANI' WHERE cue=60935700;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 IGNACIO CANA' WHERE cue=60630800;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº503 PARA SORDOS HIPOACUSICOS' WHERE cue=60752600;
                UPDATE mdi_secundario SET nombre='CENTRO EDUCATIVO NIVEL SECUNDARIO Nº453 ERNESTO CHE GUEVARA' WHERE cue=61430800;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 PARA SORDOS E HIPOACUSICOS' WHERE cue=60995600;
                UPDATE mdi_secundario SET nombre='ESCUELA DE ADULTOS Nº707 EVARISTO IGLESIAS' WHERE cue=61365300;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 DR. RICARDO GUTIERREZ' WHERE cue=60224100;
                UPDATE mdi_secundario SET nombre='CENTRO DE ADULTOS Nº705/12 CORONEL MANUEL DORREGO' WHERE cue=60897901;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 CEFERINO NAMUNCURA' WHERE cue=60457800;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 PADRE CARLOS CAJADE' WHERE cue=60883000;
                UPDATE mdi_secundario SET nombre='ESCUELA SECUNDARIA Nº6 HIPOLITO YRIGOYEN' WHERE cue=60567000;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº514 MADRE TERESA DE CALCUTA' WHERE cue=61304400;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 DRA. MARIA MONTESSORI' WHERE cue=60419500;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 ANGEL I. MURGA' WHERE cue=60587200;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 DR. OSCAR SANCHEZ SAMBUCETTI' WHERE cue=60414500;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº502 RENE FAVALORO' WHERE cue=61177500;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº503 HELEN KELLER' WHERE cue=61542600;
                UPDATE mdi_secundario SET nombre='ESCUELA DE EDUCACIÓN SECUNDARIA Nº43 INDEPENDENCIA' WHERE cue=61004200;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 HELEN KELLER' WHERE cue=60351900;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº501 ADOLFO G. CHAVES' WHERE cue=60660900;
                UPDATE mdi_secundario SET nombre='ESCUELA ESPECIAL Nº503 MIRTA AMALIA SOSA' WHERE cue=60341400;";
        $this->get_db()->ejecutar($sql);

    }

    private function crear_usuario_invitado ()
    {
        $schema_toba = toba::instancia()->get_db()->get_schema();

        $tabla = $schema_toba.'.apex_usuario';
        $clave = $this->get_db()->quote(toba_usuario::generar_clave_aleatoria(8));
        $usuario = "INSERT INTO $tabla (usuario, clave, nombre, autentificacion) 
                    VALUES ('invitado_kolla', $clave, 'Usuario Anónimo predeterminado SIU-Kolla', 'bcrypt')";
        $this->get_db()->ejecutar($usuario);

        $tabla = $schema_toba.'.apex_usuario_proyecto';
        $proyecto = $this->get_db()->quote(toba_proyecto::get_id());

        $usuario_proyecto = "INSERT INTO $tabla (proyecto, usuario_grupo_acc, usuario) 
                             VALUES ($proyecto, 'guest', 'invitado_kolla')";
        $this->get_db()->ejecutar($usuario_proyecto);
    }

    private function crear_encuestado_invitado()
    {
        $encuestado = "INSERT INTO sge_encuestado (usuario, guest) 
                        VALUES ('invitado_kolla', 'S')";
        $this->get_db()->ejecutar($encuestado);
    }

    private function crear_grupo_invitado()
    {
        $sql = "SELECT unidad_gestion, nombre
                FROM sge_unidad_gestion;
                ";
        $ugs = $this->get_db()->consultar($sql);

        $sql = "SELECT encuestado
                FROM sge_encuestado
                WHERE usuario = 'invitado_kolla';
                ";
        $e = $this->get_db()->consultar_fila($sql);
        $e = $e['encuestado'];

        foreach ($ugs as $ug) {
            $grupo= "INSERT INTO sge_grupo_definicion (nombre, estado, descripcion, unidad_gestion) 
                     VALUES ('Grupo invitado - ".$ug['nombre']."', 'O', 'Grupo predefinido para usuario invitado', '".$ug['unidad_gestion']."') 
                     RETURNING grupo";
            $res = $this->get_db()->consultar($grupo);
            $g = $res[0]['grupo'];

            $ge = "INSERT INTO sge_grupo_detalle (grupo, encuestado)
                   VALUES ($g, $e)";
            $this->get_db()->ejecutar($ge);
        }
    }

    function get_secuencia_pregunta_dependencia_destino()
    {
        $sql = "SELECT GREATEST(1000, max(pregunta_dependencia) + 1) as seq FROM sge_pregunta_dependencia";
        $res = $this->get_db()->consultar_fila($sql);
        return $res['seq'];
    }

    function get_secuencia_dependencia_definicion_destino()
    {
        $sql = "SELECT GREATEST(1000, max(dependencia_definicion) + 1) as seq FROM sge_pregunta_dependencia_definicion";
        $res = $this->get_db()->consultar_fila($sql);
        return $res['seq'];
    }

    function mover_preguntas_dependientes_existentes()
    {
        // 1-- Primero muevo las preguntas dependientes
        $id_pregunta_dependencia_rango_inicio = 36;
        $id_dependencia_definicion_rango_inicio = 85;

        $id_pregunta_dependencia_destino = $this->get_secuencia_pregunta_dependencia_destino();
        $id_dependencia_definicion_destino = $this->get_secuencia_dependencia_definicion_destino();

        $sql = "
                SET CONSTRAINTS ALL DEFERRED;
                
                -- actualizo la tabla con los corrimientos
                UPDATE sge_pregunta_dependencia
                SET pregunta_dependencia = $id_pregunta_dependencia_destino + pregunta_dependencia - $id_pregunta_dependencia_rango_inicio
                WHERE ((pregunta_dependencia >= $id_pregunta_dependencia_rango_inicio) AND (pregunta_dependencia <= 999));

                --los mismos valores deben corregirse en la tabla que los referencia
                UPDATE sge_pregunta_dependencia_definicion
                SET pregunta_dependencia = $id_pregunta_dependencia_destino + pregunta_dependencia - $id_pregunta_dependencia_rango_inicio
                WHERE ((pregunta_dependencia >= $id_pregunta_dependencia_rango_inicio) AND (pregunta_dependencia <= 999));
                
                -- la clave dependencia_definicion también requiere un corrimiento
                UPDATE sge_pregunta_dependencia_definicion
                SET dependencia_definicion = $id_dependencia_definicion_destino + dependencia_definicion - $id_dependencia_definicion_rango_inicio
                WHERE ((dependencia_definicion >= $id_dependencia_definicion_rango_inicio) 
                AND (dependencia_definicion <= 999));
                
                SET CONSTRAINTS ALL IMMEDIATE;
                ";

        $this->get_db()->ejecutar($sql);

        // 2-- Luego establezco el valor a las secuencias
        $sql = "SELECT setval('sge_pregunta_dependencia_seq', 
                (SELECT GREATEST(1000, 
                (SELECT max(pregunta_dependencia) + 1 FROM sge_pregunta_dependencia)
                ) as seq))";
        $res = $this->get_db()->consultar_fila($sql);

        $sql = "SELECT setval('sge_pregunta_dependencia_definicion_seq', 
                (SELECT GREATEST(1000, 
                (SELECT max(dependencia_definicion) + 1 FROM sge_pregunta_dependencia_definicion)
                ) as seq))";
        $res = $this->get_db()->consultar_fila($sql);
    }
    
    function negocio__18164()
    {
        // 1-- Muevo las preguntas dependintes existentes
        $this->mover_preguntas_dependientes_existentes();

        // 2-- Ahora puedo agregar con seguridad la nueva encuesta y sus preguntas dependientes
        $dir = $this->get_dir_juegos_de_datos();
        
        $archivos = array(
            $dir.'/relevamiento_convocatorias/10_datos_adicionales.sql'
        );
      
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }
    }
    
    function negocio__18323()
    {
        $sql = "ALTER TABLE sge_habilitacion 
                ADD COLUMN  descarga_pdf Char(1) NOT NULL DEFAULT 'S';
               ";
        $this->get_db()->ejecutar($sql);
    }

    function negocio__123()
    {
        $dir = $this->get_dir_ddl();

        $archivos = array(
            $dir.'/80_Procesos/200_respuestas_formulario_externo.sql'
        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }
    }
}
