<?php

namespace SIU\Kolla\Instalador\Paso;

use SIU\Instalador\Toba\Paso\ConfigurarFinalInstalacionToba;
use SIU\TobaIni\Ini;

/**
 * Class ConfigurarFinalKollaInstalacionToba
 */
class ConfigurarFinalKollaInstalacionToba extends ConfigurarFinalInstalacionToba
{
    protected function run()
    {
        $parametros_sp = $this->parametros_sp;
        if (is_array($this->parametros_sp)) {
            $parametros_sp['proyecto'] = $this->proyecto;
            $parametros_sp['forzar_https'] = $this->forzar_https;
        }

        $parametros_rest = $this->parametros_rest;
        if (is_array($this->parametros_rest)) {
            $parametros_rest['proyecto'] = $this->proyecto;
        }

        if (is_array($this->parametros_seguridad_acceso) && isset($this->parametros_seguridad_acceso['validacion_intentos'])) {
            $parametros_seguridad_acceso = $this->parametros_seguridad_acceso;
            $parametros_seguridad_acceso['proyecto'] = $this->proyecto;
            $this->tobaConfig()->configurarSeguridadAcceso($parametros_seguridad_acceso);
        }

        $this->tobaConfig()->setTobaSessionName($this->parametros['toba']['session_name']);
        $this->configurarSMTP($this->parametros_smtp);
        $this->tobaConfig()->configurarOnelogin($parametros_sp);
        $this->tobaConfig()->configurarRestHooks($this->parametros_resthooks);
        $this->tobaConfig()->configurarApiRest($parametros_rest);
        $this->tobaConfig()->configurarApiAfip($this->parametros_afip_ws);

        $this->recomendacionesFinales();
  }

  public function configurarSMTP($parametros)
  {
        $smtp_ini = new Ini($this->instalacion_dir.'/smtp.ini');
        $valores_previos = $smtp_ini->get_entradas();

        if (is_array($parametros)) {
            $smtp_ini->vaciar();
            
            $temp_config = [
                'host' => $parametros['smtp_host'],
                'puerto' => $parametros['smtp_port'],
                'auth' => $parametros['smtp_auth'],
                'usuario' => $parametros['smtp_usuario'],
                'clave' => $parametros['smtp_clave'],
                'seguridad' => $parametros['smtp_seguridad'],
                'nombre_from' => $parametros['smtp_nombre_from'],
                'helo' => $parametros['smtp_helo'],
                'from' => $parametros['smtp_from']
            ];

            if (isset($parametros['smtp_auto_tls'])) {
                $temp_config['auto_tls'] = $parametros['smtp_auto_tls'];
            }

            if (isset($parametros['smtp_destino_prueba'])) {
                $temp_config['destino_prueba'] = $parametros['smtp_destino_prueba'];
            }

            $smtp_config = array_merge($valores_previos, array($parametros['smtp_entrada'] => $temp_config));
            $smtp_ini->set_entradas($smtp_config);
            $this->msgNoteLog("Se creo la configuración del SMTP", '[ SMTP ] Se creo la configuración del SMTP '.var_export($smtp_ini->get_entradas(), true));
        } else {
            $smtp_config[';host'] = '200.70.58.124';
            $smtp_config[';auth'] = '1';
            $smtp_config[';usuario'] = 'jperez';
            $smtp_config[';clave'] = 'pepote';

            $smtp_ini->agregar_entrada('telefonica', $smtp_config);
            $this->msgNoteLog("Se creo la configuración del SMTP con un template con datos de ejemplo");
        }

        $smtp_ini->guardar();
  }

}
