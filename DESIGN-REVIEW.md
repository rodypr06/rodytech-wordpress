# RodyTech Journal brand alignment

This theme aligns the blog with the Rodytech website prototype: charcoal surfaces, Instrument Serif display typography, Inter reading text, silver/violet accents, the shared brand mark, and rounded editorial cards. The home page adds a decorative field-notes object that responds to the pointer. Article text stays still, with a small reading-progress line in the header. Headlines sit below featured images so long titles remain readable on phones.

The archive, search, category, author, article, About, and error templates continue to use WordPress queries and navigation. This change does not migrate content, change URLs, alter SEO ownership, activate a newsletter, or modify publishing integrations. The disabled newsletter placeholder is hidden from visitors. Yoast remains responsible for SEO metadata. Public pricing is unchanged.

`style.css` contains the base palette and theme metadata. `brand-harmony.css` contains the editorial presentation shared across templates. `rodytech-animations.js` uses bounded reveals and event-driven pointer effects; the old continuous background canvas is retired. Reduced motion remains readable both on initial load and when the preference changes during a visit. The local WebP brand asset is copied from the website's existing approved mark.

## Local WordPress preview

Requirements: Node.js, Python 3, curl. PHP and WordPress run inside WordPress Playground; no system PHP installation or production access is needed.

```sh
npm ci
python scripts/create-preview-blueprint.py
npx @wp-playground/cli server --port=5180 --wp=6.9.1 --mount-dir ./rodytech-theme /wordpress/wp-content/themes/rodytech-theme --blueprint=.preview/blog-preview-blueprint.json
```

The script copies twelve public articles through the blog's read-only REST API and creates an ephemeral local WordPress database. It also adds a clearly labeled reading-layout fixture. Images remain at their public URLs, with a local-only image filter installed inside the Playground filesystem. Preview counts and categories reflect the sample, not the full archive. Never use this blueprint against an existing or production WordPress installation.

Open `http://127.0.0.1:5180`. In another terminal:

```sh
python scripts/test-theme-contract.py
node --check rodytech-theme/rodytech-animations.js
npx playwright install chromium
npm run test:browser
```

Browser checks cover desktop, tablet, and phone layouts; real PHP template rendering; long headlines; images; tables and code; search and native links; the mobile menu; reading progress; reactive light; live reduced-motion changes; and navigation without JavaScript. These are Chromium checks on local WordPress, not evidence of production plugin compatibility or real-device performance.

## Review and release

Theme version 7.0 and motion-script version 3.0 refresh asset cache keys. The existing gated deployment process in `DEPLOYMENT.md` remains intact. Review the branch and local preview before any production rollout; deployment should use the exact reviewed commit, a theme backup, and the existing route smoke tests. Do not deploy this repository's dev-only packages, fixtures, or test outputs into the theme directory.
