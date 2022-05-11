<?php
/**
 * 404 template
 *
 * @package woostify
 */

$options = woostify_options( false );
?>

<div class="error-404-text has-woostify-heading-color text-center">
	<?php echo wp_kses_post( $options['error_404_text'] ); ?>
</div>
