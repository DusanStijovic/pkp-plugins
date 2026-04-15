const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://127.0.0.1:4173',
    specPattern: 'plugins/generic/*/tests/cypress/functional/*.cy.js',
    supportFile: false,
    setupNodeEvents(on, config) {
      on('before:browser:launch', (browser = {}, launchOptions) => {
        if (browser.family === 'chromium' && browser.name !== 'electron') {
          launchOptions.args.push('--no-sandbox')
          launchOptions.args.push('--disable-dev-shm-usage')
        }
        return launchOptions
      })
    },
  },
  screenshotOnRunFailure: true,
  video: true,
});
