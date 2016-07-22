<?php
/*
 * Template Name: Thumbnail floated left
 * Description: A Page Template with the post Thumbnail floated left
 *
 * @package Simple Grey
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['post_thumb_size'] = 'medium';
$context['post_thumb_classes'] = 'image-left';
Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
