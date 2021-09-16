CREATE OR REPLACE FUNCTION mask.char_to_sha256(IN p1 CHAR) RETURNS CHAR AS $$
BEGIN
 RETURN encode(digest(p1, 'sha256'), 'hex');
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.char_to_sha256(IN p1 CHAR, IN len INTEGER) RETURNS CHAR AS $$
BEGIN
 RETURN substring ( encode(digest(p1, 'sha256'), 'hex'),  1 , len);
END;
$$ LANGUAGE plpgsql;
