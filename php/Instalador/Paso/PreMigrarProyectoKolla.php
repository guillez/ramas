<?php

namespace SIU\Kolla\Instalador\Paso;

use SIU\Instalador\Toba\Paso\PreMigrarProyectoToba;
use SIU\TobaDb\DbPDO;

/**
 * Class PostMigrarProyectoKolla
 */
class PreMigrarProyectoKolla extends PreMigrarProyectoToba
{
    protected function postRun()
    {
        $this->io()->msgTitulo('Verificando existencia de tablas de backup de araucano y eliminándolas');
        $conn = new DbPDO($this->parametros['db_proyecto']);
        
        $sql = "DROP TABLE IF EXISTS arau_instituciones_backup CASCADE;
                DROP TABLE IF EXISTS arau_responsables_academicas_backup CASCADE;
                DROP TABLE IF EXISTS arau_titulos_backup CASCADE;
                ";

        $conn->ejecutar($sql);
        parent::postRun();
    }

}
