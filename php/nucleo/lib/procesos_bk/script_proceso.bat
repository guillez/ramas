rem proceso parametrizado
set toba_dir=%1
set toba_instalacion_dir=%3
set TOBA_INSTANCIA=%4
set TOBA_PROYECTO=kolla

set PATH=%PATH%;%toba_dir%\bin
echo toba item ejecutar -t %5 -u toba %6 >> %2\procesos_bk\00_comandos_ejecutados.txt
toba item ejecutar -t %5 -u toba %6 >> %2\procesos_bk\01_resultados.txt
exit