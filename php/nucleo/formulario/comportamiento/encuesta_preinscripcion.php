<?php

class encuesta_preinscripcion
{
    //Pregunta: ¿Es el mismo que tiene durante el período de clases?
    const COMBO_DISPARADOR  = 'c_pk_678';
    const RESPUESTA_SI      = '518';
    
    //Respuestas del Bloque: Domicilio durante el período de clases
    const CALLE_DOM_CLASES          = 'c_pk_668';
    const NUMERO_DOM_CLASES         = 'c_pk_669';
    const PISO_DOM_CLASES           = 'c_pk_670';
    const DEPARTAMENTO_DOM_CLASES   = 'c_pk_671';
    const UNIDAD_DOM_CLASES         = 'c_pk_672';
    const LOCALIDAD_DOM_CLASES      = 'c_pk_673';
    const LOCALIDAD_DESC_DOM_CLASES = 'desc_c_pk_673';
    const CODIGO_POSTAL_DOM_CLASES  = 'c_pk_674';
    const TELEFONO_DOM_CLASES       = 'c_pk_675';
    
    //Respuestas del Bloque: Domicilio Real (Donde vive fuera del período de clases)
    const CALLE_DOM_REAL          = 'c_pk_679';
    const NUMERO_DOM_REAL         = 'c_pk_680';
    const PISO_DOM_REAL           = 'c_pk_681';
    const DEPARTAMENTO_DOM_REAL   = 'c_pk_682';
    const UNIDAD_DOM_REAL         = 'c_pk_683';
    const LOCALIDAD_DOM_REAL      = 'c_pk_684';
    const LOCALIDAD_DESC_DOM_REAL = 'desc_c_pk_684';
    const CODIGO_POSTAL_DOM_REAL  = 'c_pk_685';
    const TELEFONO_DOM_REAL       = 'c_pk_686';
    
    function get_javascript()
    {
        //Pregunta disparadora
        $combo_disparador  = self::COMBO_DISPARADOR;
        $respuesta_si      = self::RESPUESTA_SI;
        
        //Respuestas del Domicilio de Clases
        $calle_dom_clases          = self::CALLE_DOM_CLASES;
        $numero_dom_clases         = self::NUMERO_DOM_CLASES;
        $piso_dom_clases           = self::PISO_DOM_CLASES;
        $departamento_dom_clases   = self::DEPARTAMENTO_DOM_CLASES;
        $unidad_dom_clases         = self::UNIDAD_DOM_CLASES;
        $localidad_dom_clases      = self::LOCALIDAD_DOM_CLASES;
        $localidad_desc_dom_clases = self::LOCALIDAD_DESC_DOM_CLASES;
        $codigo_postal_dom_clases  = self::CODIGO_POSTAL_DOM_CLASES;
        $telefono_dom_clases       = self::TELEFONO_DOM_CLASES;
        
        //Respuestas del Domicilio Real
        $calle_dom_real          = self::CALLE_DOM_REAL;
        $numero_dom_real         = self::NUMERO_DOM_REAL;
        $piso_dom_real           = self::PISO_DOM_REAL;
        $departamento_dom_real   = self::DEPARTAMENTO_DOM_REAL;
        $unidad_dom_real         = self::UNIDAD_DOM_REAL;
        $localidad_dom_real      = self::LOCALIDAD_DOM_REAL;
        $localidad_desc_dom_real = self::LOCALIDAD_DESC_DOM_REAL;
        $codigo_postal_dom_real  = self::CODIGO_POSTAL_DOM_REAL;
        $telefono_dom_real       = self::TELEFONO_DOM_REAL;
        
        echo "  <script>
                    $('#$combo_disparador').change(function() {
                        if ($(this).val() == '$respuesta_si') {
                            $('#$calle_dom_real').val($('#$calle_dom_clases').val());
                            $('#$numero_dom_real').val($('#$numero_dom_clases').val());
                            $('#$piso_dom_real').val($('#$piso_dom_clases').val());
                            $('#$departamento_dom_real').val($('#$departamento_dom_clases').val());
                            $('#$unidad_dom_real').val($('#$unidad_dom_clases').val());
                            $('#$localidad_dom_real').val($('#$localidad_dom_clases').val());
                            $('#$localidad_desc_dom_real').val($('#$localidad_desc_dom_clases').val());
                            
                            var codigo_postal_dom_clases = $('#$codigo_postal_dom_clases').find('option:selected').val();
                            var codigo_postal_dom_clases_text = $('#$codigo_postal_dom_clases').find('option:selected').text();
                            var html_cp = '<option value=' + codigo_postal_dom_clases + '>' + codigo_postal_dom_clases_text + '</option>';
                            $('#$codigo_postal_dom_real').html(html_cp).fadeIn();
                                
                            $('#$telefono_dom_real').val($('#$telefono_dom_clases').val());
                        } else {
                            $('#$calle_dom_real').val('');
                            $('#$numero_dom_real').val('');
                            $('#$piso_dom_real').val('');
                            $('#$departamento_dom_real').val('');
                            $('#$unidad_dom_real').val('');
                            $('#$localidad_dom_real').val('');
                            $('#$localidad_desc_dom_real').val('');
                            $('#$codigo_postal_dom_real').html('').fadeIn();
                            $('#$telefono_dom_real').val('');
                        }
                    })
                </script>
            ";
    }

}
?>
