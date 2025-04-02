DROP TABLE IF EXISTS status_reference;
CREATE TABLE IF NOT EXISTS status_reference(
    table_name      TEXT,
    description     TEXT,
    status          INT
);
