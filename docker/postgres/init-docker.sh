#!/bin/bash
set -e

psql --username "$POSTGRES_USER" -f /tmp/defAccess.sql
psql --username "$POSTGRES_USER" -d helpee -c 'create extension if not exists "uuid-ossp";'
