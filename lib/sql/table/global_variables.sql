DROP TABLE global_variables;
CREATE TABLE global_variables(
   var_name       VARCHAR(64),
   var_value      VARCHAR(128),
   var_number     NUMERIC,
   create_by      INT,
   create_time    TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
   update_by      INT,
   update_time    TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
   status         INT DEFAULT 1,
   owner_id       INT
);
