<div class="quick_banner">
	<div class="floating_menu">
		<input type="checkbox">
		<span class="floating_plus">
	    <i class="fa-solid fa-plus"></i>
		</span>
		<ul class="floating_nav">
			<li>
				<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=qna"><i class="fa-solid fa-headset"></i></a>
			</li>
			<li>
				<a href=""><i class="fa-solid fa-location-dot"></i></a>
			</li>
			<li>
				<a href=""><i class="xi-kakaotalk"></i></a>
			</li>
	    <li>
				<a href=""><i class="fa-solid fa-phone"></i></a>
			</li>
		</ul>
	</div>

	<button type="button" id="top_btn">
		<span>TOP</span>
	</button>
</div>

<script>
$(function() {
		$("#top_btn").on("click", function() {
				$("html, body").animate({scrollTop:0}, '500');
				return false;
		});
});
</script>
