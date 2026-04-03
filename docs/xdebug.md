# Xdebug Setup

This project uses Xdebug on port `9003`.

Config file:

- `config/php.xdebug.ini`

## Start

```bash
docker compose build app
docker compose up -d app
```

## VS Code

1. Install the PHP Debug extension.
2. Start `Listen For Xdebug`.
3. Put a breakpoint in a PHP file under the workspace.
4. Open OJS in the browser.

## Verify

```bash
docker exec pkp_app_ojs php -m | grep xdebug
docker exec pkp_app_ojs php -i | grep -i xdebug
```
