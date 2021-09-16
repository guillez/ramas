
------------------------------------------------------------
-- apex_servicio_web
------------------------------------------------------------
INSERT INTO apex_servicio_web (proyecto, servicio_web, descripcion, tipo, param_to, param_wsa) VALUES (
	'kolla', --proyecto
	'guarani', --servicio_web
	NULL, --descripcion
	'rest', --tipo
	NULL, --param_to
	'0'  --param_wsa
);
INSERT INTO apex_servicio_web (proyecto, servicio_web, descripcion, tipo, param_to, param_wsa) VALUES (
	'kolla', --proyecto
	'habilitaciones', --servicio_web
	'hoaoaa', --descripcion
	'soap', --tipo
	'http://localhost/kolla/3.2/servicios.php/habilitaciones', --param_to
	'0'  --param_wsa
);
INSERT INTO apex_servicio_web (proyecto, servicio_web, descripcion, tipo, param_to, param_wsa) VALUES (
	'kolla', --proyecto
	'rest_arai_reportes', --servicio_web
	'Servicio Web para acceder a Arai-Reportes', --descripcion
	'rest', --tipo
	NULL, --param_to
	'0'  --param_wsa
);
