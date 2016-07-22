<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Simple Grey
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['error_code'] = 404;
$context['error_class'] = 'not-found';
$context['title'] = __( '404 Error - Not Found', 'simple-grey' );

Timber::render( array( 'error.twig' ), $context );
