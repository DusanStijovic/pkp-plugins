const { test, expect } = require('@playwright/test');

test.describe('Simple Popup Button', () => {
  test('opens and closes the popup', async ({ page }) => {
    await page.goto('/tests/fixtures/simplePopupButton.fixture.html');

    const button = page.locator('#simplePopupButton');
    const modal = page.locator('#simplePopupModal');

    await expect(button).toBeVisible();
    await expect(modal).toHaveAttribute('hidden', '');
    await expect(modal).toHaveAttribute('aria-hidden', 'true');

    await button.click();

    await expect(modal).not.toHaveAttribute('hidden', '');
    await expect(modal).toHaveAttribute('aria-hidden', 'false');

    await page.keyboard.press('Escape');

    await expect(modal).toHaveAttribute('hidden', '');
    await expect(modal).toHaveAttribute('aria-hidden', 'true');
  });

  test('backdrop closes the popup', async ({ page }) => {
    await page.goto('/tests/fixtures/simplePopupButton.fixture.html');

    const button = page.locator('#simplePopupButton');
    const modal = page.locator('#simplePopupModal');

    await button.click();
    await page.locator('[data-simple-popup-close]').first().click();

    await expect(modal).toHaveAttribute('hidden', '');
    await expect(modal).toHaveAttribute('aria-hidden', 'true');
  });
});
