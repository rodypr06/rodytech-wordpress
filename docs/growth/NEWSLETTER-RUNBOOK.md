# Newsletter activation runbook

## Architecture

WordPress remains the publication. An established email provider owns the hosted signup form, subscriber records, consent confirmation, preferences, suppression and sending. The theme links to that public HTTPS form. It does not collect email addresses, proxy API requests, set subscriber cookies, or claim signup success. This avoids maintaining a second subscriber database or exposing a mail API key.

Configure Appearance → Customize → Journal newsletter. Enter the provider's public signup page URL. Leave the verified checkbox OFF until the checks below pass. URLs with credentials or a non-HTTPS scheme are rejected. With the flag off, both placements offer the free field kit and RSS.

This is the initial hosted signup journey. An inline provider form can be added after the actual account supplies its embed code and confirmed field requirements; do not fabricate a form endpoint or conceal a broken integration behind a success message.

## Provider configuration

- Check existing accounts, active subscriber count, plan, consent records and suppression lists before creating anything new.
- Create one Journal list with optional Operator, Builder or Both interests. Email is required; name is optional.
- State the every-two-weeks editorial promise, optional product/service mentions, privacy link and ability to unsubscribe. Avoid preselected marketing consent.
- Enable double opt-in. The confirmation page must distinguish pending from confirmed and provide a safe resend path.
- Set a monitored sender and reply address. Add the real business mailing address in the provider footer; do not invent one.
- Configure SPF/DKIM and the provider's recommended DMARC alignment. Inspect existing DNS before adding records; do not create a second SPF record.
- Configure the resource delivery link and welcome content from EMAIL-COPY.md. Verify automation availability in the actual plan; otherwise use the provider's confirmed-signup resource delivery plus a reviewed manual welcome.
- Ensure unsubscribe and preference changes apply to all later sends. Keep bounced, unsubscribed and complained addresses suppressed.
- Reconcile existing subscriber imports against consent; do not import CRM contacts just because an email address is known.

## End-to-end acceptance

Use a dedicated test mailbox approved by the owner. Record time, configuration revision and message IDs privately.

1. Desktop light/dark and mobile: open the article/footer signup link; keyboard and screen-reader labels work.
2. Submit an invalid address and verify an intelligible error. Submit a fresh test address with consent and optional interests.
3. Verify pending status. No newsletter delivery is allowed before confirmation.
4. Confirm once, then replay the confirmation URL and duplicate the signup. Verify one subscriber and no duplicate welcome sends.
5. Verify the resource and welcome email, monitored reply address, correct preference and unsubscribe links, business address and authentication results.
6. Change interests and verify the stored preference. Unsubscribe and verify a subsequent test send excludes the address.
7. Test a provider outage/unavailable form and the recovery guidance. Revoke the theme enable flag if a production check fails.
8. Confirm personal data never appears in analytics event payloads, UTM parameters, public logs or browser storage.
9. Verify a reviewed digest can be prepared and sent to confirmed test recipients. Record delivery and a genuine click; an open pixel is not proof of a human read.

Only then enable the theme checkbox, rerun the public navigation checks, and mark newsletter delivery complete.

## Measurement integration

journal-growth.js emits rodytech:reader-action locally with two fields: event and placement. Allowed events are newsletter_signup_open, reader_resource_open and rss_open. Allowed placements are article and footer. These are clicks, NOT confirmed subscriptions.

No analytics collector is installed by this change. A consent-aware adapter can listen for the local event and forward the allowed fields to the existing analytics platform. Confirmed subscriptions, deliveries, human clicks, suppression and unsubscribes come from the provider. Keep them separate from website clicks.

Do not send email, full destination URLs, query strings, referrers or free-form text with these events. Tag outgoing promotion links consistently; never rewrite internal links with campaign UTMs.

## Operational cadence

Before each edition, check source dates and examples, remove broken links, review the subject and mobile rendering, send to the test mailbox, and inspect the final recipient segment. Use one campaign identifier for idempotency and record provider campaign ID before sending. On an ambiguous send result, inspect that campaign's authoritative state before retrying.
