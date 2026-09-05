# RodyTech Journal brand alignment

## Article reading experience

Article templates now provide a sticky contents rail from 1100px upward and a collapsible contents block below that width, copy controls with language labels and accessible success/failure feedback, and two related articles with descriptive excerpts. The existing progress bar and active-section indication remain. Article content stays still.

The Next.js debugging pipeline article is the authored example: three takeaways, audience/prerequisites, a four-step workflow placed before the bounded-incident section, Key insight and Watch out callouts, and a practical next-step checklist. This is a theme-rendered editorial supplement; the WordPress article body and its sources are preserved. No universal tooling claims were added.

Editors can fill the Reader guide box on WordPress posts: takeaways, audience, prerequisites, a practical next step, and an optional approved service destination. Empty fields do not generate a summary for other articles. Use `reader-callout` for authored callout blocks and `reader-workflow` for an ordered, text-accessible diagram. Add language classes such as `language-javascript` to code; blocks without a declared language are labeled CODE rather than guessed. Related-reading descriptions use existing article excerpts and should be edited for relevance. Service links require an explicit editorial selection and a next step.

Reading assets: version 1.0. The 42 existing browser checks and six new checks passed at desktop, tablet, and phone sizes. New checks exercise the guide, diagram, rail placement, successful clipboard copying, denied clipboard feedback, and related reading. A final six-check rerun passed after moving the diagram into the article. Editor save handling includes nonce and capability checks; editor save UI has not been browser-tested. Deployment is still pending host access.

## Masthead motion update — version 7.3

### Reader appearance

The header offers Light, Dark, and System. System is the default; a saved explicit choice wins and persists across pages. A small script resolves the theme before styles load; storage failures are caught. The control synchronizes after navigation and responds to system changes and cross-tab storage events. Without JavaScript the existing readable dark theme remains available and the inactive picker stays hidden.

Light appearance uses soft paper (#f5f3f0), ink text, and deep violet links (#6650a4), while the header, footer, and animated masthead retain their dark brand frame. Articles, discovery, archives, About, forms, tables, quotations, and code receive deliberate light treatment. The selector stays outside the mobile navigation. Appearance assets are version 1.0. Tests cover system changes, persistence, storage failure, and responsive behavior; the existing route checks explicitly verify dark appearance.

The opening spread pairs a larger two-line title and drawn underline with an expanded three-dimensional journal cover. Pages enter from depth over 2.6 seconds, with staggered rotation. Foreground fragments counter-track the pointer, and the journal gently turns on a seven-second CSS cycle. Pause/Resume controls ambient motion; replay restarts the entrance. Animations pause outside the viewport and in hidden tabs. Pointer movement controls perspective and lighting without a JavaScript idle render loop. Decorative artwork is hidden from assistive technology. Reduced motion disables the entrance, ambient movement, and pointer reaction and hides motion controls. The illustration stacks below the title on phones. Featured-story tilt and article-row entrances are stronger, while headlines remain steady.

Asset cache versions: styles 7.3, script 3.3. Verification covers 1440, 768, and 390 pixels, native pagination, article reading, no-JavaScript navigation, pointer response, replay, and live reduced-motion changes. The prior 650px lead-position assertion now checks that the lead remains within the opening viewport, accommodating the requested larger masthead.

This update is awaiting production deployment; SSH access to the existing deployment host timed out during preparation.

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
