DROP TABLE IF EXISTS log_admin_access;
CREATE TABLE IF NOT EXISTS log_admin_access (
    id                      BIGSERIAL PRIMARY KEY,
    user_id                 INT,
    ip_address              TEXT,
    activity                TEXT,
    ref_code                TEXT,
    type                    TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
