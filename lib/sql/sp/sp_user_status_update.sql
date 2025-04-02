DROP FUNCTION sp_user_status_update;
CREATE OR REPLACE FUNCTION sp_user_status_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_id                IN users.id%TYPE,
	in_status        	 IN users.status%TYPE,
    in_create_by         IN users.id%TYPE,
	in_owner_id          IN businesses.id%TYPE
)	
AS $$
DECLARE
	v_cnt INT;
    v_user_id   users.user_id%TYPE;
	v_old_status users.status%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
        u.user_id,
		u.status
	INTO
        v_user_id,
        v_old_status
	FROM
		users u
	WHERE
		id = in_id;

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
		out_str := 'User is not found';

		RETURN;
	END IF;

	UPDATE users SET
		status = in_status,
		update_by = in_create_by,
		update_time = NOW()
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
			in_action 		=> 'CHANGE USER STATUS',
			in_old_value 	=> (CASE WHEN v_old_status = 1 THEN 'ACTIVE' ELSE 'INACVTIVE' END),
			in_new_value 	=> (CASE WHEN in_status = 1 THEN 'ACTIVE' ELSE 'INCACVTIVE' END),
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> v_user_id::TEXT,
			in_ref_table    => 'users'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Selected user status has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
