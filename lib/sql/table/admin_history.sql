DROP TABLE IF EXISTS admin_history;
CREATE TABLE IF NOT EXISTS admin_history(
    id                      BIGSERIAL PRIMARY KEY,
    action                  TEXT,
    old_value               TEXT,
    new_value               TEXT,
    description             TEXT,
    action_to               TEXT,
    ref_table               TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
