<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_importar_institucion_ws extends bootstrap_ci
{
    protected $s__conexion;
    
    function conf__form_conexion(toba_ei_formulario $form)
    {
        if ( isset($this->s__conexion) ) {
            $form->set_datos($this->s__conexion);
        }
    }
    
    function evt__form_conexion__modificacion($datos)
    {
        $this->s__conexion = $datos;
    }
    
    function evt__importar()
    {
        try {
            toba::db()->abrir_transaccion();
            $institucion = new importador_institucion_ws($this->s__conexion['conexion']);
            $institucion->importar();
            toba::db()->cerrar_transaccion();
            toba::notificacion()->info('La información de la institución se actualizó correctamente.');
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->agregar('Ocurrió un error al intentar importar datos institucionales.');
            throw $ex;
        } catch (toba_error $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->agregar('Ocurrió un error al intentar importar datos institucionales.');
            throw $ex;
        }
    }
}