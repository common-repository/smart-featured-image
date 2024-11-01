<?php

/**
 * Helper functions
 *
 * @package         smart-featured-image
 * @subpackage      smart-featured-image/includes
 *
 * @since           Smart Featured Image 0.1.0
 */

namespace GrottoPress\SFI\Helpers;

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Catch first image src in given content
 *
 * @see         http://www.wprecipes.com/how-to-get-the-first-image-from-the-post-and-display-it
 * @see         http://css-tricks.com/snippets/wordpress/get-the-first-image-from-a-post/
 *
 * @since       Smart Featured Images 0.1.0
 *
 * @var         string          $content            Content to retrieve first image from
 * @var         boolean         $allow_external     Whether or not to include external image sources
 *
 * @return      string          URL of first image in content
 */
function catch_first_image( $content = '', $allow_external = true ) {
    if ( ! $content ) {
        return '';
    }

    $output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );

    if ( empty( $matches[1] ) ) {
        return '';
    }

    if ( $allow_external ) {
        return esc_url( $matches[1][0] );
    }
    
    foreach ( $matches[1] as $source ) {
        $uploads_dir = wp_upload_dir();
        $base_url = isset( $uploads_dir['baseurl'] ) ? $uploads_dir['baseurl'] : false;

        if ( $base_url && 0 === stripos( $source, $base_url ) ) {
            return esc_url( $source );
        }
    }

    return '';
}

/**
 * First image ID
 *
 * Get ID of first image found in post content
 *
 * @since       Smart Featured Images 0.1.0
 *
 * @var         string          $content            Content to retrieve first image from
 *
 * @return      integer         ID of first image in content
 */
function get_first_image_ID( $content = '' ) {
    if ( ! $content ) {
        return 0;
    }

    return attachment_url_to_postid( catch_first_image( $content, false ) );
}

/**
 * Add admin notice
 *
 * @since       Smart Featured Images 0.1.0
 */
function admin_notice( $message, $class = 'notice-error' ) {
    if ( ! $message ) {
        return;
    }

    add_action( 'admin_notices', function () use ( $message, $class ) {
        echo '<div class="notice ' . $class . '">
            <p>' . $message . '</p>
        </div>';
    } );
}

/**
 * Deactivate plugin
 *
 * @since       Smart Featured Images 0.1.0
 */
function deactivate_plugin() {
    add_action( 'admin_init', function () {
        deactivate_plugins( 'smart-featured-image/smart-featured-image.php' );
    } );
}

/**
 * Get post types with thumbnail support
 * 
 * @since       Smart Featured Image 0.1.0
 */
function get_post_types() {
    $post_types = \get_post_types( array(
        'public' => true,
        // 'show_ui' => true,
    ), 'objects' );

    $thumbnail_post_types = array();

    foreach ( $post_types as $post_type ) {
        if ( ! post_type_supports( $post_type->name, 'thumbnail' ) ) {
            continue;
        }

        $thumbnail_post_types[ $post_type->name ] = $post_type;
    }

    return $thumbnail_post_types;
}

/**
 * Get images attached to a given post
 *
 * @var         integer     $post_id        The post ID
 *
 * @since       Smart Featured Image 0.1.0
 */
function get_attached_images( $post_id ) {
    return get_children( array(
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => -1,
    ) );
}

/**
 * Post content has image?
 *
 * @var         \WP_Post     $post        WP_Post object
 *
 * @since       Smart Featured Image 0.1.0
 */
function post_content_has_image( $post ) {
    return ( get_attached_images( $post->ID )
        || catch_first_image( $post->post_content, false ) );
}

/**
 * Get option
 *
 * @var         string     $post_type        Post type
 *
 * @since       Smart Featured Image 0.1.0
 */
function get_option( $post_type ) {
    return absint( \get_option( option_name( $post_type ) ) );
}

/**
 * Delete option
 *
 * @var         string     $post_type        Post type
 *
 * @since       Smart Featured Image 0.1.0
 */
function delete_option( $post_type ) {
    return \delete_option( option_name( $post_type ) );
}

/**
 * Get option name
 *
 * @var         string     $post_type        Post type
 *
 * @since       Smart Featured Image 0.1.0
 */
function option_name( $post_type ) {
    return sanitize_key( $post_type . '_default_featured_image' );
}

/**
 * Is smart featured image disabled for post
 *
 * Allows users to disable smart featured image
 * for specific posts
 *
 * @var         integer     $post_id        Post ID
 *
 * @since       Smart Featured Image 0.1.0
 *
 * @return      boolean         Whether or not 'no_sfi' custom field is set.
 */
function no_sfi( $post_id ) {
    return get_post_meta( absint( $post_id ), 'no_sfi', true );
}
