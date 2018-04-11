<?php

namespace NikolayS93\AuthPostProtector;

use NikolayS93\WPAdminForm\Form;

$box = new WP_Post_Boxes();
$box->add_fields( '_access' );
$box->add_box( __('Access', DOMAIN), __NAMESPACE__ . '\add_access_postbox', true );
function add_access_postbox() {
    global $wp_roles;

    $all_roles = array(
        '' => __('Any guest', DOMAIN),
        'administrator' => __('Administrator', DOMAIN),
        'editor' => __('Editor', DOMAIN),
        'author' => __('Author', DOMAIN),
        'contributor' => __('Contributor', DOMAIN),
        'subscriber' => __('Subscriber', DOMAIN),
    );

    if( isset($wp_roles->roles) && is_array($wp_roles->roles) ) {
        foreach ($wp_roles->roles as $keyrole => $role) {
            if( !array_key_exists($keyrole, $all_roles) )
                $all_roles[ $keyrole ] = __($role['name']);
        }
    }

    $form = new Form( array(
        'label' => __('User has role', DOMAIN),
        'id' => '_access',
        'type' => 'select',
        'options' => $all_roles,
    ), true, array(
        'postmeta' => true,
    ));

    $form->display();
}
