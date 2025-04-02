DROP FUNCTION sp_user_admin_insert;
CREATE OR REPLACE FUNCTION sp_user_admin_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_user_id           IN users.user_id%TYPE,
	in_password 		 IN users.password%TYPE,
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
	v_cnt    	    INT;
	v_Id 			INT;
	v_new_value	    admin_history.new_value%TYPE;
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
		u.user_id = UPPER(in_user_id)
		AND u.business_id = in_owner_id;

	IF v_cnt > 0 THEN
		out_num := 1;
		out_str := 'Admin already exists';

		RETURN;
	END IF;

	INSERT INTO users
	(
		business_id,
        user_id,
		password,
        first_name,
        last_name,
        gender,
        birthdate,
        email,
        phone,
        group_id,
        office_id,
		role_type,
        create_by,
        create_time
	)
	VALUES
    (
		in_owner_id,
		UPPER(in_user_id),
		in_password,
        UPPER(in_first_name),
        UPPER(in_last_name),
        UPPER(in_gender),
        in_birthdate,
        UPPER(in_email),
        in_phone,
        STRING_TO_ARRAY(in_group, ',')::INT[],
        in_office,
		'SA',
        in_create_by,
        NOW()
	) RETURNING id INTO v_Id;

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
		'Office : ' || (SELECT o.name FROM offices o WHERE o.id = u.office_id)
	INTO
		v_new_value
	FROM
		users u
	WHERE
		u.id = v_Id;
		
	-- FOR gr IN (
	-- 	SELECT
    --         (TRIM(REGEXP_SPLIT_TO_TABLE(in_group, ',')))::INT AS group_id
	-- ) LOOP
	-- 	INSERT INTO business_apps
	-- 	(
	-- 		business_id,
	-- 		group_id,
	-- 		create_by,
	-- 		create_time
	-- 	)
	-- 	VALUES
	-- 	(
	-- 		in_owner_id,
	-- 		gr.group_id,
	-- 		in_create_by,
	-- 		NOW()
	-- 	);
	-- END LOOP;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'INSERT ADMIN',
			in_old_value 	=> '',
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> UPPER(in_user_id),
			in_ref_table    => 'users'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'New Admin has been added';
	END IF;
END;
$$ LANGUAGE plpgsql;
