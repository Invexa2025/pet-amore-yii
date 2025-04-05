DROP FUNCTION IF EXISTS sp_business_insert;
CREATE FUNCTION sp_business_insert
(
    out_num                         OUT INTEGER,
    out_str                         OUT VARCHAR,
    in_business_name                IN  businesses.name%TYPE,
    in_business_domain              IN  businesses.domain%TYPE,
    in_business_user_id             IN  users.user_id%TYPE,
    in_business_user_first_name     IN  users.first_name%TYPE,
    in_business_user_last_name      IN  users.last_name%TYPE,
    in_business_user_gender         IN  users.gender%TYPE,
    in_business_user_birthdate      IN  users.birthdate%TYPE,
    in_business_user_email          IN  users.email%TYPE,
    in_business_user_phone          IN  users.phone%TYPE,
    in_business_office_code         IN  offices.code%TYPE,
    in_business_office_name         IN  offices.name%TYPE,
    in_business_office_address      IN  addresses.address%TYPE,
    in_business_office_country      IN  addresses.country_code%TYPE,
    in_business_office_city         IN  addresses.city_code%TYPE,
    in_business_office_phone        IN  offices.phone%TYPE,
    in_business_office_fax          IN  offices.fax%TYPE,
    in_contact_id                   IN  businesses.create_by%TYPE
) AS $$
DECLARE
    v_cnt               INTEGER;
    v_new_value         admin_history.new_value%TYPE;
    v_business_id       businesses.id%TYPE;
    v_office_id         offices.id%TYPE;
    v_admin_id          users.id%TYPE;
BEGIN
    -- Add output default value
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
        AND bs.status = 1;
    
    IF v_cnt > 0 THEN 
        out_num := 1;
        out_str := 'Business domain or name already exists!';

        RETURN;
    END IF;

    -- Insert data into businesses table
    INSERT INTO businesses
    (
        name,
        domain,
        create_by
    )
    VALUES
    (
        UPPER(in_business_name),
        UPPER(in_business_domain),
        in_contact_id
    ) RETURNING id INTO v_business_id;

    -- Insert new office
    SELECT
        *
    INTO
        out_num,
        out_str,
        v_office_id
    FROM
        sp_office_insert
        (
            in_office_code  => UPPER(in_business_office_code),
            in_office_name  => UPPER(in_business_office_name),
            in_country_code => in_business_office_country,
            in_city_code    => in_business_office_city,
            in_address      => in_business_office_address,
            in_phone        => in_business_office_phone,
            in_fax          => in_business_office_fax,
            in_create_by    => in_contact_id,
            in_owner_id     => v_business_id
        );
    
    IF out_num > 0 AND v_office_id IS NULL THEN
        out_str := 'Failed to create new office!';

        RETURN;
    END IF;

    -- Insert business admin
    SELECT
        * 
    INTO
        out_num,
        out_str,
        v_admin_id
    FROM
        sp_user_admin_insert
        (
            in_user_id      => in_business_user_id,
            in_password     => NULL,
            in_first_name   => in_business_user_first_name,
            in_last_name    => in_business_user_last_name,
            in_gender       => in_business_user_gender,
            in_birthdate    => in_business_user_birthdate,
            in_email        => in_business_user_email,
            in_phone        => in_business_user_phone,
            in_group        => NULL,
            in_office       => v_office_id,
            in_create_by    => in_contact_id,
            in_owner_id     => v_business_id,
            in_role_type    => 'BSA'
        );
    
    IF out_num > 0 AND v_admin_id IS NULL THEN
        out_str := 'Failed to create business admin!';

        RETURN;
    END IF;

    -- Update business set admin_id and office_id
    UPDATE 
        businesses
    SET 
        admin_id = v_admin_id,
        update_by = in_contact_id,
        update_time = NOW()
    WHERE
        id = v_business_id;
    

    -- Fetch new created business data into new value variable
    SELECT
        'Business Name : ' || bs.name || CHR(10) ||
        'Business Domain : ' || bs.domain
    INTO
        v_new_value
    FROM
        businesses bs
    WHERE
        bs.id = v_business_id;
        
    SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'INSERT BUSINESS',
			in_old_value 	=> '',
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_contact_id,
			in_owner_id 	=> v_business_id,
			in_action_to 	=> UPPER(in_business_name),
			in_ref_table    => 'businesses'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'New Business has been added';
	END IF;
END;
$$ LANGUAGE plpgsql;