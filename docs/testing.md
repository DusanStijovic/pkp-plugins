# Testing Custom Plugins

Use a layered approach.

Test files now live in:

- `tests/php/SimplePopupButtonPluginTest.php`
- `tests/php/bootstrap.php`
- `tests/e2e/simplePopupButton.spec.js`
- `tests/fixtures/simplePopupButton.fixture.html`

## How to run tests

### 1. Validation and packaging checks

These are run automatically in GitHub Actions through `Plugin CI`.

They cover:

- PHP syntax
- `version.xml` validation
- required plugin file checks
- packaging into `dist/*.zip`

### 2. Run PHP tests locally

PHP tests use PHPUnit and the lightweight PKP/OJS stubs from `tests/php/bootstrap.php`.

```bash
curl -Ls https://phar.phpunit.de/phpunit-11.phar -o phpunit.phar
php ./phpunit.phar --configuration phpunit.xml
```

What these tests are good for:

- plugin method behavior
- hook registration
- output injection logic
- template manager interactions

What they are not for:

- full OJS bootstrapping
- database-backed behavior
- browser interaction

### 3. Run browser tests locally

Playwright uses a small static fixture page and tests the plugin's shipped frontend assets.

```bash
npm ci
npx playwright install --with-deps chromium
npx playwright test
```

Helpful commands:

```bash
npx playwright test --ui
npx playwright show-report
```

On failure, Playwright now keeps:

- screenshots
- video
- trace on first retry

These are uploaded in CI as artifacts.

### 4. Run the Docker smoke test locally

This checks that the local OJS stack starts and the plugin is mounted in the app container.

```bash
chmod +x scripts/smoke-ojs.sh
./scripts/smoke-ojs.sh
```

## 1. Static validation

- PHP syntax checks
- `version.xml` validation
- required file checks
- packaging check

These run in GitHub Actions through `Plugin CI`.

## 2. Local integration testing

Run OJS locally in Docker and test the plugin inside a real OJS instance.

Examples:

```bash
docker compose up -d
docker exec pkp_app_ojs php -l /var/www/html/plugins/generic/simplePopupButton/SimplePopupButtonPlugin.php
```

Then:

- enable the plugin in OJS admin
- load frontend/backend pages
- check browser console and network
- inspect `volumes/logs/app/error.log`

## 3. Browser tests

Playwright checks the shipped frontend assets and popup behavior in a browser.

## 4. Release validation

Before production deployment:

- build the plugin zip
- test the exact tagged plugin version
- deploy the same artifact to production

## 5. Docker smoke test

CI also boots the local OJS Docker stack and checks:

- the app container starts
- the plugin is mounted into OJS
- the plugin PHP file parses in the container
- the local HTTP endpoint answers

## How to write new tests

### Add a new PHP test

1. Create or extend a file in `tests/php/`
2. Keep the test focused on isolated plugin behavior
3. If the plugin needs PKP/OJS classes, add or extend stubs in `tests/php/bootstrap.php`

Example structure:

```php
public function testSomething(): void
{
    $plugin = new MyPlugin();
    $result = $plugin->someMethod();

    $this->assertSame('expected', $result);
}
```

Use PHP tests for:

- string/output transformation
- hook registration
- settings mapping
- branching logic in plugin methods

### Add a new browser test

1. Add or reuse a fixture in `tests/fixtures/`
2. Add a spec in `tests/e2e/`
3. Point Playwright to the fixture page
4. Assert visible browser behavior

Example:

```js
test('does something in the browser', async ({ page }) => {
  await page.goto('/tests/fixtures/my.fixture.html');
  await page.locator('#myButton').click();
  await expect(page.locator('#myModal')).toBeVisible();
});
```

Use browser tests for:

- click behavior
- modal open/close
- keyboard interaction
- visible text/states
- CSS/JS integration

### Good testing split

Prefer this rule:

- PHP test for logic
- Playwright test for user-visible behavior
- Docker smoke test for container/runtime confidence
