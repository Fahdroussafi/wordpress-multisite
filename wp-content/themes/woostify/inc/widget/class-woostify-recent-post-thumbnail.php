<?php
/**
 * Widget Recent Post with Thumbnail
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woostify_Recent_Post_Thumbnail' ) ) {
	/**
	 * Widget Recent Post with Thumbnail class
	 */
	class Woostify_Recent_Post_Thumbnail extends WP_Widget {
		/**
		 * Setup
		 */
		public function __construct() {
			parent::__construct(
				'woostify_recent_post_with_thumbnail',
				__( 'Woostify Recent Post With Thumbnail', 'woostify' ),
				array(
					'classname'   => 'woostify_recent_post_with_thumbnail',
					'description' => __( 'List the most recent posts with post titles, thumbnail', 'woostify' ),
				)
			);
		}

		/**
		 * Form
		 *
		 * @param      array $instance The instance.
		 */
		public function form( $instance ) {
			$default = array(
				'title'  => __( 'Recent Posts', 'woostify' ),
				'number' => 3,
			);

			$instance = wp_parse_args( (array) $instance, $default );

			$title  = $instance['title'];
			$number = $instance['number'];
			?>

			<p>
				<label for='<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>'><?php esc_html_e( 'Title:', 'woostify' ); ?></label>
				<input
					class="widefat"
					type='text'
					value='<?php echo esc_attr( $title ); ?>'
					name='<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>'
					id='<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>' />
			</p>
			<p>
				<label for='<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>'><?php esc_html_e( 'Number:', 'woostify' ); ?></label>
				<input
					class="widefat"
					type="text"
					value='<?php echo esc_attr( $number ); ?>'
					name='<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>'
					id='<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>' />
			</p>

			<?php
		}


		/**
		 * View widget on front end
		 *
		 * @param      array $args      The arguments.
		 * @param      array $instance  The instance.
		 */
		public function widget( $args, $instance ) {
			$title  = ! empty( $instance['title'] ) ? $instance['title'] : 'Recent Posts';
			$title  = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$number = isset( $instance['number'] ) ? $instance['number'] : '3';

			if ( $number <= 0 ) {
				return;
			}

			echo wp_kses_post( $args['before_widget'] );

			if ( $title ) {
				echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
			}

			$query_args = array(
				'post_type'           => 'post',
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
				'posts_per_page'      => $number,
			);

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) :
					$query->the_post();
					?>

				<div class="widget_recent_post_thumbnail_item">
					<a href="<?php the_permalink(); ?>" class="recent-post-thumbnail-img">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail(
								'thumbnail',
								array( 'alt' => get_the_title() )
							);
						} else {
							echo '<img class="widget-post-thumbnail-default-img" alt="' . esc_attr( get_the_title() ) . '" src="' . esc_url( WOOSTIFY_THEME_URI . 'assets/images/thumbnail-default.jpg' ) . '">';
						}
						?>
					</a>

					<div class="recent-post-thumbnail-sum">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
				</div>

					<?php
				endwhile;
				wp_reset_postdata();
			endif;

			echo wp_kses_post( $args['after_widget'] );

		}

		/**
		 * Update content
		 *
		 * @param      array $new_instance  The new instance.
		 * @param      array $old_instance  The old instance.
		 *
		 * @return     array  New instance
		 */
		public function update( $new_instance, $old_instance ) {

			parent::update( $new_instance, $old_instance );

			$instance = $old_instance;

			$instance['title']  = wp_trip_all_tags( $new_instance['title'] );
			$instance['number'] = wp_trip_all_tags( $new_instance['number'] );

			return $instance;

		}
	}
}

return new Woostify_Recent_Post_Thumbnail();
