DROP FUNCTION fn_get_user_id;
CREATE OR REPLACE FUNCTION fn_get_user_id(
    in_user_uid 				IN users.id%TYPE,
	in_use_business		        IN INT DEFAULT 0 /* 0=user_id , 1=user_id@domain, 2=user_id@name */
) RETURNS VARCHAR AS
$$
DECLARE
    out_str   TEXT := '';
BEGIN
    IF in_use_business = 0 THEN

        SELECT u.user_id INTO out_str
        FROM
            users u
        WHERE
            u.id = in_user_uid;

    ELSIF in_use_business = 1 THEN

        SELECT u.user_id || '@' || (SELECT b.domain FROM businesses b WHERE b.id = u.business_id) INTO out_str
        FROM
            users u
        WHERE
            u.id = in_user_uid;

    ELSIF in_use_business = 2 THEN

        SELECT u.user_id || '@' || (SELECT b.name FROM businesses b WHERE b.id = u.business_id) INTO out_str
        FROM
            users u
        WHERE
            u.id = in_user_uid;

    END IF;

    RETURN out_str;
END;
$$ LANGUAGE plpgsql;
