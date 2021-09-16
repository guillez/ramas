
			SET toba_dir=C:\proyectos\toba_kolla
			SET TOBA_INSTANCIA=desarrollo
			SET TOBA_PROYECTO=kolla
			SET PATH=%PATH%;%toba_dir%\bin
			SET TOBA_INSTALACION_DIR=C:\proyectos\toba_kolla/instalacion/
			toba item ejecutar -p kolla -t 40000125 -u toba 11 >> C:\proyectos\toba_kolla/proyectos/kolla/procesos/logs/salida_reporte_encuestados.log
			exit
			