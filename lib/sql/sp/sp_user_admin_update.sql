DROP FUNCTION sp_user_admin_update;
CREATE OR REPLACE FUNCTION sp_user_admin_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_id                IN users.id%TYPE,
    in_first_name        IN users.first_name%TYPE,
    in_last_name         IN users.last_name%TYPE,
    in_gender            IN users.gender%TYPE,
    in_birthdate         IN users.birthdate%TYPE,
    in_email             IN users.email%TYPE,
    in_phone             IN users.phone%TYPE,
    in_group             IN TEXT,
    in_office            IN users.office_id%TYPE,
	in_create_by         IN users.id%TYPE,
	in_owner_id          IN businesses.id%TYPE
)
AS $$
DECLARE
	gr 				RECORD;
	v_user_id 		users.user_id%TYPE;
	v_cnt    	    INT;
	v_Id 			INT;
	v_old_value	    admin_history.old_value%TYPE;
	v_new_value	    admin_history.new_value%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		'User ID : ' 		|| UPPER(u.user_id) 		|| CHR(10) ||
		'First Name : ' 		|| UPPER(u.first_name) 		|| CHR(10) ||
		'Last Name : ' 		|| UPPER(u.last_name) 		||
		CASE
			WHEN in_gender IS NOT NULL THEN
				CHR(10) || 'Gender : ' || CASE WHEN in_gender = 'M' THEN 'MALE' ELSE 'FEMALE' END
			ELSE
				''
		END ||
		CASE
			WHEN in_birthdate IS NOT NULL THEN
				CHR(10) || 'Birthdate : ' || TO_CHAR(in_birthdate, 'DD/MM/YYYY')
			ELSE
				''
		END ||
		CASE
			WHEN in_email IS NOT NULL THEN
				CHR(10) || 'Email : ' || in_email
			ELSE
				''
		END ||
		CASE
			WHEN in_phone IS NOT NULL THEN
				CHR(10) || 'Phone : ' || in_phone
			ELSE
				''
		END ||
		'Group : ' || (SELECT STRING_AGG(g.name, ',') FROM groups g WHERE g.id = ANY(u.group_id::INT[])) || CHR(10) ||
		'Office : ' || (SELECT o.name FROM offices o WHERE o.id = u.office_id),
		u.user_id
	INTO
		v_old_value,
		v_user_id
	FROM
		users u
	WHERE
		u.id = in_id;

	UPDATE users
	SET
        first_name = UPPER(in_first_name),
        last_name = UPPER(in_last_name),
        gender = UPPER(in_gender),
        birthdate =in_birthdate,
        email = UPPER(in_email),
        phone =in_phone,
        group_id = STRING_TO_ARRAY(in_group, ',')::INT[],
        office_id = in_office,
        update_by = in_create_by,
        update_time = NOW()
    WHERE
        id = in_id;

	SELECT
		'User ID : ' 		|| UPPER(u.user_id) 		|| CHR(10) ||
		'First Name : ' 		|| UPPER(u.first_name) 		|| CHR(10) ||
		'Last Name : ' 		|| UPPER(u.last_name) 		||
		CASE
			WHEN in_gender IS NOT NULL THEN
				CHR(10) || 'Gender : ' || CASE WHEN in_gender = 'M' THEN 'MALE' ELSE 'FEMALE' END
			ELSE
				''
		END ||
		CASE
			WHEN in_birthdate IS NOT NULL THEN
				CHR(10) || 'Birthdate : ' || TO_CHAR(in_birthdate, 'DD/MM/YYYY')
			ELSE
				''
		END ||
		CASE
			WHEN in_email IS NOT NULL THEN
				CHR(10) || 'Email : ' || in_email
			ELSE
				''
		END ||
		CASE
			WHEN in_phone IS NOT NULL THEN
				CHR(10) || 'Phone : ' || in_phone
			ELSE
				''
		END ||
		'Group : ' || (SELECT STRING_AGG(g.name, ',') FROM groups g WHERE g.id = ANY(u.group_id::INT[])) || CHR(10) ||
		'Office : ' || (SELECT o.name FROM offices o WHERE o.id = u.office_id),
		u.user_id
	INTO
		v_new_value,
		v_user_id
	FROM
		users u
	WHERE
		u.id = in_id;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE ADMIN',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(v_user_id),
			in_ref_table    => 'users'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Admin has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
