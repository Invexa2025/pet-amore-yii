DROP TABLE IF EXISTS groups;
CREATE TABLE IF NOT EXISTS groups(
    id                      SERIAL PRIMARY KEY,
    name                    TEXT,
    description             TEXT,
    role_type               TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
