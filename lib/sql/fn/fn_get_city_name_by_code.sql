DROP FUNCTION IF EXISTS fn_get_city_name_by_code;
CREATE OR REPLACE FUNCTION fn_get_city_name_by_code(
    in_code       IN cities.code%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_city_name TEXT;
BEGIN
    SELECT
        c.name
    INTO
        out_city_name
    FROM
        cities c
    WHERE
        c.status = 1
        AND c.code = in_code;

    RETURN out_city_name;
END;
$$ LANGUAGE plpgsql;
