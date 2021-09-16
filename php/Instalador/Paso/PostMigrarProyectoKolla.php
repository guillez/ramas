<?php

namespace SIU\Kolla\Instalador\Paso;

use SIU\Instalador\Toba\Paso\PostMigrarProyectoToba;
use SIU\TobaDb\DbPDO;

/**
 * Class PostMigrarProyectoKolla
 */
class PostMigrarProyectoKolla extends PostMigrarProyectoToba
{
    protected function postRun()
    {
        $this->io()->msgTitulo('Verificando compatibilidades de cÃ³digos araucano');
        $conn = new DbPDO($this->parametros['db_proyecto']);
        $schema = $conn->get_schema();
        $existe_instituciones = $this->existe_tabla($conn, $schema, 'arau_instituciones_backup');
        $existe_ras = $this->existe_tabla($conn, $schema, 'arau_responsables_academicas_backup');
        $existe_titulos = $this->existe_tabla($conn, $schema, 'arau_titulos_backup');
        
        if ($existe_instituciones && $existe_ras && $existe_titulos) {
        
            /*
             * Tabla arau_instituciones
             */

            $sql = "SELECT  arau_instituciones.institucion_araucano,
                            arau_instituciones.nombre
                    FROM    arau_instituciones";

            $arau_instituciones = $conn->consultar($sql);

            $sql = "SELECT  arau_instituciones_backup.institucion_araucano,
                            arau_instituciones_backup.nombre
                    FROM    arau_instituciones_backup";

            $arau_instituciones_backup = $conn->consultar($sql);

            //Se controla si algún codigo se modificó
            foreach ($arau_instituciones as $institucion) {
                foreach ($arau_instituciones_backup as $institucion_backup) {
                    if ($institucion['institucion_araucano'] == $institucion_backup['institucion_araucano'] && $institucion['nombre'] != $institucion_backup['nombre']) {
                        $this->setWarnings("El registro de la institución araucano '".$institucion['institucion_araucano']."' fué modificado en su campo nombre. Paso de llamarse '".$institucion_backup['nombre']."' a llamarse '".$institucion['nombre']."'.");
                        break;
                    }
                }
            }

            //Se controla si se perdió algún codigo de la tabla vieja
            $arau_inst_aplanado = $this->aplanar_matriz_sin_nulos($arau_instituciones, 'institucion_araucano');

            foreach ($arau_instituciones_backup as $institucion_backup) {
                if (!in_array($institucion_backup['institucion_araucano'], $arau_inst_aplanado)) {
                    $this->setWarnings("El registro de la institución araucano con código '".$institucion_backup['institucion_araucano']."' y nombre '".$institucion_backup['nombre']."' fué descartado durante el proceso de actualización de la base de datos. Por favor verifique la tabla arau_instituciones_backup.");
                }
            }

            /*
             * Tabla arau_responsables_academicas
             */

            $sql = "SELECT  arau_responsables_academicas.ra_araucano,
                            arau_responsables_academicas.nombre
                    FROM    arau_responsables_academicas";

            $arau_responsables_academicas = $conn->consultar($sql);

            $sql = "SELECT  arau_responsables_academicas_backup.ra_araucano,
                            arau_responsables_academicas_backup.nombre
                    FROM    arau_responsables_academicas_backup";

            $arau_responsables_academicas_backup = $conn->consultar($sql);

            //Se controla si algún codigo se modificó
            foreach ($arau_responsables_academicas as $ra) {
                foreach ($arau_responsables_academicas_backup as $ra_backup) {
                    if ($ra['ra_araucano'] == $ra_backup['ra_araucano'] && $ra['nombre'] != $ra_backup['nombre']) {
                        $this->setWarnings("El registro de la responsable académica araucano '".$ra['ra_araucano']."' fué modificado en su campo nombre. Paso de llamarse '".$ra_backup['nombre']."' a llamarse '".$ra['nombre']."'.");
                        break;
                    }
                }
            }

            //Se controla si se perdió algún codigo de la tabla vieja
            $arau_ra_aplanado = $this->aplanar_matriz_sin_nulos($arau_responsables_academicas, 'ra_araucano');

            foreach ($arau_responsables_academicas_backup as $ra_backup) {
                if (!in_array($ra_backup['ra_araucano'], $arau_ra_aplanado)) {
                    $this->setWarnings("El registro de la responsable académica araucano con código '".$ra_backup['ra_araucano']."' y nombre '".$ra_backup['nombre']."' fué descartado durante el proceso de actualización de la base de datos. Por favor verifique la tabla arau_instituciones_backup.");
                }
            }

            /*
             * Tabla arau_titulos
             */

            $sql = "SELECT  arau_titulos.titulo_araucano,
                            arau_titulos.nombre
                    FROM    arau_titulos";

            $arau_titulos = $conn->consultar($sql);

            $sql = "SELECT  arau_titulos_backup.titulo_araucano,
                            arau_titulos_backup.nombre
                    FROM    arau_titulos_backup";

            $arau_titulos_backup = $conn->consultar($sql);

            //Se controla si algún codigo se modificó
            foreach ($arau_titulos as $titulo) {
                foreach ($arau_titulos_backup as $titulo_backup) {
                    if ($titulo['titulo_araucano'] == $titulo_backup['titulo_araucano'] && $titulo['nombre'] != $titulo_backup['nombre']) {
                        $this->setWarnings("El registro del título araucano '".$titulo['titulo_araucano']."' fué modificado en su campo nombre. Paso de llamarse '".$titulo_backup['nombre']."' a llamarse '".$titulo['nombre']."'.");
                        break;
                    }
                }
            }

            //Se controla si se perdió algún codigo de la tabla vieja
            $arau_titulo_aplanado = $this->aplanar_matriz_sin_nulos($arau_titulos, 'titulo_araucano');

            foreach ($arau_titulos_backup as $titulo_backup) {
                if (!in_array($titulo_backup['titulo_araucano'], $arau_titulo_aplanado)) {
                    $this->setWarnings("El registro del título araucano con código '".$titulo_backup['titulo_araucano']."' y nombre '".$titulo_backup['nombre']."' fué descartado durante el proceso de actualización de la base de datos. Por favor verifique la tabla arau_instituciones_backup.");
                }
            }
        }
        
        parent::postRun();
    }
    
    function aplanar_matriz_sin_nulos($matriz, $campo = null)
	{
		$aplanado = array();
		foreach ($matriz as $clave => $arreglo) {
			//Compara igualdad de valores y de tipos.
			if ($campo === null && !is_null(current($arreglo))) {
				$aplanado[$clave] = current($arreglo);
			} elseif (isset($arreglo[$campo]) && !is_null($arreglo[$campo])) {
				$aplanado[$clave] = $arreglo[$campo];
			}
		}
		return $aplanado;
	}
    
    function existe_tabla($conn, $schema, $tabla)
	{
		$tabla = $conn->quote($tabla);
		$schema = $conn->quote($schema);

		$sql = "SELECT  table_name
				FROM    information_schema.tables
				WHERE   table_name = $tabla
                AND     table_schema= $schema;";

		$rs = $conn->consultar_fila($sql);
		return !empty($rs);
	}

}
