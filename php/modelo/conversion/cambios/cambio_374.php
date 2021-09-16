<?php

require_once('cambio.php');

class cambio_374 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 374: Grupos habilitados para habilitaciones externas.';
	}
    
	function cambiar()
	{
        $sql = "SELECT  sfh.formulario_habilitado, 
                        sh.habilitacion, 
                        sh.externa, 
                        sse.sistema, 
                        se.encuestado, 
                        se.usuario, 
                        sgdetalle.grupo, 
                        sgh.grupo as grupo_habilitado, 
                        sgh.formulario_habilitado as grupo_hab_form_hab
                FROM    sge_formulario_habilitado sfh 
                        INNER JOIN sge_habilitacion sh ON (sfh.habilitacion = sh.habilitacion)
                        INNER JOIN sge_sistema_externo sse ON (sse.sistema = sh.sistema)
                        INNER JOIN sge_encuestado se ON (se.usuario = sse.usuario)
                        INNER JOIN sge_grupo_detalle sgdetalle ON (se.encuestado = sgdetalle.encuestado)
                        INNER JOIN sge_grupo_definicion sgdefinicion ON (sgdetalle.grupo = sgdefinicion.grupo)
                        LEFT JOIN sge_grupo_habilitado sgh ON (sgh.grupo = sgdefinicion.grupo AND sgh.formulario_habilitado = sfh.formulario_habilitado)
                WHERE   sh.externa = 'S' 
                        AND sgh.grupo IS NULL";
        
        $resultados =  $this->consultar($sql);
          
        foreach ($resultados as $formulario_habilitado) {
            $id_grupo = $formulario_habilitado['grupo'];
            $id_form =  $formulario_habilitado['formulario_habilitado'];
            $sql = "INSERT INTO sge_grupo_habilitado 
                                (grupo,formulario_habilitado)
                    VALUES      ($id_grupo, $id_form)";
            $this->ejecutar($sql);
          }
	}

}
?>