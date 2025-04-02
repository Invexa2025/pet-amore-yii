DROP TABLE IF EXISTS add_ons;
CREATE TABLE IF NOT EXISTS add_ons(
    id                      SERIAL PRIMARY KEY,
    code                    TEXT,
    description             TEXT,
    effective_date          DATE,
    fee                     NUMERIC,
    vat                     NUMERIC,
    notes                   TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1,
    owner_id                INT
);