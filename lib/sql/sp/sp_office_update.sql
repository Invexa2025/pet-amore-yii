DROP FUNCTION sp_office_update;
CREATE OR REPLACE FUNCTION sp_office_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
    in_office_id         IN offices.id%TYPE,
    in_office_code       IN offices.code%TYPE,
    in_office_name       IN offices.name%TYPE,
    in_country_code      IN countries.code_2%TYPE,
    in_city_code         IN cities.code%TYPE,
    in_address           IN addresses.address%TYPE,
    in_phone             IN offices.phone%TYPE,
    in_fax               IN offices.fax%TYPE,
	in_create_by         IN users.id%TYPE,
	in_owner_id          IN businesses.id%TYPE
)
AS $$
DECLARE
	v_cnt    	    INT;
    v_address_id    addresses.id%TYPE;
    v_old_value	    admin_history.old_value%TYPE;
	v_new_value	    admin_history.new_value%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

    SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		offices o
	WHERE
        o.status = 1
        AND o.owner_id = in_owner_id
        AND o.id = in_office_id;

    IF v_cnt = 0 THEN
        out_num := 1;
        out_str := 'Office is not found';

        RETURN;
    END IF;

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		offices o
	WHERE
        o.status = 1
        AND o.owner_id = in_owner_id
        AND o.id != in_office_id
        AND o.code = in_office_code;

	IF v_cnt > 0 THEN
		out_num := 1;
		out_str := 'Office Code already exists';

		RETURN;
	END IF;

    SELECT
        COUNT(1)
    INTO
        v_cnt
    FROM
        countries c
    WHERE
        c.status = 1
        AND c.code_2 = in_country_code;

    IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'Country is not found';

		RETURN;
	END IF;

    SELECT
        COUNT(1)
    INTO
        v_cnt
    FROM
        cities c
    WHERE
        c.status = 1
        AND c.code = in_city_code;

    IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'City is not found';

		RETURN;
	END IF;

    SELECT
		'Code : ' 		|| UPPER(o.code) || CHR(10) ||
        'Name : ' 		|| UPPER(o.name) || CHR(10) ||
		'Country : ' 	|| fn_get_country_name_by_code_2(a.country_code) || CHR(10) ||
        'City : ' 		|| fn_get_city_name_by_code(a.city_code) ||
        CASE
            WHEN in_address IS NOT NULL THEN
                CHR(10) || 'Address : ' ||  UPPER(in_address)
            ELSE
                ''
        END ||
		CASE
            WHEN in_phone IS NOT NULL THEN
                CHR(10) || 'Phone : ' ||  UPPER(in_phone)
            ELSE
                ''
        END ||
        CASE
            WHEN in_fax IS NOT NULL THEN
                CHR(10) || 'Fax : ' ||  UPPER(in_fax)
            ELSE
                ''
        END
	INTO
		v_old_value
	FROM
		offices o
        INNER JOIN addresses a ON
            a.id = o.address_id
            AND a.status = 1
	WHERE
		o.id = in_office_id
        AND o.status = 1
        AND o.owner_id = in_owner_id;

    UPDATE addresses
    SET
        address = UPPER(in_address),
        country_code = UPPER(in_country_code),
        city_code = UPPER(in_city_code),
        update_by = in_create_by,
        update_time = NOW(),
        owner_id = in_owner_id
    WHERE
        id = (SELECT o.address_id FROM offices o WHERE o.id = in_office_id);

	UPDATE offices
    SET
        code = UPPER(in_office_code),
        name = UPPER(in_office_name),
        phone = in_phone,
        fax = in_fax,
        update_by = in_create_by,
        update_time = NOW(),
        owner_id = in_owner_id
    WHERE
        id = in_office_id;

	SELECT
		'Code : ' 		|| UPPER(o.code) || CHR(10) ||
        'Name : ' 		|| UPPER(o.name) || CHR(10) ||
		'Country : ' 	|| fn_get_country_name_by_code_2(a.country_code) || CHR(10) ||
        'City ' 		|| fn_get_city_name_by_code(a.city_code) ||
        CASE
            WHEN in_address IS NOT NULL THEN
                CHR(10) || 'Address : ' ||  UPPER(in_address)
            ELSE
                ''
        END ||
		CASE
            WHEN in_phone IS NOT NULL THEN
                CHR(10) || 'Phone : ' ||  UPPER(in_phone)
            ELSE
                ''
        END ||
        CASE
            WHEN in_fax IS NOT NULL THEN
                CHR(10) || 'Fax : ' ||  UPPER(in_fax)
            ELSE
                ''
        END
	INTO
		v_new_value
	FROM
		offices o
        INNER JOIN addresses a ON
            a.id = o.address_id
            AND a.status = 1
	WHERE
		o.id = in_office_id
        AND o.status = 1
        AND o.owner_id = in_owner_id;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE OFFICE',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_office_code),
			in_ref_table    => 'offices'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Office has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
