DROP FUNCTION IF EXISTS fn_get_custom_app_name;
CREATE OR REPLACE FUNCTION fn_get_custom_app_name(
    in_code             IN apps.code%TYPE,
    in_owner_id         IN businesses.id%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_app_name TEXT;
BEGIN
    SELECT
        ca.name
    INTO
        out_app_name
    FROM
        custome_apps ca
    WHERE
        ca.status = 1
        AND ca.owner_id = in_owner_id
        AND ca.code = in_code;

    RETURN out_app_name;
END;
$$ LANGUAGE plpgsql;
