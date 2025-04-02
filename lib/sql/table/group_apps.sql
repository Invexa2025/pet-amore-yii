DROP TABLE IF EXISTS group_apps;
CREATE TABLE IF NOT EXISTS group_apps(
    id                      SERIAL PRIMARY KEY,
    group_id                INT,
    app_code                TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
