DROP FUNCTION sp_business_member_insert;
CREATE OR REPLACE FUNCTION sp_business_member_insert(
    out_num                     OUT INTEGER,
	out_str                     OUT VARCHAR,
    in_business_name            IN businesses.name%TYPE,
    in_business_domain          IN businesses.domain%TYPE,
    in_business_address         IN businesses.address%TYPE,
    in_business_city            IN businesses.city%TYPE,
    in_business_country         IN businesses.country%TYPE,
    in_business_phone           IN businesses.phone%TYPE,
    in_business_fax             IN businesses.fax%TYPE,
    in_first_name               IN users.first_name%TYPE,
    in_last_name                IN users.last_name%TYPE,
    in_gender                   IN users.gender%TYPE,
    in_birthdate                IN users.birthdate%TYPE,
    in_email                    IN users.email%TYPE,
    in_phone                    IN users.phone%TYPE
) AS $$
DECLARE
BEGIN
    out_num := 0;
    out_str := 'Success';


    SELECT
		*
	INTO
		out_num,
		out_str
	FROM
		sp_admin_history_insert
		(
			in_action 		=> 'CHANGE PASSWORD',
			in_old_value 	=> v_old_pass,
			in_new_value 	=> in_password,
			in_description 	=> '',
			in_create_by 	=> in_user_uid,
			in_owner_id 	=> in_owner_id,
			in_action_to 	=> in_user_uid::TEXT,
			in_ref_table    => 'users'
		);

	IF out_num = 0 THEN
		out_str := 'Password successfully changed';
	END IF;
END;
$$ LANGUAGE plpgsql;
