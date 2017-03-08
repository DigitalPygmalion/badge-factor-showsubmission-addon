### An example of a template for single submission.
### BadgeOS code is under Affero GPL and Engage theme PHP code is under GPL.  I'm assuming AGPL supercedes.
<?php get_header(); ?>

	<div id="content">
	
		<div class="wrap clf">
			
			<div id="posts">
		
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<!--Single Post-->
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-img">
						<?php if (has_post_thumbnail()) {
							the_post_thumbnail('large');
						} ?>
					</div>
					<h2 class="post-title"><?php the_title(); ?></h2>
					<div class="post-meta">
						<p><?php the_time('j F Y') ?> 
					</div>
					<div class="post-content">
						<!-- BEGIN single-submission body -->
						<?php 
							$achievement_id = get_post_meta( get_the_ID(), '_badgeos_submission_achievement_id', true );
							
							if ( is_numeric( $achievement_id ) )
								$achievement = get_post( $achievement_id );
							
							
							/*
							 * BEGIN paste from content-filters badgeos_render_achievement function
							 */								
							// Each Achievement
							$output = '';
							$output .= '<div id="badgeos-achievements-list-item-' . $achievement->ID . '" class="badgeos-achievements-list-item '. $earned_status . $credly_class .'"'. $credly_ID .'>';
						
								// Achievement Image
								$output .= '<div class="badgeos-item-image">';
								$output .= '<a href="' . get_permalink( $achievement->ID ) . '">' . badgeos_get_achievement_post_thumbnail( $achievement->ID ) . '</a>';
								$output .= '</div><!-- .badgeos-item-image -->';
						
								// Achievement Content
								$output .= '<div class="badgeos-item-description">';
						
									// Achievement Title
									$output .= '<h2 class="badgeos-item-title"><a href="' . get_permalink( $achievement->ID ) . '">' . get_the_title( $achievement->ID ) .'</a></h2>';
						
									// Achievement Short Description
									$output .= '<div class="badgeos-item-excerpt">';
									$output .= badgeos_achievement_points_markup( $achievement->ID );
									$excerpt = !empty( $achievement->post_excerpt ) ? $achievement->post_excerpt : $achievement->post_content;
									$output .= wpautop( apply_filters( 'get_the_excerpt', $excerpt ) );
									$output .= '</div><!-- .badgeos-item-excerpt -->';
						
									// Render our Steps
									if ( $steps = badgeos_get_required_achievements_for_achievement( $achievement->ID ) ) {
										$output.='<div class="badgeos-item-attached">';
											$output.='<div id="show-more-'.$achievement->ID.'" class="badgeos-open-close-switch"><a class="show-hide-open" data-badgeid="'. $achievement->ID .'" data-action="open" href="#">' . __( 'Show Details', 'badgeos' ) . '</a></div>';
											$output.='<div id="badgeos_toggle_more_window_'.$achievement->ID.'" class="badgeos-extras-window">'. badgeos_get_required_achievements_for_achievement_list_markup( $steps, $achievement->ID ) .'</div><!-- .badgeos-extras-window -->';
										$output.= '</div><!-- .badgeos-item-attached -->';
									}
						
								$output .= '</div><!-- .badgeos-item-description -->';
						
							$output .= '</div><!-- .badgeos-achievements-list-item -->';
							/*
							 * END 5paste from content-filters badgeos_render_achievement function
							 */						
							
							// Return our filterable markup
							echo $output;
							
							
							echo badgeos_get_submission_attachments(get_the_ID());
								
							
	
							##I don't thik we need this -- jlam@credil.org
							##the_content(__('Continue Reading', 'layeru')); 
						?>
						
						<!-- END single-submission body  -->
					</div>
				</div>
								
				<?php endwhile; else: ?>
				
				<!--404 No Post-->			
				<div id="404-error" class="post">
					<h2 class="post-title"><?php _e('Oops, well this is embarrassing.', 'layeru'); ?></h2>
					<div class="post-content">
						<p><?php _e('The page you were looking for could not be found. Please use the search form below to search our site, or visit our homepage.', 'layeru'); ?></p>
						<?php get_search_form(); ?>
					</div>		    
				</div>
				
				<?php endif; ?>
									
				<?php #comments_template(); ?>
										
			</div>
		
			<?php get_sidebar(); ?>
		
		</div>
	
	</div>
			
<?php get_footer(); ?>
