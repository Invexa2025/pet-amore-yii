DROP FUNCTION sp_group_update;
CREATE OR REPLACE FUNCTION sp_group_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	out_batch_id 		 OUT INTEGER,
	in_group_id          IN groups.id%TYPE,
	in_group_name        IN groups.name%TYPE,
	in_group_desc        IN groups.description%TYPE,
	in_create_by         IN groups.create_by%TYPE,
	in_owner_id          IN groups.owner_id%TYPE
)
AS $$
DECLARE
	v_cnt    		INT;
	v_group_name 	TEXT;
	v_old_batch_id  group_apps_history.batch_id%TYPE;
	v_old_value 	admin_history.old_value%TYPE;
	v_new_value 	admin_history.new_value%TYPE;
	v_new_batch_id  group_apps_history.batch_id%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		groups g
	WHERE
		g.id = in_group_id
		AND g.status = 1
		AND g.owner_id = in_owner_id;

	IF v_cnt != 1 THEN
		out_num := 1;
		out_str := 'Group is not found';

		RETURN;
	END IF;

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		groups g
	WHERE
		g.id != in_group_id
		AND g.name = UPPER(in_group_name)
		AND g.status = 1
		AND g.owner_id = in_owner_id;

	IF v_cnt > 0 THEN
		out_num := 1;
		out_str := 'Group name already exists';

		RETURN;
	END IF;

	SELECT DISTINCT
		gah.batch_id
	INTO
		v_old_batch_id
	FROM
		group_apps_history gah
	WHERE
		gah.group_id = in_group_id
		AND gah.owner_id = in_owner_id
		AND gah.status = 1;

	SELECT
		'GROUP NAME : ' 		|| UPPER(g.name) || CHR(10) ||
		'DESCRIPTION : ' 		|| UPPER(g.description) || CHR(10) ||
		'BATCH ID : ' 			|| v_old_batch_id,
		g.name
	INTO
		v_old_batch_id,
		v_group_name
	FROM
		groups g
	WHERE
		g.id = in_group_id
		AND g.status = 1
		AND g.owner_id = in_owner_id;

	UPDATE group_apps
    SET
        status = 0,
        update_by = in_create_by,
        update_time = NOW()
	WHERE
		group_id = in_group_id
        AND status = 1;

	UPDATE groups SET
		name = UPPER(in_group_name),
		description = UPPER(in_group_desc),
		update_by = in_create_by,
		update_time = CURRENT_TIMESTAMP
	WHERE
		id = in_group_id
		AND status = 1
		AND owner_id = in_owner_id;

	UPDATE group_apps_history SET
		status = 0,
		update_by = in_create_by,
		update_time = CURRENT_TIMESTAMP
	WHERE
		group_id = in_group_id
		AND status = 1
		AND owner_id = in_owner_id;

	SELECT
		NEXTVAL('group_apps_history_batch_id_seq')
	INTO
		out_batch_id;

	v_new_value := '' ||
		'GROUP NAME : ' 		|| UPPER(in_group_name) || CHR(10) ||
		'DESCRIPTION : ' 		|| UPPER(in_group_desc) || CHR(10) ||
		'BATCH ID : ' 			|| out_batch_id
	;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE GROUP',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> v_group_name,
			in_ref_table    => 'groups'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Selected group has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
