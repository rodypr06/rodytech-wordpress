# Resume RodyTech Journal growth in Codex

Handoff prepared September 5, 2026. This file is committed to GitHub so the work can resume on another computer without the original local session.

## Start here

Open this repository in Codex and use this prompt:

> Read HANDOFF.md and docs/growth/EXECUTION.md, inspect the current GitHub PR and Flowspace board, and continue the RodyTech Journal growth project autonomously from its verified state. Check Roderick's existing Slack thread for dependency replies before asking again. Preserve the Journal design and automated publisher. Do not duplicate the existing daily automation or campaign sends. Complete the next unblocked work and record evidence.

Repository: https://github.com/rodypr06/rodytech-wordpress

Branch: `codex/journal-growth-foundation`

PR: https://github.com/rodypr06/rodytech-wordpress/pull/5

Last verified remote code/document state before this handoff: `bfa7beed15a640b7b6906264754af2009c394a0a`. The feature implementation is `dd6caca4cc142bdf877000cfb9b74db56886645a`. This handoff is a later documentation commit; fetch the current branch rather than resetting to either older SHA. At handoff, main is `0797f2b2db67263bd0f24157209aa573dab4b5c8`; PR #5 is open, unmerged and undeployed. Recheck before acting.

For a new checkout:

```sh
git clone --branch codex/journal-growth-foundation https://github.com/rodypr06/rodytech-wordpress.git
cd rodytech-wordpress
git status --short
```

For an existing checkout, inspect and preserve local changes before fetching or switching. Browser sessions, credentials, local servers and ignored preview data do not transfer through Git.

## Scope and authority

The owner asked to execute the blog marketing, promotion, monetization and newsletter plan autonomously, and explicitly authorized contacting him via Slack for missing information. Continue authorized reversible implementation, testing and Git work without asking him to approve the same scope again. Production work must still use the repository's backup, exact-commit and smoke gates. Purchases, accepting provider terms and any new scope require their actual prerequisites; a proposed budget is not spending authorization. Do not fabricate consent, subscriber lists, delivery, audience metrics, client results or demand.

This repo is the WordPress Journal theme, not the main RodyTech marketing website. Earlier design work was merged in PR #4. Preserve its animated masthead, article navigation, light/dark appearance, reduced-motion support and reader experience. Preserve the existing automated article publisher cadence. The current growth work has not changed production, provider accounts or subscriber data.

## What is ready

- Reusable article/footer reader invitations replacing the inactive newsletter placeholder.
- A provider-hosted signup URL and an explicit verified-enable flag in WordPress Customizer, under **Journal newsletter**. Keep the flag off until the full provider journey passes.
- Free operator workflow scorecard and builder incident checklist under `rodytech-theme/resources/`, with copy/print, keyboard support, no-JavaScript reading and the Journal's shared appearance preference.
- Useful field-kit and RSS links when no newsletter is connected. There is no inline email form or local subscriber database.
- Local `rodytech:reader-action` events with only allowlisted event/placement metadata. These are integration hooks, not a deployed analytics collector.
- About-page positioning for operators and builders.
- Welcome/confirmation/digest copy, provider acceptance runbook, KPI definitions, a public ten-article inventory and editorial triage, and the first operator article plus promotion drafts.

Files to read:

| File | Purpose |
| --- | --- |
| `docs/growth/EXECUTION.md` | Durable status, ordered next actions and dependencies |
| `docs/growth/NEWSLETTER-RUNBOOK.md` | Provider configuration and end-to-end acceptance |
| `docs/growth/EMAIL-COPY.md` | Signup, welcome and digest copy; placeholders still need real provider values |
| `docs/growth/MEASUREMENT.md` | Definitions, tracking limits and review cadence |
| `docs/growth/ARTICLE-AUDIT.md` | Ten-article triage; not a complete technical fact check |
| `docs/growth/public-audit.json` | Public recency sample; not traffic-ranked analytics |
| `docs/growth/week-02-operator-guide.md` | Complete first article draft; not published |
| `docs/growth/week-02-promotion.md` | Two LinkedIn drafts, diagram brief and distribution receipt |
| `BACKLOG.md` | Newsletter completion criteria |
| `DEPLOYMENT.md` | Theme-only production deployment and rollback |

## Where the project lives

- Flowspace board: https://flowspace.rodytech.ai/?view=kanban&board=82486c9d-e91e-4cf0-88ec-c813c282e41d
- Full strategy: https://flowspace.rodytech.ai/?view=documents&document=80ec17c5-177c-44e7-9853-46122985b1ff
- Google Docs copy in `04_RODYTECH`: https://docs.google.com/document/d/1aeP0---qmyPMcW-M-A0da2GZTCtNFundfYKJCZBs6OI/edit
- Existing Slack dependency thread: https://rodytechllc.slack.com/archives/D096YBB7W14/p1788633866497789

Flowspace contains 15 implementation cards plus the research receipt. Research and resource/copy preparation were marked complete. Newsletter and first-article descriptions contain progress and PR evidence; baseline and article-audit cards have progress notes. The newsletter card's description says IN PROGRESS, but moving its Kanban column did not succeed; do not infer execution state only from its column. Newsletter delivery and the full editorial audit remain incomplete.

## Immediate dependencies and next actions

1. Read the existing Slack thread. No reply had arrived at the last check. Roderick was asked which newsletter account to use and to leave its dashboard signed in in Chrome. Kit is a provisional recommendation; its help pages gave conflicting free-plan automation descriptions, so verify actual account entitlements. Do not ask for credentials in Slack.
2. The configured SSH route on the original computer timed out. Roderick was asked to restore access or identify the approved route. The destination computer may already have working host/VPN access; inspect its approved configuration instead of assuming the old machine's failure applies. Do not guess production hosts or copy secret files into Git.
3. Review current PR #5 state and finish merge/deploy using `scripts/deploy-theme.sh` and `DEPLOYMENT.md`. A Git push is not a deployment. Record the backup, deployed SHA and live smoke results. The script deploys theme files only; it does not publish article drafts or configure the provider.
4. Connect the selected provider, including consent/confirmation, operator/builder interests, sender authentication, privacy, suppression, preferences and unsubscribe. Verify the entire journey before enabling the public signup path. No subscribers have been collected and no emails have been sent by this implementation.
5. Integrate the local hooks with the actual consent-aware analytics stack and establish authenticated baseline data. Traffic, subscribers and conversion are unknown, not zero.
6. Finish editorial verification and publish the first article/resources through the approved route. Replace proposed draft URLs with verified live URLs before promotion. Check for duplicates against the current archive.
7. Continue the six-article cycle and evidence-gated monetization plan. The n8n/Make comparison needs a real controlled test; the proposed $49 guide needs reader demand; sponsorship needs six delivered editions and real engagement. The 90-day review cannot be replaced with projections.

## Validation and local preview

Completed on the original Windows computer with Node 24.19.0, WordPress Playground CLI 3.1.52 and WordPress 6.9.1:

- 66 Playwright cases across 1440, 768 and 390px passed after scoping an existing RSS test to the masthead link. The new footer introduced a second valid RSS link; this was a test-selector ambiguity.
- All 18 growth cases were rerun and passed after adding shared appearance to resources.
- `scripts/test-theme-contract.py` passed.
- `tests/newsletter-contract.php` passed using PHP 8.3 WASM. This is a configuration/template contract test with WordPress stubs, not proof of actual provider delivery.
- Mobile visual inspection covered the article invitation and field kit. Git working tree was clean before adding this note.

Typical test commands from the repo root, after preparing a disposable preview:

```sh
npm ci
npx playwright install chromium
python scripts/test-theme-contract.py
php tests/newsletter-contract.php
npm run test:browser
```

Use `python3` where appropriate. PHP CLI, Node, Python, browser dependencies and SSH authentication may need to be located on the destination computer; do not reuse the original computer's absolute runtime paths. Browser tests default to `http://127.0.0.1:5180`; `RODYTECH_TEST_URL` can point them at another appropriate local preview.

The old preview server and `.preview/` are local and ignored by Git. Recreate the public-content blueprint with `python scripts/create-preview-blueprint.py`. It generates `.preview/blog-preview-blueprint.json` from current public articles and a reading fixture. **Run the generated blueprint only in a disposable local WordPress instance:** its setup deletes existing posts/pages in that instance before importing fixtures. Never execute it against production or a valuable staging database.

The working preview used Playground CLI `server --port=5180 --wp=6.9.1 --mount-dir ./rodytech-theme /wordpress/wp-content/themes/rodytech-theme --blueprint=.preview/blog-preview-blueprint.json`. On the destination, resolve the CLI's installed entrypoint and required Node/WASM options; the original runtime used `--experimental-wasm-jspi`. Inspect existing servers before starting one. The blueprint copies the latest 12 posts, so article-specific tests may need explicit fixture restoration as the archive changes; do not weaken assertions to hide a missing fixture.

## Avoid duplicate execution after changing computers

An ACTIVE Codex heartbeat already exists on the original task: `rodytech-journal-growth-execution`, daily at 09:00 America/Chicago. It reads `docs/growth/EXECUTION.md`, checks the existing Slack thread, continues unblocked work and stays quiet on unchanged state. It is attached to the original local task; cloning Git does not transfer it or guarantee it will run while that computer is offline.

Before assuming ownership of recurring execution on the other computer, inspect that automation and the latest board/PR receipts. Coordinate or pause/update the existing schedule when taking over; do not create a second independent sender or duplicate automation. This handoff itself does not pause, move or recreate the schedule. Record which task/computer owns execution when ownership changes.

Log actual campaign IDs, recipient/consent scope, URLs and delivery receipts before subsequent sends. Update `docs/growth/EXECUTION.md` and Flowspace after meaningful progress. Keep Slack questions in the existing thread when they concern the same dependencies.
