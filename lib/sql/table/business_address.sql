DROP TABLE IF EXISTS business_address;
CREATE TABLE IF NOT EXISTS business_address(
    id                      SERIAL PRIMARY KEY,
    organization_id         INT,
    address                 TEXT,
    city_code               CHAR(3),
    country_code            CHAR(2),
    phone                   TEXT,
    fax                     TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1
);

INSERT INTO business_address(organization_id, country_code, create_by) VALUES (1, 'ID', 1);
