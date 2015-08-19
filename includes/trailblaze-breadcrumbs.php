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
 * @since 1.1.0 Add structured data to the markup
 * @since 1.1.0 Add support for post format archive
 * @since 1.1.0 Post categories are now hierarchical
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze() {

    global $post, $wp_query, $page, $paged;
    
    // pluign settings
    $options    = get_option( '_wap8_trailblaze_settings' );
    $home_label = $options['trailblaze_home'];
    $separator  = '<span class="crumb-separator"> ' . wap8_convert_trailblaze_separator_items( $options['trailblaze_separator'] ) . ' </span>';
    
    if ( !empty( $home_label ) ) {
        $label = esc_attr( $home_label );
    } else {
        $label = __( 'Home', 'trailblaze' );
    }
    
    $list_element_before = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $list_element_after  = '</span>';
    
    $link_text_before = '<span itemprop="name">';
    $link_text_after = '</span>';
    
    $current_before = '<span class="current-crumb">';
    $current_after  = '</span>';

    $itemprop = ' itemprop="url"';
    
    if ( !is_home() && !is_front_page() || is_paged() || $wp_query->is_posts_page ) {

        echo "<nav role='navigation' itemprop='breadcrumb' itemscope itemtype='http://schema.org/BreadcrumbList' class='breadcrumbs'>";
        
        $home = home_url();
        
        echo $list_element_before . "<a rel='index home'" . $itemprop . " href='" . esc_url( $home ) . "'>" . $link_text_before . esc_html( $label ) . $link_text_after . "</a>" . $list_element_after . $separator;

        // if 1st of page for posts archive
        if ( $wp_query->is_posts_page && !is_paged() ) {

            $pp = get_the_title( get_option( 'page_for_posts', true ) );
            
            echo $list_element_before . $link_text_before . $current_before . esc_html( $pp ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of category archive
        } else if ( is_category() && !is_paged() ) {

            $cat_obj    = $wp_query->get_queried_object();
            $this_cat   = $cat_obj->term_id;
            $this_cat   = get_category( $this_cat );
            $parent_cat = get_category( $this_cat->parent );
            
            if ( $this_cat->parent != 0 ) {
                $cats = get_category_parents( $parent_cat, true, $separator );
                $cats = preg_replace( '#^(.+)$delimiter$#', '$1', $cats );
                $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $list_element_before . '<a$1' . $itemprop . '>' . $link_text_before . '$2' . $link_text_after . '</a>' . $list_element_after, $cats );

                echo $cats;
            }
            
            echo $list_element_before . $link_text_before . $current_before . single_cat_title( '', false ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of custom taxonomy archive
        } else if ( is_tax() && !is_tax( 'post_format' ) && !is_paged() ) {

            $term      = $wp_query->get_queried_object();
            $this_term = $term->name;
            $tax       = get_taxonomy( $term->taxonomy );
            
            // get the custom post type associated with this custom taxonomy
            $pt         = get_post_type_object( $tax->object_type[0] );
            $pt_name    = $pt->labels->name;
            $pt_archive = get_post_type_archive_link( $tax->object_type[0] );
            
            echo $list_element_before . "<a href='" . esc_url( $pt_archive ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $pt_name ) . $link_text_after . "</a>" . $list_element_after . $separator;	
            echo $list_element_before . $link_text_before . $current_before . esc_attr( $this_term ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of post format archive
        } else if ( is_tax( 'post_format' ) && !is_paged() ) {

            $format = get_post_format_string( get_post_format() );

            echo $list_element_before . $link_text_before . $current_before . esc_html( $format ) . __( ' Archive', 'trailblaze' ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of daily archive
        } else if ( is_day() && !is_paged() ) {

            $year_link  = get_year_link( get_the_time( 'Y' ) );
            $month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
            
            echo $list_element_before . "<a href='" . esc_url( $year_link ) . "'" . $itemprop . ">" . $link_text_before . get_the_time( 'Y' ) . $link_text_after . "</a>" . $list_element_after . $separator;
            echo $list_element_before . "<a href='" . esc_url( $month_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_time( 'F' ) ) . $link_text_after . "</a>" . $list_element_after . $separator;
            echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_time( 'd' ) ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of monthly archive
        } else if ( is_month() && !is_paged() ) {

            $year_link  = get_year_link( get_the_time( 'Y' ) );
            
            echo $list_element_before . "<a href='" . esc_url( $year_link ) . "'" . $itemprop . ">" . $link_text_before . get_the_time( 'Y' ) . $link_text_after . "</a>" . $list_element_after . $separator;
            echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_time( 'F' ) ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of yearly archive
        } else if ( is_year() && !is_paged() ) {
			
            echo $list_element_before . $link_text_before . $current_before . get_the_time( 'Y' ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of custom post type archive
        } else if ( is_post_type_archive() && !is_paged() ) {

            $post_type = get_post_type_object( get_post_type( get_the_ID() ) );
            echo $list_element_before . $link_text_before . $current_before . esc_html( $post_type->labels->name ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of single template
        } else if ( is_single() && !is_attachment() && $page < 2 ) {

            // custom post type single
            if ( get_post_type() != 'post' ) {

                $post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
                $posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );
                
                echo $list_element_before . "<a href='" . esc_url( $posttype_url ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $post_type->labels->name ) . $link_text_after . "</a>" . $list_element_after . $separator;
                echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_title() ) . $current_after . $link_text_after . $list_element_after;

            // blog post single
            } else {

                $cat = get_the_category();
                $cat = $cat[0];

                $cats = get_category_parents( $cat, true, $separator );
                $cats = preg_replace( '#^(.+)$delimiter$#', '$1', $cats );
                $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $list_element_before . '<a$1' . $itemprop . '>' . $link_text_before . '$2' . $link_text_after . '</a>' . $list_element_after, $cats);

                echo $cats;
                
                echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_title() ) . $current_after . $link_text_after . $list_element_after;

            }

        // if single template is paginated
        } else if ( is_single() && !is_attachment() && $page >= 2 ) {

            // custom post type single
            if ( get_post_type() != 'post' ) {

                $post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
                $posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );
                $post_link    = get_permalink( $post->ID );
                
                echo $list_element_before . "<a href='" . esc_url( $posttype_url ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $post_type->labels->name ) . $link_text_after . "</a>" . $list_element_after . $separator;
                echo $list_element_before . "<a href='" . esc_url( $post_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_title() ) . $link_text_after . "</a>" . $list_element_after . $separator;
                echo $list_element_before . $link_text_before . $current_before . sprintf( __( ' Page %s', 'trailblaze' ), get_query_var( 'page' ) ) . $current_after . $link_text_after . $list_element_after;

            // blog post single
            } else {

                $cat       = get_the_category();
                $cat       = $cat[0];
                $post_link = get_permalink( $post->ID );

                $cats = get_category_parents( $cat, true, $separator );
                $cats = preg_replace( '#^(.+)$delimiter$#', '$1', $cats );
                $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $list_element_before . '<a$1' . $itemprop . '>' . $link_text_before . '$2' . $link_text_after . '</a>' . $list_element_after, $cats);

                echo $cats;
                
                echo $list_element_before . "<a href='" . esc_url( $post_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_title() ) . $link_text_after . "</a>" . $list_element_after . $separator;
                echo $list_element_before . $link_text_before . $current_before . sprintf( __( ' Page %s', 'trailblaze' ), get_query_var( 'page' ) ) . $current_after . $link_text_after . $list_element_after;

			}

        // if is attachments
        } else if ( is_attachment() ) {

            $parent = get_post( $post->post_parent );
            $cat    = get_the_category( $parent->ID );
            $cat    = $cat[0];

            $cats = get_category_parents( $cat, true, $separator );
            $cats = preg_replace( '#^(.+)$delimiter$#', '$1', $cats );
            $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $list_element_before . '<a$1' . $itemprop . '>' . $link_text_before . '$2' . $link_text_after . '</a>' . $list_element_after, $cats);

            echo $cats;
			
            echo $list_element_before . "<a href='" . esc_url( get_permalink( $parent ) ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $parent->post_title ) . $link_text_after . "</a>" . $list_element_after . $separator;
            echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_title() ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of page template
        } else if ( is_page() && !$post->post_parent && $page < 2 ) {

			echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_title() ) . $current_after . $link_text_after . $list_element_after;

        // if paginated page template
        } else if ( is_page() && !$post->post_parent && $page >= 2 ) {

            $post_link = get_permalink( $post->ID );
            
            echo $list_element_before . "<a href='" . esc_url( $post_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_title() ) . $link_text_after . "</a>" . $list_element_after . $separator;
            echo $list_element_before . $link_text_before . $current_before . sprintf( __( ' Page %s', 'trailblaze' ), get_query_var( 'page' ) ) . $current_after . $link_text_after . $list_element_after;

        // if first page of child page
        } else if ( is_page() && $post->post_parent && $page < 2 ) {

            $parent_id   = $post->post_parent;
            $breadcrumbs = array();

			while ( $parent_id ) {

                $page          = get_page( $parent_id );
                $breadcrumbs[] = $list_element_before . '<a href="' . esc_url( get_permalink( $page->ID ) ) . '"' . $itemprop . '>' . $link_text_before . esc_html( get_the_title( $page->ID ) ) . $link_text_after . '</a>' . $list_element_after;
                $parent_id     = $page->post_parent;

			}

            $breadcrumbs = array_reverse( $breadcrumbs );

            foreach ( $breadcrumbs as $crumb )
                echo $crumb . $separator;

            echo $list_element_before . $link_text_before . $current_before . esc_html( get_the_title() ) . $current_after . $link_text_after . $list_element_after;

        // if paginated page of child page
        } else if ( is_page() && $post->post_parent && $page >= 2 ) {

            $parent_id   = $post->post_parent;
            $breadcrumbs = array();
            $post_link   = get_permalink( $post->ID );

            while ( $parent_id ) {

                $page          = get_page( $parent_id );
                $breadcrumbs[] = $list_element_before . '<a href="' . esc_url( get_permalink( $page->ID ) ) . '"' . $itemprop . '>' . $link_text_before . esc_html( get_the_title( $page->ID ) ) . $link_text_after . '</a>' . $list_element_after;
                $parent_id     = $page->post_parent;

			}

            $breadcrumbs = array_reverse( $breadcrumbs );

            foreach ( $breadcrumbs as $crumb )
                echo $crumb . $separator;

            echo "<a href='" . esc_url( $post_link ) . "'" . $itemprop . ">" . esc_html( get_the_title() ) . "</a>" . $separator;
            echo $list_element_before . $link_text_before . $current_before . sprintf( __( ' Page %s', 'trailblaze' ), get_query_var( 'page' ) ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of search results
        } else if ( is_search() && !is_paged() ) {

            echo $list_element_before . $link_text_before . $current_before . __( 'Search Results for &ldquo;', 'trailblaze' ) . esc_html( get_search_query() ) . '&rdquo;' . $current_after . $link_text_after . $list_element_after;

        // if 1st page of tag archive
        } else if ( is_tag() && !is_paged() ) {

            echo $list_element_before . $link_text_before . $current_before . single_tag_title( '', false ) . $current_after . $link_text_after . $list_element_after;

        // if 1st page of author archive
        } else if ( is_author() && !is_paged() ) {

            global $author;
            
            $userdata = get_userdata( $author );
            
            echo $list_element_before . $link_text_before . $current_before . esc_html( $userdata->display_name ) . $current_after . $link_text_after . $list_element_after;

        // if 404 page
        } else if ( is_404() ) {

            echo $list_element_before . $link_text_before . $current_before . __( '404 Error: Page not found', 'trailblaze' ) . $current_after . $link_text_after . $list_element_after;

        }

        // if paginating
        if ( get_query_var( 'paged' ) ) {

            if ( $wp_query->is_posts_page || is_category() || is_tax() || is_day() || is_month() || is_year() || is_post_type_archive() || is_search() || is_tag() || is_author() ) {

                // if paginated page for posts
                if ( $wp_query->is_posts_page ) {

                    $pp      = get_the_title( get_option( 'page_for_posts', true ) );
                    $pp_link = get_permalink( get_option( 'page_for_posts' ) );
                    
                    echo $list_element_before . "<a href='" . esc_url( $pp_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $pp ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated category archive
                } else if ( is_category() ) {

                    $cat_obj    = $wp_query->get_queried_object();
                    $this_cat   = $cat_obj->term_id;
                    $this_cat   = get_category( $this_cat );
                    $cat_link   = get_category_link( $cat_obj->term_id );

                    if ( $this_cat->parent != 0 ) {
                        $cats = get_category_parents( $parent_cat, true, $separator );
                        $cats = preg_replace( '#^(.+)$delimiter$#', '$1', $cats );
                        $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $list_element_before . '<a$1' . $itemprop . '>' . $link_text_before . '$2' . $link_text_after . '</a>' . $list_element_after, $cats );
        
                        echo $cats;
                    }

                    echo $list_element_before . "<a href='" . esc_url( $cat_link ) . "'" . $itemprop . ">" . $link_text_before . single_cat_title( '', false ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated custom taxonomy archive
				} else if ( is_tax() && !is_tax( 'post_format' ) ) {

					$term      = $wp_query->get_queried_object();
					$this_term = $term->name;
					$tax       = get_taxonomy( $term->taxonomy );
					$tax_link  = get_term_link( get_term( $term->term_id, $term->taxonomy ) );
					
					// get the custom post type associated with this custom taxonomy
					$pt         = get_post_type_object( $tax->object_type[0] );
					$pt_name    = $pt->labels->name;
					$pt_archive = get_post_type_archive_link( $tax->object_type[0] );

                    if ( !is_wp_error( $tax_link ) ) {
                        echo $list_element_before . "<a href='" . esc_url( $pt_archive ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $pt_name ) . $link_text_after . "</a>" . $list_element_after . $separator;
                        echo $list_element_before . "<a href='" . esc_url( $tax_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $this_term ) . $link_text_after . "</a>" . $list_element_after . $separator;
                        echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;
                    }

                // if paginated post format archive
                } else if ( is_tax( 'post_format' ) ) {

                    $format = get_post_format_string( get_post_format() );
                    $link   = get_post_format_link( get_post_format() );

                    echo $list_element_before . "<a href='" . esc_url( $link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $format ) . __( ' Archive', 'trailblaze' ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated daily archive
                } else if ( is_day() ) {

                    $year_link  = get_year_link( get_the_time( 'Y' ) );
                    $month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
                    $day_link   = get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) );
                    
                    echo $list_element_before . "<a href='" . esc_url( $year_link ) . "'" . $itemprop . ">" . $link_text_before . get_the_time( 'Y' ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . "<a href='" . esc_url( $month_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_time( 'F' ) ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . "<a href='" . esc_url( $day_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_time( 'd' ) ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated montly archive
                } else if ( is_month() ) {

                    $year_link  = get_year_link( get_the_time( 'Y' ) );
                    $month_link = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
                    
                    echo $list_element_before . "<a href='" . esc_url( $year_link ) . "'" . $itemprop . ">" . $link_text_before . get_the_time( 'Y' ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . "<a href='" . esc_url( $month_link ) . "'" . $itemprop . ">" . $link_text_before . esc_html( get_the_time( 'F' ) ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated yearly archive
                } else if ( is_year() ) {

                    $year_link  = get_year_link( get_the_time( 'Y' ) );
                    
                    echo $list_element_before . "<a href='" . esc_url( $year_link ) . "'" . $itemprop . ">" . $link_text_before . get_the_time( 'Y' ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated custom post type archive
                } else if ( is_post_type_archive() ) {

                    $post_type    = get_post_type_object( get_post_type( get_the_ID() ) );
                    $posttype_url = get_post_type_archive_link( get_post_type( get_the_ID() ) );
                    
                    echo $list_element_before . "<a href='" . esc_url( $posttype_url ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $post_type->labels->name ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated search results
                } else if ( is_search() ) {

                    $searched = get_search_link( get_search_query() );
                    
                    echo $list_element_before . "<a href='" . esc_url( $searched ) . "'" . $itemprop . ">" . $link_text_before . __( 'Search Results for &ldquo;', 'trailblaze' ) . esc_html( get_search_query() ) . '&rdquo;' . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated tag archive
                } else if ( is_tag() ) {

                    $tag_obj  = $wp_query->get_queried_object();
                    $this_tag = $tag_obj->term_id;
                    $tag_link = get_tag_link( $this_tag );
                    
                    echo $list_element_before . "<a href='" . esc_url( $tag_link ) . "'" . $itemprop . ">" . $link_text_before . single_tag_title( '', false ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                // if paginated author archive
                } else if ( is_author() ) {

                    global $author;
                    
                    $userdata     = get_userdata( $author );
                    $author_posts = get_author_posts_url( $userdata->ID );
                    
                    echo $list_element_before . "<a href='" . esc_url( $author_posts ) . "'" . $itemprop . ">" . $link_text_before . esc_html( $userdata->display_name ) . $link_text_after . "</a>" . $list_element_after . $separator;
                    echo $list_element_before . $link_text_before . $current_before . __( ' Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;

                }

            } else {
                echo $list_element_before . $link_text_before . $current_before . __( 'Page ','trailblaze' ) . get_query_var( 'paged' ) . $current_after . $link_text_after . $list_element_after;
            }

        }

        echo "</nav>\n";

    }

}