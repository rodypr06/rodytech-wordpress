# RodyTech Journal growth execution

Updated September 5, 2026. Owner authorization: complete this project autonomously; ask Roderick via Slack when a required decision or access is missing.

## Current state

- Branch: codex/journal-growth-foundation, based on origin/main 0797f2b.
- Implemented locally: reusable article/footer signup card, provider-hosted signup configuration with explicit verification gate, free operator scorecard, free builder checklist, copy/print behavior, local analytics integration hook, and About-page positioning.
- Newsletter service is NOT connected. No subscribers have been collected or mail sent by this implementation.
- Production deployment is NOT complete. The configured SSH route timed out on September 5. Do not imply a successful deployment from a Git push.
- Resource, welcome and initial editorial content are prepared. Published URLs in drafts are proposed until production is verified.
- Current public snapshot: docs/growth/public-audit.json. Visits, subscribers and conversion data remain unavailable, not zero.
- Board: https://flowspace.rodytech.ai/?view=kanban&board=82486c9d-e91e-4cf0-88ec-c813c282e41d
- Strategy: https://flowspace.rodytech.ai/?view=documents&document=80ec17c5-177c-44e7-9853-46122985b1ff
- Google copy: https://docs.google.com/document/d/1aeP0---qmyPMcW-M-A0da2GZTCtNFundfYKJCZBs6OI/edit

## Required responses

Slack DM D096YBB7W14 with Roderick U096YBB62CE. Parent 1788633866.497789 asks which existing newsletter provider/account to use; reply 1788634501.273729 reports the unavailable SSH deployment route. Check this thread before asking again. Do not send credentials, internal addresses or account exports in Slack. A free account still requires the user to accept vendor terms.

Provider details need verification inside the actual account. Kit's help pages currently give inconsistent plan descriptions: the Newsletter Plan article advertises one basic automation and a sequence, while other Free-plan material reviewed in the strategy limits automations. Do not promise a free welcome automation until the account shows it. Sources: https://help.kit.com/en/articles/9053602-the-kit-newsletter-plan and the strategy's cited Free-plan page.

## Next work in order

1. Push the verified foundation; record PR and exact SHA. Validation: 66 browser checks passed across desktop, tablet and mobile after scoping the existing RSS test to its masthead link. The 18 growth cases were rerun after preserving the Journal appearance preference on resource pages. Theme contract and PHP 8.3 newsletter contract passed. Mobile visual review covered the article invitation and field kit.
2. Read Slack replies and connect the chosen provider. Follow NEWSLETTER-RUNBOOK.md. Keep the public enable flag off until the full consent-to-unsubscribe journey passes.
3. Restore the approved deployment route. Use the theme deployment script's backup, clean-tree, exact-SHA and smoke gates. Do not alter the existing automated publisher cadence incidentally.
4. Publish verified resources and the first article after an editorial pass, checking that it does not duplicate an existing post. The owner has authorized the growth program, but interviews, vendor terms and any expenditure still depend on actual access and constraints.
5. Add the local event hook to the actual consent-aware analytics setup. Establish a real baseline before changing channel priorities.
6. Complete fact and link verification of the ten-article shortlist; keep only narrowly justified content changes and preserve original dates.
7. Proceed through the six-article distribution cycle, with the sequence anchored to the actual launch date. Prepare the n8n/Make comparison by running the same test workflow; do not fabricate tested outcomes.
8. Validate the paid guide with real readers. Drafting a sample is not evidence of demand. Sponsorship needs six delivered editions and real engagement; the 90-day decision needs actual results.

## Recurrence and limits

The daily 09:00 America/Chicago heartbeat is rodytech-journal-growth-execution. Continue unblocked work, log material changes here, and update Flowspace with evidence. Stay quiet on unchanged state. Notify via Slack for required decisions/access or meaningful failures. Avoid duplicate campaign sends and repetitive reminders. Stop the automation when the authorized project is complete.

The plan assumes at most six founder hours a week and a proposed $0–$100 monthly incremental budget; neither is proof of available capacity or permission to buy software. Publishing, interviewing, delivery evidence and the 90-day review cannot all be completed immediately. Work on preparatory tasks while those dependencies are pending.
