# OJS Plugin Development Workspace

This repository keeps the Docker stack, helper scripts, and CI automation for building OJS plugins. The plugin logic has been moved into its own repo (`https://github.com/DusanStijovic/ceon-help-center`). Clone that repository into `plugins/generic/ceonHelpCenter` (or symlink from wherever you keep the code) before using the workspace.

## Quick start

1. Clone or symlink the plugin repo into this workspace so it lives at `plugins/generic/ceonHelpCenter`.
2. Start the local stack with `docker compose up -d`.
3. (Optional) rebuild the `app` image when you need Xdebug or dependency changes:
   ```bash
   docker compose build app
   docker compose up -d app
   ```
4. Use `scripts/smoke-ojs.sh` to sanity-check the container setup.

## Repository layout

- `plugins/generic/`: mount point for whatever plugin you are developing
- `docker-compose.yml` / `Dockerfile`: local OJS stack configuration
- `scripts/package-plugin.sh`: generic helper that zips any plugin directory using its `version.xml`
- `scripts/smoke-ojs.sh`: boots the stack, waits for HTTP, and verifies mounted plugins parse
- `docs/`: workspace-focused development, testing, and deployment notes

## Packaging & releases

From the workspace root you can still create a deployable zip for your plugin (assuming it sits under `plugins/generic/ceonHelpCenter`):

```bash
./scripts/package-plugin.sh plugins/generic/ceonHelpCenter
```

That writes `dist/ceonHelpCenter-<release>.zip`, reusing the release number in `version.xml`. The GitHub workflows expect tags such as `ceonHelpCenter-v1.0.0` and upload the same ZIP as a release asset.

## Documentation

See `docs/development.md`, `docs/testing.md`, and `docs/deployment.md` for step-by-step instructions on how to run the stack, exercise the various test suites, and ship a release. Those notes assume the plugin repository is mounted under `plugins/generic/ceonHelpCenter`.
