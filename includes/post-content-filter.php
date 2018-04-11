<?php

namespace NikolayS93\AuthPostProtector;

function has_access() {
    if( current_user_can( 'administrator' ) )
        return true;

    $role = get_post_meta( get_the_ID(), '_access', true );
    if( $role && !current_user_can( $role ) ) {
        return false;
    }

    return true;
}

add_action( 'wp_head', __NAMESPACE__ . '\noindex_display_meta_tag' );
function noindex_display_meta_tag() {
    if ( !is_singular() ) return;

    if ( !has_access() ) {
        echo '<meta name="robots" content="noindex" />' . "\n";
    }
}

add_filter( 'the_content', __NAMESPACE__ . '\check_role_access', 10, 1 );
function check_role_access( $content ) {
    if ( !is_singular() ) return $content;

    if( !has_access() ) {
        $content = __('You\'re can\'t seen this content.', DOMAIN);
        $content.= sprintf(' <a href="%s">%s</a>',
            esc_url( '/wp-login.php?redirect_to=' . get_permalink() ),
            __('Login', DOMAIN)
        );
    }

    return $content;
}
