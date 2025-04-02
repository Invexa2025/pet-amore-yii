DROP FUNCTION sp_city_update;
CREATE OR REPLACE FUNCTION sp_city_update
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
	v_cnt               INT;
	v_old_value	        admin_history.old_value%TYPE; 
	v_new_value	        admin_history.new_value%TYPE; 
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

	IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'City is not found';

		RETURN;
	END IF;
	
	SELECT
		'Code : ' 		|| UPPER(c.code) 		|| CHR(10) ||
		'Name : ' 		|| UPPER(c.name) 		|| CHR(10) ||
		'Country Name : '	|| UPPER(fn_get_country_name_by_code_2(c.country_code)) || CHR(10) ||
		'Timezone : ' 	|| c.timezone
	INTO
		v_old_value
	FROM
		cities c
	WHERE
		c.code = UPPER(in_code)
		AND c.status  = 1;

	UPDATE 
		cities
	SET 
		name = UPPER(in_name),
		country_code = UPPER(in_country_code),
		timezone = in_timezone
	WHERE
		code = UPPER(in_code)
		AND status = 1;

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
		c.code = in_code
		AND c.status  = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE CITY',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_code),
			in_ref_table    => 'cities'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'City has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
