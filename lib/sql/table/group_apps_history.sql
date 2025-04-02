DROP TABLE IF EXISTS group_apps_history;
CREATE TABLE IF NOT EXISTS group_apps_history(
    id                  SERIAL PRIMARY KEY,
    group_id            INT,
    app_code            TEXT,
    batch_id            INT,
    create_by           INT,
    create_time         TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    update_by           INT,
    update_time         TIMESTAMPTZ,
    status              INT DEFAULT 1,
    owner_id            INT
);
