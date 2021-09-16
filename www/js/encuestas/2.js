///////////////////////////////////////
//VALORES
///////////////////////////////////////
	var si = 53;

/////////////////////////////////////////////////
//ELEMENTOS BLOQUE 206 (2) - TRAYECTORIA LABORAL
/////////////////////////////////////////////////
	var trabajo_relacionado = 87; // document.getElementById('c2_206_55_209');
	var tiempo = 89; //document.getElementById('c2_206_65_211');
	var caracteristicas_graduados = '90[]';//document.getElementById('c2_206_70_212[]');
	var primer_trabajo_profesional = 91;//document.getElementById('c2_206_75_213');
	var como_inicio = 92;//document.getElementById('c2_206_80_214');
	var como_obtuvo_primer_empleo = 93; //document.getElementById('c2_206_85_215');
	var como_se_entero = 94; //document.getElementById('c2_206_90_216');
	var adaptacion = 95; //document.getElementById('c2_206_95_217');
	var aplico_habilidades = 96; //document.getElementById('c2_206_100_218');	 
	var habilidades_suficientes = 97; // document.getElementById('c2_206_105_219');	
	var aplico_conocimientos = 98; //document.getElementById('c2_206_110_220');
	var conocimientos_suficientes = 99; //document.getElementById('c2_206_115_221');
	var formacion_adquirida = 100; //document.getElementById('c2_206_120_222');
	var oportunidades = 101; //document.getElementById('c2_206_125_223');

/////////////////////////////////////////////////
//ACCIONES BLOQUE 206 (2) - TRAYECTORIA LABORAL
////////////////////////////////////////////////

	get_preguntas(trabajo_relacionado).on('change', function(){
		if (this.value == si) {
			habilitar_elemento(this, tiempo);
			habilitar_elemento(this, caracteristicas_graduados);
			habilitar_elemento(this, primer_trabajo_profesional);
			habilitar_elemento(this, como_inicio);
			habilitar_elemento(this, como_obtuvo_primer_empleo);
			habilitar_elemento(this, como_se_entero);
			habilitar_elemento(this, adaptacion);
			habilitar_elemento(this, aplico_habilidades);
			habilitar_elemento(this, habilidades_suficientes);
			habilitar_elemento(this, aplico_conocimientos);
			habilitar_elemento(this, conocimientos_suficientes);
			habilitar_elemento(this, formacion_adquirida);
			habilitar_elemento(this, oportunidades);
		} else 	{			
			deshabilitar_elemento(this, tiempo);
			deshabilitar_elemento(this, caracteristicas_graduados);
			deshabilitar_elemento(this, primer_trabajo_profesional);
			deshabilitar_elemento(this, como_inicio);
			deshabilitar_elemento(this, como_obtuvo_primer_empleo);
			deshabilitar_elemento(this, como_se_entero);
			deshabilitar_elemento(this, adaptacion);
			deshabilitar_elemento(this, aplico_habilidades);
			deshabilitar_elemento(this, habilidades_suficientes);
			deshabilitar_elemento(this, aplico_conocimientos);
			deshabilitar_elemento(this, conocimientos_suficientes);
			deshabilitar_elemento(this, formacion_adquirida);
			deshabilitar_elemento(this, oportunidades);
		}
		return false;
	}).change();
	
	get_preguntas(caracteristicas_graduados).on('change', function () {
		limitar_selecciones(this, caracteristicas_graduados, 3);
  });
  
	get_preguntas(primer_trabajo_profesional).on('change', function(){
		var independiente = 214;
		var dependencia = 215;
		if (this.value == independiente) {
			habilitar_elemento(this, como_inicio);
			deshabilitar_elemento(this, como_obtuvo_primer_empleo);
		} 
		if (this.value == dependencia) {
			deshabilitar_elemento(this, como_inicio);
			habilitar_elemento(this, como_obtuvo_primer_empleo);
		}
		return false;
	}).change();
	
		
//////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 207 (3) - SITUACION LABORAL ACTUAL
//////////////////////////////////////////////////////
	var condicion_ocupacional_actual = 102; //document.getElementById('c2_207_130_224');

//////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 200 (4) - PARA LOS OCUPADOS
//////////////////////////////////////////////////////
	var cantidad_ocupaciones = 103; //document.getElementById('c2_200_135_225');
	var primer_trabajo_etiq = 104;
	var segundo_trabajo_etiq = 117;
	var tercer_trabajo_etiq = 130;

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 202 (6) - PARA LOS DESOCUPADOS
////////////////////////////////////////////////////////
	var cuanto_que_no_trabaja = 143; //document.getElementById('c2_202_335_241');
	var motivos_no_consigue = '144[]'; //document.getElementById('c2_202_340_242[]');	
	
/////////////////////////////////////////////////////
//ACCIONES BLOQUE 207 (3) - SITUACION LABORAL ACTUAL 
/////////////////////////////////////////////////////
	var trabaja = 234;
	var trabaja_y_busca = 235;
	var no_trabaja_y_busca = 236;
	var no_trabaja_no_busca = 237;


	function habilitacion_bloque_repetido(fuente, pregunta_inicio, pregunta_fin, estado) 
	{
		for (var pregunta=pregunta_inicio; pregunta<=pregunta_fin; pregunta++) {
			if (estado) {
				habilitar_elemento(fuente, pregunta);
			} else {
				deshabilitar_elemento(fuente, pregunta);
			}
		}	
	}
	
	get_preguntas(condicion_ocupacional_actual).on('change', function(){

		if (this.value == trabaja || this.value == trabaja_y_busca) {
			//habilita bloque para ocupados
			//habilitar_elemento(this, cantidad_ocupaciones); 
			//habilita bloque propias de cada una de sus ocupaciones
			//habilitacion_bloque_repetido(this, 104, 116, true);
			//habilitacion_bloque_repetido(this, 118, 129, true);
			//habilitacion_bloque_repetido(this, 131, 142, true);
			habilitar_bloque(this, 16);
			habilitar_bloque(this, 17);
			
			//deshabilita bloque para los desocupados
			deshabilitar_elemento(this, cuanto_que_no_trabaja); 
			deshabilitar_elemento(this, motivos_no_consigue); 

		} else if ((this.value == no_trabaja_y_busca) || (this.value == no_trabaja_no_busca)) {
			//deshabilita bloque para ocupados
			//deshabilitar_elemento(this, cantidad_ocupaciones); 
			//deshabilita bloque propias de cada una de sus ocupaciones
			deshabilitar_bloque(this, 16);
			deshabilitar_bloque(this, 17);
			
			//habilitacion_bloque_repetido(this, 104, 116, false);
			//habilitacion_bloque_repetido(this, 118, 129, false);
			//habilitacion_bloque_repetido(this, 131, 142, false);
			//habilita bloque para los desocupados
			habilitar_elemento(this, cuanto_que_no_trabaja); 
			habilitar_elemento(this, motivos_no_consigue);
		}
		return false;
	}).change();
	

/////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 203 (7) - OTROS ESTUDIOS DE GRADO
/////////////////////////////////////////////////////
	var realizo_otros = 145; //document.getElementById('c2_203_345_243');
	var nombre_carrera = 146; //document.getElementById('c2_203_350_244');
	var tipo_institucion = 147; //document.getElementById('c2_203_355_245');
	var fin_carrera = 148; //document.getElementById('c2_203_360_246');
	
////////////////////////////////////////////////////
//ACCIONES BLOQUE 203 (7) - OTROS ESTUDIOS DE GRADO 
///////////////////////////////////////////////////

	get_preguntas(realizo_otros).on('change', function(){
		if (this.value == si) {
			habilitar_elemento(this, nombre_carrera);
			habilitar_elemento(this, tipo_institucion);
			habilitar_elemento(this, fin_carrera);
		} else {
			deshabilitar_elemento(this, nombre_carrera);
			deshabilitar_elemento(this, tipo_institucion);
			deshabilitar_elemento(this, fin_carrera);
		}
		return false;
	}).change();
	
	
////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 204 (8) -  ESTUDIOS DE POSGRADO
////////////////////////////////////////////////////////
	var realizando_posgrado = 149; //document.getElementById('c2_204_365_247');
	var interesa = 150; //document.getElementById('c2_204_370_248');
	var tipo_carrera = 152; //document.getElementById('c2_204_380_250');
	var institucion = 153; //document.getElementById('c2_204_385_251');
	var denominacion = 154; //document.getElementById('c2_204_390_252');
	var nombre_institucion = 155; //document.getElementById('c2_204_395_253');
	var acreditado = 156; //document.getElementById('c2_204_400_254');
	var costea = 157; //document.getElementById('c2_204_405_255');
	
	get_preguntas(realizando_posgrado).on('change', function(){
		if (this.value == si) {
			deshabilitar_elemento(this, interesa);
			habilitar_elemento(this, tipo_carrera);
			habilitar_elemento(this, institucion);
			habilitar_elemento(this, denominacion);
			habilitar_elemento(this, nombre_institucion);
			habilitar_elemento(this, acreditado);
			habilitar_elemento(this, costea);
		} else {
			habilitar_elemento(this, interesa);
			deshabilitar_elemento(this, tipo_carrera);
			deshabilitar_elemento(this, institucion);
			deshabilitar_elemento(this, denominacion);
			deshabilitar_elemento(this, nombre_institucion);
			deshabilitar_elemento(this, acreditado);
			deshabilitar_elemento(this, costea);
		}
		return false;
	}).change();
