DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users(
    id                      SERIAL PRIMARY KEY,
    business_id             INT,
    office_id               INT,
    user_id                 TEXT,
    first_name              TEXT,
    last_name               TEXT,
    password                TEXT,
    gender                  CHAR(1),
    email                   TEXT,
    phone                   TEXT,
    birthdate               DATE,
    last_activity           TIMESTAMPTZ,
    group_id                INT[],
    password_expiry_time    TIMESTAMPTZ,
    role_type               TEXT,
    create_by               INT,
    create_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by               INT,
    update_time             TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    status                  INT DEFAULT 1
);

INSERT INTO users(business_id, office_id, user_id, first_name, last_name, email, password, role_type, create_by) VALUES (1, 1, 'SYS', 'SYSTEM', 'ADMIN', 'SUSANTO2025.ID@GMAIL.COM', '$2y$10$FSo2ZEvJZtQxCE9KVyTCJubJ4cM0AOL702OvGOQcYOAjBj23FZJ3C', 'SSA', 1);
