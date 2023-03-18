<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
	if (in_array($atts['wheel_hash'],$this->embeded_wheels)) return;
	$this->embeded_wheels[] = $atts['wheel_hash'];
?>

<div class="couponwheel_embed_<?php echo $atts['wheel_hash']; ?>"></div>

<style>
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> {
	position: relative;
	overflow: hidden;
	overflow-y: auto;
	border-radius: 6px;
}
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> .couponwheel_popup_close_container {
	display: none;
}
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> .couponwheel_popup_shadow,
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> .couponwheel_popup {
	position: relative;
	z-index: auto;
}
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> .couponwheel_popup {
	box-shadow: none;
}
.couponwheel_embed_<?php echo $atts['wheel_hash']; ?> .couponwheel_manual_open {
	display: none;
}
</style>

<script>
jQuery(document).ready(function(){
	couponwheel_manual_trigger('<?php echo $atts['wheel_hash']; ?>',true);
});

window.couponwheel_embed = true;
</script>