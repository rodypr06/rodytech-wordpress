import { test, expect } from '@playwright/test';
const article = '/a-resilient-next-js-16-devtools-mcp-debugging-pipeline/';
test('provides an authored guide, responsive reading rail, and copyable checklist', async ({ page, context }) => {
  await context.grantPermissions(['clipboard-read', 'clipboard-write']);
  await page.goto(article);
  await expect(page.getByRole('heading', { name: 'What you’ll learn' })).toBeVisible();
  await expect(page.locator('.reader-workflow li')).toHaveCount(4);
  if (page.viewportSize().width >= 1100) {
    await expect(page.locator('.article-reading-rail .article-toc')).toBeVisible();
    await expect(page.locator('.article-reading-rail')).toHaveCSS('position', 'sticky');
  } else {
    await expect(page.locator('.article-body > .article-toc')).toBeVisible();
  }
  await page.locator('.reader-next-step summary').click();
  const step = page.locator('.reader-next-step');
  await step.getByRole('button', { name: 'Copy code' }).click();
  await expect(step.getByRole('status')).toHaveText('Copied to clipboard.');
  expect(await page.evaluate(() => navigator.clipboard.readText())).toContain('Leading explanation and disconfirming test:');
  expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
  await expect(page.locator('.related-card')).toHaveCount(2);
  await expect(page.locator('.related-reading-reason').first()).not.toBeEmpty();
});
test('explains a denied clipboard request without hiding the code', async ({ page }) => {
  await page.addInitScript(() => { Object.defineProperty(navigator, 'clipboard', { value: { writeText: () => Promise.reject(new Error('Denied')) } }); });
  await page.goto(article);
  await page.locator('.reader-next-step summary').click();
  await page.locator('.reader-next-step').getByRole('button', { name: 'Copy code' }).click();
  await expect(page.locator('.reader-next-step [role="status"]')).toContainText('copy it manually');
  await expect(page.locator('.reader-next-step pre')).toBeVisible();
});
