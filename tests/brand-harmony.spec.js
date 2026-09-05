import { test, expect } from '@playwright/test';

test('renders the journal, archive, search, about, and missing-page templates', async ({ page }, testInfo) => {
  const errors=[];
  page.on('pageerror', error => errors.push(error.message));
  for (const path of ['/', '/articles/', '/about/', '/?s=automation', '/missing-brand-review-page/']) {
    const response=await page.goto(path);
    expect(response.status()).toBe(path.includes('missing-brand')?404:200);
    await expect(page.locator('main')).toHaveCount(1);
    await expect(page.locator('h1')).toHaveCount(1);
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('body')).toHaveCSS('background-color','rgb(9, 9, 11)');
    expect(await page.evaluate(()=>document.documentElement.scrollWidth)).toBeLessThanOrEqual(page.viewportSize().width);
    await expect(page.locator('#network')).toHaveCount(0);
    if(path==='/') {
      await expect(page.locator('h1')).toHaveCSS('font-family', /Instrument Serif/);
      await page.screenshot({path:testInfo.outputPath('journal-home.png')});
    }
    if(path==='/articles/') await page.screenshot({path:testInfo.outputPath('journal-archive.png')});
  }
  expect(errors).toEqual([]);
});

test('keeps native search, navigation, and article links working', async ({ page }) => {
  await page.goto('/');
  if(page.viewportSize().width<=768) {
    const toggle=page.getByRole('button',{name:'Toggle navigation'});
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded','true');
    await page.keyboard.press('Escape');
    await expect(toggle).toBeFocused();
    await expect(toggle).toHaveAttribute('aria-expanded','false');
    await toggle.click();
  }
  const nav=page.getByRole('navigation',{name:'Primary'});
  await expect(nav.getByRole('link',{name:'Visit RodyTech'})).toHaveAttribute('href','https://www.rodytech.ai');
  await expect(nav.getByRole('link',{name:'Visit RodyTech'})).toHaveCSS('color','rgb(9, 9, 11)');
  await nav.getByRole('searchbox').fill('automation');
  await nav.getByRole('button',{name:'Search',exact:true}).click();
  await expect(page).toHaveURL(/s=automation/);
  const card=page.locator('.story-card-link').first();
  await expect(card).toBeVisible();
  await card.click();
  await expect(page.locator('.single-article .article-content')).toBeVisible();
});

test('keeps real long headlines and article reading content readable', async ({ page }, testInfo) => {
  await page.goto('/');
  await page.locator('.story-card-link').first().click();
  await expect(page.locator('.article-title')).toBeVisible();
  const title=await page.locator('.article-title').boundingBox();
  const header=await page.locator('.article-hero, .hero-no-image').boundingBox();
  expect(title.y+title.height).toBeLessThanOrEqual(header.y+header.height);
  expect(title.x+title.width).toBeLessThanOrEqual(page.viewportSize().width);
  const image=page.locator('.hero-image');
  await expect(image).toBeVisible();
  await expect.poll(()=>image.evaluate(img=>img.complete && img.naturalWidth>0)).toBe(true);
  await page.screenshot({path:testInfo.outputPath('journal-article.png')});
  const progress=page.locator('.reading-progress span');
  const start=await progress.evaluate(node=>getComputedStyle(node).transform);
  await page.locator('.article-content').evaluate(node=>window.scrollTo(0,node.getBoundingClientRect().bottom+scrollY-innerHeight));
  await expect.poll(()=>progress.evaluate(node=>getComputedStyle(node).transform)).not.toBe(start);
  await page.goto('/reading-layout-review/');
  await expect(page.locator('.article-content table')).toBeVisible();
  await expect(page.locator('.article-content pre')).toHaveCSS('overflow-x','auto');
  expect(await page.evaluate(()=>document.documentElement.scrollWidth)).toBeLessThanOrEqual(page.viewportSize().width);
});

test('reacts to pointer movement and stops when reduced motion is enabled', async ({ page }) => {
  await page.emulateMedia({reducedMotion:'no-preference'});
  await page.goto('/');
  const object=page.locator('.publication-story-lead');
  await object.scrollIntoViewIfNeeded();
  const box=await object.boundingBox();
  await page.mouse.move(box.x+box.width*.8,box.y+box.height*.4);
  await expect.poll(()=>object.evaluate(node=>node.style.getPropertyValue('--pointer-x'))).not.toBe('');
  await page.emulateMedia({reducedMotion:'reduce'});
  await expect.poll(()=>object.evaluate(node=>node.style.getPropertyValue('--pointer-x'))).toBe('');
  await page.locator('.sidebar-card').first().scrollIntoViewIfNeeded();
  await expect(page.locator('.sidebar-card').first()).toHaveCSS('opacity','1');
  await expect(page.locator('.publication-story-image img').first()).toHaveCSS('transition-duration','0s');
});

test('keeps the archive readable without JavaScript', async ({ browser }, testInfo) => {
  const context=await browser.newContext({javaScriptEnabled:false,viewport:testInfo.project.use.viewport});
  const page=await context.newPage();
  try {
    await page.goto(process.env.RODYTECH_TEST_URL || 'http://127.0.0.1:5180');
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('.story-card-link').first()).toBeVisible();
    await expect(page.getByRole('navigation',{name:'Primary'})).toBeVisible();
    await page.locator('.story-card-link').first().click();
    await expect(page.locator('.article-content')).toBeVisible();
  } finally { await context.close(); }
});

test('puts stories first and paginates without losing or repeating articles', async ({ page }) => {
  await page.goto('/');
  const lead=page.locator('.publication-story-lead h2');
  // The illustrated masthead is taller; keep the lead within the opening viewport.
  expect((await lead.boundingBox()).y).toBeLessThan(page.viewportSize().height - 80);
  await expect(page.getByRole('navigation',{name:'Topics',exact:true})).toBeVisible();
  const ids=await page.locator('[data-post-id]').evaluateAll(nodes=>nodes.map(node=>node.dataset.postId));
  expect(ids.length).toBe(9);
  await page.locator('.pagination .next').click();
  await expect(page).toHaveURL(/page\/2/);
  const older=await page.locator('[data-post-id]').evaluateAll(nodes=>nodes.map(node=>node.dataset.postId));
  expect(older.length).toBeGreaterThan(0);
  expect(older.every(id=>!ids.includes(id))).toBe(true);
  await page.goto('/articles/');
  await expect(page.locator('.pagination .next')).toHaveAttribute('href',/\/articles\/page\/2\//);
  await page.locator('.pagination .next').click();
  await expect(page.locator('.publication-story')).toHaveCount(1);
  await page.getByRole('navigation',{name:'Topics',exact:true}).getByRole('link',{name:'Developer',exact:true}).click();
  await expect(page.locator('h1')).toHaveText('Developer');
  await expect(page.getByRole('navigation',{name:'Topics',exact:true}).getByRole('link',{name:'Developer',exact:true})).toHaveAttribute('aria-current','page');
});

test('provides working section links and an RSS feed', async ({ page }) => {
  await page.goto('/');
  const feedUrl=await page.getByRole('link',{name:'Follow via RSS'}).getAttribute('href');
  const feed=await page.request.get(feedUrl);
  expect(feed.status()).toBe(200);
  expect(await feed.text()).toContain('<rss');
  await page.locator('.story-card-link').first().click();
  const toc=page.locator('.article-toc');
  await expect(toc).toBeVisible();
  if(!(await toc.evaluate(node=>node.open))) await toc.locator('summary').click();
  const links=toc.getByRole('link');
  expect(await links.count()).toBeGreaterThanOrEqual(3);
  const link=links.last();
  const anchor=await link.getAttribute('href');
  const title=await link.textContent();
  await link.click();
  expect(new URL(page.url()).hash).toBe(anchor);
  const heading=page.locator('.article-content h2').filter({hasText:title}).last();
  await expect(heading).toBeInViewport();
  expect((await heading.boundingBox()).y).toBeGreaterThanOrEqual(65);
});

test('connects bylines to a readable author archive', async ({ page }) => {
  await page.goto('/');
  const author=page.locator('.publication-meta a').first();
  const name=await author.textContent();
  await author.click();
  await expect(page.locator('h1')).toHaveText(name);
  await expect(page.locator('main')).toHaveCount(1);
  await expect(page.locator('.publication-story').first()).toBeVisible();
  expect(await page.evaluate(()=>document.documentElement.scrollWidth)).toBeLessThanOrEqual(page.viewportSize().width);
});
