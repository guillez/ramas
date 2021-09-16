<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_formulario.php';

class form_usuario extends bootstrap_formulario
{
	function extender_objeto_js()
	{
		$mensaje_usuario_sin_espacios = 'Usuario no puede contener espacios.';
		$mensaje_usuario_sin_prefijo_reservado = "Usuario no puede tener el prefijo \"ue_\".";
		        
		echo "
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.deshabilitar_campo = function(id)
		{
            if (id == 'barra_personales') {
                this.ef(id).ocultar();
            } else {
                this.ef(id).ocultar(true);
            }
		}
		
		{$this->objeto_js}.habilitar_campo = function(id)
		{
			this.ef(id).mostrar();
		}
		
		{$this->objeto_js}.evt__usuario_grupo_acc__procesar = function(es_inicial)
		{
			var acceso = this.ef('usuario_grupo_acc').get_estado();
			
            this.controlador.desactivar_tab('grupos');
            this.controlador.ajax('get_acceso', [], this, this.alert_perfil_acceso);
            
			if (acceso == 'encuesta') {
				this.controlador.activar_tab('titulos');
				this.controlador.activar_tab('grupos');
			} else {
				this.controlador.desactivar_tab('titulos');
				this.controlador.desactivar_tab('grupos');
			}
			
            if (this.ef('usuario_perfil_datos')) {
                if (acceso == 'gestor') {
                    this.ef('usuario_perfil_datos').mostrar();
                } else {
                    this.ef('usuario_perfil_datos').ocultar(true);
                }
            }
		            
			if ((acceso == 'guest') || (acceso == 'externo')) {
				this.deshabilitar_campo('documento_pais');
				this.deshabilitar_campo('documento_tipo');
				this.deshabilitar_campo('documento_numero');
				this.deshabilitar_campo('fecha_nacimiento');
				this.deshabilitar_campo('nombres');
				this.deshabilitar_campo('apellidos');
				this.deshabilitar_campo('sexo');
				this.deshabilitar_campo('email');
		        this.deshabilitar_campo('barra_personales');
		                
			} else {
				this.habilitar_campo('documento_pais');
				this.habilitar_campo('documento_tipo');
				this.habilitar_campo('documento_numero');
				this.habilitar_campo('fecha_nacimiento');
				this.habilitar_campo('nombres');
				this.habilitar_campo('apellidos');
				this.habilitar_campo('sexo');
				this.habilitar_campo('email');
		        this.habilitar_campo('barra_personales');
			}
            
		    return true;
		}
		        
        function firstFocus()
        {
            document.getElementById('ef_form_40000290_form_usuariousuario_grupo_acc').focus();
        }
        
        {$this->objeto_js}.alert_perfil_acceso = function(datos)
		{
			var acceso_nuevo = this.ef('usuario_grupo_acc').get_estado();
            
            if (datos['acceso_anterior'] == 'admin' && acceso_nuevo == 'gestor') {
                alert('No es posible modificar el Perfil de Acceso de Administrador a Gestor.');
            } else if (datos['acceso_anterior'] == 'gestor' && acceso_nuevo == 'admin') {
                alert('No es posible modificar el Perfil de Acceso de Gestor a Administrador.');
            } else if (datos['acceso_anterior'] == 'admin' && acceso_nuevo == 'guest') {
                alert('No es posible modificar el Perfil de Acceso de Administrador a Anónimo.');
            } else if (datos['acceso_anterior'] == 'gestor' && acceso_nuevo == 'guest') {
                alert('No es posible modificar el Perfil de Acceso de Gestor a Anónimo.');
            }
		}
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__usuario__validar = function()
		{
            var usuario = this.ef('usuario').get_estado();

            if (/\s+/.test(usuario)) {
                this.ef('usuario').set_error('$mensaje_usuario_sin_espacios');
                return false;
            }

            if (/^ue_/.test(usuario)) {
                this.ef('usuario').set_error('$mensaje_usuario_sin_prefijo_reservado');
                return false;
            }

            return true;
		}
        
        ";
	}

}
?>