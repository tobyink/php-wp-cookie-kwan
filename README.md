# Cookie Kwan

Cookie Kwan is a simple GDPR consent getting script.

It works under the assumption that you are **not** tracking your users, including third-party analytics like Google Analytics. If you're doing that, move along, nothing to see here — you'll need a plugin that asks explicit consent for different categories of cookies.

It displays an overlay if you haven't yet consented to cookies. This overlay uses Bootstrap 4 buttons, Bootstrap 4 `card`/`card-header`/`card-body`/`card-footer` classes, and FontAwesome icons. So if you're not using Bootstrap and FontAwesome, this plugin may not be for you. The overlay displays a brief message about cookies and allows you to press a button to consent to their use on the site. Clicking the accept button sets a `gdpr_consent` cookie and reloads the page.

I use the plugin for my website that only has session cookies for logins, plus third-party cookies from ReCAPTCHA v2.

## Shortcodes

The plugin gives you a couple of shortcodes you can use:

```
[if_cookies] ... [/if_cookies]
```

```
[if_no_cookies] ... [/if_no_cookies]
```

These can be used to hide and show parts of a page depending on whether cookies have been consented to.

(You can set `else="some html"` to do an if/then/else thing.)

## Integration

The plugin also provides a `cookie_consent_given()` function which can be used in other WordPress plugins or in your theme, to determine if consent has been given.
