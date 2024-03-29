<?php
$component = empty( $component ) ? '' : $component;
if ( ! $component ) {
	return;
}

$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$dow_value   = empty( $dow_value ) ? false : $dow_value;
$is_member   = ! empty( $_view['is_member'] );
$disabled    = $is_member ? '' : 'disabled';
$monday      = strtotime( 'this Monday' );
$monthly     = ! empty( $monthly );
$days        = array(
	esc_html__( 'Sunday', 'smartcrawl-seo' ),
	esc_html__( 'Monday', 'smartcrawl-seo' ),
	esc_html__( 'Tuesday', 'smartcrawl-seo' ),
	esc_html__( 'Wednesday', 'smartcrawl-seo' ),
	esc_html__( 'Thursday', 'smartcrawl-seo' ),
	esc_html__( 'Friday', 'smartcrawl-seo' ),
	esc_html__( 'Saturday', 'smartcrawl-seo' ),
);
$dow_range   = $monthly ? range( 1, 28 ) : range( 0, 6 );

$select_id   = "wds-{$component}-dow" . ( $monthly ? '-monthly' : '' );
$select_name = "{$option_name}[{$component}-dow]";
?>

<label
	for="<?php echo esc_attr( $select_id ); ?>"
	class="sui-label"
>
	<?php
	$monthly
		? esc_html_e( 'Day of the month', 'smartcrawl-seo' )
		: esc_html_e( 'Day of the week', 'smartcrawl-seo' );
	?>
</label>

<select
	class="sui-select" <?php echo esc_attr( $disabled ); ?>
	id="<?php echo esc_attr( $select_id ); ?>"
	data-minimum-results-for-search="-1"
	name="<?php echo esc_attr( $select_name ); ?>"
>
	<?php foreach ( $dow_range as $dow ) : ?>
		<option value="<?php echo esc_attr( $dow ); ?>"
			<?php selected( $dow, $dow_value ); ?>>
			<?php
			if ( $monthly ) {
				echo esc_html( $dow );
			} else {
				$day_number = date( 'w', $monday + ( $dow * DAY_IN_SECONDS ) ); // phpcs:ignore
				echo esc_html( $days[ $day_number ] );
			}
			?>
		</option>
	<?php endforeach; ?>
</select>
