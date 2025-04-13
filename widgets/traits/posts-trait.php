<?php
namespace EnvisionBlocks\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait Posts_Trait {

	/**
	 * Render posts.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_posts( $settings, $query, $layout = 'grid', $is_ajax = false ) {
		$columns      = ( ! empty( $settings['post_columns_mobile'] ) ? 'envision-blocks-col-' . $settings['post_columns_mobile'] : '' ) . ( ! empty( $settings['post_columns_tablet'] ) ? ' envision-blocks-col-md-' . $settings['post_columns_tablet'] : '' ) . ( ! empty( $settings['post_columns'] ) ? ' envision-blocks-col-lg-' . $settings['post_columns'] : '' );
		$sticky_posts = get_option( 'sticky_posts' );
		$post_classes = array(
			'envision-blocks-entry',
		);
		$post_count   = 0;

		while ( $query->have_posts() ) :
			++$post_count;
			$query->the_post();

			if ( 1 === $post_count && 'yes' === $settings['featured_post'] && ! $is_ajax ) {
				$layout_classes = 'envision-blocks-masonry-item envision-blocks-posts--featured-post envision-blocks-col-lg-12' . $this->get_categories_classes();
			} else {
				$layout_classes = 'envision-blocks-masonry-item ' . $columns . $this->get_categories_classes();
			}

			if ( 'slider' === $layout ) :
				$layout_classes = 'swiper-slide envision-blocks-posts-slider-item';
			else :
				$layout_classes .= ' envision-blocks-post-default';
			endif; ?>

			<div class="<?php echo esc_attr( $layout_classes ); ?>">

				<?php
				if ( in_array( get_the_ID(), $sticky_posts ) ) {
					$post_classes[2] = 'sticky';
				} else {
					unset( $post_classes[2] );
				}
				?>

				<article <?php post_class( $post_classes ); ?> itemscope="itemscope" itemtype="https://schema.org/Article">
					<?php $this->render_image( $settings ); ?>

					<?php
						echo '<div class="envision-blocks-posts__body">';

							$this->render_categories( $settings );

							$this->render_title( $settings, $layout );

							$this->render_meta( $settings );

							$this->render_excerpt( $settings );

						echo '</div">';

					?>
				</article><!-- #post-## -->
			</div>

		<?php endwhile; ?>

		<?php
		wp_reset_postdata();
	}


	/**
	 * Render image.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_image( $settings ) {
		$image_size = $settings['image_size'];

		if ( 'yes' !== $settings['image_hide'] && has_post_thumbnail() ) :
			?>
			<div class="envision-blocks-entry__img-holder envision-blocks-posts__img-holder envision-blocks-hover-scale">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( $image_size, array( 'class' => 'envision-blocks-entry__img envision-blocks-posts__img' ) ); ?>
				</a>
			</div>
			<?php
		endif;
	}

	/**
	 * Render title.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_title( $settings, $layout = 'grid' ) {
		?>
		<<?php echo \EnvisionBlocks\Utils::validate_html_tag( $settings['title_tag'] ); ?> class="envision-blocks-entry__title envision-blocks-posts__title">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
		</<?php echo \EnvisionBlocks\Utils::validate_html_tag( $settings['title_tag'] ); ?>>
		<?php
	}


	/**
	 * Render categories.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_categories( $settings ) {
		$categories        = get_the_category();
		$separator         = ' ';
		$categories_output = '';
		$output            = '';

		if ( 'yes' === $settings['categories_hide'] ) {
			return;
		}

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			echo '<span class="envision-blocks-posts__meta-category">';
			foreach ( $categories as $index => $category ) :
				if ( $index > 0 ) :
					$categories_output .= $separator;
endif;
				$categories_output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
				endforeach;

			if ( 'post' == get_post_type() ) :
				$output .= $categories_output;
				endif;

				echo wp_kses_post( $output );

			echo '</span>';
		}
	}


	/**
	 * Render meta.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_meta( $settings ) {
		if ( 'yes' !== $settings['author_hide'] || 'yes' !== $settings['date_hide'] ) {
			?>

			<ul class="envision-blocks-posts__meta">

				<?php
				if ( 'yes' !== $settings['author_hide'] ) {
					echo '<li class="envision-blocks-posts__meta-author">';
					?>
						<a class="envision-blocks-posts__meta-author-url" rel="author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 24, null, null, array( 'class' => array( 'envision-blocks-posts__meta-author-img' ) ) ); ?>
							<span itemprop="author" itemscope itemtype="//schema.org/Person" class="envision-blocks-posts__meta-author-name">
								<?php echo esc_html( get_the_author() ); ?>
							</span>
						</a>
						<?php
						echo '</li>';
				}
				?>

				<?php
				if ( 'yes' !== $settings['date_hide'] ) {
					echo '<li class="envision-blocks-posts__meta-date">';
						echo get_the_date();
					echo '</li>';
				}
				?>
				
			</ul>

			<?php
		}
	}

	/**
	 * Render post excerpt.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_excerpt( $settings ) {
		if ( 'yes' !== $settings['excerpt_hide'] ) {
			?>
			<div class="envision-blocks-posts__excerpt">
				<?php
				if ( empty( $settings['excerpt_length'] ) ) {
					the_excerpt();
				} else {
					echo '<p>' . wp_trim_words( get_the_content(), $settings['excerpt_length'] ) . '</p>';
				}
				?>
			</div>
			<?php
		}
	}


	/**
	 * Get the list of categories classes of post.
	 */
	protected function get_categories_classes() {
		$terms   = get_the_terms( get_the_ID(), 'category' );
		$classes = '';

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$classes .= ' ' . $term->slug;
			}
		}

		return $classes;
	}
}