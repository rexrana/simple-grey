<?php
/*
 * Template Name: Feature Page
 * Description: A Page Template with a featured sidebar, which can be used for
 * a front page or a splash page
 *
 * @package Simple Grey
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['sidebar'] = Timber::get_widgets('sidebar-featured');
$context['sidebar_id'] = 'featured';
$context['post_thumb_size'] = 'large';

Timber::render( array( 'page.twig' ), $context );
