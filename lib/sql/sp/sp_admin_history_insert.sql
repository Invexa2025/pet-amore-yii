DROP FUNCTION sp_admin_history_insert;
CREATE OR REPLACE FUNCTION sp_admin_history_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_action 			 IN admin_history.action%TYPE,
	in_old_value 		 IN admin_history.old_value%TYPE,
	in_new_value 		 IN admin_history.new_value%TYPE,
	in_description 		 IN admin_history.description%TYPE,
	in_create_by 		 IN admin_history.create_by%TYPE,
	in_owner_id 		 IN admin_history.owner_id%TYPE,
	in_action_to 		 IN admin_history.action_to%TYPE,
	in_ref_table 		 IN admin_history.ref_table%TYPE
)
AS $$
DECLARE
BEGIN
	out_num := 0;
	out_str := 'Success';

	INSERT INTO	admin_history
	(
		action,
		old_value,
		new_value,
		description,
		create_by,
		owner_id,
		action_to,
		ref_table
	)
	VALUES (
		UPPER(in_action),
		in_old_value,
		in_new_value,
		in_description,
		in_create_by,
		in_owner_id,
		in_action_to,
		in_ref_table
	);
END;
$$ LANGUAGE plpgsql;
