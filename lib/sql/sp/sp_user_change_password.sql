DROP FUNCTION sp_user_change_password;
CREATE OR REPLACE FUNCTION sp_user_change_password
(
	out_num             OUT INTEGER,
	out_str             OUT VARCHAR,
	in_id               IN users.id%TYPE,
	in_password			IN users.password%TYPE,
	in_user_uid         IN users.create_by%TYPE,
	in_owner_id         IN users.business_id%TYPE
)
AS $$
DECLARE
	v_cnt       	INT;
	v_user_id		users.user_id%TYPE;
	v_old_pass  	TEXT;
BEGIN
	out_num := 0;
	out_str := 'Success';

	-- get newest old pass
	SELECT
		u.user_id,
		u.password
	INTO
		v_user_id,
		v_old_pass
	FROM
		users u
	WHERE
		u.id = in_id;

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		users u
	WHERE
		u.id = in_id
		AND u.status != 0
		AND u.business_id = in_owner_id;

	IF v_cnt != 1 THEN
		out_num := 1;
		out_str := 'No user found';

		RETURN;
	END IF;

	UPDATE users SET
		password = in_password,
		update_by = in_user_uid,
		update_time = CURRENT_TIMESTAMP,
		password_expiry_time = CURRENT_TIMESTAMP + (SELECT fn_get_global_variables('password_validity', in_owner_id) ||' DAY')::INTERVAL
	WHERE
		id = in_id
		AND business_id = in_owner_id;	

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'CHANGE PASSWORD',
			in_old_value 	=> v_old_pass,
			in_new_value 	=> in_password,
			in_description 	=> '',
			in_create_by 	=> in_user_uid,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> v_user_id::TEXT,
			in_ref_table    => 'users'
		);

	IF out_num = 0 THEN
		out_str := 'Password successfully changed';
	END IF;
END;
$$ LANGUAGE plpgsql;
