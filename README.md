# Smart Featured Image WordPress Plugin

* Author: [@GrottoPress](https://gitlab.com/grottopress)
* Author Website: [https://www.grottopress.com](https://www.grottopress.com)
* Contributor(s): [@akadusei](https://gitlab.com/akadusei)
* License: [GNU General Public License v2.0 or later](http://www.gnu.org/licenses/gpl-2.0.html)

## Description

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

Download the plugin from the official WordPress plugin directory, or search for, install and activate the plugin from the *Add Plugins* screen ('**Plugins**' -> '**Add New**') of the WordPress admin area.

Find out more about the plugin from [here](https://www.grottopress.com/tutorials/smart-featured-image-wordpress-plugin/).