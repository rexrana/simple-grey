<?php
/*
 * Template Name: Thumbnail floated right
 * Description: A Page Template with the post Thumbnail floated right
 *
 * @package Simple Grey
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['post_thumb_size'] = 'medium';
$context['post_thumb_classes'] = 'image-right';
Timber::render( array( 'page.twig' ), $context );
