<?php

toba::memoria()->desactivar_reciclado();

if(isset($_POST['get_ajax'])){
   $form_loc = new formulario_localidad();
   $form_loc->ajax_request();
   return;
}
			
?>
