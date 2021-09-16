<?php


$_SERVER['TOBA_PROYECTO'] = 'kolla';

if (! isset($_SERVER['TOBA_INSTANCIA'])) {
	//se podría dejar esto como default
	$_SERVER['TOBA_INSTANCIA'] = 'desarrollo';
}

if (! isset($_SERVER['TOBA_DIR'])) {
	//se podría dejar esto como default
	//los proyectos podrían mayormente harcodear este parámetro asi
    if (! file_exists('paths.php')) {
        $_SERVER['TOBA_DIR'] = realpath(__DIR__.'/../../../');
    } else {
        include_once 'paths.php';
        $_SERVER['TOBA_DIR'] = paths::toba_dir();
    }

}

echo "Iniciando contexto de ejecucion de toba...\n";
echo "\n";


if (! isset($_SERVER['TOBA_DIR'])) {
    die("ATENCION: La variable de entorno 'TOBA_DIR' no esta definida!");
}

include($_SERVER['TOBA_DIR']."/php/consola/bootstrap_test.php");
require_once $_SERVER["TOBA_DIR"].'/php/lib/rest/composer/autoload_real.php';

echo "Cargando namespaces... \n \n ";

$map = require __DIR__ . '/base/namespaces.php';
foreach ($map as $namespace => $path) {
	ComposerAutoloaderInit59f47c3efc0f016a89a4d87e99595a71::getLoader()->setPsr4($namespace, $path);
}
