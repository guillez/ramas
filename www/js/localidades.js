var f_localidad = new Object;
f_localidad.input_actual = -1;
f_localidad.input_desc_actual = -1;

f_localidad.get_localidad = function(id_input_rta, id_input_desc){
	this.input_actual = id_input_rta;
	this.input_desc_actual = id_input_desc;
	$('#form_localidades').modal('show');
	
	this.form_ = $('#form_localidades');
	this.select_loc =	this.form_.find('#fl_localidad');
	this.select_dpt =	this.form_.find('#fl_departamento');
	this.select_pai =	this.form_.find('#fl_pais');
	this.select_prv =	this.form_.find('#fl_provincia');
	
	//Si ya tiene algo seteado le dejo todo como esta... lo mas probable es que
	//se cambie la localidad como mucho.
	var s = this.select_pai[0]; //IE
	var val = s.options[s.selectedIndex].value; //IE
	if(val >= 0){
		return;
	}else{
			this.load_paises();
		}
};

f_localidad.guardar = function()
{
	var sel = this.form_.find('#fl_localidad')[0];
	if(sel.selectedIndex < 0 || sel.value < 0){ //TODO: no tiene seleccion
            return;
	}
	this.loc_act = sel.value;
	this.input_actual.value = sel.value;
        // Se dispara el onchange del hidden
        $(this.input_actual).change();
        
	this.input_desc_actual.value = sel.options[sel.selectedIndex].text;
        $(this.input_desc_actual).change();
        this.form_.find('#fl_pais')[0].selectedIndex = 0;
        this.form_.find('#fl_pais')[0].value = -1;
        this.form_.find('#fl_provincia')[0].selectedIndex = 0;
        this.form_.find('#fl_provincia')[0].value = -1;
        this.form_.find('#fl_departamento')[0].selectedIndex = 0;
        this.form_.find('#fl_departamento')[0].value = -1;
        this.form_.find('#fl_localidad')[0].selectedIndex = 0;
        this.form_.find('#fl_localidad')[0].value = -1;
        this.form_.find('#fl_pais').change();
        
	$('#form_localidades').modal('hide');
};

f_localidad.cancelar = function() {
    $('#form_localidades').modal('hide');
};

f_localidad.load_paises = function(){
	var sel = $('#fl_pais')[0];
	
	this.select_pai.attr('disabled', 'true');	
	this.select_prv.attr('disabled', 'true');
	this.select_dpt.attr('disabled', 'true');
	this.select_loc.attr('disabled', 'true');
	this.fill_combo(sel, 'paises','');
};
f_localidad.load_provincias = function(id){
	//id = $('#fl_pais')[0].value;
	select = $('#fl_provincia')[0];
	this.select_prv.attr('disabled', 'true');
	this.select_dpt.attr('disabled', 'true');
	this.select_loc.attr('disabled', 'true');
	this.fill_combo(select, 'provincias', id);
};
f_localidad.load_departamentos = function(id){
	select = $('#fl_departamento')[0];
	this.select_dpt.attr('disabled', 'true');
	this.select_loc.attr('disabled', 'true');
	this.fill_combo(select, 'departamentos', id);
};
f_localidad.load_localidades = function(id){
	select = $('#fl_localidad')[0];
	this.select_loc.attr('disabled', 'true');
	this.fill_combo(select, 'localidades', id);
};
	
f_localidad.fill_combo = function (select, name, cc_param){
	$.ajax({
		//url: window.location.href, //se podrian sacar los parametros
		url: post_url, //se incluye en el formulario.
		
		data: {get_ajax: name, cc_par: cc_param},
		type: 'POST',
		dataType: 'json',
		success:function(result){
			select.options.length = 0;
			select.options[0] = new Option('--Seleccionar--', -1);
		
			for (var i=0;i<result.length;i++){ 
				select.options[i+1] = new Option(result[i].nombre, result[i].clave);
			//	str += '<option value='+result[i].clave+'>' + result[i].nombre + '</option>';
			}
			//alert(str);
			//select.innerHTML = str;
			select.removeAttribute('disabled');
			//$("#div1").html(result);
		},
		error:function(error){
			alert('error en la operacion'+error.responseText);
		} 
	});
};