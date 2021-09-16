//////////////////////////////////////////
//ELEMENTOS BLOQUE 2 - DATOS ECON?MICOS
//////////////////////////////////////////

	var cond_act_laboral_alumno = 268; // document.getElementById('c4_312_115_385');
	var trabajo_es_alumno = 269; //document.getElementById('c4_312_120_386');
	var ocupacion_es_alumno = 270; // document.getElementById('c4_312_125_387');
	var hs_semanales_alumno = 271; //document.getElementById('c4_312_130_388');
	var rel_trab_carr_alumno = 272; //document.getElementById('c4_312_135_389');

	//var etiq_trabajo_es_alumno = document.getElementById('d4_312_120_386');
	//var etiq_ocupacion_es_alumno = document.getElementById('d4_312_125_387');
	//var etiq_hs_semanales_alumno = document.getElementById('d4_312_130_388');
	//var etiq_rel_trab_carr_alumno = document.getElementById('d4_312_135_389');

	var vive_padre = 275; //document.getElementById('c4_312_150_392');
	var cond_act_laboral_padre = 276; //document.getElementById('c4_312_152_385');
	var trabajo_es_padre = 277; //document.getElementById('c4_312_155_393');
	var ocupacion_es_padre = 278;// document.getElementById('c4_312_160_394');
	var no_trab_no_busca_padre = 279; //document.getElementById('c4_312_165_395');

	//var etiq_cond_act_laboral_padre = document.getElementById('d4_312_152_385');
	//var etiq_trabajo_es_padre = document.getElementById('d4_312_155_393');
	//var etiq_ocupacion_es_padre = document.getElementById('d4_312_160_394');

	var vive_madre = 282; //document.getElementById('c4_312_180_392');
	var cond_act_laboral_madre = 283; //document.getElementById('c4_312_182_385');
	var trabajo_es_madre = 284; //document.getElementById('c4_312_185_393');
	var ocupacion_es_madre = 285; //document.getElementById('c4_312_190_394');
	var no_trab_no_busca_madre = 286; //document.getElementById('c4_312_195_395');

	//var etiq_cond_act_laboral_madre = document.getElementById('d4_312_182_385');
	//var etiq_trabajo_es_madre = document.getElementById('d4_312_185_393');
	//var etiq_ocupacion_es_madre = document.getElementById('d4_312_190_394');
	
///////////////////////////////////////
//ACCIONES BLOQUE 2 - DATOS ECON?MICOS
///////////////////////////////////////
	
    var codigo_desconoce = 3;
    remover_opcion(get_preguntas(cond_act_laboral_alumno), codigo_desconoce);

    var codigo_desconoce = 4;
    remover_opcion(get_preguntas(trabajo_es_alumno), codigo_desconoce);
	
    var codigo_desconoce = 2;
    remover_opcion(get_preguntas(ocupacion_es_alumno), codigo_desconoce);

	get_preguntas(cond_act_laboral_alumno).on('change', function(){
		if (this.value!=2) {
			deshabilitar_elemento(this, trabajo_es_alumno);
			deshabilitar_elemento(this, ocupacion_es_alumno);
			deshabilitar_elemento(this, hs_semanales_alumno);
			deshabilitar_elemento(this, rel_trab_carr_alumno);
			sacar_obligatoria(this, trabajo_es_alumno);
			sacar_obligatoria(this, ocupacion_es_alumno);
			sacar_obligatoria(this, hs_semanales_alumno);
			sacar_obligatoria(this, rel_trab_carr_alumno);
		} else {
			habilitar_elemento(this, trabajo_es_alumno);
			habilitar_elemento(this, ocupacion_es_alumno);
			habilitar_elemento(this, hs_semanales_alumno);
			habilitar_elemento(this, rel_trab_carr_alumno);
			//setearlas como obligatorias
			hacer_obligatoria(this, trabajo_es_alumno);
			hacer_obligatoria(this, ocupacion_es_alumno);
			hacer_obligatoria(this, hs_semanales_alumno);
			hacer_obligatoria(this, rel_trab_carr_alumno);
		}
		return false;
	}).change();

///////////////////////////////////////
//INFO DEL PADRE
///////////////////////////////////////

    var cod_pasante = 3;
    remover_opcion(get_preguntas(trabajo_es_padre), cod_pasante);

	get_preguntas(vive_padre).on('change', function(){
		if (this.value == 1) {                              //SI
			//habilitar el campo condicion de actividad ...
			habilitar_elemento(this, cond_act_laboral_padre);
			hacer_obligatoria(this, cond_act_laboral_padre);
		} else {                                            //NO, O DESCONOCE
			deshabilitar_elemento(this, cond_act_laboral_padre);
			sacar_obligatoria(this, cond_act_laboral_padre);            
			deshabilitar_elemento(this, trabajo_es_padre);
			deshabilitar_elemento(this, ocupacion_es_padre);
			deshabilitar_elemento(this, no_trab_no_busca_padre);
		}
		return false;
	}).change();
	
	get_preguntas(cond_act_laboral_padre).on('change', function(){
		var valor = this.value;

		if (valor == 0 && valor != '') { 							//NO TRABAJO Y NO BUSCO TRABAJO
			//habilitar  si no trabaja y busca trabajo
			deshabilitar_elemento(this, trabajo_es_padre);
			deshabilitar_elemento(this, ocupacion_es_padre);
            sacar_obligatoria(this, trabajo_es_padre);
			sacar_obligatoria(this, ocupacion_es_padre);
			//no_trab_no_busca_padre.disabled = false;
			habilitar_elemento(this, no_trab_no_busca_padre);
		} else if ((valor == 1) || (valor == 3)) {	//NO TRABAJ? Y NO BUSCO EN LOS ULTIMOS 30 DIAS O DESCONOCE
			//deshabilitar ?en ese trabajo es? ?esa ocupacion es? y si no trabaja ...
			deshabilitar_elemento(this, trabajo_es_padre);
			deshabilitar_elemento(this, ocupacion_es_padre);
			sacar_obligatoria(this, trabajo_es_padre);
			sacar_obligatoria(this, ocupacion_es_padre);
			deshabilitar_elemento(this, no_trab_no_busca_padre);
		} else if (valor == 2) { 					//TRABAJO AL MENOS UNA HORA
			//habilitar obligatorios ?en ese trabajo es? ?esa ocupacion es?
			habilitar_elemento(this, trabajo_es_padre);
			habilitar_elemento(this, ocupacion_es_padre);
			hacer_obligatoria(this, trabajo_es_padre);
			hacer_obligatoria(this, ocupacion_es_padre);
			//deshabilitar si no trabaja ...
			deshabilitar_elemento(this, no_trab_no_busca_padre);
		} else {
			deshabilitar_elemento(this, trabajo_es_padre);
			deshabilitar_elemento(this, ocupacion_es_padre);
			sacar_obligatoria(this, trabajo_es_padre);
			sacar_obligatoria(this, ocupacion_es_padre);
			deshabilitar_elemento(this, no_trab_no_busca_padre);
         }
		return false;
	}).change();
    
///////////////////////////////////////	
//INFO DE LA MADRE
///////////////////////////////////////	

    var cod_pasante = 3;
    remover_opcion(get_preguntas(trabajo_es_madre), cod_pasante);
 
	get_preguntas(vive_madre).on('change', function(){
		if (this.value == 1) {                              //SI
			//habilitar el campo condicion de actividad ...
			habilitar_elemento(this, cond_act_laboral_madre);
			hacer_obligatoria(this, cond_act_laboral_madre);
		} else {                                            //NO O DESCONOCE
			deshabilitar_elemento(this, cond_act_laboral_madre);
			sacar_obligatoria(this, cond_act_laboral_madre);
			deshabilitar_elemento(this, trabajo_es_madre);
			deshabilitar_elemento(this, ocupacion_es_madre);
			deshabilitar_elemento(this, no_trab_no_busca_madre);
		}
		return false;
	}).change();
   
	get_preguntas(cond_act_laboral_madre).on('change', function(){
		var valor = this.value;

		if (valor == 0 && valor != '') { 								//NO TRABAJO Y NO BUSCO TRABAJO
			//habilitar  si no trabaja y busca trabajo
			deshabilitar_elemento(this, trabajo_es_madre);
			deshabilitar_elemento(this, ocupacion_es_madre);
            sacar_obligatoria(this, trabajo_es_madre);
			sacar_obligatoria(this, ocupacion_es_madre);
			habilitar_elemento(this, no_trab_no_busca_madre);
		} else if ((valor == 1) || (valor == 3)) {		//NO TRABAJ? Y NO BUSCO EN LOS ULTIMOS 30 DIAS O DESCONOCE
            //deshabilitar ?en ese trabajo es? ?esa ocupacion es? y si no trabaja ...
			deshabilitar_elemento(this, trabajo_es_madre);
			deshabilitar_elemento(this, ocupacion_es_madre);
			sacar_obligatoria(this, trabajo_es_madre);
			sacar_obligatoria(this, ocupacion_es_madre);
			deshabilitar_elemento(this, no_trab_no_busca_madre);
		} else if (valor == 2) {						//TRABAJO AL MENOS UNA HORA
			//habilitar obligatorios ?en ese trabajo es? ?esa ocupacion es?
			habilitar_elemento(this, trabajo_es_madre);
			habilitar_elemento(this, ocupacion_es_madre);
			hacer_obligatoria(this, trabajo_es_madre);
			hacer_obligatoria(this, ocupacion_es_madre);
			//deshabilitar si no trabaja ...
			deshabilitar_elemento(this, no_trab_no_busca_madre);
		} else {
			deshabilitar_elemento(this, trabajo_es_madre);
			deshabilitar_elemento(this, ocupacion_es_madre);
			sacar_obligatoria(this, trabajo_es_madre);
			sacar_obligatoria(this, ocupacion_es_madre);		
            deshabilitar_elemento(this, no_trab_no_busca_madre);
		}
		return false;
	}).change();