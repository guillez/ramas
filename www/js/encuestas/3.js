	
	////////////////////////////////////////////////////////
	//ELEMENTOS BLOQUE 302 (2) - TRAYECTORIA LABORAL ACTUAL
	///////////////////////////////////////////////////////
		var relacion_ocupacion = 175; //		document.getElementById('c3_302_45_309');
		var aplico_conocimientos = 200; //		document.getElementById('c3_302_170_331');
		var conocimientos_suficientes = 201; //document.getElementById('c3_302_175_332');
		var aplico_habilidades = 202; //		document.getElementById('c3_302_180_333');
		var habilidades_suficientes = 203; //	document.getElementById('c3_302_185_334');
		
		//actividad que realiza
		var actividad_ocupacion = '176[]'; //document.getElementById('c3_302_50_310[]');
		var actividad_cuales = 	177; //	document.getElementById('c3_302_55_311');

		//en relacion de dependencia
		var tipo_relacion_laboral = 178; //	document.getElementById('c3_302_60_312');
		var tipo_organizacion = 180;//	document.getElementById('c3_302_70_314');
		var otros_cuales = 181; //		document.getElementById('c3_302_75_315');
		var publico_cual = 182; //		document.getElementById('c3_302_80_316');
		var otros_publico_cuales = 183;//	document.getElementById('c3_302_85_315');
		var personas_trabajan = 184; //	document.getElementById('c3_302_90_317');

		//
		var categoria_ocupacional = 186;//	document.getElementById('c3_302_100_318');
		var otro_cual = 187; //		document.getElementById('c3_302_105_319');

		//
		var misma_categoria = 	188; //	document.getElementById('c3_302_110_320');
		var categoria_inicial = 189; //	document.getElementById('c3_302_115_321');
		var cual_categoria = 190; //	document.getElementById('c3_302_120_319');

		//
		var rama_actividad = 191; //		document.getElementById('c3_302_125_322');
		var rama_otra =	192; //			document.getElementById('c3_302_130_323');

	/////////////////////////////////////////////////
	//ACCIONES BLOQUE 302 (2) - TRAYECTORIA LABORAL
	////////////////////////////////////////////////
		
		get_preguntas(relacion_ocupacion).on('change', function(){
			var total = 238;
			var parcial = 239;

			if ((this.value==total) || (this.value==parcial)) {
				habilitar_elemento(this, aplico_conocimientos);
				habilitar_elemento(this, conocimientos_suficientes);
				habilitar_elemento(this, aplico_habilidades);
				habilitar_elemento(this, habilidades_suficientes);
			} else {
				deshabilitar_elemento(this, aplico_conocimientos);
				deshabilitar_elemento(this, conocimientos_suficientes);
				deshabilitar_elemento(this, aplico_habilidades);
				deshabilitar_elemento(this, habilidades_suficientes);
			}
			return false;
		}).change();
		
		
		get_preguntas(actividad_ocupacion).on('change', function(){
			var otros = 244;
			var es_otras = false;
			for(var i=0; i<this.options.length; i++) {
				var elto = this.options[i];
				if (elto.selected && (elto.value == otros)) {
					es_otras = true;
				}			
			}

			if (!es_otras) {
				deshabilitar_elemento(this, actividad_cuales);
			} else {
				habilitar_elemento(this, actividad_cuales);
			}
			return false;
		}).change();
		
		get_preguntas(tipo_relacion_laboral).on('change', function(){
			var relacion  = this.value;
			if ((relacion==3061) || (relacion==3007) || (relacion==3062)) {
				habilitar_elemento(this, tipo_organizacion);
				habilitar_elemento(this, otros_cuales);
				habilitar_elemento(this, publico_cual);
				habilitar_elemento(this, otros_publico_cuales);
				habilitar_elemento(this, personas_trabajan);
			} else {
				deshabilitar_elemento(this, tipo_organizacion);
				deshabilitar_elemento(this, otros_cuales);
				deshabilitar_elemento(this, publico_cual);
				deshabilitar_elemento(this, otros_publico_cuales);
				deshabilitar_elemento(this, personas_trabajan);
			}
			return false;
		}).change();
		
		
		get_preguntas(tipo_organizacion).on('change', function(){ 
			var publico= 250;		
			var otros = 259;
				
			if (this.value == publico) {
				habilitar_elemento(this, publico_cual);
				deshabilitar_elemento(this, otros_cuales);
			} else {
				deshabilitar_elemento(this, publico_cual);
				deshabilitar_elemento(this, otros_publico_cuales);
				if (this.value == otros) {
					habilitar_elemento(this, otros_cuales);				
				} else {
					deshabilitar_elemento(this, otros_cuales);
				}
			}
			return false;
		}).change();
		
		get_preguntas(publico_cual).on('change', function(){
			var otros = 3011;
				
			if (this.value == otros) {
				habilitar_elemento(this, otros_publico_cuales);
				
			} else {
				deshabilitar_elemento(this, otros_publico_cuales);
			}
			return false;
		}).change();
		
		
		get_preguntas(categoria_ocupacional).on('change', function(){ 
			var otro = 79;
			
			if (this.value!=otro) {
				deshabilitar_elemento(this, otro_cual);			
			} else {
				habilitar_elemento(this, otro_cual);
			}
			return false;
		}).change();
		

		get_preguntas(misma_categoria).on('change', function(){ 
			var si = 53;	
			var otro = 79;
		
			if (this.value==si) {
				deshabilitar_elemento(this, categoria_inicial);			
				deshabilitar_elemento(this, cual_categoria);
			} else {
				var cat_ini = get_pregunta_encuesta(this.id, categoria_inicial);
				habilitar_elemento(this, categoria_inicial);
				if (cat_ini.value==otro) {
					habilitar_elemento(this, cual_categoria);
				}
			}
			return false;
		}).change();
		
		get_preguntas(categoria_inicial).on('change', function(){
			var otro = 79;
			
			if (this.value!=otro) {
				deshabilitar_elemento(this, cual_categoria);			
			} else {
				habilitar_elemento(this, cual_categoria);
			}
			return false;
		}).change();
		
		get_preguntas(rama_actividad).on('change', function(){ 
			var otra= 24;
			
			if (this.value==otra) {
				habilitar_elemento(this, rama_otra);			
			} else {
				deshabilitar_elemento(this, rama_otra);
			}
			return false;
		}).change();
		

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 303 (3) - TRAYECTORIA LABORAL PASADA
////////////////////////////////////////////////////////
	var otros_trabajos = 204; //	document.getElementById('c3_303_190_335');
	var cuantos = 205; // 			document.getElementById('c3_303_195_336');
	var categoria = 206;//			document.getElementById('c3_303_200_337');
	var ingreso = 207; //			document.getElementById('c3_303_205_338');
	var carga_horaria = 208; // 	document.getElementById('c3_303_210_339');
	var causas_cambio = '209[]'; //	document.getElementById('c3_303_215_340[]');	

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 303 (3) - TRAYECTORIA LABORAL PASADA
////////////////////////////////////////////////////////
	
	get_preguntas(otros_trabajos).on('change', function(){ 	
		if (this.value==53) {
			habilitar_elemento(this, cuantos);
			habilitar_elemento(this, categoria);
			habilitar_elemento(this, ingreso);
			habilitar_elemento(this, carga_horaria);
			habilitar_elemento(this, causas_cambio);
		} else {
			deshabilitar_elemento(this, cuantos);
			deshabilitar_elemento(this, categoria);
			deshabilitar_elemento(this, ingreso);
			deshabilitar_elemento(this, carga_horaria);
			deshabilitar_elemento(this, causas_cambio);
		}
		return false;
	}).change();
	

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 304 (4) - PARA LOS DESOCUPADOS
////////////////////////////////////////////////////////
	var motivos_no_consigue = '211[]'; //	document.getElementById('c3_304_225_342[]');
	var no_consigue_cuales = 212; //		document.getElementById('c3_304_226_315');

	get_preguntas(motivos_no_consigue).on('change', function(){ 	
		var otros_motivos = 298;
		var es_otros = false;
		for (var i=0; i< this.options.length; i++) {
			var elto = this.options[i];
			if (elto.selected && (elto.value == otros_motivos)) {
				es_otros = true;
			}			
		}

		if (!es_otros) {
			deshabilitar_elemento(this, no_consigue_cuales);
		} else {
			habilitar_elemento(this, no_consigue_cuales);
		}
		return false;
	}).change();
	

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 305 (5) - NO TRABAJAN Y NO BUSCAN
////////////////////////////////////////////////////////
	var motivos_no_busca = '213[]'; //	document.getElementById('c3_305_230_343[]');
	var no_busca_cuales = 	214; //document.getElementById('c3_305_235_315');

	get_preguntas(motivos_no_busca).on('change', function(){ 	
		var otros = 259;
		var es_otros = false;
		for(var i=0; i<this.options.length; i++) {
			var elto = this.options[i];
			if (elto.selected && (elto.value == otros)) {
				es_otros = true;
			}
		}

		if (!es_otros) {
			deshabilitar_elemento(this, no_busca_cuales);
		} else {
			habilitar_elemento(this, no_busca_cuales);
		}
		return false;
	}).change();


////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 306 (6) - OTROS ESTUDIOS DE GRADO
////////////////////////////////////////////////////////

	var otros_estudios = 215; //	document.getElementById('c3_306_240_344');
	var nombre_carrera_grado = 217;	//document.getElementById('c3_306_250_346');
	var tipo_institucion = 218; //	document.getElementById('c3_306_255_347');
	var otros_estudios_cuales =	219; //document.getElementById('c3_306_260_315');
	var estado_grado = 220; //	document.getElementById('c3_306_265_348');
	
	get_preguntas(otros_estudios).on('change', function(){ 
		
		var si = 53;
		if (this.value == si) {
			habilitar_elemento(this, nombre_carrera_grado);
			habilitar_elemento(this, tipo_institucion);
			habilitar_elemento(this, estado_grado);
			//if (tipo_institucion.value == 259) 
				//habilitar_elemento(this, otros_estudios_cuales);
		} else {
			deshabilitar_elemento(this, nombre_carrera_grado);
			deshabilitar_elemento(this, tipo_institucion);
			deshabilitar_elemento(this, otros_estudios_cuales);
			deshabilitar_elemento(this, estado_grado);
		}
		return false;
	}).change();


	get_preguntas(tipo_institucion).on('change', function(){ 
		if (this.value == 259) {
			habilitar_elemento(this, otros_estudios_cuales);
		} else {
			deshabilitar_elemento(this, otros_estudios_cuales);
		}
		return false;
	}).change();

	

////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 307 (7) - ESTUDIOS DE POSGRADO
////////////////////////////////////////////////////////
	var estudios_posgrado =	221; //			document.getElementById('c3_307_270_349');
	//si contesta si
	var tipo_carrera =	223; //				document.getElementById('c3_307_280_350');
	var institucion_posgrado = 224; //			document.getElementById('c3_307_285_351');
	var denominacion_titulo = 225; //			document.getElementById('c3_307_290_352');
	var nombre_institucion_posgrado	= 226; //	document.getElementById('c3_307_295_353');
	var posgrado_estado	= 227; //				document.getElementById('c3_307_300_354');
	var abandono = 3044;
	var motivos_abandono_posgrado	= '228[]'; //	document.getElementById('c3_307_305_355[]');
	var motivos_otros_posgrado	= 229; //		document.getElementById('c3_307_310_315');
	var modalidad_posgrado	= 230; //			document.getElementById('c3_307_315_356');
	var acreditada		= 231; //				document.getElementById('c3_307_320_357');
	var costea_posgrado	= 232; //				document.getElementById('c3_307_325_358');
	var costea_otros	= 233; //				document.getElementById('c3_307_330_315');	
	//si contesta no
	var interesa_posgrado	= '235[]'; //			document.getElementById('c3_307_340_360[]');
	var area_interes 	= 236; //				document.getElementById('c3_307_345_361');
	
	get_preguntas(estudios_posgrado).on('change', function(){ 
		var si = 53;
		var otros = 259;
		if (this.value == si) {
			habilitar_elemento(this, tipo_carrera);
			habilitar_elemento(this, institucion_posgrado);
			habilitar_elemento(this, denominacion_titulo);
			habilitar_elemento(this, nombre_institucion_posgrado);
			habilitar_elemento(this, posgrado_estado);
			/*if (posgrado_estado.value == abandono)
			{
				habilitar_elemento(this, motivos_abandono_posgrado);
				if (motivos_abandono_posgrado.value == otros)
					habilitar_elemento(this, motivos_otros_posgrado); 
			}*/
			habilitar_elemento(this, modalidad_posgrado);
			habilitar_elemento(this, acreditada);
			habilitar_elemento(this, costea_posgrado);
			/*if (costea_posgrado.value == otros)
				habilitar_elemento(this, costea_otros);*/
			deshabilitar_elemento(this, interesa_posgrado); //deshab_list_mult(4)
			deshabilitar_elemento(this, area_interes);
		} else {
			deshabilitar_elemento(this, tipo_carrera);
			deshabilitar_elemento(this, institucion_posgrado);
			deshabilitar_elemento(this, denominacion_titulo);
			deshabilitar_elemento(this, nombre_institucion_posgrado);
			deshabilitar_elemento(this, posgrado_estado);
			deshabilitar_elemento(this, motivos_abandono_posgrado);
			//deshabilitar_lista_multiple(motivos_abandono_posgrado,5);
			deshabilitar_elemento(this, motivos_otros_posgrado);
			deshabilitar_elemento(this, modalidad_posgrado);
			deshabilitar_elemento(this, acreditada);
			deshabilitar_elemento(this, costea_posgrado);
			deshabilitar_elemento(this, costea_otros);
			
			habilitar_elemento(this, interesa_posgrado);
			
			/*var interesa = false;
			var elto;
			for(var i=0; i<interesa_posgrado.options.length; i++)
			{
				elto = interesa_posgrado.options[i];
				if (elto.selected && (elto.value != 305))
				{
					interesa = true;
				}			
			}
			if (interesa)
			{
				if (area_interes.disabled) habilitar_elemento(this, area_interes);
			}*/
		}
		return false;
	}).change();


	get_preguntas(posgrado_estado).on('change', function(){ 
		if (this.value == abandono) {
			habilitar_elemento(this, motivos_abandono_posgrado);			
		} else {
			deshabilitar_elemento(this, motivos_abandono_posgrado);
			deshabilitar_elemento(this, motivos_otros_posgrado);
		}
	}).change();
	
	get_preguntas(motivos_abandono_posgrado).on('change', function(){ 
		var otros = 259;	
		var es_otros = false;
		for(var i=0; i<this.options.length; i++) {
			var elto = this.options[i];
			if (elto.selected && (elto.value == otros)) {
				es_otros = true;
			}			
		}

		if (!es_otros) {
			deshabilitar_elemento(this, motivos_otros_posgrado);
		} else {
			habilitar_elemento(this, motivos_otros_posgrado);
		}
		return false;
	}).change();

	get_preguntas(costea_posgrado).on('change', function(){ 
		var otros = 259;	
		if (this.value == otros) {
			habilitar_elemento(this, costea_otros);
		} else {
			deshabilitar_elemento(this, costea_otros);
		}	

		return false;
	}).change();
	
	get_preguntas(interesa_posgrado).on('change', function(){ 
		var interesa = false;
		var elto;
		for(var i=0; i<this.options.length; i++) {
			elto = this.options[i];
			if (elto.selected && (elto.value != 305)) {
				interesa = true;
			}
		}
		if (interesa) {
			habilitar_elemento(this, area_interes);
			this[3].selected = false;
		} else {
			deshabilitar_elemento(this, area_interes);
			this[0].selected = false;
			this[1].selected = false;
			this[2].selected = false;
		}
		return false;
	}).change();
	
////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 308 (8) - OTRAS ACTIVIDADES
////////////////////////////////////////////////////////
	var realiza_actividades = 237; //	document.getElementById('c3_308_350_362');
	var cuales_actividades = '238[]'; //	document.getElementById('c3_308_355_363[]');
	var institucion = '239[]'; //	document.getElementById('c3_308_360_364[]');
	
	get_preguntas(realiza_actividades).on('change', function(){ 
		var si = 53;
		if (this.value == si) {
			habilitar_elemento(this, cuales_actividades);
			habilitar_elemento(this, institucion);
		} else {
			deshabilitar_elemento(this, cuales_actividades);
			deshabilitar_elemento(this, institucion);
		}
		return false;
	}).change();
	
////////////////////////////////////////////////////////
//ELEMENTOS BLOQUE 310 (10) - VINCULACION CON LA UNIV.
////////////////////////////////////////////////////////
	var deseos_universidad = '243[]'; //document.getElementById('c3_310_380_368[]');
	var otros_deseos_cuales = 244; //document.getElementById('c3_310_385_315');
	
	get_preguntas(deseos_universidad).on('change', function(){ 
		var otros = 259;
		var es_otros = false;
		for(var i=0; i<this.options.length; i++) {
			var elto = this.options[i];
			if (elto.selected && (elto.value == otros)) {
				es_otros = true;
			}			
		}	
		
		if (es_otros) {
			habilitar_elemento(this, otros_deseos_cuales);
		} else {
			deshabilitar_elemento(this, otros_deseos_cuales);
		}
		return false;
	}).change();
