# Growth measurement specification

The public WordPress archive is not an analytics dataset. All traffic, subscriber and conversion baselines remain unknown until authenticated accounts are connected.

| Measure | Source | Definition and limitation |
|---|---|---|
| Signup-page opens | Consent-aware website analytics | Local newsletter_signup_open events; this is intent, not a confirmed signup |
| Confirmed signup rate | Provider plus eligible landing visits | New confirmed subscribers / eligible visits in the same window and attribution scope |
| Net subscriber growth | Email provider | Confirmed additions minus unsubscribes and permanent suppressions |
| Human email engagement | Provider plus reviewed click data | Filtered unique clicks and substantive replies / delivered messages; opens are directional only |
| Qualified inquiries | CRM | Unique inquiries with a real workflow, fit and agreed next step |
| Product conversion | Checkout plus product-page analytics | Paid orders / relevant product-page visits; exclude tests and refunds as appropriate |
| Publication contribution | Payment ledger and effort log | Revenue minus fees, refunds, direct tools, support and allocated labor |
| Contribution per hour | Ledger and time record | Contribution before founder labor / founder hours |

## Baseline capture

Export the previous 90 days from the authenticated analytics and Search Console accounts. Record availability, date range, timezone, bot/test filters and the extraction timestamp. Capture the provider's current confirmed subscriber and suppression counts separately. Retain account exports privately; publish only aggregates.

Do not use zero as a stand-in for missing data. Do not compare a full month's traffic to a partial month's sales. Deduplicate first-touch and assisted conversions rather than adding them.

## Promotion tags

Use source linkedin, medium organic_social, campaign inquiry_series, content diagram_post or lesson_post for the first article. Use the same article campaign in the digest with source journal and medium email. Encode URLs with a URL builder. Never include personal data or use promotional UTMs on internal links.

## Weekly review

Record week, published article, delivery count, confirmed additions, human clicks, replies, relevant conversations, purchases, refunds, hours and costs. Explain missing data and anomalies. At day 30 inspect the resource promise and acquisition quality before increasing output. At day 90 choose channels and products based on contribution and effort rather than impressions alone.
