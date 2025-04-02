DROP FUNCTION sp_group_delete;
CREATE OR REPLACE FUNCTION sp_group_delete
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_group_id          IN groups.id%TYPE,
	in_create_by         IN groups.create_by%TYPE,
	in_owner_id          IN groups.owner_id%TYPE
)
AS $$
DECLARE
	v_cnt    		INT;
    v_group_name    groups.name%TYPE;
	v_old_value 	admin_history.old_value%TYPE;
	v_new_value 	admin_history.new_value%TYPE;
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
		'GROUP NAME : ' 	|| g.name || CHR(10) ||
		'DESCRIPTION : ' 	|| g.description,
        g.name
	INTO
		v_old_value,
        v_group_name
	FROM
		groups g
	WHERE
		g.id = in_group_id
        AND g.status = 1
		AND g.owner_id = in_owner_id;

	UPDATE groups
    SET
		status = 0,
		update_by = in_create_by,
		update_time = NOW()
	WHERE
		id = in_group_id
        AND status = 1
		AND owner_id = in_owner_id;

	UPDATE group_apps
    SET
        status = 0,
        update_by = in_create_by,
        update_time = NOW()
	WHERE
		group_id = in_group_id
        AND status = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'DELETE GROUP',
			in_old_value 	=> v_old_value,
			in_new_value 	=> '',
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> v_group_name,
			in_ref_table    => 'groups'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Selected group has been deleted';
	END IF;
END;
$$ LANGUAGE plpgsql;
