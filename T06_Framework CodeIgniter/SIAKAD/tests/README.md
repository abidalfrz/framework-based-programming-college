# Application regression tests

Run `vendor/bin/phpunit` from the application directory after `composer install`.

- `unit/`: local health checks, setup-target rejection and application business rules where present.
- `feature/AccessTest.php`: real route/filter tests for login, guest access, roles and unsupported GET logout.
- KampusMart also includes `database/ProdukAggregateTest.php`, a read-only MySQL check enabled with `PBKK_MYSQL_ACCEPTANCE=1`.

The default suite does not migrate or seed the application database. Generic starter SQLite/session examples and their dummy migrations/models are deliberately excluded from the publication candidate. Tests are kept when they check actual application behavior.

See [the root verification record](../../docs/VERIFICATION.md) for current results, historical E2E evidence, and the distinction between test cases and assertions.
