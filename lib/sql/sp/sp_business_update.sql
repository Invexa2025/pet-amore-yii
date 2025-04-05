DROP FUNCTION IF EXISTS sp_business_update;
CREATE OR REPLACE FUNCTION sp_business_update
(
    out_num             OUT INTEGER,
    out_str             OUT VARCHAR,
    in_business_id      IN  businesses.id%TYPE,
    in_business_name    IN  businesses.name%TYPE,
    in_business_domain  IN  businesses.domain%TYPE,
    in_contact_id       IN  businesses.update_by%TYPE
) AS $$
DECLARE
    v_cnt           INTEGER;
    v_new_value     admin_history.new_value%TYPE;
    v_old_value     admin_history.old_value%TYPE;
BEGIN
    -- Add default value
    out_num := 0;
    out_str := 'Success';

    -- Validate business name or domain must be unique
    SELECT
        COUNT(1)
    INTO
        v_cnt
    FROM
        businesses bs 
    WHERE
        (
            bs.domain = UPPER(in_business_domain)
            OR bs.name = UPPER(in_business_name)
        )
        AND bs.id != in_business_id
        AND bs.status = 1;
    
    IF v_cnt > 0 THEN 
        out_num := 1;
        out_str := 'Business domain or name already exists!';

        RETURN;
    END IF;

    -- Get old value
    SELECT
        'Business Name : ' || bs.name || CHR(10) ||
        'Business Domain : ' || bs.domain
    INTO
        v_old_value
    FROM
        businesses bs
    WHERE
        bs.id = in_business_id;

    -- Update busines
    UPDATE
        businesses
    SET
        domain = in_business_domain,
        name = in_business_name,
        update_by = in_contact_id,
        update_time = NOW()
    WHERE
        id = in_business_id;
    
    -- Get new value
    SELECT
        'Business Name : ' || bs.name || CHR(10) ||
        'Business Domain : ' || bs.domain
    INTO
        v_new_value
    FROM
        businesses bs
    WHERE
        bs.id = in_business_id;

    SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE BUSINESS',
			in_old_value 	=> '',
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_contact_id,
			in_owner_id 	=> in_business_id,
			in_action_to 	=> UPPER(in_business_name),
			in_ref_table    => 'businesses'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Success';
	END IF;
END;
$$ LANGUAGE plpgsql;