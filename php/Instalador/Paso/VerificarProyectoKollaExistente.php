<?php

namespace SIU\Kolla\Instalador\Paso;

use SIU\Instalador\Toba\Paso\VerificarProyectoExistente;

class VerificarProyectoKollaExistente extends VerificarProyectoExistente
{

    public function verificarParamsEnvToba($dir_instalacion, $entorno_toba)
    {
        if (!$this->is_actualizacion_exportacion) {
            if ($entorno_toba['TOBA_INSTALACION_DIR'] == $dir_instalacion) {
                $msg = 'El parámetro TOBA_INSTALACION_DIR de la instalación anterior esta configurado correctamente';
                $this->msgOkLog($msg);
            } else {
                $msg = "El parámetro de entorno TOBA_INSTALACION_DIR de la instalación anterior no corresponde con la ruta '$dir_instalacion'";
                $this->msgErrorLog($msg);
                exit(1);
            }
        }

        if (!$this->is_actualizacion_exportacion) {
            //Dependiendo del origen de la instalación anterior
            // la distancia entre $dir_instalacion y la ubicación del framwework puede ser diferente.
            //Por esto se intenta determinar cuál es la que realmente existe

            $inst_cli = realpath($dir_instalacion."/../vendor/siu-toba/framework");
            $inst_web = realpath($dir_instalacion."/../aplicacion/vendor/siu-toba/framework");
            //$inst_old = realpath($dir_instalacion."/../toba");
            if ($inst_cli) {
                $dir_toba = $inst_cli;
                $msg = 'Se verificó la existencia del directorio del framework en la ruta: '.$dir_toba;
                $this->msgOkLog($msg);
            } elseif ($inst_web) {
                $dir_toba = $inst_web;
                $msg = 'Se verificó la existencia del directorio del framework en la ruta: '.$dir_toba;
                $this->msgOkLog($msg);
            } else {
                $msg = "No se pudo verificar la existencia del directorio del framework en la instalación anterior";
                $this->msgErrorLog($msg);
                exit(1);
            }

            if ($entorno_toba['TOBA_DIR'] == $dir_toba) {
                $msg = 'El parámetro TOBA_DIR de la instalación anterior esta configurado correctamente';
                $this->msgOkLog($msg);
            } else {
                $msg = "El parámetro de entorno TOBA_DIR de la instalación anterior no corresponde con la ruta '$dir_toba'";
                $this->msgErrorLog($msg);
                exit(1);
            }
        }

        if (isset($entorno_toba['TOBA_INSTANCIA'])) {
            if ($entorno_toba['TOBA_INSTANCIA'] != 'produccion') {
                $msg = 'El entorno de toba no esta configurado correctamente para una instancia de produccion (TOBA_INSTANCIA=produccion)';
                $this->msgErrorLog($msg);
                exit(1);
            }
        } else {
            $msg = "No esta configurado el paramentro (TOBA_INSTANCIA=produccion) en la instalación '$dir_instalacion'";
            $this->msgErrorLog($msg);
            exit(1);
        }
    }

}
