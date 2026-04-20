# RodyTech Blog Production Plan

Date: 2026-04-20
Owner: Codex orchestration
Target: `https://blog.rodytech.ai`

## Objective

Take the redesigned WordPress theme from a strong visual prototype to a production-ready blog platform with reliable content flows, maintainable operations, and better conversion paths.

## Workstreams

1. Author Archive Routing
   - Goal: make `/author/<slug>/` resolve to the dedicated author experience instead of the generic archive path.
   - Deliverables:
     - verified author route behavior
     - working `author.php` template rendering
     - smoke test for the live author slug
   - Owner: `Author Routing Agent`
   - Files: `author.php`, `functions.php` only if routing support is required

2. Newsletter System
   - Goal: replace the placeholder newsletter cookie flow with a real server-side capture path.
   - Deliverables:
     - nonce-protected submission flow
     - persistent subscriber storage
     - admin export surface
     - user success/error states
   - Owner: `Newsletter Agent`
   - Files: `functions.php`, optional new theme admin/export file if needed

3. Dynamic Menus
   - Goal: move header and footer navigation from hardcoded links to WordPress-managed menus.
   - Deliverables:
     - `wp_nav_menu()` in header/footer
     - fallback menus so the theme remains safe before menu assignment
     - CTA preserved
   - Owner: `Navigation Agent`
   - Files: `header.php`, `footer.php`, `functions.php` only if menu helpers are needed

4. Taxonomy Cleanup
   - Goal: improve archive quality and reduce weak taxonomy surfaces.
   - Deliverables:
     - stronger tag/category empty-state handling
     - adjacent taxonomy guidance in archive sidebars
     - guardrails for thin tag pages
   - Owner: `Codex local`
   - Files: `index.php`, `page-articles.php`, `style.css`

5. Single Post Conversion
   - Goal: improve article-level conversion and retention.
   - Deliverables:
     - stronger in-article CTA
     - improved author/follow block
     - related reading kept prominent without interrupting the article body
   - Owner: `Single Post Agent`
   - Files: `single.php`, `style.css`

6. Performance and Accessibility Hardening
   - Goal: preserve the redesign while reducing avoidable motion/perf/accessibility risk.
   - Deliverables:
     - reduced-motion handling for the animated background
     - lower animation overhead when the canvas is offscreen or unsupported
     - improved focus and empty-state polish where needed
   - Owner: `Codex local`
   - Files: `style.css`, `rodytech-animations.js`, selective template tweaks

7. Deployment Workflow
   - Goal: stop relying on ad hoc live edits and make deployments repeatable.
   - Deliverables:
     - documented deploy script
     - remote backup step
     - post-deploy smoke checks
   - Owner: `Deployment Agent`
   - Files: new scripts/docs under repo root

## Agent Assignment

- `Author Routing Agent`
  - Specialization: WordPress template resolution, archive routing, request handling
- `Newsletter Agent`
  - Specialization: form handling, persistence, admin tooling
- `Navigation Agent`
  - Specialization: theme navigation, menu fallbacks, UX-safe rendering
- `Single Post Agent`
  - Specialization: editorial conversion design inside existing theme patterns
- `Deployment Agent`
  - Specialization: release workflow, remote backup, smoke testing
- `Codex local`
  - Specialization: cross-stream integration, taxonomy UX, performance/accessibility, production deploy

## Execution Order

1. Run routing, newsletter, navigation, single-post, and deployment work in parallel.
2. Implement taxonomy cleanup and performance hardening locally while the agents work.
3. Integrate and verify the combined theme locally.
4. Deploy to `helix-worker` with backup and smoke tests.

## Done Criteria

- `/author/<valid-slug>/` renders the dedicated author template
- newsletter submissions persist and can be exported
- header/footer menus are manageable from WordPress
- single posts include a clear conversion path
- archive pages provide better context for thin taxonomy states
- animation respects reduced motion and degrades cleanly
- theme deploy is scripted and reversible
