<?php
/**
 * Clase singleton para gestionar las etiquetas.
 * La responsabilidad de esta clase es:
 * - Consultar si se trata de un componente etiqueta.
 * - Llamar al método que obtiene el correspondiente HTML mediante el builder.
*/

abstract class TipoEtiqueta
{
    const LABEL = "label"; // Por cuestiones de "legacy compat"...
    const TITULO = "etiqueta_titulo";
    const SUBTITULO = "label"; //"etiqueta_subtitulo";
    const TEXTO_ENRIQUECIDO = "etiqueta_texto_enriquecido";
}

class repositorio_etiquetas
{
    /**
     * Call this method to get singleton
     */
    public static function instance()
    {
        static $instance = false;
        
        if ($instance === false) {
            // Late static binding (PHP 5.3+)
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct() {}

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

    public function es_etiqueta($tipo)
    {
        if (($tipo == TipoEtiqueta::TITULO) || ($tipo == TipoEtiqueta::SUBTITULO) || ($tipo == TipoEtiqueta::TEXTO_ENRIQUECIDO) || ($tipo == TipoEtiqueta::LABEL)) {
            return true;
        }

        return false;
    }

    public function crear_salida(vista_builder $builder, $tipo, $id, $texto)
    {
        // Se llama al método del builder que corresponda.
        if ( $tipo == TipoEtiqueta::TITULO ) {
            $builder->crear_componente_titulo($id, $texto);
        } elseif ( $tipo == TipoEtiqueta::SUBTITULO ) {
            $builder->crear_componente_subtitulo($id, $texto);
        } elseif ( $tipo == TipoEtiqueta::TEXTO_ENRIQUECIDO ) {
            $builder->crear_componente_texto_enriquecido($id, $texto);
        } elseif ( $tipo == TipoEtiqueta::LABEL) {
            $builder->crear_componente_label($id, $texto);
        }
    }
}