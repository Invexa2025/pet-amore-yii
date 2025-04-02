DROP TABLE IF EXISTS offices;
CREATE TABLE IF NOT EXISTS offices(
    id                      SERIAL PRIMARY KEY,
    address_id              INT,
    code                    TEXT,
    name                    TEXT,
    phone                   TEXT,
    fax                     TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ,
    status                  INT DEFAULT 1,
    owner_id                INT
);

INSERT INTO offices(address_id, code, name, phone, fax, create_by, owner_id) VALUES (1, 'SYS', 'SYS', '081929797930', null, 1, 1);
