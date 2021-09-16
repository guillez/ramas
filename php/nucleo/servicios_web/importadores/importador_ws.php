<?php

class importador_ws 
{
    protected $server;
    protected $_client;
    
    function get_tipo_conexion()
    {
        return $this->server['ws_tipo'];
    }
    
    function get_ug()
    {
        return $this->server['ug'];
    }
    
    function es_soap()
    {
        return $this->server['ws_tipo'] == 'soap';
    }
    
    function get_conexion()
    {
        return $this->server['conexion'];
    }

    function __construct($conexion=null)
    {
        if (isset($conexion)) {
            $where = 'conexion = '.kolla_db::quote($conexion);
            $res = toba::consulta_php('consultas_mgi')->get_conexiones_ws($where);
            $server = $res[0];
            $this->server['conexion'] = $conexion;
            $this->server['ws_url']   = $server['ws_url'];
            $this->server['ws_user']  = $server['ws_user'];
            $this->server['ws_clave'] = $server['ws_clave'];
            $this->server['ws_tipo']  = $server['ws_tipo'];
            $this->server['ug']       = $server['unidad_gestion'];   
        }
    }
    
    protected function _conectar()
    {
        if (isset($this->server)) {
            if ( $this->server['ws_tipo'] == 'soap' ) {
                $servidor = $this->server['ws_url'].'?wsdl';
                $this->_client = new nusoap_client($servidor, 'wsdl');
                $this->_client->setCredentials($this->server['ws_user'], $this->server['ws_clave'], 'basic');
            } else {
                $this->_client = new guarani($this->server['ws_url'], $this->server['ws_user'], $this->server['ws_clave']);   
            }    
        }
    }
    
    function get_cliente()
    {
        return $this->_client;
    }
    
    function _validar_response($datos)
    {
        $error = $this->_client->getError();
            
        if ($error) {
            toba::notificacion()->agregar('Error importando datos: '.$error.'\n');
        } elseif (isset($datos['faultcode']) && $datos['faultcode'] != null) {
            toba::notificacion()->agregar('El Sistema Guaraní respondió con ERROR');
        }
    }
    
}
