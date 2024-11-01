<?php

/**
 * Plugin Setup
 *
 * @package         smart-featured-image
 * @subpackage      smart-featured-image/includes
 *
 * @since       Smart Featured Image 0.1.0
 */

namespace GrottoPress\SFI\Setup;

if ( ! defined( 'WPINC' ) ) {
    die;
}

use GrottoPress\SFI\Helpers;

/**
 * Begins execution of the plugin.
 * 
 * @action      plugins_loaded
 *
 * @since       Smart Featured Images 0.1.0
 */
function run() {
    add_action( 'admin_init', __NAMESPACE__ . '\add_settings' );

    add_action( 'save_post', __NAMESPACE__ . '\set_featured_image' );
    add_action( 'edit_attachment', __NAMESPACE__ . '\set_featured_image' );

    add_action( 'the_post', __NAMESPACE__ . '\set_featured_image' );
    add_action( 'draft_to_publish', __NAMESPACE__ . '\set_featured_image' );
    add_action( 'new_to_publish', __NAMESPACE__ . '\set_featured_image' );
    add_action( 'pending_to_publish', __NAMESPACE__ . '\set_featured_image' );
    add_action( 'future_to_publish', __NAMESPACE__ . '\set_featured_image' );

    add_filter( 'get_post_metadata', __NAMESPACE__ . '\set_default_featured_image', 10, 4 );
    add_filter( 'update_post_metadata', __NAMESPACE__ . '\unsave_default_featured_image', 10, 5 );

    add_filter( 'pre_update_option', __NAMESPACE__ . '\unset_default_featured_image', 10, 3 );
}

/**
 * Plugin activation checks
 *
 * Passed to the `register_activation_hook` handler
 *
 * @since       Smart Featured Images 0.1.0
 */
function activation_checks() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_die( esc_html__( 'You are not allowed to perform this action!', 'smart-featured-image' ) );
    }

    if ( isset( $_GET['plugin'] ) ) {
        check_admin_referer( "activate-plugin_{$_GET['plugin']}" );
    } else {
        check_admin_referer( 'bulk-plugins' );
    }

    // $PHP_version = phpversion();
    // $required_PHP = '5.3';

    // if ( version_compare( $PHP_version, $required_PHP, '<' ) ) {
    //     $message = sprintf( __( 'This plugin requires PHP version %1$s and up. Your current version is %2$s. Contact your hosting provider to update.', 'smart-featured-image' ), $required_PHP, $PHP_version );

    //     admin_notice( $message );

    //     deactivate_plugin();
    // }

    $WP_version = get_bloginfo( 'version' );
    $required_WP = '4.0';

    if ( version_compare( $WP_version, $required_WP, '<' ) ) {
        $message = sprintf( __( 'This plugin requires WordPress version %1$s and up. Your current version is %2$s. Kindly <a href=\'%3$s\'>update</a>.', 'smart-featured-image' ), $required_WP, $WP_version, network_admin_url( 'update-core.php' ) );

        Helpers\admin_notice( $message );

        Helpers\deactivate_plugin();
    }
}

/**
 * Plugin deactivation checks
 *
 * Passed to the `register_deactivation_hook` handler
 *
 * @since       Smart Featured Images 0.1.0
 */
function deactivation_checks() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_die( esc_html__( 'You are not allowed to perform this action!', 'smart-featured-image' ) );
    }

    if ( isset( $_GET['plugin'] ) ) {
        check_admin_referer( "deactivate-plugin_{$_GET['plugin']}" );
    } else {
        check_admin_referer( 'bulk-plugins' );
    }
}

/**
 * Initialize our options.
 *
 * @action      admin_init
 *
 * @since       Smart Featured Image 0.1.0
 */
function add_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    add_settings_section( 'grotto_default_featured_image_section', 
        esc_html__( 'Default Featured Image', 'smart-featured-image' ),
        function () {
            echo '<p>' . esc_html__( 'Select the default featured image for each post type.',
                'smart-featured-image' ) . '</p>';
        }, 'media' );

    add_action( 'admin_enqueue_scripts', function () { wp_enqueue_media(); } );

    if ( ( $post_types = Helpers\get_post_types() ) ) {
        foreach ( $post_types as $post_type ) {
            $setting_name = Helpers\option_name( $post_type->name );
            $setting_value = Helpers\get_option( $post_type->name );
            $setting_value = wp_attachment_is_image( $setting_value ) ? $setting_value : 0;

            register_setting( 'media', $setting_name, 'absint' );

            add_settings_field( $setting_name, $post_type->labels->singular_name,
                function ( $args ) use ( $post_type, $setting_name, $setting_value ) {
                    echo '<!--<input id="' . esc_attr( $setting_name ) . '" type="hidden" name="' . esc_attr( $setting_name ) . '" value="' . esc_attr( $setting_value ) . '" />-->

                    <input class="regular-text" id="' . esc_attr( $setting_name ) . '-url" type="text" name="' . esc_attr( $setting_name ) . '-url" value="' . esc_attr( wp_get_attachment_url( $setting_value ) ) . '" /> <input id="' . esc_attr( $setting_name ) . '-button" class="button upload-button" type="button" value="' . esc_html__( 'Select file', 'smart-featured-image' ) . '" />
                    
                    <script type="text/javascript">
                        jQuery(function( $ ) {
                            var smart_featured_image_uploader, attachment;
                            $( "#' . esc_attr( $setting_name ) . '-button" ).click(function( e ) {
                                e.preventDefault();
                                if ( smart_featured_image_uploader ) {
                                    smart_featured_image_uploader.open();
                                    return;
                                }
                                smart_featured_image_uploader = wp.media.frames.file_frame = wp.media({
                                    title: "' . esc_html__( 'Upload file', 'smart-featured-image' ) . '",
                                    button: {
                                        text: "' . esc_html__( 'Use file', 'smart-featured-image' ) . '"
                                    },
                                    multiple: false
                                });
                                smart_featured_image_uploader.on("select", function() {
                                    attachment = smart_featured_image_uploader.state().get( "selection" ).first().toJSON();
                                    $( "#' . esc_attr( $setting_name ) . '-url" ).val( attachment.url );
                                    // $( "#' . esc_attr( $setting_name ) . '" ).val( attachment.id );
                                });
                                smart_featured_image_uploader.open();
                            });
                        });
                    </script>';
                },
            'media', 'grotto_default_featured_image_section' );
        }
    }
}

/**
 * Save attachment ID, instead of URL, for each default featured image
 *
 * @filter      pre_update_option
 *
 * @since       Smart Featured Images 0.1.0
 */
function unset_default_featured_image( $value, $option, $old_value ) {
    if (
        ! is_admin()
        || ! isset( $_POST['option_page'] )
        || 'media' != $_POST['option_page']
    ) {
        return $value;
    }

    if ( ! ( $post_types = Helpers\get_post_types() ) ) {
        return $value;
    }

    foreach ( $post_types as $post_type ) {
        if ( $option != ( $option_name = Helpers\option_name( $post_type->name ) ) ) {
            continue;
        }
        
        if ( empty( $_POST[ $option_name . '-url' ] ) ) {
            return 0;
        }

        return attachment_url_to_postid( $_POST[ $option_name . '-url' ] );
    }

    return $value;
}

/**
 * Set featured image.
 *
 * Image to use when featured image not set:
 *
 * - Use first attached image in post OR
 * - Use first image src in post content OR
 * - Use default featured image
 *
 * @action      the_post
 * @action      new_to_publish
 * @action      draft_to_publish
 * @action      pending_to_publish
 * @action      future_to_publish
 *
 * @action      save_post
 * @action      edit_attachment
 *
 * @since       Smart Featured Images 0.1.0
 */
function set_featured_image( $post ) {
    if ( isset( $post->ID ) ) {
        $post_id = $post->ID;
        $my_post = $post;
    } else {
        $post_id = $post;
        $my_post = get_post( $post_id );
    }

    if ( has_post_thumbnail( $post_id ) ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! post_type_supports( $my_post->post_type, 'thumbnail' ) ) {
        return;
    }

    if ( 'publish' != $my_post->post_status ) {
        return;
    }

    if ( Helpers\no_sfi( $post_id ) ) {
        return;
    }

    if ( ( $attached_images = Helpers\get_attached_images( $post_id ) ) ) {
        foreach( $attached_images as $attachment_id => $attachment ) {
            set_post_thumbnail( $post_id, $attachment_id );
            break;
        }
    } elseif ( $image_id = Helpers\get_first_image_ID( $my_post->post_content ) ) {
        set_post_thumbnail( $post_id, $image_id );
    }
}

/**
 * Return default/fallback featured image if post has no featured
 * image
 *
 * @filter          get_post_metadata
 * 
 * @since           Smart Featured Images 0.1.0
 *
 * @see             https://wpseek.com/function/get_metadata/
 * @see             https://wpseek.com/function/get_post_meta/
 * @see             https://wpseek.com/function/get_post_thumbnail_id/
 *
 * @var             mixed       $metadata       The post metadata
 * @var             integer     $post_id        The post ID
 * @var             string      $meta_key       Custom field key
 * @var             boolean     $single         Retrieve single field
 *
 * @return          mixed       The custom field's value
 */
function set_default_featured_image( $metadata, $post_id, $meta_key, $single ) {
    if ( '_thumbnail_id' != $meta_key ) {
        return $metadata;
    }

    global $wpdb;

    $thumbnail_exists = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d AND meta_key=%s", $post_id, $meta_key ) );

    if ( $thumbnail_exists ) {
        return $metadata;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return $metadata;
    }

    $my_post = get_post( $post_id );

    if ( Helpers\post_content_has_image( $my_post ) ) {
        return $metadata;
    }

    if ( ! post_type_supports( $my_post->post_type, 'thumbnail' ) ) {
        return $metadata;
    }

    if ( 'publish' != $my_post->post_status ) {
        return $metadata;
    }

    if ( Helpers\no_sfi( $post_id ) ) {
        return $metadata;
    }

    if ( ( $default = Helpers\get_option( $my_post->post_type ) ) ) {
        return $default;
    }

    return $metadata;
}

/**
 * Never save default/fallback featured as post thumbnail.
 *
 * Simply display it, without saving to database.
 * This would ensure that whenever we change the default (fallback)
 * image, all posts without featured image displays the new one, since
 * the old one was never saved as featured image for those posts.
 *
 * @filter          update_post_metadata
 * 
 * @since           Smart Featured Images 0.1.0
 *
 * @see             https://wpseek.com/function/update_metadata/
 * @see             https://wpseek.com/function/update_post_meta/
 *
 * @var             mixed       $metadata       The post metadata
 * @var             integer     $post_id        The post ID
 * @var             string      $meta_key       Custom field key
 * @var             boolean     $single         Retrieve single field
 *
 * @return          mixed       The custom field's value
 */
function unsave_default_featured_image( $metadata, $post_id, $meta_key, $meta_value, $previous_value ) {
    if ( '_thumbnail_id' != $meta_key ) {
        return $metadata;
    }

    if ( $meta_value != Helpers\get_option( get_post_type( $post_id ) ) ) {
        return $metadata;
    }

    return false;
}
