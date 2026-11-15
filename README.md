# Team 10 — Medication Manager

CS/CE/SE 3354 Software Engineering — Fall 2026

A medication tracking application that lets users record their medications,
receive reminders when a dose is due, and keep a history of doses taken and missed.
Our project includes software requirements, system design, cost estimation, and automated unit testing

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

## Installation and setup
Require PHP and composer (dev and tested on PHP 8.3)

1. Clone repository
git clone clone https://github.com/DevaanshSinghal06/F26_3354_Team10.git
cd F26_3354_Team10

2. Install dependenceis:
`composer install`
`composer dump-autoload`

3. Environment config:
4. No environment vars or database credentials required to run the current Dose unit test. My SQL is part of the planned app design but not needed for test

##Run tests locally
Current implementation includes Dose class and its automated unit tests. Full web app not yet implemented
Form project root directory run:
`php vendor/bin/phpunit`

Expected output: `OK (5 tests, 13 assertions)`

## Team

Jana Pulak · Devaansh Singhal · Edgar Ordonez · Diego Valle ·
Michael Oluwafemi · Kieran Roundy · Muhammad Saad Sadiq · Asdaq Aufer
