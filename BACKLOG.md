# RodyTech Journal implementation backlog

## Priority 1 Newsletter subscriptions and email delivery

Added September 5, 2026. Status: in progress; provider and production access pending. This is a free reader email subscription; paid memberships remain a later strategy option.

- [ ] Select an email provider after checking any existing account. Compare Kit and beehiiv against actual subscriber count, budget, branding, and welcome-email automation requirements; verify current plan limits.
- [ ] Replace the inactive newsletter placeholder with working signup forms in the footer and appropriate article locations, styled for light and dark appearance.
- [ ] Offer optional operator or builder interests and a clear every-two-weeks publishing promise.
- [ ] Implement consent recording, confirmation, privacy information, unsubscribe, and preference management.
- [ ] Configure the sending domain and email authentication through the selected provider.
- [ ] Create a branded welcome email with the relevant resource and an invitation to reply. Confirm whether the chosen plan supports automatic delivery.
- [ ] Prepare reusable digest templates and an editorial review process for scheduled sends.
- [ ] Track confirmed subscriptions, acquisition source, delivery, human clicks, unsubscribes, and relevant business conversions without putting personal information in analytics events.
- [ ] Verify the complete signup-to-delivery journey, duplicate submissions, error states, confirmation, resource access, and unsubscribe on desktop and mobile.

Done when a reader can subscribe, receive the promised email/resource, choose interests, and unsubscribe successfully; consent and delivery records are available; and the owner can prepare and send a reviewed digest.

Implemented and tested: reusable article/footer invitations, verified hosted-signup configuration, free operator and builder resources, shared reader appearance, and privacy-safe local event hooks. Welcome and digest copy are prepared in docs/growth/EMAIL-COPY.md. See docs/growth/EXECUTION.md for evidence and remaining dependencies. No provider purchases, subscriber collection, live sends, or production deployment have occurred.
