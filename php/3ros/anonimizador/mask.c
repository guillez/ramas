#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <iostream>
#include <libpq-fe.h>
#include <stdarg.h>

#define LEN 1024
#define CNT_TABLAS 500
#define CNT_COLS 5000
#define COLS_X_TABLA 512
#define DATA_TAG "Data for Name: "
#define TABLE_TAG "Type: TABLE DATA"
#define COPY_TAG "COPY"

PGconn *cnn = NULL;
PGresult *result = NULL;

char host[30] = "localhost";
char port[8] = "5432";
char dataBase[30] = "postgres";
char user[30] = "user";
char passwd[30] = "password";
char variable_start_character[2] = "$";

struct tabletabs {
	char esquema[50];
	char tabla[50];
	short int status;
	short int tabid;
	short int ncols;
} tabletomask[CNT_TABLAS] ;

struct tablecols {
	short int tabid;
	char columna[50];
	char procedure[512];
	short int poscol;
} tablemapeos[CNT_COLS];

struct table {
	short int tabid;
	short int poscol;
	char columna[50];
} tablecolumnas[COLS_X_TABLA];

short int vecmapeos[50];

/* este vector tiene los valores de las columnas de la fila original */
char valcol[COLS_X_TABLA][512];

/* este vector tiene los valores de las columnas de la fila final (pueden ser enmascarados o no)*/
char valcol_final[COLS_X_TABLA][512];

int conectDB();
short int detectTableToMask();
int MaskTable(int tabid);
void AplicarTransformaciones(char *linea, short int tabid);
void MaskIt( char *col_value, short int p_cant_cols);
void setea_vecmapeos(short int p_tabid);
short int getProcedureInvocation(char * sql_command, short int sub_mapeo);
void Debug (short int severidad,  const char * format, ... );
void getColumnsByTable(short int tabid);
short int getColPosByName(short int tabid, char *colname);
int leerConfigFile();
short int getTabidByEsquemaTabla(char *esquema, char *tabla);
void inicializa();
void printConfigFile();

char linea[LEN];
int main(int argc, char * argv[])
{
	short int tabid ;
	char *retval;
	char linea2[LEN];

	/* Si las variables DBNAME, DBPORT, DBHOST, DBUSER, DBPASSWD estan en el environment, las tomamos de ahi */
	if ( getenv("DBNAME") != (char *)0 ) 
		strcpy( dataBase, getenv("DBNAME"));
	if ( getenv("DBHOST") != (char *)0 )
		strcpy( host, getenv("DBHOST"));
	if ( getenv("DBPORT") != (char *)0 )
		strcpy( port, getenv("DBPORT"));

	if ( getenv("DBUSER") != (char *)0 )
		strcpy( user, getenv("DBUSER"));
	if ( getenv("DBPASSWD") != (char *)0 )
		strcpy( passwd, getenv("DBPASSWD"));

	if ( conectDB() != 0) {
		printf("Problemas en conexion a la BD[%s] enHOST[%s] PORT[%s] con USR[%s] PSD[%s]\n",dataBase,host,port,user,passwd);
		return -1;
	}

	Debug(2,"Conecto OK a la BD[%s] con USR[%s] PSD[%s]\n",dataBase,user,passwd);
	inicializa();
	if ( leerConfigFile() < 0)
		return -1;
	printConfigFile();

	/* Ciclo principal, leemos por stdin y escribe en stdout, a menos que sea una tabla a enmascarar */
	while (1){
		retval = fgets(linea, LEN-1, stdin);
		if (retval == (char *)0 ) // Si llego el fin de archivo, terminamos
			break;
		tabid = detectTableToMask();
		if (tabid != 0 ){
			Debug(2,"Mask detecto datos de tabla:<%s.%s> a enmascarar\n",tabletomask[tabid-1].esquema,tabletomask[tabid-1].tabla);
			sprintf(linea2, "-- Esta es una tabla a enmascarar (tabid:%d)\n",tabid);
			fputs(linea2, stdout);	
			fputs(linea, stdout);	
			/* Vamos a enmascarar la tabla */
			MaskTable(tabid);
		}
		else{
			fputs(linea, stdout);
		}
	}
}

int conectDB()
{
	cnn = PQsetdbLogin(host,port,NULL,NULL,dataBase,user,passwd);
	if (PQstatus(cnn) == CONNECTION_BAD) 
		return -1;
	else
		return 0;
}

short int detectTableToMask()
{
	int i;
	char tabla_a_enmascarar[512];
	/* Nos fijamos si esta linea tiene un "Type: TABLE DATA" */
	if ( strstr(linea, TABLE_TAG) != NULL ) {  /* NULL actua como (char *)0 */
		/* Esta linea corresponde a la cabecera de los datos de una tabla, veamos si es una de las tablas que nos interesa */
		Debug(9,"DATA TABLE line detected:<%s>\n",linea);
		i = 0;
		while (tabletomask[i].tabid != -1 ){  // recorremos la tabla tabletomask
			/* Se fija si corresponde a una tabla de la lista de tablas a enmascarar */
			sprintf(tabla_a_enmascarar,"%s%s;",DATA_TAG, tabletomask[i].tabla);
			Debug(9,"detectTableToMask prueba con <%s>\n",tabla_a_enmascarar);
			// if ( strstr(linea,tabletomask[i].tabla) != NULL ){
			if ( strstr(linea,tabla_a_enmascarar) != NULL ){
				// Encontramos una tabla para enmascarar
				Debug(5,"detectTableToMask encontro comienzo de datos para <%s>\n",tabla_a_enmascarar);
				return tabletomask[i].tabid;
			}
			i++;
		}
	}
	return 0;
}
int MaskTable(int tabid)
{
	char *retval;

	/* Leemos las columnas de la tabla y las almacenamos en la tablecolumns  */
	getColumnsByTable(tabid);

	/* Aqui vamos a setear una estructura de datos para que sea mas eficiente el enmascaramiento 
	** La idea es tener un vector con una posicion por cada columna de la tabla. En cada posicion se guardara el subindice de la tabla 
	** de mapeos que tienen funciones para enmascarar a esa columna. Si una columna no se enmascara lleva -1 en esa posicion
	** Asi evitamos recorrer todos los mapeos por cada fila de la tabla. El vector de mapeos tiene el subindice que apunta a la tabla
	** mapeos de cada columna a enmascarar, y termina con -2
	*/
	setea_vecmapeos(tabid);

	/* Estamos leyendo datos de la tabla, primero hacemos un ciclo hasta llegar a la primer linea de datos */
	while (strstr(linea,COPY_TAG) == NULL ){
		retval = fgets(linea, LEN-1, stdin);
		if (retval == (char *)0 ) // Si llego el fin de archivo, terminamos
			return 0;
		fputs(linea,stdout);
	}
	/* la linea actual tiene el COPY, leemos una linea mas para posicionarnos en los datos */
	retval = fgets(linea, LEN-1, stdin);
	if (retval == (char *)0 ) // Si llego el fin de archivo, terminamos
		return 0;
//	fputs(linea,stdout);
	while (strstr(linea, "\\.") == NULL){
		AplicarTransformaciones(linea, tabid);
//		fputs(linea,stdout);
		retval = fgets(linea, LEN-1, stdin);
		if (retval == (char *)0 ) // Si llego el fin de archivo, terminamos
			return 0;
	}
	// Imprime la linea que tiene el finalizador de los dato "\."
	fputs(linea,stdout);
	
	return 0;
}
/* Esta funcion, separa la linea con los datos de la fila en N valores correspondientes a las N columnas de la tabla
** Luego para cada columna, se fija si corresponde enmascarla, y de ser asi llama a la funcion MaskIt que enmascara
** el valor devolviendo el valor enmascarado. Luego con los N valores (algunos enmascarados y otros no) vuelve a armar
** la linea correspondiente a la fila. Por ultimo, la linea alterada la escribe en el stdout
*/
void AplicarTransformaciones(char *linea, short int tabid)
{
	short int i,j,desde,cant_cols,p;
	char lineaout[LEN];

	/* primero tenemos que obtener la candidad de columnas de la tabla */
	cant_cols = tabletomask[tabid-1].ncols;
	/* Separamos la linea de entrada en los N valores de las columnas y los dejamos como strings en valcol[i] */ 
	desde = 0;
	j = 0;	
	for(i=0 ; i < cant_cols ; i++){
		while(linea[j] != '\t' && linea[j] != '\0' ) j++;   /* avanzamos en el string hasta un TAB o NULL TERMINATOR */
		strncpy(valcol[i], linea + desde, j - desde); valcol[i][j-desde] = '\0';
		desde = j + 1;
		j = j+ 1;
	}
	/* Hacemos el camino inverso de la separacion, o sea juntamos todos los valores y les intercalamos un TAB como separador*/
	/* Aquellos valores que corresponde son enmascarados antes de armar la linea nuevamente */
	lineaout[0]='\0';
	for(i=0 ; i < cant_cols ; i++){
		/* copiamos el valor original al arreglo donde quedaran los enmascarados, si hay que enmascarar lo pisamos */
		strcpy(valcol_final[i], valcol[i]);
		if (vecmapeos[i] >= 0) {
			// Esta columna tiene que enmascarse, pero antes vamos a chequear si tiene valor NULL. Los NULL no se enmascaran
			if ( strcmp(valcol[i],"\\N") != 0 ){
				// No es NULL, Vamos a enmascarar la columna i, el resultado enmascarado vuelve en valcol[i]
				MaskIt(valcol_final[i], vecmapeos[i]);
			} else {
				Debug(9,"Detecto valor NULL, no se enmascara <%s>\n",valcol[i]);
			}
			// Si es la ultima columna de la tabla, tenemos que agregarlo un \n
			if ( i == cant_cols -1) {
				p = strlen(valcol_final[i]);
				valcol_final[i][p]='\n';
				valcol_final[i][p+1]='\0';
			}
		}
		if ( i < cant_cols - 1 )
			sprintf(lineaout, "%s%s\t",lineaout, valcol_final[i]);
		if ( i == cant_cols -1 )
			sprintf(lineaout, "%s%s",lineaout, valcol_final[i]);
	}
	fputs(lineaout, stdout);
	return;
}
/* MaskIt recibe un valor de una columna, y el subindice al vector tablemapeos que describe como se enmascara esa columna
** Primero tiene que armar la sentencia SQL que invoca al stored procedure que enmascara. Para ello llama a getProcedureInvocation
** Luego ejecuta el SQL y devuelve el resultado en el mismo char pointer donde recibio el valor original 
*/
void MaskIt( char *col_value, short int sub_mapeo)
/* col_value es el valor original de la columna a enmascarar */
/* sub_mapeo es el subindice de la tablemapeos que hay que aplicar a esta columna*/
{
	char sql_command[1024];
	int tuplas, campos ;
	char errmsg[1024];

	/* Aqui tenemos que llamar a una funcion que resuelve la invocacion al procedure, expandiendo cada $columna, */
	/* por el valor de esa columna. si hay constantes vienen fijas en el string de la invocacion del stored */
	/* HAy que tener en cuenta el tipo de la columna para ver si la expande con comillas o sin comillas*/
	if ( getProcedureInvocation(sql_command, sub_mapeo) < 0){
		Debug(0,"Problemas en getProcedureInvocation(%s,%d)\n",sql_command, sub_mapeo);
	}
	Debug(7,"MaskIT va a ejecutar:<%s>\n",sql_command);
	// printf("Procedure: %s(%s)\n",tablemapeos[sub_mapeo].procedure,col_value);
        result = PQexec(cnn, sql_command);
	strcpy(errmsg, PQresultErrorMessage(result));

	if (result != NULL) {
		tuplas = PQntuples(result);
		campos = PQnfields(result);
	}	
	Debug(8,"Ejecucion del SQL devolvio tuplas:%d campos:%d\n",tuplas, campos);
	if (tuplas == 1 && campos == 1){
		strcpy(col_value,PQgetvalue(result,0,0) );
		Debug(7,"Valor enmascarado es:<%s>\n",col_value);
	} else {
		Debug(0,"Error tuplas:<%d> campos:<%d>\n",tuplas, campos);
	}
}
/* El vector vecmapeos evita tener que recorrer la tabla de mapeos de columnas integramente por cada valor de una fila */
/* Esta funcion setea el vector vecmapeos.Si la tabla tiene 5 columnas,vecmapeos tiene 5 entradas + un terminador (-2) en la sexta entrada */
/* Cada entrada corresponde a una columna de la tabla,e indica como se enmascara la columna.un -1 indica que no hay que enmascarar esa col */
/* CUalquier otro valor (0,1,2...N) indica en que posicion de la tabla tablecols esta descripta como se mapea esta columna */
void setea_vecmapeos(short int p_tabid)
{
short int i, cant_cols;

	/* obtenemos la cantidad de columnas en la tabla tabid */
	cant_cols = tabletomask[p_tabid-1].ncols;

	/* inicialmente todos los valores de vecmapeos en -1 */
	for(i=0 ; i<cant_cols ; i++) vecmapeos[i]=-1;  
	vecmapeos[i] = -2;  /* el -2 indica que termina vecmapeos */

	/* recorremos la tabla tablecols, cada vez que encontramos un mapeo de la tabla tabid, ponemos el subindice del mapeo en vecmapeos*/
	i = 0;
	while ( tablemapeos[i].tabid != -1){
		if ( tablemapeos[i].tabid == p_tabid )
			vecmapeos[tablemapeos[i].poscol] = i;
		i++;
	}
	fputs("-- vecmapeos", stdout);  
	for(i=0 ; i<cant_cols ; i++) fprintf(stdout,"[%d]=%d ",i,vecmapeos[i]);  
	fputs("\n", stdout);
}


void Debug ( short int severidad, const char * format, ... )
{
	static int firsttime=0;
	static FILE *dbgfile;
	static short int nivel_debug;
	char buffer[1024];
	va_list args;
	/* La primera vez que se ejecuta la funcion, abrimos el archivo de debug, y seteamos el nivel de debug */
	if (firsttime==0){
		dbgfile = fopen("mask.log","a");
		if ( dbgfile ){
			firsttime = 1;
		} else {
			printf("No puede abrir el archivo de debug\n");
		}
		/* Seteamos el nivel de debug */
		if ( getenv("DEBUG") != (char *)0 ) {
			nivel_debug = atoi(getenv("DEBUG"));
		} else {
			nivel_debug = 1; /* Por defecto solo imprimimos en el log , los mensajes de debug de error */
		}
		if ( nivel_debug > 0 )
			fprintf (dbgfile, "Mask: Ejecuta con Nivel de debug: %d\n", nivel_debug );
	}

	va_start (args, format);
	if ( severidad <= nivel_debug ){
		vsprintf (buffer,format, args);
		fputs (buffer, dbgfile);
	}
	va_end (args);
}

/* Tiene que dejar en el char *invocacion la sentencia de invocacion del procedure. La toma de tablemapeos[sub_mapeo].procedure
** Si el comando tiene referencias del tipo $columna1 tiene que reemplazar por el valor de esa columna en la fila actual */
short int getProcedureInvocation(char *invocacion, short int sub_mapeo)
{
	char *localptr1, *localptr2 , localbuffer[1024],primeraparte[1024], ultimaparte[1024], nombrecolumna[50];
	int offset, offset_col, len_col, colpos;

	offset = 0;
	strcpy(localbuffer, tablemapeos[sub_mapeo].procedure);
	Debug(9,"Invocacion Original<%s>\n",localbuffer);
	/* Hacemos un ciclo por cada parametro del tipo $columna y lo expandemos por el valor de la columna */
	while ( ( localptr1 = strstr(&localbuffer[offset],variable_start_character) ) != (char *)0 ){
	/* while ( ( localptr1 = strstr(&localbuffer[offset],"$") ) != (char *)0 ){ */
		/* buscamos donde termina el nombre de la columna */
		localptr2 = strpbrk(localptr1,", )\'");	
		Debug(9,"localptr1:<%s>\nlocalptr2:<%s>\n",localptr1, localptr2);

		/* obtenemos la posicion donde se encontro el $ */
		offset_col = strlen(&localbuffer[offset]) - strlen(localptr1) ;

		/* copiamos la primera parte de la invocacion a primeraparte */
		strncpy(primeraparte, &localbuffer[offset], offset_col);
		primeraparte[offset_col] = '\0';
		Debug(9,"offset_col:<%d> primeraparte:<%s>\n",offset_col, primeraparte);

		/* copiamos la ultima parte de la invocacion a ultimaparte */
		sprintf(ultimaparte,"%s",localptr2 );

		/*copiamos a nombrecolumna el nombre de la columna (a expandir) que viene despues del $*/
		len_col = (int)(localptr2-localptr1); 
		strncpy(nombrecolumna,localptr1+1,len_col-1);nombrecolumna[len_col-1]='\0';	

		/* Obtenemos el colpos de la columna a expandir */
		if ((colpos = getColPosByName( tablemapeos[sub_mapeo].tabid, nombrecolumna)) < 0 ){
			Debug(0,"getColByName(%d,%s) no encontro la columna\n",tablemapeos[sub_mapeo].tabid, nombrecolumna);
			return -1;
		}
		/* Calculamos el nuevo offset a partir del cual seguimos buscando una ocurrencia tipo $columna*/
		// offset = strlen(primeraparte) + strlen(valcol[colpos]) ;
		Debug(9,"ultimaparte:<%s> nombrecolumna:<%s> nuevo offset:<%d>\n",ultimaparte,nombrecolumna ,offset );
		Debug(9,"colpos:<%d> colvalue<%s>\n",colpos,valcol[colpos]);
		// Aui tenemos que considerar si el valor de valcol[colpos] es \N lo expandimos por NULL
		sprintf(localbuffer,"%s%s%s", primeraparte, valcol[colpos], ultimaparte);
		Debug(9,"Despues de expandir Columna <%s> quedo la invocaion:<%s>\n",nombrecolumna,localbuffer);
	}
	sprintf(invocacion, "SELECT %s",localbuffer);
	Debug(9,"Invocacion con variable reemplazadas:<%s>\n",invocacion);
	return 0;
}

short int getColPosByName(short int v_tabid, char *v_columna)
{
	int i=0;
	Debug(9,"getColPosByName(%d,%s) ",v_tabid, v_columna);
	while(tablecolumnas[i].tabid != -1 ){
		if (tablecolumnas[i].tabid == v_tabid ){
			/* es la tabla que nos interesa, veamos si coincide la columna */
			if ( strcmp(tablecolumnas[i].columna, v_columna) == 0 ) {
				/* encontramos una columna con ese nombre en la tabla, devolvemos su posicion */
				Debug(9," devuelve %d\n", i);
				return i;
			}
		} else {
			Debug(9," Tabid Incorrecto devuelve -1\n");
			return -1;
		}
		i++;
	}
	Debug(9,"Columna <%s> no encontrada\n", v_columna);
	return -1;
}

void getColumnsByTable(short int tabid)
{
	char sqlcomm[512];
	int i;
	
	// sprintf(sqlcomm,"SELECT a.attnum, a.attname FROM pg_catalog.pg_attribute a WHERE a.attnum > 0 AND NOT a.attisdropped AND a.attrelid = ( SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname = '%s' AND pg_catalog.pg_table_is_visible(c.oid))" ,tabletomask[tabid-1].tabla);
	sprintf(sqlcomm,"select a.attnum, a.attname from pg_catalog.pg_attribute a, pg_catalog.pg_class t , pg_namespace s where a.attrelid = t.oid and   t.relnamespace = s.oid and nspname = '%s' and t.relname = '%s' and a.attnum > 0 order by 1",tabletomask[tabid-1].esquema,tabletomask[tabid-1].tabla);
	Debug(6,"Query de getColumnsByTable:<%s.%s> \n<%s>\n",tabletomask[tabid-1].esquema,tabletomask[tabid-1].tabla,sqlcomm);
        result = PQexec(cnn, sqlcomm );
	Debug(6,"Ejecucion del query devolvio %d(%s)\n", PQresultStatus(result),PQresStatus(PQresultStatus(result)) );
        if (result != NULL) {
        	int tuplas = PQntuples(result);
       		int campos = PQnfields(result);
		Debug(6,"Ejecucion de query de columnas devolvio rows:<%d> campos:<%d>\n",tuplas, campos);
		for(i=0;i<tuplas;i++) {
			tablecolumnas[i].tabid = tabid;
			tablecolumnas[i].poscol = atoi( PQgetvalue(result,i,0))-1;
			sprintf(tablecolumnas[i].columna,"%s", PQgetvalue(result,i,1));
			Debug(6,"Leyo columna:<%s> poscol:<%d>\n",tablecolumnas[i].columna,tablecolumnas[i].poscol);
		}
		tabletomask[tabid-1].ncols = tuplas;		
		tablecolumnas[i].tabid = -1;
	}

}
/* Lee el archivo de configuracion y carga 2 vectores de records: tabletomask (con campos esquema, tabla, tabid y ncols) y el vector
** tablemapeos (con campos columna, procedure y poscol);
** Si encuentra un comentario lo saltea (# en la primera posicion)
** El nombre del archivo de configuracion default es mask.conf, o se puede usar otro nombre seteandolo en la env variable MASKCONFIG
*/
int leerConfigFile()
{
	char filename[128];
	char linea[LEN], *ptr, *retval;
	int  nrolinea=0, lentoken, pos=0;
	short int v_tabid;
	FILE *cfgfile;
	char v_esquema[50], v_tabla[50];

	Debug(6,"leerConfigFile\n");
	/* obtenemos el nombre del archivo de configuracion */
	if ( (ptr=getenv("MASKCONFIG")) == (char *)0 ) {
		strcpy(filename,"mask.conf");
	} else {
		strcpy(filename, ptr);
	}

	/* abrimos el archivo */
	cfgfile = fopen(filename,"r");
	if ( !cfgfile ){
		Debug(0,"No puede abrir el archivo de configuracion:<%s>\n",filename);
		return -1;
	}
	Debug(6,"Abrio archivo de configuracion:<%s>\n",filename);
	/* leemos el archivo, y por cada linea separammos las componentes esquema, tabla, columna y stored proc */
	while (1){
		pos = 0;
		retval = fgets(linea, LEN-1, cfgfile); 
		if (retval == (char *)0 ) // Si llego el fin de archivo, terminamos
			break;
		Debug(6,"Leyo:<%s>\n",linea);
		/* obtenemos el esquema */
		if ( linea[0] == '#' ){ // Encontro un comentario, saltamos a la siguiente linea
			Debug(9,"Comentario:<%s>\n",linea);
			continue;
		}
		if ((ptr = strpbrk(&linea[pos]," ,\t")) == (char *)0 ){
			Debug(0,"Problemas en <%s> con esquema (linea nro:%d)\n",filename,nrolinea+1);
			return -1;
		}
		lentoken = strlen(linea)-strlen(ptr);
		strncpy(v_esquema, &linea[pos], lentoken);
		v_esquema[lentoken] = '\0';
		Debug(7,"lentoken:%d v_esquema:<%s>\n",lentoken,v_esquema);

		/* obtenemos la tabla */
		pos= pos+lentoken+1;while(linea[pos]== ' ' || linea[pos]== ',' || linea[pos]== '\t' ) pos++; 
		if ((ptr = strpbrk(&linea[pos]," ,\t")) == (char *)0 ){
			Debug(0,"Problemas en <%s> con tabla (linea nro:%d)\n",filename,nrolinea+1);
			return -1;
		}
		lentoken = strlen(&linea[pos]) - strlen(ptr);
		strncpy(v_tabla, &linea[pos], lentoken);
		v_tabla[lentoken] = '\0';
		Debug(7,"lentoken:%d v_tabla:<%s>\n",lentoken,v_tabla);

		/* obtenemos el tabid de esta tabla */
		v_tabid = getTabidByEsquemaTabla(v_esquema, v_tabla);
		tablemapeos[nrolinea].tabid = v_tabid;
		Debug(7,"tabid:%d \n",v_tabid);

		/* obtenemos la columna */
		pos=pos+lentoken+1;while(linea[pos]== ' ' || linea[pos]== ',' || linea[pos]== '\t' ) pos++; 
		if ((ptr = strpbrk(&linea[pos]," ,\t")) == (char *)0 ){
			Debug(0,"Problemas en <%s> con columna (linea nro:%d)\n",filename,nrolinea+1);
			return -1;
		}
		lentoken = strlen(&linea[pos]) - strlen(ptr);
		strncpy(tablemapeos[nrolinea].columna, &linea[pos], lentoken);
		tablemapeos[nrolinea].columna[lentoken] = '\0';
		
		/* obtenemos el procedure  */
		pos=pos+lentoken+1;while(linea[pos]== ' ' || linea[pos]== ',' || linea[pos]== '\t' ) pos++; 
		strcpy(tablemapeos[nrolinea].procedure, &linea[pos]);
		Debug(7,"tabid:<%d> columna:<%s> procedure:<%s> \n",tablemapeos[nrolinea].tabid,tablemapeos[nrolinea].columna,tablemapeos[nrolinea].procedure);
		/* obtenemos la posicion de la columna dentro de la tabla y seteamos tablemapeos[i].poscol */
		tablemapeos[nrolinea].poscol = getColPosByName(tablemapeos[nrolinea].tabid, tablemapeos[nrolinea].columna);
		nrolinea++;
	}
	tablemapeos[nrolinea].tabid = -1;
	fclose(cfgfile);
	return 0;
}

short int getTabidByEsquemaTabla(char *esquema, char *tabla)
{
	int i=0;

	/* recorremos la tabla tabletomask, si el par esquema/tabla ya esta, devolvemos el tabid */
	while (tabletomask[i].tabid != -1){
		if (strcmp(tabletomask[i].esquema, esquema) == 0 && strcmp(tabletomask[i].tabla, tabla) == 0 ){
			getColumnsByTable(tabletomask[i].tabid);
			return tabletomask[i].tabid;
		}
		i++;
	}
	/* no estaba, es un par esquema/tabla nuevo, lo insertamos */
	strcpy(tabletomask[i].esquema, esquema);
	strcpy(tabletomask[i].tabla, tabla);
	tabletomask[i].tabid = i+1;
	getColumnsByTable(tabletomask[i].tabid);
	/* ponemos el terminador en la siguiente posicion */
	tabletomask[i+1].tabid = -1;
	return tabletomask[i].tabid;
}

void inicializa()
{
	char *ptr;

	tabletomask[0].tabid = -1;
	Debug(6,"Obtener caracter de Inicio de columnas\n");
	/* obtenemos el caracter de inicio de variable, default es $ */
	if ( (ptr=getenv("VARSTARTCHAR")) == (char *)0 ) {
		strcpy( variable_start_character ,"$");
	} else {
		strcpy( variable_start_character ,ptr);
	}
	Debug(6,"Caracter de Inicio de columnas:<%s>\n", variable_start_character);
	Debug(6,"inicializa OK\n");
}
void printConfigFile()
{
	int i=0, j=0;
	while (tabletomask[i].tabid != -1){
		Debug(6,"Tabla:<%s.%s> Tabid:%d Ncols:%d Status:%d\n",
		        tabletomask[i].esquema,tabletomask[i].tabla,tabletomask[i].tabid,tabletomask[i].ncols,tabletomask[i].status);
		j=0;
		while(tablemapeos[j].tabid != -1){
			if (tablemapeos[j].tabid == tabletomask[i].tabid)
				Debug(6,"     Columna:<%s> Procedure:<%s> Poscol:<%d>\n", 
				        tablemapeos[j].columna,tablemapeos[j].procedure,tablemapeos[j].poscol);
			j++;
		}
		i++;
	}
}

/*
    if (PQstatus(cnn) != CONNECTION_BAD) {

        printf("Estamos conectados a PostgreSQL\n");
        result = PQexec(cnn, "SELECT * FROM pg_tables");

        if (result != NULL) {
            int tuplas = PQntuples(result);
            int campos = PQnfields(result);
            printf("No. Filas:%d\n",tuplas);
            printf("No. Columnas:%d\n",campos);

	    printf("Los nombres de las columnas son:\n");
            for (i=0; i<campos; i++) {
              printf("%s |",PQfname(result,i));
            }

	    printf("\nContenido de la tabla\n");

            for (i=0; i<tuplas; i++) {
                for (int j=0; j<campos; j++) {
			printf("%s, ",PQgetvalue(result,i,j) );
                }
		printf("\n");
            }
        }

        // Ahora nos toca liberar la memoria
        PQclear(result);

    } else {
        printf("Error de conexion \n");
        return 0;
    }

    PQfinish(cnn);

    return 0;
}
*/
