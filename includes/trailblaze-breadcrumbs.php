<?php
/*-----------------------------------------------------------------------------------*/
/* Trailblaze
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze
 *
 * Add a breadcrumb navigation list to post, pages and custom post types with the
 * wap8_trailblaze() template tag.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.8 Properly escaping HTML in the output
 * @author Heavy Heavy <@notdivisible>
 *
 */

function wap8_trailblaze() {

	global $post, $wp_query, $page, $paged;

	// pluign settings
	$options    = get_option( '_wap8_trailblaze_settings' );
	$home_label = $options['trailblaze_home'];
	$separator  = '<span class="crumb-separator">' . wap8_convert_trailblaze_separator_items( $options['trailblaze_separator'] ) . '</span>';

	if ( !empty( $home_label ) ) {
		$label = esc_attr( $home_label );
	} else {
		$label = __( 'Home', 'trailblaze' );
	}

	$current_before = '<span class="current-crumb">';
	$current_after  = '</span>';

	if ( !is_home() && !is_front_page() || is_paged() || $wp_query->is_posts_page ) {

		echo "<nav class='breadcrumbs' itemprop='breadcrumbs'>\n";

		$home = home_url();

		echo "\t<a href='" . esc_url( $home ) . "'>" . esc_html( $label ) . "</a> " . $separator . " ";

		if ( $wp_query->is_posts_page && !is_paged() ) {

			$pp = get_the_title( get_option( 'page_for_posts', true ) );

			echo $current_before . esc_html( $pp ) . $current_after;

		} else if ( is_category() && !is_paged() ) {

			$cat_obj    = $wp_query->get_queried_object();
			$this_cat   = $cat_obj->term_id;
			$this_cat   = get_category( $this_cat );
			$parent_cat = get_category( $this_cat->parent );

			if ( $this_cat->parent != 0 )
				echo ( get_category_parents( $parent_cat, true, ' ' . $separator . ' ' ) );

			echo $current_before . single_cat_title( '', false ) . $current_after;

		} else if ( is_tax() && !is_paged() ) {

			$term      = $wp_query->get_queried_object();
			$this_term = $term->name;
			$tax       = get_taxonomy( $term->taxonomy );

			// get the custom post type associated with this custom taxonomy
			$pt         = get_post_type_object( $tax->object_type[0] );
			$pt_name    = $pt->labels->name;
			$pt_archive = get_post_type_archive_link( $tax->object_type[0] );

			echo "<a href='" . esc_url( $pt_archive )  . "'>" . esc_html( $pt_name ) . "</a> " . $separator . " ";	
			echo $current_before . esc_attr( $this_term ) . $current_after;

		} else if ( is_day() && !is_paged() ) {

			$year_link  = get_year_link( get_the_time( 'Y' ) );
			$month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );

			echo "<a href='" . esc_url( $year_link )  . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
			echo "<a href='" . esc_url( $month_link ) . "'>" . esc_html( get_the_time( 'F' ) ) . "</a> " . $separator . " ";
			echo $current_before . esc_html( get_the_time( 'd' ) ) . $current_after;

		} else if ( is_month() && !is_paged() ) {

			$year_link  = get_year_link( get_the_time( 'Y' ) );

			echo "<a href='" . esc_url( $year_link ) . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
			echo $current_before . esc_html( get_the_time( 'F' ) ) . $current_after;

		} else if ( is_year() && !is_paged() ) {
			
			echo $current_before . get_the_time( 'Y' ) . $current_after;

		} else if ( is_post_type_archive() && !is_paged() ) {

			$post_type = get_post_type_object( get_post_type( get_the_ID() ) );
			echo $current_before . esc_html( $post_type->labels->name ) . $current_after;

		} else if ( is_single() && !is_attachment() && $page < 2 ) {

			if ( get_post_type() != 'post' ) {

				$post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
				$posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );

				echo "<a href='" . esc_url( $posttype_url ) . "'>" . esc_html( $post_type->labels->name ) . "</a> " . $separator . " ";
				echo $current_before . esc_html( get_the_title() ) . $current_after;

			} else {

				$cat = get_the_category();
				$cat = $cat[0];

				echo get_category_parents( $cat, true, ' ' . $separator . ' ' );
				echo $current_before . esc_html( get_the_title() ) . $current_after;

			}

		} else if ( is_single() && !is_attachment() && $page >= 2 ) {

			if ( get_post_type() != 'post' ) {

				$post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
				$posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );
				$post_link    = get_permalink( $post->ID );

				echo "<a href='" . esc_url( $posttype_url ) . "'>" . esc_html( $post_type->labels->name ) . "</a> " . $separator . " ";
				echo "<a href='" . esc_url( $post_link ) . "'>" . esc_html( get_the_title() ) . "</a> " . $separator . " ";
				echo $current_before . sprintf( __( ' Page %s', 'wap8theme-i18n' ), get_query_var( 'page' ) ) . $current_after;

			} else {

				$cat       = get_the_category();
				$cat       = $cat[0];
				$post_link = get_permalink( $post->ID );

				echo get_category_parents( $cat, true, ' ' . $separator . ' ' );
				echo "<a href='" . esc_url( $post_link ) . "'>" . esc_html( get_the_title() ) . "</a> " . $separator . " ";
				echo $current_before . sprintf( __( ' Page %s', 'wap8theme-i18n' ), get_query_var( 'page' ) ) . $current_after;

			}

		} else if ( is_attachment() ) {

			$parent = get_post( $post->post_parent );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			
			echo get_category_parents( $cat, true, ' ' . $separator . ' ' );
			echo "<a href='" . esc_url( get_permalink( $parent ) ) . "'>" . esc_html( $parent->post_title ) . "</a> " . $separator . " ";
			echo $current_before . esc_html( get_the_title() ) . $current_after;

		} else if ( is_page() && !$post->post_parent && $page < 2 ) {

			echo $current_before . esc_html( get_the_title() ) . $current_after;

		} else if ( is_page() && !$post->post_parent && $page >= 2 ) {

			$post_link = get_permalink( $post->ID );

			echo "<a href='" . esc_url( $post_link ) . "'>" . esc_html( get_the_title() ) . "</a> " . $separator . " ";
			echo $current_before . sprintf( __( ' Page %s', 'wap8theme-i18n' ), get_query_var( 'page' ) ) . $current_after;

		} else if ( is_page() && $post->post_parent && $page < 2 ) {

			$parent_id   = $post->post_parent;
			$breadcrumbs = array();

			while ( $parent_id ) {

				$page          = get_page( $parent_id );
				$breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
				$parent_id     = $page->post_parent;

			}

			$breadcrumbs = array_reverse( $breadcrumbs );

			foreach ( $breadcrumbs as $crumb )
				echo $crumb . ' ' . $separator . ' ';

			echo $current_before . esc_html( get_the_title() ) . $current_after;

		} else if ( is_page() && $post->post_parent && $page >= 2 ) {

			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			$post_link   = get_permalink( $post->ID );

			while ( $parent_id ) {

				$page          = get_page( $parent_id );
				$breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
				$parent_id     = $page->post_parent;

			}

			$breadcrumbs = array_reverse( $breadcrumbs );

			foreach ( $breadcrumbs as $crumb )
				echo $crumb . ' ' . $separator . ' ';

			echo "<a href='" . esc_url( $post_link ) . "'>" . esc_html( get_the_title() ) . "</a> " . $separator . " ";
			echo $current_before . sprintf( __( ' Page %s', 'wap8theme-i18n' ), get_query_var( 'page' ) ) . $current_after;

		} else if ( is_search() && !is_paged() ) {

			echo $current_before . __( 'Search Results for &ldquo;', 'trailblaze' ) . esc_html( get_search_query() ) . '&rdquo;' . $current_after;

		} else if ( is_tag() && !is_paged() ) {

			echo $current_before . single_tag_title( '', false ) . $current_after;

		} else if ( is_author() && !is_paged() ) {

			global $author;

			$userdata = get_userdata( $author );

			echo $current_before . esc_html( $userdata->display_name ) . $current_after;

		} else if ( is_404() ) {

			echo $current_before . __( '404 Error: Page not found', 'trailblaze' ) . $current_after;

		}

		if ( get_query_var( 'paged' ) ) {

			if ( $wp_query->is_posts_page || is_category() || is_tax() || is_day() || is_month() || is_year() || is_post_type_archive() || is_search() || is_tag() || is_author() ) {

				if ( $wp_query->is_posts_page ) {

					$pp      = get_the_title( get_option( 'page_for_posts', true ) );
					$pp_link = get_permalink( get_option( 'page_for_posts' ) );

					echo "<a href='" . esc_url( $pp_link ) . "'>" . esc_html( $pp ) . "</a>" . " " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_category() ) {

					$cat_obj    = $wp_query->get_queried_object();
					$this_cat   = $cat_obj->term_id;
					$this_cat   = get_category( $this_cat );
					$parent_cat = get_category( $this_cat->parent );
					$cat_link   = get_category_link( $cat_obj->term_id );

					if ( $this_cat->parent != 0 )
						echo ( get_category_parents( $parent_cat, true, ' ' . $separator . ' ' ) );

					echo "<a href='" . esc_url( $cat_link ) . "'>" . single_cat_title( '', false ) . "</a>" . " " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_tax() ) {

					$term      = $wp_query->get_queried_object();
					$this_term = $term->name;
					$tax       = get_taxonomy( $term->taxonomy );
					$tax_link  = get_term_link( get_term( $term->term_id, $term->taxonomy ) );
					
					// get the custom post type associated with this custom taxonomy
					$pt         = get_post_type_object( $tax->object_type[0] );
					$pt_name    = $pt->labels->name;
					$pt_archive = get_post_type_archive_link( $tax->object_type[0] );

					if ( !is_wp_error( $tax_link ) ) {
						echo "<a href='" . esc_url( $pt_archive )  . "'>" . esc_attr( $pt_name ) . "</a> " . $separator . " ";
						echo "<a href='" . esc_url( $tax_link )  . "'>" . esc_attr( $this_term ) . "</a> " . $separator . " ";
						echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;
					}

				} else if ( is_day() ) {

					$year_link  = get_year_link( get_the_time( 'Y' ) );
					$month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
					$day_link   = get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) );

					echo "<a href='" . esc_url( $year_link )  . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
					echo "<a href='" . esc_url( $month_link ) . "'>" . esc_attr( get_the_time( 'F' ) ) . "</a> " . $separator . " ";
					echo "<a href='" . esc_url( $day_link ) . "'>" . esc_attr( get_the_time( 'd' ) ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_month() ) {

					$year_link  = get_year_link( get_the_time( 'Y' ) );
					$month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );

					echo "<a href='" . esc_url( $year_link ) . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
					echo "<a href='" . esc_url( $month_link ) . "'>" . esc_attr( get_the_time( 'F' ) ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_year() ) {

					$year_link  = get_year_link( get_the_time( 'Y' ) );

					echo "<a href='" . esc_url( $year_link ) . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_post_type_archive() ) {

					$post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
					$posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );

					echo "<a href='" . esc_url( $posttype_url ) . "'>" . esc_html( $post_type->labels->name ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_search() ) {

					$searched = get_search_link( get_search_query() );

					echo "<a href='" . esc_url( $searched ) . "'>" . __( 'Search Results for &ldquo;', 'trailblaze' ) . esc_attr( get_search_query() ) . '&rdquo;' . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_tag() ) {

					$tag_obj  = $wp_query->get_queried_object();
					$this_tag = $tag_obj->term_id;
					$tag_link = get_tag_link( $this_tag );

					echo "<a href='" . esc_url( $tag_link ) . "'>" . single_tag_title( '', false ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				} else if ( is_author() ) {

					global $author;

					$userdata     = get_userdata( $author );
					$author_posts = get_author_posts_url( $userdata->ID );

					echo "<a href='" . esc_url( $author_posts ) . "'>" . esc_html( $userdata->display_name ) . "</a> " . $separator . " ";
					echo $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after;

				}

			} else {
				echo __( 'Page ','trailblaze' ) . get_query_var( 'paged' );
			}

		}

		echo "</nav>\n";

	}

}