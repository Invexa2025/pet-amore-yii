DROP TABLE global_variables_desc;
CREATE TABLE global_variables_desc(
   var_name       VARCHAR(64),
   var_desc       VARCHAR(512),
   var_value      VARCHAR(128),
   var_number     NUMERIC,
   var_group      TEXT,
   status         INT DEFAULT 1
);

TRUNCATE global_variables_desc;

INSERT INTO global_variables_desc(var_name, var_desc, var_value, var_number, var_group) VALUES 
('session_timeout', 'Define session timeout per companies; var_number in seconds. If blank, set to default as 900 seconds.', '', 900, 'PET_AMORE'),
('password_validity', 'Define how long the user''s password valids in the system; var_number in days. If blank, set to default as 90 days.', '', 90, 'PET_AMORE');
