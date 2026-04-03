# Development Workflow

This repository is optimized for local OJS plugin development.

## Local development

- Use Docker locally to run OJS and MariaDB.
- Keep custom plugins in `plugins/generic/`.
- Mount the plugin into the local OJS container through `docker-compose.yml`.

## Production model

- Production OJS can stay outside Docker.
- Deploy only the plugin directory or plugin zip to the production OJS server.
- Do not deploy this repo's local Docker setup to production unless you intentionally adopt a containerized production stack.

## Typical loop

1. Start local OJS with Docker.
2. Develop plugin code in `plugins/generic/<pluginName>`.
3. Enable the plugin in OJS admin.
4. Test in the browser and logs.
5. Package the plugin with `scripts/package-plugin.sh`.
6. Deploy the packaged plugin to the production OJS installation.
