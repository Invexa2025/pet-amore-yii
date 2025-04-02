DROP FUNCTION sp_currency_update;
CREATE OR REPLACE FUNCTION sp_currency_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_code		         IN currencies.code%TYPE,
	in_name		         IN currencies.name%TYPE,
	in_numeric_code      IN currencies.numeric_code%TYPE,
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
		currencies c
	WHERE
		c.code = UPPER(in_code)
		AND c.status = 1;

	IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'Currency is not found';

		RETURN;
	END IF;
	
	SELECT
		'Code : ' 		|| UPPER(c.code) || CHR(10) ||
		'Name : ' 		|| UPPER(c.name) || CHR(10) ||
		'Numeric Code : ' 	|| c.numeric_code
	INTO
		v_old_value
	FROM
		currencies c
	WHERE
		c.code = UPPER(in_code)
		AND c.status = 1;

	UPDATE 
		currencies
	SET 
        code = UPPER(in_code),
		name = UPPER(in_name),
		numeric_code = in_numeric_code
	WHERE
		code = UPPER(in_code)
		AND status = 1;

	SELECT
		'Code : ' 		|| UPPER(c.code) || CHR(10) ||
		'Name : ' 		|| UPPER(c.name) || CHR(10) ||
		'Numeric Code : ' 	|| c.numeric_code
	INTO
		v_new_value
	FROM
		currencies c
	WHERE
		c.code = UPPER(in_code)
		AND c.status = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE CURRENCY',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_code),
			in_ref_table    => 'currencies'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Currency has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
