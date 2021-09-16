<?php

class kolla_migracion_4_5_0 extends kolla_migracion
{
    function negocio__33951()
    {
        $dir_ddl = $this->get_dir_ddl();
        $dir_juegos_datos = $this->get_dir_juegos_de_datos();
        
        $archivos = array(
            $dir_ddl.'20_Tablas/mdp_identidad_genero.sql',
            $dir_ddl.'70_Permisos/grant_mdp_identidad_genero.sql',
            $dir_juegos_datos.'/mdp/mdp_identidad_genero_datos.sql',
            $dir_ddl.'80_Procesos/190_copiar_encuesta_a_unidad_gestion.sql'
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
        
        $sql = "INSERT INTO sge_tabla_externa(unidad_gestion, tabla_externa_nombre)
                VALUES      ('0', 'mdp_identidad_genero');
                ";
        
        $this->get_db()->ejecutar($sql);
        
        $sql = "INSERT INTO sge_pregunta(   pregunta, nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, 
                                            tabla_asociada_descripcion, tabla_asociada_orden_campo, tabla_asociada_orden_tipo, 
                                            unidad_gestion, descripcion_resumida, ayuda, oculta, visualizacion_horizontal)
                VALUES      (712, '¿Cuál de las siguientes opciones considera que le describe mejor?', 3, 'mdp_identidad_genero',
                            'identidad_genero', 'nombre', 'codigo', 'ASC ', '0', 'Identidad de género', '', 'N', 'N');
                ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__33952()
    {
        /*
         * Baja lógica de la versión antigua de la encuesta. Lo que equivale
         * a no ofrecer mas a la encuesta para la creación de un formulario.
         */
        $sql = "UPDATE  sge_encuesta_atributo
                SET     estado = 'B'
                WHERE   encuesta = 8;
                ";
        
        $this->get_db()->ejecutar($sql);
        
        /*
         * Creación de la nueva versión de la encuesta en base a las preguntas
         * y respuestas preexistentes.
         */
        
        $dir = $this->get_dir_datos_actualizaciones().'/4_5_0';
        
        $archivo = $dir.'/40_formulario_preinscripcion_act.sql';

        $this->get_db()->ejecutar_archivo($archivo);
    }

    function negocio__34042()
    {
        $archivo = $this->get_dir_ddl().'/90_Vistas/10_resumen_estado_habilitacion.sql';
        $this->get_db()->ejecutar_archivo($archivo);
    }

}
