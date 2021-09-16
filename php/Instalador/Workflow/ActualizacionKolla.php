<?php

namespace SIU\Kolla\Instalador\Workflow;

use SIU\Instalador\Toba\Workflow\Actualizacion;

class ActualizacionKolla extends Actualizacion
{
   public function inicializarPasos()
    {
        return [
            new \SIU\Instalador\Toba\Paso\RecomendacionInicial(),
            new \SIU\Instalador\Toba\Paso\ParametrizarToba(),
            new \SIU\Instalador\Toba\Paso\ChequearDependenciasToba(),
            new \SIU\Kolla\Instalador\Paso\VerificarProyectoKollaExistente(),
            new \SIU\Instalador\Toba\Paso\ExportarProyectoExistente(),
            new \SIU\Instalador\Toba\Paso\InstalarToba(),
            new \SIU\Kolla\Instalador\Paso\PreMigrarProyectoKolla(),
            new \SIU\Instalador\Toba\Paso\MigrarProyectoToba(),
            new \SIU\Kolla\Instalador\Paso\PostMigrarProyectoKolla(),
            new \SIU\Kolla\Instalador\Paso\ConfigurarFinalKollaInstalacionToba(),
        ];
    }
}