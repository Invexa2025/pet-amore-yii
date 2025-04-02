DROP FUNCTION sp_business_insert;
CREATE FUNCTION sp_business_insert(

) 
DECLARE
    v_business_id       businesses.id%TYPE;
BEGIN
    INSERT INTO businesses(
        name,
    )
    VALUES (
        in_business_name
    ) RETURNINIG id INTO v_business_id;
END