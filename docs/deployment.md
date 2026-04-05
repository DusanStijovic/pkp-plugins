# Production Deployment

Packaging and deployment still happen from this workspace, but the plugin code lives in the `ceon-help-center` repository under `plugins/generic/ceonHelpCenter` when you mount it here.

## Manual zip upload

1. Build a release artifact:
   ```bash
   ./scripts/package-plugin.sh plugins/generic/ceonHelpCenter
   ```
2. Copy the generated `dist/ceonHelpCenter-<release>.zip` to production and unzip it into `/var/www/html/plugins/generic/ceonHelpCenter` on the target server.
3. Enable or reload the plugin from the OJS admin UI.

## GitHub release flow

- Tag the plugin repository with `ceonHelpCenter-vX.Y.Z`.
- CI should produce the same ZIP as a release asset.
- Deploy that identical artifact to production via FTP/rsync so the artifact you test locally is the one you ship.
