<?php
class cuadro_usuarios_seleccion_multiple extends cuadro_seleccion_multiple
{
    //-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
        $msj_sin_seleccion = 'Debe seleccionar al menos un usuario.';
                
		echo "
            
		//---- Eventos ---------------------------------------------------------
		
		{$this->objeto_js}.evt__agregar_marcados = function()
		{
            return controlar_marcados(this);
		}
        
        {$this->objeto_js}.evt__quitar_marcados = function()
		{
            return controlar_marcados(this);
		}
        
        function controlar_marcados(cuadro)
        {
            filas_seleccionadas = cuadro.get_ids_seleccionados('seleccion_multiple');
            
            if (filas_seleccionadas.length == 0) {
                alert('$msj_sin_seleccion');
                return false;
            }
            
            return true;
        }
		";
	}
}
?>