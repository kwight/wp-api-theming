WP API Theming
===

WP API Theming is meant for use on top of the coming WordPress core [REST API](http://wp-api.org). It fills in some of the missing parts required for theme development.

Requires the [WP REST API](https://github.com/WP-API/WP-API) plugin.

This plugin currently adds the following to the `posts` and `pages` endpoints:
* `post_classes` using `get_post_class()`
* adds `author_link` (the author archive) and `name` to the `author` property
* categories and tags

Note that author and term data are available in the API on individual items [through embedding](http://v2.wp-api.org/extending/linking/), but not yet on collections, which is why they're here. Follow [#865](https://github.com/WP-API/WP-API/issues/865) and [#1196](https://github.com/WP-API/WP-API/issues/1196) for further progress.

## Installation

1. Install and activate the [WP REST API](https://github.com/WP-API/WP-API) plugin . Leave it on the `develop` branch.
2. Clone this repo into your `wp-content/plugins/` folder: `git clone git@github.com:kwight/wp-api-theming.git`
3. Activate the plugin from `Plugins` in the admin.
