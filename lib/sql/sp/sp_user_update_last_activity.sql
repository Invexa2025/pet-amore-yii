DROP FUNCTION sp_user_update_last_activity;
CREATE FUNCTION sp_user_update_last_activity
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_id                IN users.id%TYPE,
	in_activity 		 IN user_activities.activity%TYPE
)	
AS $$
DECLARE
	v_cnt    INT;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		users u
	WHERE
		u.id = in_id
		AND u.status != 0;

	IF v_cnt != 1 THEN
		out_num := 1;
		out_str := 'User is not found';

		RETURN;
	END IF;

	UPDATE users SET
		last_activity = NOW()
	WHERE
		id = in_id;
	
	INSERT INTO user_activities
	(
		admin_id,
		activity
	) VALUES (
		in_id,
		in_activity 
	);
END;
$$ LANGUAGE plpgsql;
