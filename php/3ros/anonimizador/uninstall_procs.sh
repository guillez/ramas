DROP FUNCTION IF EXISTS char_to_md5(CHAR);
DROP FUNCTION IF EXISTS char_to_md5(CHAR, INTEGER);
DROP FUNCTION IF EXISTS char_to_sha256(CHAR);
DROP FUNCTION IF EXISTS char_to_sha256(IN p1 CHAR, IN len INTEGER) ;
DROP FUNCTION IF EXISTS get_cuil1();
DROP FUNCTION IF EXISTS get_cuil();
DROP FUNCTION IF EXISTS get_cuil2();
DROP FUNCTION IF EXISTS hash_mail(CHAR, CHAR, CHAR);
DROP FUNCTION IF EXISTS init_shuffle_dni(INTEGER, INTEGER);
DROP FUNCTION IF EXISTS rand_apellido(VARCHAR);
DROP FUNCTION IF EXISTS rand_apellido(VARCHAR, INTEGER);
DROP FUNCTION IF EXISTS rand_apyno();
DROP FUNCTION IF EXISTS rand_apyno(INTEGER);
DROP FUNCTION IF EXISTS rand_dni(INTEGER, INTEGER);
DROP FUNCTION IF EXISTS rand_nombre(VARCHAR);
DROP FUNCTION IF EXISTS rand_nombre(VARCHAR, INTEGER);
DROP FUNCTION IF EXISTS set_text(VARCHAR);
DROP FUNCTION IF EXISTS shuffle_cuilt(INTEGER, INTEGER, NUMERIC);
DROP FUNCTION IF EXISTS shuffle_dni(INTEGER, INTEGER, INTEGER) ;
