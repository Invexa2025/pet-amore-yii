DROP FUNCTION IF EXISTS fn_get_country_name_by_code_2;
CREATE OR REPLACE FUNCTION fn_get_country_name_by_code_2(
    in_code_2       IN countries.code_2%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_country_name TEXT;
BEGIN
    SELECT
        c.name
    INTO
        out_country_name
    FROM
        countries c
    WHERE
        c.status = 1
        AND c.code_2 = in_code_2;

    RETURN out_country_name;
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS fn_get_country_name_by_code_3;
CREATE OR REPLACE FUNCTION fn_get_country_name_by_code_3(
    in_code_3       IN countries.code_3%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_country_name TEXT;
BEGIN
    SELECT
        c.name
    INTO
        out_country_name
    FROM
        countries c
    WHERE
        c.status = 1
        AND c.code_3 = in_code_3;

    RETURN out_country_name;
END;
$$ LANGUAGE plpgsql;
