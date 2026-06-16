# Status

Last updated: 2026-06-16.

## Current State

- Application code, migration, Docker Compose, README and unit tests are present.
- The API exposes endpoints for creating, reading and listing car advertisements.
- The domain flow is separated into Controller, Request Model, Service, Repository, DataMapper, Entity and ActiveRecord layers.
- `composer.lock` is committed for reproducible dependency installation.

## Notes

- Runtime availability depends on local PHP/PostgreSQL or Docker Compose setup.
