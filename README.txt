=== Smart Featured Image ===
Contributors: grottopress, attakusiadusei
Donate link: 
Tags: automatic-featured-image, smart-featured-image, featured-image, post-thumbnail
Requires at least: 4.0
Tested up to: 4.8
Requires PHP: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automagically add featured image to posts using images inserted into post content, if no featured image is explicitly added to post.

== Description ==

**IMPORTANT:** *This plugin requires **PHP** version **5.3** or newer. We recommend **PHP** version **7.0** or newer.*

*Smart Featured Image* is a WordPress plugin to automagically add featured images to posts using images inserted into post content, if no featured image is added to a post.

WordPress, by default, requires you to explicitly set a featured image for each post even if there are images already used in the post.

The Smart Featured Image plugin frees you from the extra step of setting a featured image by saving the first local image it finds in the post content as featured image.

The plugin displays a configurable default image if no image is found in post content.

The default image settings are available via the *Media Settings* screen ('**Settings**' -> '**Media**') in the WordPress administration area. There are options to set the default featured image for each public post type.

*Smart Featured Image* works by:

1. Checking if a featured image was added to the post.
1. If no featured image was added, the plugin looks for images attached to the post (ie images uploaded to the current post). If found, the first attached image is saved as the post's featured image.
1. If no image was attached to the post, the plugin looks for an image inserted into the post content, but not necessary attached to the post. If found, the first local image inserted is saved as featured image.
1. If all of the above fail, the plugin falls back to the default featured image set for the post type in question under the *Default Featured Image* section of the *Media Settings* screen.

You would notice that, in step 4 above, the plugin **does not save** the default featured image as the featured image. Rather, it simply returns that image whenever a call to get the post thumbnail is made. This is the intended behaviour.

This means, any time the default featured image changes, all posts that used the previous default featured image would automatically use the new default featured image since the old one was never saved to the database as featured image.

You can disable smart featured image for any specific post by setting a `no_sfi` custom field to any value other than `0`. Delete the custom field, or set to `0`, to restore plugin functionality for that post.

Give *Smart Featured Image* a shot. You will love it.

== Installation ==

Follow the steps below to install the plugin:

1. Unzip and upload `smart-featured-image` to the `wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the *Default Featured Image* under '**Settings**' -> '**Media**'.

Find out more from [here](https://www.grottopress.com/tutorials/smart-featured-image-wordpress-plugin/).

== Frequently Asked Questions ==

= Where are the plugin's settings? =

The plugin simply works upon activation. You may set the default featured image for each public post type under '**Settings**' -> '**Media**' sub-menu of the WordPress admin area.

= Can I disable Smart Featured Image for specific posts =

Of course! For any post you want to disable smart featured image, just set a `no_sfi` custom field to any value other than `0`. To restore smart featured image functionality, simply delete the `no_sfi` custom field, or set to `0`, for that post.

= How do I contribute to development? =

The plugin's source code is on [Gitlab](https://gitlab.com/GrottoPress/smart-featured-image)

== Screenshots ==

1. The Default Featured Image section on the Media Settings screen.

== Changelog ==

= 0.2.4 =
- Release date: 2017-08-29
- Added required PHP version to readme.txt header
- Fixed error: autoloader not found

= 0.2.3 =
- Release date: 2017-08-28
- Fixed error: $catch_image_id undefined in plugin setup.

= 0.2.1 =
- Release date: 2017-07-20
- Improved performance by reducing database queries.

= 0.2.0 =
- Release date: 2017-07-07
- Added ability to disable smart featured image functionality for any specific post.

= 0.1.4 =
- Fixed permission issue when bulk activating or deactivating plugins

= 0.1.3 =
- Release date: 2017-06-12
- Fix: Pasting URL in default featured image fields not saving.

= 0.1.2 =
- Release date: 2017-06-11
- Fix: Default featured image settings could not be unset.

= 0.1.1 =
- Release date: 2017-06-08
- Fixed typo in README.txt

= 0.1.0 =
- Release date: 2017-06-08
- Initial public release
