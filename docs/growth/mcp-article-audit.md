# MCP article: source verification and targeted update

Reviewed September 28, 2026. Status: editorial review complete for the claims listed below; proposed changes are NOT published. This is a source audit of an article, not a security assessment of a deployed MCP system.

## Publication target and concurrency guard

- Existing post: **832**, [MCP Is the Pipe, Not the Permission Boundary](https://blog.rodytech.ai/mcp-is-the-pipe-not-the-permission-boundary/).
- Read from the public WordPress REST API on September 28. Its `modified` value is `2026-09-13T09:35:21` (API-local timestamp; timezone was not supplied).
- SHA-256 of the UTF-8 `content.rendered` value: `b186959e1aca7124c031b33d38c72f10ea483d1210a78bfc8085d4fd2a24ae19`.
- The article has changed since the September 5 inventory. Do not overwrite it from that older snapshot. Before publishing, fetch the latest authenticated editable content and reconcile these targeted edits. A rendered-content hash is a comparison signal, not an atomic WordPress write precondition; check for concurrent edits and retain a revision/backup.
- Preserve post ID, slug and original publication date. Add an update date only after the change is actually published. No new article or redirect is needed.

## Findings and decisions

| Topic | Evidence | Editorial action |
| --- | --- | --- |
| Specification labeled current | The article's three specification links target 2025-06-18; the official version page identifies 2026-07-28 as current on the review date. [Versioning](https://modelcontextprotocol.io/docs/2026-07-28/learn/versioning) | Replace all three dated links; identify the reviewed revision explicitly. |
| HTTP versus STDIO | The reviewed authorization specification retains this distinction. [Authorization](https://modelcontextprotocol.io/specification/2026-07-28/basic/authorization) | Keep the article's qualification; add the compact transport checklist below. |
| MCP and downstream credentials | The HTTP authorization rules require audience validation and reject unrelated tokens. [Token handling](https://modelcontextprotocol.io/specification/2026-07-28/basic/authorization#token-handling) | Avoid wording that could imply forwarding the incoming bearer credential to the downstream API. |
| Local proxy boundary | Official guidance distinguishes direct STDIO from proxies that can spawn processes. [Security guidance](https://modelcontextprotocol.io/docs/2026-07-28/tutorials/security/security_best_practices#stdio-transport-security-in-proxy-scenarios) | Add a proxy-specific row; do not describe STDIO itself as inherently vulnerable. |
| Glean example | Glean documents user-specific execution, source permissions, short-lived OAuth and a user-scoped API-token fallback. [Glean documentation](https://docs.glean.com/administration/platform/mcp/security) | Existing product-specific framing is supported. This is documentation evidence, not an independently tested deployment. |
| Scalekit SSO example | The vendor separates identity from method-level authorization. [SSO pattern](https://www.scalekit.com/blog/sso-backed-mcp-authentication-for-enterprise-engineering-teams) | Keep it explicitly vendor-specific. |
| Scalekit notes example | The current walkthrough distinguishes application `permissions` from OAuth `scopes` and obtains a fresh token after changing the grant. [Notes walkthrough](https://www.scalekit.com/blog/scoped-permission-mcp-server-mcp-use) | Clarify this distinction near the example; do not present `notes:write` as a protocol-standard scope. |
| Tool policy and approval | Tenant checks, source ACLs and business approvals are the article's application-design advice. | Keep that advice labeled as such; do not turn it into a universal MCP protocol requirement. |

## Exact editorial changes

1. Replace the three occurrences of `https://modelcontextprotocol.io/specification/2025-06-18/basic/authorization` with `https://modelcontextprotocol.io/specification/2026-07-28/basic/authorization` after confirming the live count still matches.
2. Change the opening link label from “MCP authorization specification” to “MCP authorization specification, revision 2026-07-28,” and replace the preceding unqualified “current” with “reviewed.” This makes the citation's age visible rather than silently claiming perpetual freshness.
3. Insert `mcp-transport-checklist.html` after the opening transport discussion. It is intentionally brief and does not replace the existing detailed negative-test matrix.
4. Replace the sentence beginning “MCP's security guidance also prohibits token passthrough” with: “Do not forward the client's MCP bearer token to another service; use credentials intended for that downstream API.” Preserve a link to the reviewed authorization reference.
5. After the Scalekit scoped-permission example, add: “In that walkthrough, `notes:read` and `notes:write` are application permissions checked through `ctx.auth.permissions`, distinct from the OAuth grants exposed through `ctx.auth.scopes`. That mapping belongs to the example, not to a universal MCP scope taxonomy.”

## Checks before the update goes live

- Read the final rendered article on desktop and mobile, including its table of contents. The new heading should appear once; check that the table fits the article's scroll container.
- Confirm all three source links changed without losing the surrounding text or existing source list.
- Recheck the revised vendor paragraph against the exact SDK/example version used if executable code is later added.
- Verify the new fragment remains a short reader aid rather than another long introduction.
- Preserve the existing negative-test matrix. No tests against Glean, Scalekit or a production authorization system were performed in this review.
- The RFC references and every downstream vendor page were not exhaustively re-audited; this receipt covers the listed claims. Do not mark the entire ten-article audit complete.

## Evidence and remaining work

Source URLs above were opened and their relevant sections read on September 28. The public article was fetched through `/wp-json/wp/v2/posts/832` because the web reader could not open its page directly. Source checks found a citation revision mismatch and a useful vendor terminology clarification; they did not establish an exploitable flaw in RodyTech infrastructure.

Deployment access is still unavailable, and there is no verified publishing receipt. Pending Flowspace note for the article-audit card: MCP source review and transport checklist prepared; live update remains open. Keep promotion tied to the final verified article URL and publication receipt.
