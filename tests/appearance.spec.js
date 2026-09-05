import { test, expect } from '@playwright/test';

test('follows the system until an explicit appearance is saved', async ({ page }) => {
  await page.emulateMedia({ colorScheme: 'light' });
  await page.goto('/');
  const root = page.locator('html');
  const picker = page.getByRole('combobox', { name: 'Appearance' });
  await expect(root).toHaveAttribute('data-theme', 'light');
  await expect(picker).toHaveValue('system');
  await page.emulateMedia({ colorScheme: 'dark' });
  await expect(root).toHaveAttribute('data-theme', 'dark');
  await picker.selectOption('light');
  await page.locator('.publication-story-lead h2 a').click();
  await expect(root).toHaveAttribute('data-theme', 'light');
  await expect(picker).toHaveValue('light');
  await expect(page.locator('.article-content')).toHaveCSS('color', 'rgb(63, 61, 73)');
  await page.reload();
  await expect(root).toHaveAttribute('data-theme', 'light');
  await picker.selectOption('system');
  await expect(root).toHaveAttribute('data-theme', 'dark');
  expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
});

test('works when preference storage is unavailable', async ({ page }) => {
  await page.addInitScript(() => {
    Object.defineProperty(window, 'localStorage', { get() { throw new Error('Storage unavailable'); } });
  });
  await page.goto('/');
  await page.getByRole('combobox', { name: 'Appearance' }).selectOption('light');
  await expect(page.locator('html')).toHaveAttribute('data-theme', 'light');
  await expect(page.locator('h1')).toBeVisible();
});
