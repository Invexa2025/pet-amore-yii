DROP FUNCTION sp_log_admin_access_insert;
CREATE OR REPLACE FUNCTION sp_log_admin_access_insert
(
	out_num              OUT INTEGER,
	out_str              OUT VARCHAR,
	in_user_id 		 	 IN log_admin_access.user_id%TYPE,
	in_ip_address 		 IN log_admin_access.ip_address%TYPE,
	in_activity 		 IN log_admin_access.activity%TYPE,
	in_owner_id 		 IN log_admin_access.owner_id%TYPE,
	in_ref_code 		 IN log_admin_access.ref_code%TYPE DEFAULT NULL,
	in_type 		 	 IN log_admin_access.type%TYPE DEFAULT NULL
)
AS $$
DECLARE
BEGIN
	out_num := 0;
	out_str := 'Success';

	INSERT INTO	log_admin_access
	(
		user_id,
		ip_address,
		activity,
		ref_code,
		type,
		owner_id
	)
	VALUES (
		in_user_id,
		in_ip_address,
		in_activity,
		in_ref_code,
		in_type,
		in_owner_id
	);
END;
$$ LANGUAGE plpgsql;
