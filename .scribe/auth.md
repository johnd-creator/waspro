# Authenticating requests

To authenticate requests, include a **`Authorization`** header with the value **`"Bearer {YOUR_SANCTUM_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

<p>Gunakan token Sanctum yang dihasilkan melalui endpoint <code>POST /api/login</code>. Sertakan pada header Authorization dengan format <code>Bearer {token}</code>.</p>
