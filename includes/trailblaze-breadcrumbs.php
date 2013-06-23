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
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_trailblaze() {
	
	global $post;
	global $wp_query;
	
	// pluign settings
	$options    = get_option( '_wap8_trailblaze_settings' );
	$home_label = $options['trailblaze_home'];
	$separator  = '<span class="crumb-separator">' . wap8_convert_trailblaze_separator_items( $options['trailblaze_separator'] ) . '</span>';
	
	if ( !empty( $home_label ) ) {
		$label = esc_attr( $home_label );
	} else {
		$label = __( 'Home', 'wap8plugin-i18n' );
	}
	
	$current_before = '<span class="current-crumb">';
	$current_after  = '</span>';
	
	if ( !is_home() && !is_front_page() || is_paged() ) {
		
		echo "<nav class='breadcrumbs' itemprop='breadcrumbs'>\n";
		
		$home = home_url();
		
		echo "\t<a href='" . esc_url( $home ) . "'>" . $label . "</a> " . $separator . " ";
		
		if ( is_category() ) {
			
			$cat_obj    = $wp_query->get_queried_object();
			$this_cat   = $cat_obj->term_id;
			$this_cat   = get_category( $this_cat );
			$parent_cat = get_category( $this_cat->parent );
			
			if ( $thisCat->parent != 0 )
				echo ( get_category_parents( $parent_cat, true, ' ' . $separator . ' ' ) );
			
			echo $current_before . single_cat_title( '', false ) . $current_after;
			
		} else if ( is_day() ) {
			
			$year_link  = get_year_link( get_the_time( 'Y' ) );
			$month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
			
			echo "<a href='" . esc_url( $year_link )  . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
			echo "<a href='" . esc_url( $month_link ) . "'>" . esc_attr( get_the_time( 'F' ) ) . "</a> " . $separator . " ";
			echo $current_before . esc_attr( get_the_time( 'd' ) ) . $current_after;
			
		} else if ( is_month() ) {
			
			$year_link  = get_year_link( get_the_time( 'Y' ) );
			
			echo "<a href='" . esc_url( $year_link ) . "'>" . get_the_time( 'Y' ) . "</a> " . $separator . " ";
			echo $current_before . esc_attr( get_the_time( 'F' ) ) . $current_after;
			
		} else if ( is_year() ) {
			
			echo $current_before . get_the_time( 'Y' ) . $current_after;
			
		} else if ( is_post_type_archive() ) {
			
			$post_type = get_post_type_object( get_post_type() );
			echo $current_before . esc_attr( $post_type->labels->singular_name ) . $current_after;
			
		} else if ( is_single() && !is_attachment() ) {
			
			if ( get_post_type() != 'post' ) {
				
				$post_type    = get_post_type_object( get_post_type() );
				$posttype_url = get_post_type_archive_link( get_post_type() );
				
				echo "<a href='" . esc_url( $posttype_url ) . "'>" . esc_attr( $post_type->labels->singular_name ) . "</a> " . $separator . " ";
				echo $current_before . esc_attr( get_the_title() ) . $current_after;
			
			} else {
				
				$cat = get_the_category();
				$cat = $cat[0];
				
				echo get_category_parents( $cat, true, ' ' . $separator . ' ' );
				echo $current_before . esc_attr( get_the_title() ) . $current_after;
				
			} 
			
		} else if ( is_attachment() ) {
			
			$parent = get_post( $post->post_parent );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			
			echo get_category_parents( $cat, true, ' ' . $separator . ' ' );
			echo "<a href='" . esc_url( get_permalink( $parent ) ) . "'>" . esc_attr( $parent->post_title ) . "</a> " . $separator . " ";
			echo $current_before . esc_attr( get_the_title() ) . $current_after;
			
		} else if ( is_page() && !$post->post_parent ) {
			
			echo $current_before . esc_attr( get_the_title() ) . $current_after;
			
		} else if ( is_page() && $post->post_parent ) {
			
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			
			while ( $parent_id ) {
			
				$page          = get_page( $parent_id );
				$breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_attr( get_the_title( $page->ID ) ) . '</a>';
				$parent_id     = $page->post_parent;
			
			}
			
			$breadcrumbs = array_reverse( $breadcrumbs );
			
			foreach ( $breadcrumbs as $crumb )
				echo $crumb . ' ' . $separator . ' ';
			
			echo $current_before . esc_attr( get_the_title() ) . $current_after;
			
		} else if ( is_search() ) {
			
			echo $current_before . __( 'Search Results for &ldquo;', 'wap8plugin-i18n' ) . esc_attr( get_search_query() ) . '&rdquo;' . $current_after;
			
		} else if ( is_tag() ) {
			
			echo $current_before . single_tag_title( '', false ) . $current_after;
			
		} else if ( is_author() ) {
			
			global $author;
			
			$userdata = get_userdata( $author );
			
			echo $current_before . esc_attr( $userdata->display_name ) . $current_after;
			
		} else if ( is_404() ) {
			
			echo $current_before . __( '404 Error: Page not found', 'wap8plugin-i18n' ) . $current_after;
			
		}
		
		if ( get_query_var('paged') ) {
			
			echo $current_before . __( 'Page ','wap8plugin-i18n' ) . get_query_var( 'paged' ) . $current_after;
			
		}
		
		echo "</nav>\n";
		
	}
	
}