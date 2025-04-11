DROP FUNCTION IF EXISTS sp_business_apps_insert;
CREATE OR REPLACE FUNCTION sp_business_apps_insert
(
    out_num         OUT INTEGER,
    out_str         OUT VARCHAR,
    in_business_id  IN  businesses.id%TYPE,
    in_groups_id    IN  
) AS $$
DECLARE
BEGIN
END;
$$ LANGUAGE plpgsql;