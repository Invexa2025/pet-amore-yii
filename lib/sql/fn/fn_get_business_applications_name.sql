DROP FUNCTION IF EXISTS fn_get_business_applications_name;
CREATE OR REPLACE FUNCTION fn_get_business_applications_name(
    in_code         IN business_applications.code%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_business_application_name TEXT;
BEGIN
    SELECT
        ba.name
    INTO
        out_business_application_name
    FROM
        business_applications ba
    WHERE
        ba.status = 1
        AND ba.code = UPPER(in_code);

    RETURN out_business_application_name;
END;
$$ LANGUAGE plpgsql;
