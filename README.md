# Team 10 — Medication Manager

CS/CE/SE 3354 Software Engineering — Fall 2026

A medication tracking application that lets users record their medications,
receive reminders when a dose is due, and keep a history of doses taken and missed.

## Technology Stack

- PHP 8.1 or later
- MySQL
- PHPUnit 12.5 for automated unit testing
- No framework; the application uses PHP's built-in session management,
  `password_hash()`/`password_verify()`, and PDO for data access

## Repository Layout

    src/      application source
    tests/    PHPUnit test suite
    docs/     deliverable reports and presentation materials

## Running the Tests

Requires PHP 8.1 or later (developed and tested on PHP 8.3) and Composer.

    composer install
    composer dump-autoload
    ./vendor/bin/phpunit

Expected output: `OK (5 tests, 13 assertions)`

## Team

Jana Pulak · Devaansh Singhal · Edgar Ordonez · Diego Valle ·
Michael Oluwafemi · Kieran Roundy · Muhammad Saad Sadiq · Asdaq Aufer