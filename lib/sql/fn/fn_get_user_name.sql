DROP FUNCTION IF EXISTS fn_get_user_name;
CREATE OR REPLACE FUNCTION fn_get_user_name(
    in_user_uid     IN users.id%TYPE
)
RETURNS TEXT AS
$$
DECLARE
    out_name text;
BEGIN
    SELECT
        CASE
            WHEN (last_name IS NULL OR last_name = '') THEN ''
            ELSE last_name || '/'
        END || COALESCE(first_name, '')
    INTO
        out_name
    FROM
        users c
    WHERE
        c.id = in_user_uid;

    RETURN out_name;
END;
$$ LANGUAGE plpgsql;
