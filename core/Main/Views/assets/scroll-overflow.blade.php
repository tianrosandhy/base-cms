@push ('style')
<style>
	.scroll-helper .left-scroll, .right-scroll{
		display:block;
		display:flex;
		display:-moz-flex; display:-webkit-flex; display:-ms-flexbox; 
		justify-content:center;
		align-items:center;
		color:#fff;
		font-size:40px;
		position:absolute;
		left:0;
		top:0;
		width:15%;
		height:100%;
		background:rgba(0,0,0,0.1);
		z-index: 2;
		visibility:hidden;
		opacity:0;
		transition:.3s ease;
		-moz-transition:.3s ease;
		-webkit-transition:.3s ease;
		-ms-transition:.3s ease;
	}
	
	.scroll-helper .right-scroll{
		left:auto;
		right:0;
	}

	.scroll-helper.active .left-scroll, .scroll-helper.active .right-scroll{
		visibility: visible;
		opacity:1;
	}

</style>
@endpush

@push('script')
<script>
$(function(){
	var xovpos = 0;
	var scrollDirection = null;
	var scrollThreshold = 0.15;
	var in_limit = false;

	var leftScrollLimit = $(".ov table").width() - $(".ov").width();
	var left_ax = $(".ov").offset().left;
	var left_bx = left_ax + ($(".ov").width() * scrollThreshold);
	var right_ax = left_ax + ($(".ov").width() * (1 - scrollThreshold));
	var right_bx = left_ax + $(".ov").width();

	$(window).on('resize', function(){
		leftScrollLimit = $(".ov table").width() - $(".ov").width();
		left_ax = $(".ov").offset().left;
		left_bx = left_ax + ($(".ov").width() * scrollThreshold);
		right_ax = left_ax + ($(".ov").width() * (1 - scrollThreshold));
		right_bx = left_ax + $(".ov").width();
	});


	$(".ov").on('mousemove', function(e){
		if($(window).width() < 767){
			return false;
		}
		//scroll to right condition
		if(e.clientX > right_ax && e.clientX < right_bx){
			if(typeof itv == 'undefined'){
				if(scrollDirection != 'right'){
					itv = setInterval(rightScroll, 200);
				}
				scrollDirection = 'right';
			}
			else{
				if(scrollDirection != 'right'){
					clearInterval(itv);
					itv = setInterval(rightScroll, 200);
					scrollDirection = 'right';
				}
			}
		}

		//scroll to left condition
		else if(e.clientX > left_ax && e.clientX < left_bx){
			if(typeof itv == 'undefined'){
				if(scrollDirection != 'left'){
					itv = setInterval(leftScroll, 200);
				}
				scrollDirection = 'left';
			}
			else{
				if(scrollDirection != 'left'){
					clearInterval(itv);
					itv = setInterval(leftScroll, 200);
					scrollDirection = 'left';
				}
			}
		}

		//no scroll position
		else{
			if(typeof itv != 'undefined'){
				clearInterval(itv);
				scrollDirection = null;
				if($(".ov .scroll-helper").hasClass('active')){
					$(".ov .scroll-helper").removeClass('active');
				}
			}
		}
	});

	$(".ov").on('mouseleave', function(){
		if($(window).width() < 767){
			return false;
		}
		
		if($(".ov .scroll-helper").hasClass('active')){
			$(".ov .scroll-helper").removeClass('active');
		}

		if(typeof itv != 'undefined'){
			clearInterval(itv);
			scrollDirection = null;
		}
	});


	function leftScroll(){
		xovpos -= 50;
		if(xovpos < 0){
			in_limit = true;
			xovpos = 0;
		}
		else{
			in_limit = false;
		}

		if(! $(".ov .scroll-helper").hasClass('active') && !in_limit){
			$(".ov .scroll-helper").addClass('active');
		}

		if(in_limit){
			$(".ov .scroll-helper").removeClass('active');
		}

		$(".ov").animate({
			scrollLeft : xovpos
		}, 200, 'linear');


	}

	function rightScroll(){
		leftScrollLimit = $(".ov table").width() - $(".ov").width();
		xovpos += 50;
		$(".ov").animate({
			scrollLeft : xovpos
		}, 200, 'linear');

		if(xovpos > leftScrollLimit){
			in_limit = true;
			xovpos = leftScrollLimit;
		}
		else{
			in_limit = false;
		}

		if(! $(".ov .scroll-helper").hasClass('active') && !in_limit){
			$(".ov .scroll-helper").addClass('active');
		}

		if(in_limit){
			$(".ov .scroll-helper").removeClass('active');
		}
	}


});
</script>
@endpush