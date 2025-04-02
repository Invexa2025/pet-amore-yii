DROP FUNCTION sp_city_insert;
CREATE OR REPLACE FUNCTION sp_city_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_code		         IN cities.code%TYPE,
	in_name		         IN cities.name%TYPE,
	in_country_code      IN cities.country_code%TYPE,
	in_timezone          IN cities.timezone%TYPE,
	in_create_by         IN users.id%TYPE,
	in_owner_id          IN businesses.id%TYPE
)
AS $$
DECLARE
	v_cnt    	    INT;
	v_new_value	    admin_history.new_value%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		cities c
	WHERE
		c.code = UPPER(in_code)
        AND c.status = 1;

	IF v_cnt > 0 THEN
		out_num := 1;
		out_str := 'City Code already exists';

		RETURN;
	END IF;

    SELECT
        COUNT(1)
    INTO
        v_cnt
    FROM
        countries c
    WHERE
        code_2 = UPPER(in_country_code);

    IF v_cnt = 0 THEN
        out_num := 1;
        out_str := 'Country not found';

        RETURN;
    END IF;

	INSERT INTO cities
	(
		code,
		name,
		country_code,
		timezone
	)
	VALUES (
		UPPER(in_code),
		UPPER(in_name),
		UPPER(in_country_code),
		in_timezone
	);

	SELECT
		'Code : ' 		|| UPPER(c.code) 		|| CHR(10) ||
		'Name : ' 		|| UPPER(c.name) 		|| CHR(10) ||
		'Country Name : '	|| UPPER(fn_get_country_name_by_code_2(c.country_code)) || CHR(10) ||
		'Timezone : ' 	|| c.timezone
	INTO
		v_new_value
	FROM
		cities c
	WHERE
		c.code = UPPER(in_code)
		AND c.status  = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'INSERT CITY',
			in_old_value 	=> '',
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_code),
			in_ref_table    => 'cities'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'New City has been added';
	END IF;
END;
$$ LANGUAGE plpgsql;
