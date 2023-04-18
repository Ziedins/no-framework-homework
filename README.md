# Homework entry (no framework)
This project answer to a homework assignment issued by sunfinancegroup
[Assignment](https://github.com/sunfinancegroup/docs-home-task-nordic)

## Prerequisites
* PHP8.2
* sqlite3
* composer

## SETUP :

### Run migrations :

* sqlite3 loans.db < application/migrations/01-tables.sql
* sqlite3 loans.db < application/migrations/02-records.sql

### composer install

### Run csv import
example : bin/import --file=public/import/payments.csv

### Report by Date
example : bin/report --date=YYYY-MM-DD

### Run tests
./vendor/bin/phpunit --testdox tests


