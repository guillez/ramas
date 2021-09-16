#Proceso parametrizado
export toba_dir=$1
export toba_instalacion_dir=$3
export TOBA_INSTANCIA=$4
export TOBA_PROYECTO=kolla

export PATH=$PATH:${toba_dir}/bin
echo toba item ejecutar -t $5 -u toba $6 >> ${2}/procesos_bk/00_comandos_ejecutados.txt
toba item ejecutar -t $5 -u toba $6 >> ${2}/procesos_bk/01_resultados.txt
#exit
