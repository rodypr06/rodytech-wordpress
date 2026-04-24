# Changelog

## 2026-04-24

- Investigated the failed/incomplete publish alert for “What Iowa Small Businesses Need to Know About NIST's New Cybersecurity Framework in 2026”.
- Confirmed the cron delivery summary was premature: the worker had only reached “Writing article via HQ-backed LLM...” when the channel saw the incomplete publish message, and the later remote log showed the actual crash.
- Root cause: `/root/workspace/blog_article_generator.py` completed research, then the writer stage failed three times with `No valid JSON payload found in model response`, raising `RuntimeError` before WordPress publish.
- Patched the remote generator so a successful research stage can produce a deterministic fallback article when writer JSON parsing fails, avoiding future “no post ID” crashes for this failure mode.
- Re-ran the failed NIST article successfully.
- Published live WordPress post:
  - Post ID: `387`
  - Featured media ID: `388`
  - URL: `https://blog.rodytech.ai/iowa-small-businesses-and-nist-csf-2-0-what-to-know-in-2026/`
- Verified the final public URL returns HTTP `200`.

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
