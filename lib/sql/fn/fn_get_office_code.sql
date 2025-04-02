DROP FUNCTION fn_get_office_code;
CREATE OR REPLACE FUNCTION fn_get_office_code
(
    in_office_id    offices.id%TYPE
) RETURNS VARCHAR AS $$
DECLARE
    out_office_code   VARCHAR(128);
BEGIN
    SELECT
        o.code
    INTO
        out_office_code
    FROM
        offices o
    WHERE
        o.id = in_office_id;

    IF out_office_code IS NULL THEN
        out_office_code := '-';
    END IF;

    RETURN out_office_code;
END;
$$ LANGUAGE plpgsql;
