# Car Ads API

Yii2 REST API service for car advertisements.

## Scope

- PHP 8, Yii2, PostgreSQL.
- REST endpoints for creating, reading and listing car advertisements.
- Layered structure: Controller, Request Model, Service, Repository, DataMapper, Entity, ActiveRecord.
- Optional technical car characteristics are modeled as a has-one `car_option` record.

## Main Commands

- `composer install`
- `php yii migrate/up`
- `php yii serve --port=8080`
- `composer test`
