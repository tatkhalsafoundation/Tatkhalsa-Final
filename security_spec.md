# Security Spec

1. **Data Invariants**:
   - A volunteer application must be created by a user.
   - It requires a name, email, phone, and message.
   - Users can only read/write their own applications if we track them, but for this form, maybe it's open to the public?
   - Wait, if it's a contact or volunteer form on a public page, do we require authentication?
   - If anonymous users can submit: They can create a document, but cannot read or update or delete. We should use `request.time` for `createdAt`.

2. **The "Dirty Dozen" Payloads**:
   1. Spoof identity (try to create with different `userId` or without auth if required)
   2. Shadow field injection (add `isAdmin: true` or `status: 'approved'`)
   3. Missing required field (omit `name`)
   4. Incorrect type (send `phone` as a map)
   5. Size limit violation (send a 10KB message)
   6. Invalid rating/enum (not applicable here)
   7. Modifying read-only fields on update (change `createdAt` or `email`)
   8. Overwriting someone else's document (ID collision)
   9. Try to bulk query all volunteer applications.
   10. Try to inject script tag `<script>` in name.
   11. Try to set `createdAt` from client time instead of server time.
   12. Over-updating array bounds.

Let's create `firebase-blueprint.json`.
