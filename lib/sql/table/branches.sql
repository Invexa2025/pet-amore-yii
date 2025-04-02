DROP TABLE IF EXISTS branches;
CREATE TABLE IF NOT EXISTS branches(
    id                      SERIAL PRIMARY KEY,
    business_id             INT,
    name                    TEXT,
    code                    TEXT
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
