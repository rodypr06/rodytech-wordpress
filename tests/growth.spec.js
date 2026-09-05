import { test, expect } from '@playwright/test';
const resources = '/wp-content/themes/rodytech-theme/resources/';
const article = '/a-resilient-next-js-16-devtools-mcp-debugging-pipeline/';

test('unconfigured newsletter offers useful resources without collecting email', async ({ page }) => {
  const errors = []; page.on('pageerror', error => errors.push(error.message));
  await page.goto(article);
  await expect(page.locator('.journal-join')).toHaveCount(2);
  await expect(page.locator('.journal-join input[type=email]')).toHaveCount(0);
  await expect(page.locator('.journal-join--article')).toContainText('Good ideas deserve a next step.');
  await page.locator('.journal-join--article a[data-journal-event=reader_resource_open]').click();
  await expect(page).toHaveURL(new RegExp('resources/start-here.html$'));
  await expect(page.getByRole('heading', { name: 'Take one idea into practice.' })).toBeVisible();
  await page.getByLabel('Appearance').selectOption('light');
  await page.getByRole('link', { name: 'Open the workflow scorecard' }).click();
  await expect(page.locator('html')).toHaveAttribute('data-theme', 'light');
  await expect(page.getByLabel('Appearance')).toHaveValue('light');
  expect(errors).toEqual([]);
});

for (const path of ['operator-scorecard.html', 'builder-checklist.html']) {
  test(`${path} provides a private worksheet that works in both appearances`, async ({ page, context }) => {
    await context.grantPermissions(['clipboard-read', 'clipboard-write']);
    await page.goto(resources + path);
    await expect(page.getByRole('checkbox')).toHaveCount(6);
    await page.getByRole('checkbox').first().check();
    await expect(page.locator('.progress')).toContainText('1 of 6 questions reviewed');
    await page.getByRole('button', { name: 'Copy worksheet' }).click();
    await expect(page.locator('.copy-status')).toContainText('Worksheet copied.');
    expect(await page.evaluate(() => navigator.clipboard.readText())).toContain(path.startsWith('operator') ? 'Workflow and trigger:' : 'Incident and owner:');
    for (const colorScheme of ['light', 'dark']) {
      await page.emulateMedia({ colorScheme });
      expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
    }
    await page.reload();
    await expect(page.locator('.progress')).toContainText('0 of 6 questions reviewed');
  });
}

test('worksheet explains clipboard denial and retains the visible copy', async ({ page }) => {
  await page.addInitScript(() => Object.defineProperty(navigator, 'clipboard', { value: { writeText: () => Promise.reject(new Error('Denied')) } }));
  await page.goto(resources + 'builder-checklist.html');
  await page.getByRole('button', { name: 'Copy worksheet' }).click();
  await expect(page.locator('.copy-status')).toContainText('copy it manually');
  await expect(page.locator('#worksheet')).toBeVisible();
});

test('resource is readable without JavaScript', async ({ browser }) => {
  const context = await browser.newContext({ javaScriptEnabled: false });
  const page = await context.newPage();
  await page.goto((process.env.RODYTECH_TEST_URL || 'http://127.0.0.1:5180') + resources + 'operator-scorecard.html');
  await expect(page.locator('#worksheet')).toContainText('Stop condition and rollback:');
  await expect(page.getByRole('button', { name: 'Copy worksheet' })).toBeHidden();
  await context.close();
});

test('reader-action hook contains only allowlisted metadata', async ({ page }) => {
  await page.goto(article + '?email=private@example.test');
  await page.evaluate(() => window.addEventListener('rodytech:reader-action', e => { window.lastReaderAction = e.detail; }));
  await page.locator('.journal-join--article a').first().evaluate(a => a.addEventListener('click', e => e.preventDefault()));
  await page.locator('.journal-join--article a').first().click();
  expect(await page.evaluate(() => window.lastReaderAction)).toEqual({ event: 'reader_resource_open', placement: 'article' });
});
