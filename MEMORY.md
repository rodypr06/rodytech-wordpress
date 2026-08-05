# Long-Term Memory


## Promoted From Short-Term Memory (2026-08-04)

<!-- openclaw-memory-promotion:memory:memory/2026-07-30.md:3:6 -->
- ReportHub 4738 provenance reconciliation: recovered the live renderer/test fixture into a clean worktree based on canonical-main baseline `2231a940ca3d048cbc3e3e71dfc8b0e32963edd7`.; Created local scoped commit `bbfa6853eb4190c362f9dce64bb34e314f151c88` (`fix(reporthub): render executive artifacts safely`) with five client paths. Scoped lint passed, focused Vitest passed 4/4, and the client production build passed.; Canonical HTTPS Gitea integration is blocked: exact `git ls-remote https://git.helix.internal/helix/reporthub.git refs/heads/main` cannot obtain credentials noninteractively; smart HTTP returns 401.... [score=0.861 recalls=0 avg=0.620 source=memory/2026-07-30.md:3-6]
<!-- openclaw-memory-promotion:memory:memory/2026-07-30.md:7:7 -->
- Dedicated pre-4738 DB, source, dist, and report JSON backups under `/opt/reporthub/server/data/backups/` remain present. [score=0.861 recalls=0 avg=0.620 source=memory/2026-07-30.md:7-7]
<!-- openclaw-memory-promotion:memory:memory/2026-07-31.md:3:6 -->
- Closed the CT110 Gitea capacity-monitoring gap in the existing Helix Sentinel plane. The active daemon now runs a five-minute read-only guard for guest root usage (warn 70%, urgent 85%), 14-calendar-day backup retention/count/freshness, Gitea health API/container state, and Postgres health. - Found `com.helix.sentinel-event-loop` persistently disabled and without explicit outbound delivery. Re-enabled its existing 60-second launchd record and bound its security-agent delivery wrapper to the existing Rody-visible Mattermost security channel without changing OpenClaw raw config.... [score=0.806 recalls=0 avg=0.620 source=memory/2026-07-31.md:3-6]

## Promoted From Short-Term Memory (2026-08-05)

<!-- openclaw-memory-promotion:memory:memory/2026-07-31.md:7:7 -->
- Source work is committed locally on `codex/ct110-sentinel-guard` at `fbf2553`; active pre-change files are backed up under `/Users/rrabelo/helix-sentinel/backups/ct110-guard-20260731T143753Z`. [score=0.829 recalls=0 avg=0.620 source=memory/2026-07-31.md:7-7]
