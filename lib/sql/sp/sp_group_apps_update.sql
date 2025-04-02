DROP FUNCTION sp_group_apps_update;
CREATE OR REPLACE FUNCTION sp_group_apps_update
(
	out_num              OUT   INTEGER,
	out_str              OUT   VARCHAR,
	in_group_id        	 IN    groups.id%TYPE,
	in_apps        		 IN    VARCHAR[],
	in_edit_id 			 IN    group_apps_history.edit_id%TYPE,
	in_contact_id        IN    groups.create_by%TYPE,
	in_owner_id          IN    groups.owner_id%TYPE
)
AS $$
DECLARE
	v_edit_id    	group_apps_history.edit_id%TYPE;
	v_new_value 	admin_history.new_value%TYPE;
BEGIN
	out_num := 0;
	out_str := 'Success';

	FOR i IN 1 .. array_length(in_apps, 1) LOOP
		INSERT INTO group_apps
		(
			group_id,
			app_code,
			create_by,
			owner_id
		)
		VALUES (
			in_group_id,
			in_apps[i],
			in_contact_id,
			in_owner_id
		);

		INSERT INTO group_apps_history
		(
			group_id,
			app_code,
			edit_id,
			create_by,
			owner_id
		)
		VALUES (
			in_group_id,
			in_apps[i],
			in_edit_id,
			in_contact_id,
			in_owner_id
		);
	END LOOP;

	IF out_num = 0 THEN
		out_str := 'Selected group has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;