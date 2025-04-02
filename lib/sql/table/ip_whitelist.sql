DROP TABLE IF EXISTS ip_whitelist;
CREATE TABLE IF NOT EXISTS ip_whitelist(
    id                      SERIAL PRIMARY KEY,
    ip_address              TEXT,
    notes                   TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);
