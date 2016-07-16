<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Simple Grey
 */

if ( ! function_exists( 'simple_grey_get_the_category_list' ) ) :
/**
 * get a list of categories by post id.
 */
function simple_grey_get_the_category_list( $id )
 {

    $categories = get_the_category( $id );
    $category_list = array();
    foreach ($categories as $category) {
      // exclude Uncategorized from lists
      if($category->name != 'Uncategorized') {
        $category_list[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'simple-grey' ), $category->name ) ) . '" rel="category tag">'. $category->name.'</a>';
      }
    }

	return $category_list;
}
endif;

if ( ! function_exists( 'simple_grey_list_categories_without_uncategorized' ) ) :
/**
 * list post categories without 'Uncategorized' category.
 */
function simple_grey_list_categories_without_uncategorized($args=array())
 {
    if(get_category_by_slug('uncategorized')) {
      $uncatObj = get_category_by_slug('uncategorized');
    	$args['exclude'] = strval($uncatObj->term_id);
    }
    $args['title_li'] = '';
  	return wp_list_categories($args);

}
endif;


if ( ! function_exists( 'get_the_modified_author_id' ) ) :
/**
 * get the modified author id and meta
 */
function get_the_modified_author_id() {
    if ( $last_id = get_post_meta( get_post()->ID, '_edit_last', true) ) {
      return $last_id;
    }
}
endif; // get_the_modified_author_id



if ( ! function_exists( 'simple_grey_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function simple_grey_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'simple-grey' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'simple-grey' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'simple-grey' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'simple_grey_numbered_pagingation' ) ) :
/**
 * Display numbered pagination links
 */
function simple_grey_numbered_pagingation($pages = '', $range = 2)
{
  $showitems = ($range * 2)+1;

  global $paged;
  if(empty($paged)) $paged = 1;

  if($pages == '')
  {
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    if(!$pages)
    {
      $pages = 1;
    }
  }

  if(1 != $pages) {
    echo "<nav class='numbered-pagination'><ul>";
    if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
    if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";

    for ($i=1; $i <= $pages; $i++) {
      if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
      {
        echo ($paged == $i)? "<li class='current'>".$i."</li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
      }
    }

    if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>";
    if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
    echo "</ul></nav>\n";
  }
}
endif;

if ( ! function_exists( 'simple_grey_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function simple_grey_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'simple-grey' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>%title', 'Previous post link', 'simple-grey' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title<span class="meta-nav">&rarr;</span>', 'Next post link',     'simple-grey' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'simple_grey_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function simple_grey_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'simple-grey' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'simple-grey' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'simple-grey' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'simple-grey' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit', 'simple-grey' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'simple-grey' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
				comment_reply_link( array_merge( $args, array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<div class="reply">',
					'after'     => '</div>',
				) ) );
			?>
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for simple_grey_comment()

if ( ! function_exists( 'simple_grey_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function simple_grey_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'simple-grey' ) );
		if ( $categories_list && simple_grey_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'simple-grey' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'simple-grey' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'simple-grey' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'simple-grey' ), __( '1 Comment', 'simple-grey' ), __( '% Comments', 'simple-grey' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'simple-grey' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'simple-grey' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'simple-grey' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'simple-grey' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'simple-grey' ), get_the_date( _x( 'Y', 'yearly archives date format', 'simple-grey' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'simple-grey' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'simple-grey' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'simple-grey' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'simple-grey' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'simple-grey' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'simple-grey' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'simple-grey' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'simple-grey' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'simple-grey' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function simple_grey_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'simple_grey_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'simple_grey_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so simple_grey_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so simple_grey_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in simple_grey_categorized_blog.
 */
function simple_grey_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'simple_grey_categories' );
}
add_action( 'edit_category', 'simple_grey_category_transient_flusher' );
add_action( 'save_post',     'simple_grey_category_transient_flusher' );

/**
* Get the posts page URL.
* See more at: http://cuppster.com/2011/02/17/retrieve-the-posts-page-in-wordpress/
*/

function simple_grey_get_posts_page_url() {

	$siteurl = site_url();
	$homeurl = home_url();

  if( 'page' == get_option( 'show_on_front' ) ) {
    $posts_page_id = get_option( 'page_for_posts' );
    $posts_page = get_page( $posts_page_id );
    $posts_page_url = site_url( get_page_uri( $posts_page_id ) );
  } else {
    $posts_page_url = site_url();
  }

  if(strlen($siteurl) > strlen($homeurl)) {
  	$posts_page_url = str_replace($siteurl, $homeurl, $posts_page_url);
  }

  return $posts_page_url;
}

// Get the posts page title.
function simple_grey_get_posts_page_title() {
  if( 'page' == get_option( 'show_on_front' ) ) {
    $posts_page_title = get_the_title( get_option('page_for_posts', true) );
  } else {
    $posts_page_title = '';
  }
  return $posts_page_title;
}
