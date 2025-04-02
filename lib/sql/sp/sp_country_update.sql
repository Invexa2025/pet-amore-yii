DROP FUNCTION sp_country_update;
CREATE OR REPLACE FUNCTION sp_country_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_code_2		     IN countries.code_2%TYPE,
    in_code_3		     IN countries.code_3%TYPE,
	in_name		         IN countries.name%TYPE,
    in_ccy_code          IN countries.ccy%TYPE,
	in_phone_code        IN countries.phone_code%TYPE,
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
		countries c
	WHERE
        c.code_2 = UPPER(in_code_2)
		AND c.status = 1;

	IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'Country is not found';

		RETURN;
	END IF;
	
	SELECT
		'Code 2 : ' 		|| UPPER(c.code_2) || CHR(10) ||
        'Code 3 : ' 		|| UPPER(c.code_3) || CHR(10) ||
		'Name : ' 		    || UPPER(c.name) || CHR(10) ||
        'Ccy : ' 		    || UPPER(c.ccy) || CHR(10) ||
		'Phone Code : ' 	|| c.phone_code
	INTO
		v_old_value
	FROM
		countries c
	WHERE
		c.code_2 = UPPER(in_code_2)
		AND c.status = 1;

	UPDATE 
		countries
	SET 
        code_2 = UPPER(in_code_2),
        code_3 = UPPER(in_code_3),
		name = UPPER(in_name),
        ccy = UPPER(in_ccy_code),
		phone_code = in_phone_code
	WHERE
		code_2 = UPPER(in_code_2)
		AND status = 1;

	SELECT
		'Code 2 : ' 		|| UPPER(c.code_2) || CHR(10) ||
        'Code 3 : ' 		|| UPPER(c.code_3) || CHR(10) ||
		'Name : ' 		    || UPPER(c.name) || CHR(10) ||
        'Ccy : ' 		    || UPPER(c.ccy) || CHR(10) ||
		'Phone Code : ' 	|| c.phone_code
	INTO
		v_new_value
	FROM
		countries c
	WHERE
		c.code_2 = UPPER(in_code_2)
		AND c.status = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE COUNTRY',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_code_2),
			in_ref_table    => 'countries'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Country has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
