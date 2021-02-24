# vCard
Clean, light-weight, and user-friendly business card web app. Written in PHP and JavaScript.
## Features
* Dynamically loaded content
* Multiple languages support
* Auto-obfuscation of your e-mail address to prevent bots from reading it
* Autosave of user's language preferences
* Clean, shareable links for every supported language (`example.com/en`, `example.com/de`, `example.com/es`)
* Works even with JavaScript disabled in the client's browser
## Demo
https://michalbrzozowski.com
## Instalation
1. Clone the repository into your public directory of the webserver.
2. Configure the app through `content/content.json`
## Configuration
You can configure the app easily using the `content/content.json` file. The configuration includes setting the
contents of the business card in multiple languages. The purpose and explanation of every key used in the configuration is
presented in the table below. 

|Key | Purpose|
|-----------|------------|
`languages` | An array of supported languages
`default_language` | A language that'll be loaded by default. Has to be set to one of the language codes of supported languages defined in the `languages` array.
`style_src` | Path of the stylesheet of the business card. Don't change it if you want to keep the original style.
`email` | The e-mail address you want to show to the user reading your business card. No anti-bot obfuscation needed - it will be done by the app! 
`canonical` | URL of where the business app is hosted
`avatar_src` | Path of the avatar image
`icon_links` | Array of social media (or any other) links as a key (name of the link, can be anything) and an array of two values: the path of the icon image (`icon_src`) and the URL (`url`) as the key's value.
`web_content` | Helper array to encircle the config keys for business card contents. For every config key inside, you have to include contents for every supported language in the following way: `"languageCode": "contents"`.
`meta_title` | Contents of the meta title. 
`meta_description` | Contents of the meta description. 
`name` | Contents of the main header. 
`short_description` | Contents of the short description
`long_description` | Main contents of your business card.
`bot_safe_email` | The e-mail address you want to show to the users with no JavaScript support enabled. I encourage you to make it as unreadable to robots as possible. The safest way is to use this to notify the user to turn on the JavaScript support, instead of trying to tell the e-mail address.
`css_active_language_menu_option` | HTML class of the **active** language menu option. If you want to keep the original style you shouldn't change it.
`css_inactive_language_menu_option` | HTML class of the **inactive** language menu option.


