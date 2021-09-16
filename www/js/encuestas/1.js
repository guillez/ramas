///////////////////////////////////////
//VALORES
///////////////////////////////////////
	var si = 53;
	var nolee = 29;

///////////////////////////////////////
//ELEMENTOS BLOQUE 4 - HÁBITOS
///////////////////////////////////////
	var habito_lectura = 19;
	var tipo_lectura = 20;
	
///////////////////////////////////////
//ELEMENTOS BLOQUE 6 - DATOS LABORALES
///////////////////////////////////////
	var condicion_laboral_actual = 25;	
	var busca_trabajo = 26;
	var caract_graduados = 28;
	var otras_caracteristicas = 29;
	
///////////////////////////////////////
//ELEMENTOS BLOQUE 7 - TRABAJO ACTUAL
///////////////////////////////////////	
	var recibe_remuneracion = 33;
	var realiza_aportes = 34;
	var tipo_relacion = 35;
	var tipo_contratacion = 36;
	var hs_semanales = 37;
	var satisfecho = 38;
	var entidad = 39;
	var por_recibirse = 40;
	
///////////////////////////////////////
//ELEMENTOS BLOQUE 8 - SI NO TRABAJA
///////////////////////////////////////	
	var razon_no_trabaja = 41;

///////////////////////////////////////
//ELEMENTOS BLOQUE 9 - EN CASO DE BUSCAR TRABAJO
///////////////////////////////////////	
	var sector = 42;
	var actividad = 43;
	var exigencias = 44;

///////////////////////////////////////
//ELEMENTOS BLOQUE 12 - OTROS ESTUDIOS 
///////////////////////////////////////	
	var otros_estudios_superior = 63;
	var tipo_institucion = 64;
	var nombre_carrera = 65;
	var finalizo = 66;
	var piensa_seguir = 73; 
	var que_le_gustaria = 74;
	var donde = 75;
	var por_que_otra = 76;
	var interesado_formacion_continua = 77;
	
	$(document).ready(function(){
		
///////////////////////////////////////
//ACCIONES BLOQUE 4 - HÁBITOS
///////////////////////////////////////
	
	get_preguntas(habito_lectura).on('change', function(){
		if (this.value == nolee) {
			deshabilitar_elemento(this, tipo_lectura);
		} else 	{
			habilitar_elemento(this, tipo_lectura);
		}
		return false;
	}).change();


///////////////////////////////////////
//ACCIONES BLOQUE 6 - DATOS LABORALES
///////////////////////////////////////	

	get_preguntas(condicion_laboral_actual).on('change', function(){
		var notrabaja = 52;
		if (this.value==notrabaja) {
			//deshabilitar todos los elementos del bloque 7 (trabajo actual)
			deshabilitar_elemento(this, recibe_remuneracion);
			deshabilitar_elemento(this, realiza_aportes);
			deshabilitar_elemento(this, tipo_relacion);
			deshabilitar_elemento(this, tipo_contratacion);
			deshabilitar_elemento(this, hs_semanales);
			deshabilitar_elemento(this, satisfecho);
			deshabilitar_elemento(this, entidad);
			deshabilitar_elemento(this, por_recibirse);
			//habilitar todos los elementos del bloque 8 (si no trabaja)
			habilitar_elemento(this, razon_no_trabaja);	
		} else {
			//habilitar los elementos del bloque 7
			habilitar_elemento(this, recibe_remuneracion);
			habilitar_elemento(this, realiza_aportes);
			habilitar_elemento(this, tipo_relacion);
			habilitar_elemento(this, tipo_contratacion);
			habilitar_elemento(this, hs_semanales);
			habilitar_elemento(this, satisfecho);
			habilitar_elemento(this, entidad);
			habilitar_elemento(this, por_recibirse);
			//deshabilitar todos los elementos del bloque 8 (si no trabaja)
			deshabilitar_elemento(this, razon_no_trabaja);
		}
		return false;
	}).change();

	get_preguntas(busca_trabajo).on('change', function(){
		if (this.value==si) {//habilitar todos los elementos del bloque 9 (en caso de buscar trabajo)
			habilitar_elemento(this, sector);
			habilitar_elemento(this, actividad);
			habilitar_elemento(this, exigencias);
		} else {//deshabilitar los elementos del bloque 9
			deshabilitar_elemento(this, sector);
			deshabilitar_elemento(this, actividad);
			deshabilitar_elemento(this, exigencias);
		}
			return false;
	}).change();
	
	get_preguntas(caract_graduados).on('change', function(){
		var otras = 72;
		if (this.value==otras) {//habilitar pregunta "cuales capacidades"
			habilitar_elemento(this, otras_caracteristicas);
		} else {//deshabilitar pregunta "cuales capacidades"
			deshabilitar_elemento(this, otras_caracteristicas);
		}
		return false;
	}).change();
	
	
///////////////////////////////////////
//ACCIONES BLOQUE 12 - OTROS ESTUDIOS 
///////////////////////////////////////	
   	get_preguntas(otros_estudios_superior).on('change', function(){
		if (this.value==si) {//habilita
			habilitar_elemento(this, tipo_institucion);
			habilitar_elemento(this, nombre_carrera);
			habilitar_elemento(this, finalizo);
		} else {//deshabilita
			deshabilitar_elemento(this, tipo_institucion);
			deshabilitar_elemento(this, nombre_carrera);
			deshabilitar_elemento(this, finalizo);
		}
		return false;
	}).change();
	
   	get_preguntas(piensa_seguir).on('change', function(){	
		if (this.value==si) {//habilita
			habilitar_elemento(this, que_le_gustaria);
			habilitar_elemento(this, donde);
			habilitar_elemento(this, por_que_otra);
			habilitar_elemento(this, interesado_formacion_continua);
		} else {//deshabilita
			deshabilitar_elemento(this, que_le_gustaria);
			deshabilitar_elemento(this, donde);
			deshabilitar_elemento(this, por_que_otra);
			deshabilitar_elemento(this, interesado_formacion_continua);
		}
		return false;
	}).change();

});

	
