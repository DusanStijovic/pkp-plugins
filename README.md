# OJS Plugin Development Workspace

This repository is set up for custom OJS plugin development.

Suggested repository name: `ojs-plugins-workspace`

The current model is:

- Docker is used locally for development and testing.
- Production OJS may run without Docker.
- Only plugin code is intended to be deployed to production.

## Repository layout

- `plugins/generic/`: custom OJS plugins
- `docker-compose.yml`: local development stack
- `Dockerfile`: local development image, including optional Xdebug support
- `scripts/package-plugin.sh`: packages a plugin directory into a deployable zip
- `docs/development.md`: local development workflow
- `docs/deployment.md`: production deployment approach
- `tests/php/`: PHPUnit plugin logic tests
- `tests/e2e/`: Playwright browser tests

## Local development

Start the local stack:

```bash
docker compose up -d
```

If you are using the local image build with Xdebug enabled:

```bash
docker compose build app
docker compose up -d app
```

The current custom plugin is:

- `plugins/generic/simplePopupButton`

## Plugin packaging

Create a deployable zip for a plugin:

```bash
./scripts/package-plugin.sh plugins/generic/simplePopupButton
```

That writes a zip to `dist/`.

## Production deployment

For a non-Docker production OJS server, deploy only the plugin directory or the generated plugin zip into the production OJS install:

```text
plugins/generic/simplePopupButton
```

See:

- `docs/development.md`
- `docs/deployment.md`
- `docs/testing.md`

For test commands and examples for adding new tests, start with `docs/testing.md`.

## GitHub automation

This repository now includes workflows for:

- plugin validation on PRs and `main`
- plugin zip packaging
- GitHub release assets from plugin tags
- optional SSH/`rsync` deployment to production

Suggested release tag format:

```text
simplePopupButton-v1.0.0
```
