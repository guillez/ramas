<h1>Configuraci&oacute;n</h1>

<?php 
$datos = inst::paso()->get_datos_configuracion(); 
?>
<form method="post" action="<?php echo inst::accion()->get_url();?>" onsubmit='return esperar_operacion(this)'>

<h2>Publicaci�n</h2>
<span class='aclaracion'>Por favor indique la direcci&oacute;n donde ser&aacute; publicada la aplicaci&oacute;n</span>
<div style='margin-left: 50px; margin-top: 10px; font-size: 12px; '>
		http://<?php echo $_SERVER["SERVER_NAME"]; ?>/<input id="url_prefijo" name="url_prefijo" type="text" size="10" value="<?php echo $datos['url_prefijo']; ?>"><?php echo inst::configuracion()->get('instalador', 'url_sufijo'); ?>
</div>
<h2>Nueva Instalaci&oacute;n</h2> 
<span class='aclaracion'>Por favor indique los datos b&aacute;sicos de la instalaci&oacute;n</span>
<table>
<tr>
	<td class="label"><label class='ayuda' title='Nombre, que sirve para identificar la instalaci�n en logs y reportes' for='instalacion_id'>Nombre</label></td>
	<td colspan=2><input id="instalacion_id" name="instalacion_id" type="text" size="20" value="<?php echo $datos['instalacion_id']; ?>"></td>
</tr>
</table>

<h2>Usuario Administrador</h2>
<span class='aclaracion'>Por favor indique los datos b&aacute;sicos del usuario inicial de la aplicaci&oacute;n</span>
<table>
<tr>
	<td class="label"><label class='ayuda' title='Identificador del usuario administrador de la aplicaci&oacute;n, m�nimo 4 caracteres y no debe contener espacios' for='usuario_id'>Usuario</label></td>
	<td colspan=2><input id="usuario_id" name="usuario_id" type="text" size="20" value="<?php echo $datos['usuario_id']; ?>"></td>
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Contrase&ntilde;a usuario administrador de la aplicaci&oacute;n, m&iacute;nimo 6 caracteres' for='usuario_clave'>Clave</label></td>
	<td><input id="usuario_clave" name="usuario_clave" type="password" onKeyUp="runPassword(this.value, 'usuario_clave');" size="20" value="<?php echo $datos['usuario_clave']; ?>">
	</td>
	<td>
		<div style="width: 100px;"> 
			<div id="usuario_clave_text" style="font-size: 10px;"></div>
			<div id="usuario_clave_bar" style="font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></div> 
		</div>
	</td>
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Nombre y Apellido del usuario administrador de la aplicaci&oacute;n' for='usuario_nombre'>Nombre Completo</label></td>
	<td colspan=2><input id="usuario_nombre" name="usuario_nombre" type="text" size="30" value="<?php echo $datos['usuario_nombre']; ?>"></td>
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Correo electr&oacute;nico del usuario administrador de la aplicaci&oacute;n' for='usuario_email'>E-Mail</label></td>
	<td colspan=2><input id="usuario_email" onblur="cambiar_smtp_from(this.value);" name="usuario_email" type="text" size="30" value="<?php echo $datos['usuario_email']; ?>"></td>
</tr>
</table>


<h2>Envio de Mails</h2>
<span class='aclaracion'>Permite que la aplicaci�n utilize este servicio para reportar notificaciones o errores durante su ejecuci�n</span>
<table id='configurar_smtp'>
<tr>
	<td class="label"><label class='ayuda' title='Cuenta que se utilizar� como From en los envios de correo' for='smtp_from'>Cuenta de Correo Saliente</label></td>
	<td colspan=3>
		<input id="smtp_from" name="smtp_from" type="text" size="20" value="<?php echo $datos['smtp_from']; ?>">
	</td>	
	
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Direcci�n IP o nombre del servidor SMTP' for='smtp_host'>Servidor SMTP</label></td>
	<td colspan=3>
		<input id="smtp_host" name="smtp_host" type="text" size="20" value="<?php echo $datos['smtp_host']; ?>">
	</td>	
	
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Puerto' for='smtp_puerto'>Puerto</label></td>
	<td colspan=3>
		<input id="smtp_puerto" name="smtp_puerto" type="text" size="20" value="<?php echo $datos['smtp_puerto']; ?>">
	</td>	
	
</tr>
<tr>
	<td></td>
	<td colspan=3>
		<label> <input type='radio' value='' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == '') echo 'checked'; ?>>Sin Encriptaci�n</label>
		<label> <input type='radio' value='ssl' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == 'ssl') echo 'checked'; ?>>SSL</label>
		<label> <input type='radio' value='tls' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == 'tls') echo 'checked'; ?>>TLS</label>
	</td>		
</tr>			
<tr>	
	<td class="label"><label for='smtp_auth'>Requiere autentificaci�n</label></td>		
	<td colspan=3>
		<input id="smtp_auth" name="smtp_auth" type="checkbox" onclick='cambio_smtp_auth(this.checked)' <?php if (isset($datos['smtp_auth']) && $datos['smtp_auth']) echo 'checked'; ?>>
	</td>
</tr>
<tr id='smtp_usuario_clave'>
	<td class="label"><label for='smtp_usuario'>Usuario</label></td>
	<td>
		<input id="smtp_usuario" name="smtp_usuario" type="text" size="20" value="<?php echo $datos['smtp_usuario']; ?>">
	</td>		
	<td class="label"><label for='smtp_clave'>Clave</label></td>
	<td>
		<input id="smtp_clave" name="smtp_clave" type="password" size="20" value="<?php echo $datos['smtp_clave']; ?>">
	</td>		
</tr>
<tr>
	<td colspan='4' style='text-align: center'>
		<input type='submit' name='smtp_probar' value='Probar Conexi�n'/> 
	</td>
</tr>
</table>
</form>
<br>

<?php if(inst::paso()->tiene_errores()): 
	inst::paso()->generar_html_errores();
endif; ?>

<script type='text/javascript'>

function cambio_smtp_auth(auth)
{
	var display = auth ? '': 'none';
	document.getElementById('smtp_usuario_clave').style.display = display;		

}
function cambiar_smtp_from(cuenta)
{
	var from = document.getElementById('smtp_from');
	if (from.value == '') {
		from.value = cuenta;
	}
}
document.getElementById("smtp_auth").onclick();
</script>

<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()">Crear Configuraci�n</a>
	</span>
</div>

<?php
	if (isset($datos['smtp_ok'])) {
		echo "
			<script type='text/javascript'>
				alert('Conexi�n exitosa!');
			</script>
		";
	}
?>
