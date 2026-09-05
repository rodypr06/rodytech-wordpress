# RodyTech Journal brand alignment

The theme shares the website's charcoal, silver/violet, Instrument Serif, and Inter identity. Version 7.1 changes the layout to prioritize published articles: a compact masthead, lead story, chronological article rows, topic navigation, bylines, and RSS. Articles show their headline before their featured image and provide collapsible section navigation for longer posts. See EDITORIAL-REVIEW.md for research and rationale.

WordPress owns content, queries, feeds, archive URLs, and pagination. Yoast remains responsible for SEO. The theme does not activate email subscriptions or change publishing integrations. The local preview uses public article copies and is separate from production.

style.css holds base theme styles; brand-harmony.css defines the shared identity; publication.css defines the editorial layouts. Motion is event-driven and respects reduced motion. Theme and stylesheet versions are 7.1; the script version is 3.1.

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

Theme version 7.1 and motion-script version 3.1 refresh asset cache keys. The existing gated deployment process in `DEPLOYMENT.md` remains intact. Review the branch and local preview before any production rollout; deployment should use the exact reviewed commit, a theme backup, and the existing route smoke tests. Do not deploy this repository's dev-only packages, fixtures, or test outputs into the theme directory.
