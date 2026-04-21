# Changelog

## 2026-04-20

- Rebuilt the live `blog.rodytech.ai` theme around an editorial homepage and archive layout with the interactive network background, refined hero treatment, and responsive glass-card design system.
- Replaced hardcoded header and footer navigation with WordPress-managed menu locations: `primary`, `footer`, and `footer_connect`, then configured those menu assignments on production.
- Hardened author archive behavior so valid author slugs render the dedicated author template and unresolved author requests fail cleanly instead of degrading into generic archive output.
- Replaced the placeholder newsletter cookie flow with a real `admin-post` submission path using nonce validation, honeypot protection, duplicate detection, persistent storage, admin export tooling, and live success/error notices.
- Added single-post conversion modules: an inline subscribe block wired to the real newsletter backend and a post-bottom retention band with archive/category continuation plus previous/next reading links.
- Improved archive UX for category, tag, search, and author views with smarter sidebar context, better empty-state copy, and stronger “return to the archive” guidance.
- Added production-hardening around motion and accessibility, including focus-visible styles, reduced-motion handling in CSS, and runtime throttling/pause logic for the interactive background canvas.
- Added archive SEO guardrails so search pages and low-value thin/empty archive routes emit `noindex, follow`.
- Created a repeatable deployment workflow with [DEPLOYMENT.md](/Users/rrabelo/.openclaw/workspace/rodytech-wordpress/DEPLOYMENT.md), [scripts/deploy-theme.sh](/Users/rrabelo/.openclaw/workspace/rodytech-wordpress/scripts/deploy-theme.sh), and [scripts/smoke-test.sh](/Users/rrabelo/.openclaw/workspace/rodytech-wordpress/scripts/smoke-test.sh), then used that workflow for live deploys.
- Cleaned live taxonomy by deleting empty duplicate categories and zero-post tags, with pre/post audit artifacts saved under [reports](/Users/rrabelo/.openclaw/workspace/rodytech-wordpress/reports).
- Applied two rounds of conservative live tag consolidation:
  - `AI Inferencing` -> `AI Inference`
  - `GenAI` -> `Generative AI`
  - `Multi-agent AI systems` -> `Multi-Agent Systems`
  - `Autonomous agents` -> `Autonomous AI Agents`
  - `Prompt Injection detection` -> `Prompt Injection`
  - `DevOps Automation` -> `DevOps`
