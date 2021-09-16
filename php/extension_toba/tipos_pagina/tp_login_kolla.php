<?php
use ext_bootstrap\tipos_pagina\tp_login;

class tp_login_kolla extends tp_login
{
    function pre_contenido()
	{
		echo "<div class='login-titulo text-center'>". toba_recurso::imagen_proyecto("logo-kolla-instalador.png", true);
		echo "<div>".toba::proyecto()->get_version()."</div>";
		echo "</div>";
	}
}
?>