# Development Workflow

This workspace provides the Docker stack, helper scripts, and CI automation that the Ceon Help Center plugin relies on. The plugin source code now lives in https://github.com/DusanStijovic/ceon-help-center, so clone or symlink that repository into this workspace so it appears at `plugins/generic/ceonHelpCenter`.

## Setup steps

1. Clone the plugin repo (or symlink from another location) into `plugins/generic/ceonHelpCenter`.
2. Start the local stack with:
   ```bash
   docker compose up -d
   ```
3. If you need Xdebug or rebuild the base image:
   ```bash
   docker compose build app
   docker compose up -d app
   ```
4. Run `scripts/smoke-ojs.sh` whenever you want a quick sanity check that the container boots and the plugin parses.

## Workflow

- Edit the plugin files inside `plugins/generic/ceonHelpCenter` (the changes belong in that repo).
- Restart the container when PHP changes are made, or reload the plugin from the OJS admin UI.
- Use `scripts/package-plugin.sh plugins/generic/ceonHelpCenter` to build deployment zips.

Keeping the plugin code outside this repo ensures the workspace stays focused on the tooling and environment configuration.
