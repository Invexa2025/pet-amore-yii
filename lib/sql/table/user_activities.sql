DROP TABLE IF EXISTS user_activities;
CREATE TABLE IF NOT EXISTS user_activities(
    id                  BIGSERIAL PRIMARY KEY,
    admin_id            INT,
    activity            TEXT,
    create_time         TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status              INT DEFAULT 1
);
