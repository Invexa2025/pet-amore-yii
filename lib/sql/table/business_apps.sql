DROP TABLE IF EXISTS business_apps;
CREATE TABLE IF NOT EXISTS business_apps (
    id                      SERIAL PRIMARY KEY,
    business_id             INT,
    group_id                INT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
