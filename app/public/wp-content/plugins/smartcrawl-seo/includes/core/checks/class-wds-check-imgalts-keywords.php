<?php

class Smartcrawl_Check_Imgalts_Keywords extends Smartcrawl_Check_Abstract {

	/**
	 * State of the check.
	 *
	 * @var $state
	 */
	private $state;

	/**
	 * Images count.
	 *
	 * @var int $image_count
	 */
	private $image_count = 0;

	/**
	 * Count of images with focus keywords in alt.
	 *
	 * @var int $images_with_focus_count
	 */
	private $images_with_focus_count = 0;

	/**
	 * Get status text.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		$image_count = $this->image_count ? $this->image_count : 0;

		if ( $this->state ) {
			$message = esc_html__( 'A good balance of images contain the focus keyword(s) in their alt attribute text', 'smartcrawl-seo' );
		} elseif ( 0 === $image_count && ! $this->has_featured_image() ) {
			$message = esc_html__( "You haven't added any images", 'smartcrawl-seo' );
		} else {
			$percentage = $this->get_percentage();
			if ( $percentage > 75 ) {
				$message = esc_html__( 'Too many of your image alt texts contain the focus keyword(s)', 'smartcrawl-seo' );
			} elseif ( 0 === $percentage ) {
				$message = esc_html__( 'None of your image alt texts contain the focus keyword(s)', 'smartcrawl-seo' );
			} else {
				$message = esc_html__( 'Too few of your image alt texts contain the focus keyword(s)', 'smartcrawl-seo' );
			}
		}

		return $message;
	}

	/**
	 * Apply the check to subject.
	 *
	 * @return bool
	 */
	public function apply() {
		$subjects          = Smartcrawl_Html::find( 'img', $this->get_markup() );
		$this->image_count = count( $subjects );
		if ( empty( $subjects ) ) {
			return false;
		}

		foreach ( $subjects as $subject ) {
			$alt = $subject->getAttribute( 'alt' );

			$this->images_with_focus_count += (int) $this->has_focus( $alt );
		}

		$this->state = $this->is_check_successful();

		return ! ! $this->state;
	}

	/**
	 * Check if check is successful.
	 *
	 * @return bool
	 */
	private function is_check_successful() {
		if ( $this->image_count < 5 ) {
			return (bool) $this->images_with_focus_count;
		} else {
			$percentage = $this->get_percentage();

			return $percentage >= 30 && $percentage <= 75;
		}
	}

	/**
	 * Check if current post has a featured image.
	 *
	 * @since 3.4.0
	 *
	 * @return boolean
	 */
	private function has_featured_image() {
		$post_id = $this->get_post_id();
		// Get parent ID if post revision.
		$post_parent = wp_is_post_revision( $post_id );
		// If it's a revision use parent post ID.
		if ( $post_parent ) {
			$post_id = $post_parent;
		}

		return has_post_thumbnail( $post_id );
	}

	/**
	 * Get percentage of images with focus keywords.
	 *
	 * @return float|int
	 */
	private function get_percentage() {
		$image_count = $this->image_count;
		if ( ! $image_count ) {
			return 0;
		}

		$images_with_focus = $this->images_with_focus_count;

		return $images_with_focus / $image_count * 100;
	}

	/**
	 * Get recommendation text.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		$image_count             = $this->image_count ? $this->image_count : 0;
		$images_with_focus_count = $this->images_with_focus_count ? $this->images_with_focus_count : 0;

		if ( $this->state ) {
			$message = esc_html__( "Alternative attribute text for images help search engines correctly index images and aid visually impaired readers. The text is also used in place of the image if it's unable to load. You should add alternative text for all images in your content.", 'smartcrawl-seo' );
		} elseif ( 0 === $image_count ) {
			$message = esc_html__( 'Images are a great addition to any piece of content and it’s highly recommended to have imagery on your pages. Consider adding a few images that relate to your body content to enhance the reading experience of your article. Where possible, it’s also a great opportunity to include your focus keyword(s) to further associate the article with the topic you’re writing about.', 'smartcrawl-seo' );
		} else {
			$percentage = $this->get_percentage();
			if ( $percentage > 75 ) {
				$message = sprintf(
				// translators: %d images with focus count, %d image count.
					esc_html__( '%1$d/%2$d images on this page have alt text with your keyword(s) which is too much. Whilst it’s great that you have image alternative text with your focus keyword(s), you can also get penalized for having too many keywords on a page. Try to include your keyword(s) in image alt texts only when it makes sense.', 'smartcrawl-seo' ),
					$images_with_focus_count,
					$image_count
				);
			} elseif ( 0 === $percentage ) {
				$message = esc_html__( 'None of the images on this page have alt text containing your focus keyword. It’s recommended practice to have your topic keywords in a few of your images to further associate the article with the topic you’re writing about. Add your keyword to one or more of your images, but be careful not to overdo it.', 'smartcrawl-seo' );
			} else {
				$message = sprintf(
				// translators: %d images with focus count, %d image count.
					esc_html__( '%1$d/%2$d images on this page have alt text with your chosen keyword(s). Alternative attribute text for images helps search engines correctly index images and aid visually impaired readers. It’s recommended practice to have your topic keywords in a good number of your images to further associate the article with the topic you’re writing about. Add your keyword(s) to a few more of your images, but be careful not to overdo it.', 'smartcrawl-seo' ),
					$images_with_focus_count,
					$image_count
				);
			}
		}

		return $message;
	}

	/**
	 * Get more info text.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return esc_html__( "Image alternative text attributes help search engines correctly index images, aid visually impaired readers, and the text is used in place of the image if it's unable to load. You should add alternative text for all images in your content.", 'smartcrawl-seo' );
	}
}
