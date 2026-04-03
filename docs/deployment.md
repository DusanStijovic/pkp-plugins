# Production Deployment

This project assumes production OJS may run without Docker.

## Recommended production deployment

Deploy only the plugin code:

- source path: `plugins/generic/simplePopupButton`
- target path on server: `plugins/generic/simplePopupButton` inside the production OJS install

## Deployment options

### Manual zip upload

1. Create a release zip:

```bash
./scripts/package-plugin.sh plugins/generic/simplePopupButton
```

2. Copy the zip to the server.
3. Unzip it inside the production OJS root.
4. Enable or refresh the plugin in OJS admin.

### SSH/rsync deployment

Use the `Deploy Plugin` GitHub workflow after configuring repository secrets.

Required secrets:

- `PROD_SSH_HOST`
- `PROD_SSH_USER`
- `PROD_SSH_KEY`
- `PROD_PLUGIN_BASE_PATH`

`PROD_PLUGIN_BASE_PATH` should point to the production OJS `plugins/generic` directory.

## Safe release flow

1. Open PR.
2. CI validates plugin files.
3. Merge to `main`.
4. Create tag like `simplePopupButton-v1.0.0`.
5. GitHub builds the zip artifact.
6. Deploy the same tagged artifact to production.
