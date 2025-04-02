DROP FUNCTION sp_group_apps_insert;
CREATE OR REPLACE FUNCTION sp_group_apps_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_group_id        	 IN groups.id%TYPE,
	in_apps        		 IN TEXT,
	in_create_by         IN groups.create_by%TYPE,
	in_owner_id          IN groups.owner_id%TYPE
)
AS $$
DECLARE
    rg              RECORD;
    v_group_name    groups.name%TYPE;
	v_batch_id    	group_apps_history.batch_id%TYPE;
	v_new_value 	admin_history.new_value%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		NEXTVAL('group_apps_history_batch_id_seq')
	INTO
		v_batch_id;

    SELECT
        g.name
    INTO
        v_group_name
    FROM
        groups g
    WHERE
        g.status = 1
        AND g.id = in_group_id
        AND g.owner_id = in_owner_id;

    FOR rg IN (
        SELECT
            (TRIM(REGEXP_SPLIT_TO_TABLE(in_apps, ',')))::TEXT AS app
    ) LOOP
		INSERT INTO group_apps
		(
			group_id,
			app_code,
			create_by,
			owner_id
		)
		VALUES (
			in_group_id,
			rg.app,
			in_create_by,
			in_owner_id
		);

		INSERT INTO group_apps_history
		(
			group_id,
			app_code,
			batch_id,
			create_by,
			owner_id
		)
		VALUES (
			in_group_id,
			rg.app,
			v_batch_id,
			in_create_by,
			in_owner_id
		);
	END LOOP;

	SELECT
		'GROUP NAME : ' 		|| UPPER(g.name) || CHR(10) ||
		'DESCRIPTION : ' 		|| UPPER(g.description) || CHR(10) ||
		'BATCH ID : '	        || v_batch_id
	INTO
		v_new_value
	FROM
		groups g
	WHERE
		g.id = in_group_id
        AND g.status = 1
		AND g.owner_id = in_owner_id;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'INSERT GROUP',
			in_old_value 	=> '',
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> v_group_name,
			in_ref_table    => 'groups'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'New group has been added';
	END IF;
END;
$$ LANGUAGE plpgsql;
