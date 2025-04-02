DROP FUNCTION fn_get_office_name;
CREATE OR REPLACE FUNCTION fn_get_office_name
(
    in_office_id    offices.id%TYPE
) RETURNS VARCHAR AS $$
DECLARE
    out_office_name   VARCHAR(128);
BEGIN
    SELECT
        o.name
    INTO
        out_office_name
    FROM
        offices o
    WHERE
        o.id = in_office_id;

    IF out_office_name IS NULL THEN
        out_office_name := '-';
    END IF;

    RETURN out_office_name;
END;
$$ LANGUAGE plpgsql;
