# Testing Custom Plugins

This workspace contains the environment used to run the Ceon Help Center plugin, but the tests themselves live in the plugin repository (`tests/php/` and `tests/e2e/` inside `ceon-help-center`). Use the following commands from that repo:

## PHP/unit tests

```bash
curl -Ls https://phar.phpunit.de/phpunit-11.phar -o phpunit.phar
php phpunit.phar --configuration phpunit.xml
```

These exercises cover hook logic, output injection, and configuration parsing.

## Browser tests (Playwright)

```bash
npm ci
npx playwright install --with-deps chromium
npx playwright test
```

Playwright points at `tests/fixtures/ceonHelpCenter.fixture.html` and verifies the popup markup and CSS/JS interactions.

## Smoke tests

From this workspace you can run `scripts/smoke-ojs.sh` after `docker compose up -d` to ensure the container starts, the plugin folder is mounted, and the PHP files parse successfully. The smoke test is a useful precursor to e2e/browser work.
