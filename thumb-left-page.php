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
$context['float_thumb_dir'] = 'left';
Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
