CREATE TABLE IF NOT EXISTS tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    token VARCHAR(64) UNIQUE NOT NULL,
    opportunity_id VARCHAR(24) NOT NULL,
    form_type VARCHAR(10) NOT NULL CHECK(form_type IN ('checkin', 'checkout')),
    used BOOLEAN DEFAULT 0,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_token ON tokens(token);
CREATE INDEX IF NOT EXISTS idx_opportunity ON tokens(opportunity_id);

CREATE TABLE IF NOT EXISTS form_submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    opportunity_id VARCHAR(24) NOT NULL,
    form_type VARCHAR(10) NOT NULL,
    submitted_by VARCHAR(100),
    ip_address VARCHAR(45),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    pdf_filename VARCHAR(255)
);
