DROP FUNCTION IF EXISTS sp_business_global_variables_insert;
CREATE OR REPLACE FUNCTION sp_business_global_variables_insert
(
    out_num             OUT INTEGER,
    out_str             OUT VARCHAR,
    in_contact_id       IN  global_variables.create_by%TYPE,
    in_owner_id         IN  businesses.id%TYPE,
    in_global_variables IN  JSONB DEFAULT '[]'::JSONB
) AS $$
DECLARE
    v_cnt               INTEGER;
    global_variable     JSONB;
BEGIN
    -- Add default value for output
    out_num := 0;
    out_str := 'Success';

    -- Check if global variables already exists 
    SELECT
        COUNT(1)
    INTO
        v_cnt
    FROM
        global_variables gv 
    WHERE
        gv.owner_id = in_owner_id
        AND gv.status = 1;

    IF v_cnt > 0 THEN
        -- Delete previous global variables from the table
        DELETE FROM
            global_variables
        WHERE
            owner_id = in_owner_id;
    END IF;

    -- Insert into global variables desc
    IF jsonb_array_length(in_global_variables) > 0 THEN
        FOR global_variable IN
            SELECT * FROM jsonb_array_elements(in_global_variables)
        LOOP
            INSERT INTO global_variables
            (
                var_name,
                var_value,
                var_number,
                create_by,
                owner_id
            )
            VALUES
            (
                (global_variable->>'var_name')::VARCHAR,
                NULLIF((global_variable->>'var_value')::VARCHAR, ''),
                NULLIF((global_variable->>'var_number')::VARCHAR, '')::NUMERIC,
                in_contact_id,
                in_owner_id
            );
        END LOOP;
    END IF;
END;
$$ LANGUAGE plpgsql;