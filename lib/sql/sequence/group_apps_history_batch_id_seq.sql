DROP SEQUENCE group_apps_history_batch_id_seq;
CREATE SEQUENCE group_apps_history_batch_id_seq
INCREMENT BY 1
MINVALUE 1
NO MAXVALUE
START WITH 1
CACHE 1
OWNED BY group_apps_history.batch_id;