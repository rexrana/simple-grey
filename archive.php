<?php
/**
* The template for displaying Archive pages.
*
* Used to display archive-type pages if nothing more specific matches a query.
* For example, puts together date-based pages if no date.php file exists.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* Methods for TimberHelper can be found in the /lib sub-directory
*
 * @package Simple Grey (Twig)
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array( 'archive.twig', 'index.twig' );

$data = Timber::get_context();

$data['title'] = __( 'Archives', 'simple-grey' );

if ( is_day() ) {
  $data['title'] = sprintf( __( 'Day: %s', 'simple-grey' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'simple-grey' ) ) );
} else if ( is_month() ) {
  $data['title'] = sprintf( __( 'Month: %s', 'simple-grey' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'simple-grey' ) ) );
} else if ( is_year() ) {
  $data['title'] = sprintf( __( 'Year: %s', 'simple-grey' ), get_the_date( _x( 'Y', 'yearly archives date format', 'simple-grey' ) ) );
} else if ( is_tag() ) {
  $data['title'] = sprintf( __( 'Tag: %s', 'simple-grey' ), single_tag_title( '', false ) );
} else if ( is_category() ) {
  $data['title'] = sprintf( __( 'Category: %s', 'simple-grey' ), single_cat_title( '', false ) );
	array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.twig' );
} else if ( is_author() ) {
  $data['title'] = sprintf( __( 'Author: %s', 'simple-grey' ), '<span class="vcard">' . get_the_author() . '</span>' );
} else if ( is_tax( 'post_format', 'post-format-aside' ) ) {
  $data['title'] = _x( 'Asides', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-gallery' ) ) {
  $data['title'] = _x( 'Galleries', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-image' ) ) {
  $data['title'] = _x( 'Images', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-video' ) ) {
  $data['title'] = _x( 'Videos', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-quote' ) ) {
  $data['title'] = _x( 'Quotes', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-link' ) ) {
  $data['title'] = _x( 'Links', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-status' ) ) {
  $data['title'] = _x( 'Statuses', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-audio' ) ) {
  $data['title'] = _x( 'Audio', 'post format archive title', 'simple-grey' );
} else if ( is_tax( 'post_format', 'post-format-chat' ) ) {
  $data['title'] = _x( 'Chats', 'post format archive title', 'simple-grey' );
} else if ( is_tax() ) {
  $tax = get_taxonomy( get_queried_object()->taxonomy );
  /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
  $data['title'] = sprintf( __( '%1$s: %2$s', 'simple-grey' ), $tax->labels->singular_name, single_term_title( '', false ) );
  /* set template suggestion */
  // array_unshift( $templates, 'taxonomy-' . $tax->name . '.twig' );
} else if ( is_post_type_archive() ) {
  $data['title'] = sprintf( __( 'Archives: %s', 'simple-grey' ), post_type_archive_title( '', false ) );
	array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
}

// archive description
if ( is_tag() ||  is_category() || is_tax() ) {
  $data['archive_description'] = apply_filters( 'get_the_archive_description', term_description() );
}

$data['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

Timber::render( $templates, $data );
