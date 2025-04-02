DROP FUNCTION fn_get_global_variables;
CREATE OR REPLACE FUNCTION fn_get_global_variables(
    in_var_name     IN global_variables_desc.var_name%TYPE,
    in_owner_id     IN INTEGER
)
RETURNS TABLE (out_value VARCHAR, out_number FLOAT) AS $$
DECLARE
    i RECORD;
    j RECORD;
BEGIN
    FOR i IN
        SELECT
            gv.var_value,
            gv.var_number
	    FROM
            global_variables gv
        WHERE
            gv.var_name = in_var_name
            AND gv.owner_id = in_owner_id
            AND gv.status = 1
    LOOP
        out_value := i.var_value;
        out_number := i.var_number;
    END LOOP;

    FOR j IN
        SELECT
            gv.var_value,
            gv.var_number
        FROM
            global_variables_desc gv
        WHERE
            gv.var_name = in_var_name
            AND gv.status = 1
    LOOP
        out_value := COALESCE(out_value, j.var_value);
        out_number := COALESCE(out_number, j.var_number);
    END LOOP;

    RETURN QUERY SELECT out_value, out_number;
    
END;
$$ LANGUAGE plpgsql;
