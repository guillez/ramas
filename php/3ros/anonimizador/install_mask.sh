#-- Antes de ejecutar se deben modificar los rangos en la invocacion a init_shuffle_dni con 
#-- los rangos que se usaran en el mask.conf
#-- Se ejecuta ./install_mask host port database
#-- Donde database es el nombre de la base a enmascarar, es decir que en la misma base se crean un schema 
#   mask y los stored
PGHOST=$1
PGPORT=$2
DBNAME=$3
echo Procesando create_tables_mask.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/create_tables_mask.sql
echo Procesando sql/mask_firstnames.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/mask_firstnames.sql
echo Procesando mask_lastnames.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/mask_lastnames.sql
echo Procesando proc_init_shuffle_dni.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_init_shuffle_dni.sql
echo Procesando sql/proc_char_to_md5.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_char_to_md5.sql
echo Procesando sql/proc_char_to_sha256.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_char_to_sha256.sql
echo Procesando sql/proc_get_cuilt.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_get_cuilt.sql
echo Procesando sql/proc_hash_mail.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_hash_mail.sql
echo Procesando sql/proc_rand_apellido.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_apellido.sql
echo Procesando sql/proc_rand_apyno.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_apyno.sql
echo Procesando sql/proc_rand_dni.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_dni.sql
echo Procesando sql/proc_rand_nombre.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_nombre.sql
echo Procesando sql/proc_hash_nombre.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_hash_nombre.sql
echo Procesando sql/proc_rand_fecha.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_fecha.sql
echo Procesando sql/proc_set_text.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_set_text.sql
echo Procesando sql/proc_shuffle_dni.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_shuffle_dni.sql
echo Procesando sql/proc_shuffle_cuilt.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_shuffle_cuilt.sql
echo Procesando sql/proc_get_username.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_get_username.sql
echo Procesando sql/proc_rand_importe.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/proc_rand_importe.sql
echo Procesando sql/mask_generate_data.sql
psql -h$PGHOST -Upostgres -p$PGPORT $DBNAME -f sql/mask_generate_data.sql
