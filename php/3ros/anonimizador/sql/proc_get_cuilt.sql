CREATE OR REPLACE FUNCTION mask.get_cuil1() RETURNS INTEGER AS $$
DECLARE
BEGIN
	RETURN 20;
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION mask.get_cuil() RETURNS INTEGER AS $$
DECLARE
BEGIN
	RETURN 11111111;
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION mask.get_cuil2() RETURNS INTEGER AS $$
DECLARE
BEGIN
	RETURN 4;
END;
$$ LANGUAGE plpgsql;