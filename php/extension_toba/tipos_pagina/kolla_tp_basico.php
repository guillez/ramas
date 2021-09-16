<?php

use ext_bootstrap\tipos_pagina\tp_basico;
use Zend\Validator\Date;

class kolla_tp_basico extends tp_basico
{
	protected function info_version() {}
	
	protected function comienzo_cuerpo()
	{
		parent::comienzo_cuerpo_basico();
		$this->menu();
		//echo "<div class='{$this->clase_encabezado}'>";
		//$this->cabecera_kolla();
		//echo "</div>\n";
	}	
	
	protected function custom_cabecera()
    {
		$inst = toba::consulta_php('consultas_mgi')->get_institucion();
		$nombre_institucion = empty($inst) ? 'No definida' : $inst[0]['nombre'];
		$conexion = toba::consulta_php('consultas_usuarios')->get_ultima_conexion(toba::usuario()->get_id());
		$fecha = ($conexion) ? new DateTime($conexion['ingreso']):'< Sin datos >';
		$fecha_header = ($conexion) ? $fecha->format("d/m/Y H:i:s"):$fecha;
		
        $cabecera = "<div class='col-md-4'> \n";
		$cabecera .= '	<span class="text-red text-center col-md-12 padding-encabezado1"> Última Conexión </span>';
		$cabecera .= "	<span class='col-md-12 padding-encabezado2 text-center'> $fecha_header </span>";
		$cabecera .= "</div> \n";
		
		if (!empty($inst)) {
			$cabecera .= "<div class='col-md-8 hidden-sm'> \n";
			$cabecera .= '	<span class="text-red text-center col-md-12 padding-encabezado1"> Institución </span>';
			$cabecera .= "	<span class='col-md-12 padding-encabezado2 text-center span-texto-header-fix span-texto-header hidden-sm'> $nombre_institucion </span>";
			$cabecera .= "</div> \n";
		}

        //$cabecera .= "<div class='col-md-8 hidden-sm LAUNCHER'> \n";
        //$cabecera .= toba_app_launcher::instancia()->get_html_app_launcher();
        //$cabecera .= "</div> \n";
		
		return $cabecera;
	}
	
	protected function cabecera_kolla()
	{
		//-------------------------------------------------------------------------------------
		//---- Inicialización de fecha y hora -------------------------------------------------	
		//-------------------------------------------------------------------------------------
		
		$dia_db = kolla_fecha::get_hoy_parte('dia');
        //Se resta 1 porque la función devuelve mes + 1
        $mes_db = kolla_fecha::get_hoy_parte('mes') - 1;
        $anio_db = kolla_fecha::get_hoy_parte('anio');
        $hora_db = kolla_fecha::get_hoy_parte('hora');
        $minuto_db = kolla_fecha::get_hoy_parte('minuto');
		$segundo_db = kolla_fecha::get_hoy_parte('segundo');

		echo toba_js::abrir();
		echo "
				var now = new Date($anio_db, $mes_db, $dia_db, $hora_db, $minuto_db, $segundo_db);

				function decodeDate(d)
				{
					day = d.getDate();
			   		if (day < 10) {
						day = '0' + day;
			   		}
					month = d.getMonth() + 1;
					if (month < 10) {
						month = '0' + month;
					}
					return day + '/' + (month) + '/' + d.getFullYear();
				}

				function decodeTime(t)
				{
					hours = t.getHours();
					if (hours < 10) {
						hours = '0' + hours;
					}
					minutes = t.getMinutes();
			   		if (minutes < 10) {
						minutes = '0' + minutes;
			   		}
					seconds = t.getSeconds();
			   		if (seconds < 10) {
						seconds = '0' + seconds;
			   		}
					return hours + ':' + minutes + ':' + seconds;
				}

				function initializeDate()
				{
			   		document.getElementById('fecha_de_hoy').innerHTML = decodeDate(now);
				}

				function Clock()
				{
			   		timerID = setTimeout(\"Clock()\", 1000);
				   	now.setSeconds(now.getSeconds() + 1);
			   		document.getElementById('relojito').innerHTML = decodeTime(now);
				}

				function initializeClock()
				{
			   		Clock();
				}
		";
		echo toba_js::cerrar();
		
		//-------------------------------------------------------------------------------------
		
		//--- Logo
		$ini = new toba_ini(toba_proyecto::get_path().'/proyecto.ini');
		echo "
			<div class='cabecera'>
				<div style='height:70px; width: 18%'>
		";
		$this->mostrar_logo();
		echo "
				<div class='kolla_cab kolla_cab_titulo' style='left:6%; width: 10%;'> Versión ".toba::proyecto()->get_version()."</div>
			</div>\n
		";
		
		//--- Logo institución
		$logo = toba::proyecto()->get_www('img/logo_institucion.jpg');
		if (file_exists($logo['path'])) {
			echo "<div style='width:5%; height: {$this->alto_cabecera}; position:absolute; left:26%; top:0; margin-top: 2px;'>".toba_recurso::imagen($logo['url'], null, '34px')."</div>";
		}
		
		//--- Nombre institución
		$inst = toba::consulta_php('consultas_mgi')->get_institucion();
		echo "<div class='kolla_cab kolla_cab_titulo' style='left:17%; width:49%;'>Institución</div>";
		echo "<div class='kolla_cab' style='left:17%; width:49%'>";
		echo empty($inst) ? 'No definida' : $inst[0]['nombre'];
		echo "</div>\n";
		
		//--- Fecha
		echo "<div class='kolla_cab kolla_cab_titulo' style='right:25%; width: 5%'>Fecha</div>";
		echo "<div id='fecha_de_hoy' class='kolla_cab' style='right:25%; width: 5%'></div> \n";		
		
		//--- Hora
		echo "<div class='kolla_cab kolla_cab_titulo' style='right:19%; width: 5%'>Hora</div>";
		echo "<div id='relojito' class='kolla_cab' style='right:19%; width: 5%'></div> \n";
		
		//--- Usuario
		$us = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario(toba::usuario()->get_id());
		echo "<div class='kolla_cab kolla_cab_titulo' style='right:5%; width: 13%'>Usuario</div>";
		echo "<div class='kolla_cab' style='right:5%; width: 13%'>";
		echo texto_plano(ucwords(isset($us) ? $us['nombres'].' '.$us['apellidos'] : '')).' <span style="font-weight: normal">('.texto_plano(toba::usuario()->get_id()).')</span>';
		echo "</div>\n";
		
		//--- Inicializo fecha y hora
		echo "<script>initializeDate();</script>";
		echo "<script>initializeClock();</script>";
	}
	
	function mostrar_logo()
	{
		// Link a la operación "Inicio".
		$proyecto = toba::proyecto()->get_id();
		$item = '2';
		$js = "return toba.ir_a_operacion(\"$proyecto\", \"$item\", false)";
		$img = toba_recurso::imagen_proyecto('logo-kolla-iso.png', true, '35', '35');
		echo "<a href='#' onclick='$js' style='display: block; margin: 2px 0 2px 10px;'>$img</a>";
	}
	
}
?>