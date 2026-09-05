import { defineConfig } from '@playwright/test';
export default defineConfig({
  testDir: './tests',
  fullyParallel: true,
  workers: 2,
  use: {
    baseURL: process.env.RODYTECH_TEST_URL || 'http://127.0.0.1:5180',
    contextOptions: { reducedMotion: 'reduce' },
    screenshot: 'only-on-failure',
    trace: 'retain-on-failure',
  },
  projects: [1440, 768, 390].map(width => ({ name: `chromium-${width}`, use: { browserName: 'chromium', viewport: { width, height: 1000 } } })),
});
