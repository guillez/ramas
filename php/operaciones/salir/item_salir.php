<?php
	$item = toba::memoria()->get_item_solicitado_original();
	
	//Si originalmente no se pidio salir, ir a la página inicial
	if ($item[1] != '38000148') {
		toba::vinculador()->navegar_a(toba::proyecto()->get_id(), 2);
	} else {
        if (toba_editor::modo_prueba()) {
            $js = 'window.close()';
        } else {
            $js = ' if (confirm("¿Desea terminar la sesión?")) {
                        var prefijo = toba_prefijo_vinculo.substr(0, toba_prefijo_vinculo.indexOf("?"));
                        var vinculo = prefijo + "?fs=1";
                        
                        if (top) {
                            top.location.href= vinculo;
                        } else {
                            location.href = vinculo;
                        }
                    } else {
                        toba.ir_a_operacion("kolla", "2", false);
                    }';
        }
		
		echo "<script language='javascript'>";
		echo $js;
		echo '</script>';
	}
?>