DROP TABLE IF EXISTS business_applications;
CREATE TABLE IF NOT EXISTS business_applications(
    code            TEXT PRIMARY KEY,
    name            TEXT,
    status          INT DEFAULT 1
);

INSERT INTO business_applications(code, name) VALUES
('PET_AMORE', 'GROOMING APPLICATION');
