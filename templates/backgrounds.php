<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php include('gradients.php') ?>
<style>
#couponwheel_popup_backgrounds_container div {
	box-sizing: border-box;
	cursor: pointer;
	height: 10em;
	padding-top: 3.2em;
	width: 50%;
	display: inline-block;
	vertical-align: top;
	position: relative;
	border-right: 1px solid white;
	border-bottom: 1px solid white;
}
#couponwheel_popup_backgrounds_container div span {
	background-color: rgba(0,0,0,.4);
	color: white;
	padding: .8em 0;
	line-height: 1;
	font-size: .9em;
	letter-spacing: .025em;
	font-weight: bold;
	text-transform: uppercase;
	position: absolute;
	bottom: 0;
	width: 100%;
	display: block;
	text-align: center;
}
</style>
<h1>Select popup background color</h1>
<h2>Custom solid color</h2>
<p><input id="couponwheel_popup_background_css" name="popup_background_css" value="<?php echo $wheel->popup_background_css ?>"></p>
<a class="tb_remove_button button">Close</a>
<h2>Gradients</h2>
<?php include('gradients.php') ?>
<div id="couponwheel_popup_backgrounds_container"><!--
---><?php foreach($popup_background_gradients as $gradient) { ?><!--
---><div data-css="<?php echo $gradient->css ?>" data-name="<?php echo $gradient->name ?>" style="background: <?php echo $gradient->css ?>"><span><?php echo $gradient->name ?></span></div><!--
---><?php } ?><!--
---></div>
<script>
	
window.addEventListener('load',function(){
	
	jQuery('.tb_remove_button').click(function(){
		tb_remove();
	});
	
	var bkgint;
	
	jQuery('#couponwheel_popup_background_css').wpColorPicker({
		change: function() {
			clearInterval(bkgint);
			bkgint = setTimeout(function(){
			var e = jQuery('#couponwheel_popup_background_css');
			couponwheel_set_popup_background(e.val(),e.val());},50);
		},
		clear: function() {
			setTimeout(function(){
			var e = jQuery('#couponwheel_popup_background_css');
			couponwheel_set_popup_background('#d3d3d3','#d3d3d3');},1);
		}
	});
	
	jQuery('#couponwheel_popup_backgrounds_container div').click(function(event){
		couponwheel_set_popup_background(jQuery(event.target).attr('data-name'),jQuery(event.target).attr('data-css'));
		tb_remove();
	});
	
});

</script>
