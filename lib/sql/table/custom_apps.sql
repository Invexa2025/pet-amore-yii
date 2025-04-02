DROP TABLE IF EXISTS custom_apps;
CREATE TABLE IF NOT EXISTS custom_apps (
    id                      SERIAL PRIMARY KEY,
    code                    TEXT,
    name                    TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
