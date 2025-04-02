DROP TABLE IF EXISTS businesses;
CREATE TABLE IF NOT EXISTS businesses(
    id                      SERIAL PRIMARY KEY,
    address_id              INT,
    name                    TEXT,
    domain                  TEXT,
    admin_id                INT,
    approved_time           TIMESTAMPTZ,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1
);

INSERT INTO businesses(address_id, name, domain, admin_id, approved_time, create_by) VALUES (1, 'INVEXA TECH SOLUTIONS', 'SYSTEM', 1, NOW(), 1);
