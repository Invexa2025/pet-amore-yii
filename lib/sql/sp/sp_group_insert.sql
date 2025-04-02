DROP FUNCTION sp_group_insert;
CREATE OR REPLACE FUNCTION sp_group_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	out_group_id 		 OUT INTEGER,
	in_group_name        IN groups.name%TYPE,
	in_group_desc        IN groups.description%TYPE,
	in_role_type         IN groups.role_type%TYPE,
	in_create_by         IN groups.create_by%TYPE,
	in_owner_id          IN groups.owner_id%TYPE
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
		groups g
	WHERE
		g.name = UPPER(in_group_name)
		AND g.status = 1
		AND g.owner_id = in_owner_id;

	IF v_cnt > 0 THEN
		out_num := 1;
		out_str := 'Group name already exists';

		RETURN;
	END IF;

	INSERT INTO groups
	(
		name,
		description,
		role_type,
		create_by,
		owner_id
	)
	VALUES (
		UPPER(in_group_name),
		UPPER(in_group_desc),
		in_role_type,
		in_create_by,
		in_owner_id
	)
	RETURNING id INTO out_group_id;

	IF out_num = 0 THEN
		out_str := 'New group has been added';
	END IF;
END;
$$ LANGUAGE plpgsql;
