import { test, expect } from '@playwright/test';

test('replays the journal entrance and respects a live reduced-motion change', async ({ page }) => {
  await page.emulateMedia({ reducedMotion: 'no-preference' });
  await page.goto('/');
  const masthead = page.locator('.publication-masthead');
  const replay = page.getByRole('button', { name: 'Replay motion' });
  await replay.click();
  await expect(masthead.locator('.journal-page-cover')).toHaveCSS('animation-name', 'journal-open');
  const box = await masthead.boundingBox();
  await page.mouse.move(box.x + box.width * .8, box.y + box.height * .3);
  await expect.poll(() => masthead.evaluate(n => n.style.getPropertyValue('--depth-y'))).not.toBe('');
  await page.getByRole('button', { name: 'Pause motion', exact: true }).click();
  await expect(masthead.locator('.journal-float')).toHaveCSS('animation-play-state', 'paused');
  await page.getByRole('button', { name: 'Resume motion', exact: true }).click();
  await expect(masthead.locator('.journal-float')).toHaveCSS('animation-play-state', 'running');
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await expect(replay).toBeHidden();
  await expect(masthead.locator('.journal-page-cover')).toHaveCSS('animation-name', 'none');
  await expect(masthead.locator('.journal-float')).toHaveCSS('animation-name', 'none');
  await expect.poll(() => masthead.evaluate(n => n.style.getPropertyValue('--depth-y'))).toBe('');
  await expect(masthead.locator('h1')).toBeVisible();
});

test('moves image depth while keeping the lead headline steady', async ({ page }) => {
  await page.emulateMedia({ reducedMotion: 'no-preference' });
  await page.goto('/');
  const lead = page.locator('.publication-story-lead');
  await lead.scrollIntoViewIfNeeded();
  const title = lead.locator('h2');
  const before = await title.boundingBox();
  const box = await lead.boundingBox();
  await page.mouse.move(box.x + box.width * .85, box.y + box.height * .35);
  await expect.poll(() => lead.evaluate(n => n.style.getPropertyValue('--depth-y'))).not.toBe('');
  await expect(lead.locator('.publication-story-image')).not.toHaveCSS('transform', 'none');
  const after = await title.boundingBox();
  expect(Math.abs(after.x - before.x)).toBeLessThan(1);
  expect(Math.abs(after.y - before.y)).toBeLessThan(1);
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await expect.poll(() => lead.evaluate(n => n.style.getPropertyValue('--depth-y'))).toBe('');
  await expect(lead.locator('.publication-story-image')).toHaveCSS('transform', 'none');
});

test('tracks keyboard topic focus and the current reading section', async ({ page }) => {
  await page.goto('/');
  const nav = page.locator('.publication-topics');
  const marker = nav.locator('.topic-indicator');
  await expect(marker).toBeVisible();
  const start = await marker.evaluate(n => n.style.transform);
  await nav.getByRole('link').nth(1).focus();
  await expect.poll(() => marker.evaluate(n => n.style.transform)).not.toBe(start);
  await expect(marker).toHaveCSS('transition-duration', '0s');
  await page.locator('.story-card-link').first().click();
  const headings = page.locator('.article-content h2');
  const target = headings.nth(1);
  await target.evaluate(n => window.scrollTo(0, scrollY + n.getBoundingClientRect().top - 110));
  const current = page.locator('.article-toc a[aria-current="location"]');
  await expect(current).toHaveCount(1);
  await expect(current).toHaveText(await target.textContent());
});

test('reveals article rows once and keeps them available after leaving view', async ({ page }) => {
  await page.emulateMedia({ reducedMotion: 'no-preference' });
  await page.goto('/');
  const row = page.locator('.publication-story-list').last();
  await row.scrollIntoViewIfNeeded();
  await expect(row).toHaveCSS('opacity', '1');
  await page.locator('h1').scrollIntoViewIfNeeded();
  await expect(row).toHaveCSS('opacity', '1');
});
