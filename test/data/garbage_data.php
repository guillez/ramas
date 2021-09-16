<?php
namespace Kolla\Test\data;

class  garbage_data 
{
    static $usuario = "usuario1";
    // Unidad de gestion
	static $unidad_gestion = ['unidad_gestion' => '0'];
    static $unidad_gestion_error = ['unidad_gestion'=>'9999'];
    static $unidad_gestion_otro = ['unidad_gestion'=>'1'];
    static $unidad_gestion_invalido = ['unidad_gestion'=>'xx'];
    
    //Formularios
    static $formulario_initial = ["formulario"=>"101"];
    static $formulario_detalle_initial = ["formulario_detalle"=>"101"];
    
    // Habilitaciones
    static $habilitacion_initial = ["habilitacion"=>"100","habilitacion2"=>"101"];
    static $habilitacion_error = ["habilitacion"=>"999"];
    static $habilitacion_individual = [	'fecha_desde'=>'2016-02-01',
    									'fecha_hasta'=>'2016-02-28',
    									'descripcion'=>'Primer habilitacion'];
    
    // Tipo Elemento
    static $tipo_elemento_initial = ["tipo_elemento"=>"100","tipo_elemento2"=>"102"];
    static $tipo_elemento_error = ["tipo_elemento"=>"999"];
    static $tipo_elemento_otro =["tipo_elemento"=>"101"];
    static $tipo_elementos =[
                                ["tipo_elemento"=>"01","descripcion"=>"Tipo elemento 01"],
                                ["tipo_elemento"=>"02","descripcion"=>"Tipo Elemento 02"],
                                ["tipo_elemento"=>"03","descripcion"=>"Tipo Elemento 03"]
                                
                            ];
    static $tipo_elemento_overflow = ["tipo_elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                 "descripcion"=>"overflow descripcion"
                                ];
    static $tipo_elemento_individual = ["tipo_elemento"=>"104","descripcion"=>"Tipo Elemento 104"];
    static $tipo_elemento_modificado = ["tipo_elemento"=>"104","descripcion"=>"Tipo Elemento 104 modificado"];
    static $tipo_elemento_incompleto = ["tipo_elemento"=>"105"];
    static $tipo_elemento_incompleto2 = ["tipo_elemento"=>"104"];
    static $tipo_elementos_invalidos_todos =   [
                                                    ["tipo_elemeno"=> "100","descripcion"=>"elemento 100"],
                                                    ["tipo_elemento"=> "101","descripcin"=>"elemento 101"],
                                                    ["tipo_elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                                        "descripcion"=>"overflow descripcion"
                                                       ]
                                                ];
    static $tipo_elementos_invalidos =   [
                                        ["tipo_elemento"=> "100","descripcion"=>"elemento 100"],
                                        ["tipo_elemento"=> "101","descripcion"=>"elemento 101"],
                                        ["tipo_elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                            "descripcion"=>"overflow descripcion"
                                           ]
                                    ];
    //Elementos 
    static $elemento_initial = ["elemento"=>"100","elemento2"=>"102"];
    static $elemento_error = ["elemento"=>"999"];
    static $elementos = [
                            ["elemento"=> "100","descripcion"=>"elemento 100"],
                            ["elemento"=> "101","descripcion"=>"elemento 101"],
                            ["elemento"=> "102","descripcion"=>"elemento 102"],
                        ];
    static $elemento_individual = ["elemento"=>"104", "descripcion"=>"Elemento 104"];
    static $elemento_modificado = ["elemento"=>"104", "descripcion"=>"Elemento Modificado"];
    static $elemento_incompleto2 = ["elemento"=>"104"];
    static $elemento_incompleto = ["elemento"=>"105"];
    static $elemento_overflow = ["elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                 "descripcion"=>"overflow descripcion"
                                ];
    static $elementos_invalidos =   [
                                        ["elemento"=> "100","descripcion"=>"elemento 100"],
                                        ["elemento"=> "101","descripcion"=>"elemento 101"],
                                        ["elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                            "descripcion"=>"overflow descripcion"
                                           ]
                                    ];
    static $elementos_invalidos_todos =   [
                                        ["elemeno"=> "100","descripcion"=>"elemento 100"],
                                        ["elemento"=> "101","descripcin"=>"elemento 101"],
                                        ["elemento"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                            "descripcion"=>"overflow descripcion"
                                           ]
                                    ];
    // Conceptos
    static $concepto_initial = ["concepto"=>"100","concepto2"=>"102"]; // Conceptos que son agregas a BD por setUp() en seed.xml
    static $concepto_initial_modificado = ["concepto"=>"100","descri"=>"102"];
    static $concepto_error = ["concepto"=>"999"]; // Concepto que no existe
    static $conceptos = [   
                            ["concepto"    => "01","descripcion" => "concepto 01"],
                            ["concepto"    => "02","descripcion" => "concepto 02"],
                            ["concepto"    => "03","descripcion" => "concepto 03"],
                        ];// Conceptos que se utilizan para ingreso masivo
    static $conceptos_invalido = [   
                            ["concepto"    => "01","descripcion" => "concepto 01"],
                            ["concepto"    => "02","descripcion" => "concepto 02"],
                            ["concepto"    => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb","descripcion" => "concepto 03"],
                        ];// Conceptos que se utilizan para ingreso masivo
    static $conceptos_invalido_todos = [   
                            ["concepto"    => "01"], //concepto sin descripcion
                            ["descripcion" => "concepto 02"], //concepto sin id_externo
                            ["concpto"=>"03","descripsion"=>"asdasdasd"], //concepto mal formado
                        ];// Conceptos que se utilizan para ingreso masivo
    static $concepto_individual = ["concepto"=>"04","descripcion"=>"concepto 04"]; // Concepto para insertar individualemente
    static $concepto_modificado = ["concepto"=>"04","descripcion"=>"concepto 04 modificado"];
    static $concepto_incompleto = ["concepto"=>"04"];
    static $concepto_overflow = ["concepto"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbb",
                                 "descripcion"=>"overflow descripcion"
                                ];
    static $concepto_invalido = ["concepto"=>"xx","descripcion"=>"concepto xx invalido"];
    
}
