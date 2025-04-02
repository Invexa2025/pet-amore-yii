DROP TABLE IF EXISTS addresses;
CREATE TABLE IF NOT EXISTS addresses(
    id                      SERIAL PRIMARY KEY,
    address                 TEXT,
    country_code            CHAR(2),
    city_code               CHAR(3),
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ,
    status                  INT DEFAULT 1,
    owner_id                INT
);

INSERT INTO addresses(address, country_code, city_code, create_by, owner_id) VALUES('Tanjung Duren Utara No.10, Jakarta Barat', 'ID', 'JKT', 1, 1);
