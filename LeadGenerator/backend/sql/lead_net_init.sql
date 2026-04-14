-- lead_net_init.sql
-- Minimal schema required for LeadNet.Api auth + activity logging.
-- Run inside the `lead_net` database.

CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE TABLE IF NOT EXISTS users (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name varchar(255) NOT NULL,
  email varchar(255) UNIQUE,
  phone varchar(50) UNIQUE,
  password varchar(255) NOT NULL,
  role varchar(50) NOT NULL,
  is_active boolean NOT NULL DEFAULT true,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS activity_logs (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id uuid NULL REFERENCES users(id) ON DELETE SET NULL,
  event_type varchar(100) NOT NULL,
  email_or_phone varchar(255) NULL,
  role varchar(50) NULL,
  realm varchar(50) NULL,
  ip_address varchar(45) NULL,
  user_agent text NULL,
  metadata text NULL,
  occurred_at_utc timestamptz NOT NULL DEFAULT now()
);

