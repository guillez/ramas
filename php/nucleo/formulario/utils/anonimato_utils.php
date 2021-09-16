<?php
/**
 * Description of anonimato_utils
 *
 * @author demo
 */
class anonimato_utils {
	
	public static $default_digest = 'sha1';
	
    /**
     *Este metodo se llama despues de guardar en la base de datos para darselo
     * al usuario.
     * @param formulario $formulario lleno como fue a la base.
     * @param type $digest el tipo de hash
     * @return type el hash
     */
    static function hashing_de_obj_form(formulario $formulario, $digest = 'sha256'){
        $str = $formulario->to_string();
        kolla::logger()->debug('Hasheado: '.$str);
        return hash($digest, $str, false);
    }

    /**
     *Este levanta de la base de datos una encuesta en base al random y devuelve
     * el hash. Ojo que puede haber varios random iguales. Levanta el primero por ahora.
     * @param type $id_respondido_formulario el id del formulario cabecera de la respuesta 
     * @return type el hash
     */
    static function hashing_de_id_formulario($id_respondido_formulario){
        //recuperar planilla
        $enc = catalogo::consultar(dao_encuestas::instancia(), 'get_respondido_formulario', array($id_respondido_formulario));
        $planilla = catalogo::consultar(dao_encuestas::instancia(), 'get_planilla_id', array($enc[0]['formulario_habilitado']));
        $obtener_el_digest_en_base_al_random = $enc[0]['version_digest'];
        $f = new formulario($planilla, $id_respondido_formulario);
        return self::hashing_de_obj_form($f, $obtener_el_digest_en_base_al_random);	
    }	
	
	static function generar_random(){
		return mt_rand(0, hexdec('7fffffff'));//php no tiene unsigned int. Lo mas seguro es usar la parte positiva solamente
		//para que funcione igual en cualquier plataforma.
	}
}

?>
