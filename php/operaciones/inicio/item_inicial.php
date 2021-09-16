<?php
    $es_admin  = toba::consulta_php('consultas_usuarios')->es_admin_actual();
    $es_gestor = toba::consulta_php('consultas_usuarios')->es_gestor_actual();
    
    if ($es_admin || $es_gestor) {
        if ($es_admin) {
            $panel1 = toba_recurso::imagen_proyecto('panel1_admin.png');
            $panel2 = toba_recurso::imagen_proyecto('panel2_admin.png');
            $panel3 = toba_recurso::imagen_proyecto('panel3_admin.png');
            $panel4 = toba_recurso::imagen_proyecto('panel4_admin.png');
            $class_panel1 = 'panel1_admin';
            $class_panel2 = 'panel2_admin';
            $class_panel3 = 'panel3_admin';
            $class_panel4 = 'panel4_admin';
            $class_numero_paso1 = 'numero-paso';
            $class_numero_paso2 = 'numero-paso';
            $class_numero_paso3 = 'numero-paso';
            $class_numero_paso4 = 'numero-paso';
        } else {
            $panel1 = toba_recurso::imagen_proyecto('panel1_gestor.png');
            $panel2 = toba_recurso::imagen_proyecto('panel2_gestor.png');
            $panel3 = toba_recurso::imagen_proyecto('panel3_gestor.png');
            $panel4 = toba_recurso::imagen_proyecto('panel4_gestor.png');
            $class_panel1 = 'panel1_gestor';
            $class_panel2 = 'panel2_gestor';
            $class_panel3 = 'panel3_gestor';
            $class_panel4 = 'panel4_gestor';
            $class_numero_paso1 = 'numero-paso1-gestor';
            $class_numero_paso2 = 'numero-paso2-gestor';
            $class_numero_paso3 = 'numero-paso3-gestor';
            $class_numero_paso4 = 'numero-paso4-gestor';
        }
?>
    <div class="paneles row">
        <div class="col-xs-offset-1 col-xs-10 col-ss-offset-0 col-ss-6 col-sm-offset-0 col-sm-6 col-md-3">
            <div class="<?php echo $class_numero_paso1 ?>"><h1>1</h1></div>
            <div class="">
                <img alt="Brand" src="<?php echo $panel1 ?>" class="img-responsive center-block icono-panel <?php echo $class_panel1 ?> img-circle">
            </div>
            <div class="<?php echo $class_numero_paso1 ?>"><h4>Gestión de Encuestas</h4></div>
            <ol class="center-block">
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "200000014", false)'>Crear preguntas</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "200000016", false)'> Crear respuestas</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "200000018", false)'>Asociar respuestas a preguntas</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "45000007", false)' >Crear encuestas</a></li>
            </ol>
        </div>
        <div class="col-xs-offset-1 col-xs-10 col-ss-offset-0 col-ss-6 col-sm-offset-0 col-sm-6 col-md-3">
            <div class="<?php echo $class_numero_paso2 ?>"><h1>2</h1></div>
            <div class="">
                <img alt="Brand" src="<?php echo $panel2 ?>" class="img-responsive center-block icono-panel <?php echo $class_panel2 ?> img-circle">
            </div>
            <div class="<?php echo $class_numero_paso2 ?>"><h4>Gestión de Usuarios</h4></div>
            <ol class="center-block">
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "40000111", false)'>Crear usuarios</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "45000016", false)'>Crear grupos de usuarios</a></li>
            </ol>
        </div>
        <div class="clearfix hidden-md hidden-lg"></div>
        <div class="col-xs-offset-1 col-xs-10 col-ss-offset-0 col-ss-6 col-sm-offset-0 col-sm-6 col-md-3">
            <div class="<?php echo $class_numero_paso3 ?>"><h1>3</h1></div>
            <div class="">
                <img alt="Brand" src="<?php echo $panel3 ?>" class="img-responsive center-block icono-panel <?php echo $class_panel3 ?> img-circle">
            </div>
            <div class="<?php echo $class_numero_paso3 ?>"><h4>Habilitación de Encuestas</h4></div>
            <ol class="center-block">
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "38000160", false)'>Crear formularios</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "200000024", false)'>Habilitar encuestas</a></li>
            </ol>
        </div>
        <div class="col-xs-offset-1 col-xs-10 col-ss-offset-0 col-ss-6 col-sm-offset-0 col-sm-6 col-md-3">
            <div class="<?php echo $class_numero_paso4 ?>"><h1>4</h1></div>
            <div class="">
                <img alt="Brand" src="<?php echo $panel4 ?>" class="img-responsive center-block icono-panel <?php echo $class_panel4 ?> img-circle">
            </div>
            <div class="<?php echo $class_numero_paso4 ?>"><h4>Obtención de Resultados</h4></div>
            <ol class="center-block">
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "40000176", false)'>Emitir reportes</a></li>
                <li class='step'><a href="#" onclick='return toba.ir_a_operacion("kolla", "40000128", false)'>Descargar reportes</a></li>
            </ol>
        </div>
    </div>
<?php
    } else {
?>
    <script language='javascript'>
        toba.ir_a_operacion("kolla", "200000026", false)
    </script>
<?php
    }
?>