<?php

abstract class cambio
{
    /**
     * @var conversor
     */
    protected $conversor;
	protected $schema;
	protected $cambio;
	protected $path_proyecto;
    protected $logger;
    protected $mostrar_debug = true;

    // Constructores

	function __construct()
	{
        $partes = explode('_', get_class($this));
		$this->cambio = $partes[1];
		$this->path_proyecto = dirname(__FILE__) . '/../../../..';
	}
    
    function set_logger($logger=null)
    {
        if ( isset($logger) ) {
            $this->logger = $logger;
        }
    }
	
	abstract function get_descripcion();
	abstract function cambiar();

	function deshacer() {}
    
    function get_path_proyecto()
    {
        return $this->path_proyecto;
    }
    
    function get_path_inst()
    {
        return $this->conversor->get_path_inst();
    }

    function set_conversor($conversor)
    {
        $this->conversor = $conversor;
    }
    
    function set_schema($schema)
    {
        $this->schema = $schema;
    }
    
    function get_version_desde()
    {
        return $this->conversor->get_version_desde();
    }
    
    function get_version_hasta()
    {
        return $this->conversor->get_version_hasta();
    }
    
    function get_schema()
	{
		return $this->conversor->get_schema();
	}

	function get_id()
	{
		return $this->cambio;
	}
    
    function quote($valor)
    {
        return $this->conversor->quote($valor);
    }
    
    function mostrar_debug()
    {
        return $this->mostrar_debug;
    }
    
    function consultar($sql)
	{
		return $this->conversor->consultar($sql);
	}
    
    function consultar_fila($sql)
	{
		return $this->conversor->consultar_fila($sql);
	}

	function ejecutar($sql)
	{
        $this->conversor->ejecutar($sql);
	}
	
	function ejecutar_archivo($archivo)
	{
        $this->conversor->ejecutar_archivo($archivo);
	}

	function existe_tabla($esquema, $tabla)
	{
		return $this->conversor->existe_tabla($esquema, $tabla);
	}

    public function existe_columna($esquema, $tabla, $columna)
    {
        return $this->conversor->existe_columna($esquema, $tabla, $columna);
    }

	/**
	 * Obtiene todos los archivos sql de un directorio. Util para ejecutar cosas
	 * del ddl, o cambios administrados completamente por archivos sql de una
	 * carpeta.
	 * @param type $dir
	 * @return array
	 */
	function get_sqls_de_directorio($dir)
	{
		$files = glob($dir. '/*.sql');
		return $files;
	}
}