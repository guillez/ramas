<?php

namespace SIU\Kolla\Instalador;

use SIU\Instalador\Toba\Configuracion as TobaConfiguracion;

class Configuracion extends TobaConfiguracion
{
    public function getProyectoLogo()
    {
       $logo = <<<'LOGO'
  ____ ___ _   _       ____                            _        
 / ___|_ _| | | |     | |/ /___ | | | __ _ 
 \___ \| || | | |_____| ' // _ \| | |/ _` |
  ___) | || |_| |_____| . \ (_) | | | (_| |
 |____/___|\___/      |_|\_\___/|_|_|\__,_|
                                           
LOGO;

        return $logo;
    }
}

