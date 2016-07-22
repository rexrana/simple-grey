<?php
/**
 * The template for displaying search results pages.
  *
  * Methods for TimberHelper can be found in the /lib sub-directory
  *
  * @package Simple Grey
  * @subpackage  Timber
  * @since    Timber 0.1
  */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['title'] = sprintf( __( 'Search Results for: %s', 'simple-grey' ), get_search_query() );
$context['post_thumb_classes'] = 'image-left';

Timber::render( array( 'search.twig' ), $context );
