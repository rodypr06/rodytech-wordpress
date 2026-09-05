# RodyTech Journal: editorial layout review
Reviewed September 4, 2026. Scope: homepage, article archive, topic/search/author routes, and article reading layout.

## Assessment
The previous design had a coherent identity and working blog features, but the large slogan, decorative notebook, three introductory calls to action, and repeated archive guidance gave it the structure of a marketing landing page. There is no single industry certification for a blog layout. The relevant comparison is whether readers can quickly find, assess, read, and continue through the writing.

## Research and design decisions
- [Cloudflare Blog](https://blog.cloudflare.com/) presents a brief publication introduction followed by dated headlines, summaries, and named authors, with topic navigation. RodyTech now has a compact masthead, a lead article and two recent headlines, and visible bylines/dates/reading times. This is an observed publishing pattern, not evidence that copying the layout will increase conversion.
- [Smashing Magazine](https://www.smashingmagazine.com/) foregrounds authors, article titles, dates, topics, and a clearly named latest-post feed. RodyTech now uses descriptive section labels and chronological article rows, with topic links and an author archive. Repeated instructions about how to use the archive have been removed.
- [Ghost's publication homepage tutorial](https://ghost.org/tutorials/custom-homepage/) demonstrates content organization through topic sections and publication-specific homepages. RodyTech uses actual WordPress categories and chronological queries; it does not label automatically selected posts as editor's picks or most popular.
- [Nielsen Norman Group's table-of-contents guidance](https://www.nngroup.com/articles/table-of-contents/) explains how section navigation helps readers understand a long page and jump to relevant material. Articles with at least three nonempty second-level headings now get an “In this article” navigator with matching heading labels, underlined links, and a back-to-top link. It is collapsible on phones and expanded initially on desktop. Existing heading IDs are preserved; generated IDs are unique. Article content remains readable without JavaScript.

## Implemented
1. Compact RodyTech Journal masthead; the first headline appears near the top of the page.
2. Lead story and two supporting stories, followed by a chronological feed. The lead is the newest published article, not an editorial endorsement.
3. Topic navigation with current-category state, archive search, and native paginated links.
4. Shared article rows across archive, topic, search, and author pages. Thumbnails support scanning; long headlines remain visible.
5. Article headlines and author/date metadata before featured images. Existing image captions are displayed.
6. Section navigation, simpler related/adjacent reading, and removal of two repetitive archive-promotion panels.
7. RSS discovery and follow links using WordPress's existing feed.
8. Existing charcoal/silver/violet identity, Instrument Serif headings, and restrained pointer lighting remain.

## Correctness and verification
The homepage previously used a separate offset query whose page count could exceed WordPress's main query, making later pages inaccessible. It now uses nine posts from the main query, showing the first three as lead/supporting stories only on page one. The Articles template also captured its pagination base after an article loop, risking article URLs in archive pagination; it now captures the archive page URL before querying articles.

24 browser checks passed across 1440, 768, and 390 px Chromium viewports on WordPress 6.9.1: rendering, long headlines, images, native search/navigation, chronological pagination without duplicates, correct archive page URLs, category state, author archives, RSS, section anchors, reading progress, reduced motion, and no-JavaScript reading. Theme contract and JavaScript syntax checks passed. This is a local preview using public article copies, not a formal WCAG certification or production plugin/performance audit.

## Editorial work beyond layout
The source articles and their publication dates were preserved. The preview contains twelve public articles plus one clearly marked QA fixture; production has a larger archive. Repeated generic images and broad topic labels are content decisions the theme alone cannot resolve. Future editorial work should prioritize article-specific illustrations, accurate sourcing, consistent topic assignment, and a distinct point of view. No fabricated popularity rankings, testimonials, editorial reviews, or newsletter signup were added.
