DROP TABLE IF EXISTS bookings;
CREATE TABLE IF NOT EXISTS bookings(
    id                  BIGSERIAL PRIMARY KEY,
    code                TEXT,
    contact_name        TEXT,
    contact_phone       TEXT,
    contact_email       TEXT,
    payment_limit       TIMESTAMPTZ DEFAULT NULL,
    notes               TEXT,
    create_by           INT,
    create_time         TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by           INT,
    update_time         TIMESTAMPTZ,
    status              INT DEFAULT 1,
    owner_id            INT
);
