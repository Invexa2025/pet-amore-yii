DROP FUNCTION sp_global_variables_desc_update;
CREATE OR REPLACE FUNCTION sp_global_variables_desc_update
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_var_name		     IN global_variables_desc.var_name%TYPE,
	in_var_desc		     IN global_variables_desc.var_desc%TYPE,
	in_var_value         IN global_variables_desc.var_value%TYPE,
	in_var_number        IN global_variables_desc.var_number%TYPE,
    in_var_group         IN global_variables_desc.var_group%TYPE,
	in_create_by         IN users.id%TYPE,
	in_owner_id          IN businesses.id%TYPE
)
AS $$
DECLARE
	v_cnt               INT;
	v_old_value	        admin_history.old_value%TYPE; 
	v_new_value	        admin_history.new_value%TYPE; 
BEGIN
	out_num := 0;
	out_str := 'Success';

	SELECT
		COUNT(1)
	INTO
		v_cnt
	FROM
		global_variables_desc gv
	WHERE
        gv.var_name = LOWER(in_var_name)
		AND gv.status = 1;

	IF v_cnt = 0 THEN
		out_num := 1;
		out_str := 'Global Variables Desc is not found';

		RETURN;
	END IF;
	
	SELECT
		'Variable Name : ' 		|| LOWER(gv.var_name) 	|| CHR(10) ||
		'Description : ' 		|| (gv.var_desc) 		|| CHR(10) ||
		'Var Value : '	        || (gv.var_value)       || CHR(10) ||
        'Var Number : '	        || (gv.var_number)      || CHR(10) ||
        'Var Group : '	        || (gv.var_group)
	INTO
		v_new_value
	FROM
		global_variables_desc gv
	WHERE
		gv.var_name = LOWER(in_var_name)
		AND gv.status  = 1;

	UPDATE 
		global_variables_desc
	SET 
        var_name = in_var_name,
        var_desc = in_var_desc,
        var_value = in_var_value,
        var_number = CAST(in_var_number AS NUMERIC),
        var_group = in_var_group
	WHERE
		var_name = LOWER(in_var_name)
		AND status = 1;

	SELECT
		'Variable Name : ' 		|| LOWER(gv.var_name) 	|| CHR(10) ||
		'Description : ' 		|| (gv.var_desc) 		|| CHR(10) ||
		'Var Value : '	        || (gv.var_value)       || CHR(10) ||
        'Var Number : '	        || (gv.var_number)      || CHR(10) ||
        'Var Group : '	        || (gv.var_group)
	INTO
		v_new_value
	FROM
		global_variables_desc gv
	WHERE
		gv.var_name = LOWER(in_var_name)
		AND gv.status  = 1;

	SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'UPDATE GLOBAL VARIABLES DESC',
			in_old_value 	=> v_old_value,
			in_new_value 	=> v_new_value,
			in_description 	=> '',
			in_create_by 	=> in_create_by,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> LOWER(in_var_name),
			in_ref_table    => 'global_variables_desc'::TEXT
		);

	IF out_num = 0 THEN
		out_str := 'Global Variables Desc has been updated';
	END IF;
END;
$$ LANGUAGE plpgsql;
