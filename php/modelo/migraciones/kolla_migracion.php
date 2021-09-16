<?php

class kolla_migracion 
{

    /**
     * @var toba_db_postgres7
     */
    protected $db;
    protected $interface;

    function __construct($db, $interface)
    {
        $this->db = $db;
        $this->db->set_encoding('LATIN1');
        $this->interface = $interface;
        $this->ini();
    }
    
    /**
     * @return toba_db_postgres7
     */
    function get_db()
    {
        return $this->db;
    }
    
    /**
     * Ventana para poder hacer cosas antes que cualquier cambio
     */
    function ini()
    {   
    }

    function set_interface($interface)
    {
        $this->interface = $interface;
    }

    function es_consola()
    {
        return (php_sapi_name() === 'cli');
    }
    
    function get_dir_ddl() {
        
        if ($this->es_consola()) {
            $dir = toba::proyecto()->get_path().'/sql/ddl/';
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion().'/sql/ddl/';
        }
        return $dir;
    }
    
    function get_dir_juegos_de_datos() {
        
        if ($this->es_consola()) {
            $dir = toba::proyecto()->get_path().'/sql/datos/juegos_de_datos';
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion().'/sql/datos/juegos_de_datos';
        }
        return $dir;
    }
    
    function get_dir_datos_base() {
        
        if ($this->es_consola()) {
            $dir = toba::proyecto()->get_path().'/sql/datos/base';
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion().'/sql/datos/base';
        }
        return $dir;
    }
    
    function get_dir_datos_actualizaciones() {
        
        if ($this->es_consola()) {
            $dir = toba::proyecto()->get_path().'/sql/datos/actualizaciones';
        } else {
            $dir = inst::configuracion()->get_dir_inst_aplicacion().'/sql/datos/actualizaciones';
        }
        return $dir;
    }
    
    function ejecutar_metodos_negocio()
    {
        foreach (get_class_methods($this) as $metodo) {
            $es_metodo_negocio = ($metodo == 'negocio__' || strpos($metodo, 'negocio__') === 0);

            if ($es_metodo_negocio) {
                $this->interface->mensaje("Se ejecuta: ".$metodo);
                $this->{$metodo}();
            }
        }
    }
}