<?php

session_start();


if( isset($_SESSION['role']) )
{	
	echo '<h1 class="ml11">
			<span class="text-wrapper">
				<span class="line line1"></span>
				<span class="letters">Dzień dobry</span>
			</span>
		  </h1>';
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>

<script>
var textWrapper = document.querySelector('.ml11 .letters');
textWrapper.innerHTML = textWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
	.add({
		targets: '.ml11 .line',
		scaleY: [0,1],
		opacity: [0.5,1],
		easing: "easeOutExpo",
		duration: 700
	})
	.add({
		targets: '.ml11 .line',
		translateX: [0, document.querySelector('.ml11 .letters').getBoundingClientRect().width + 10],
		easing: "easeOutExpo",
		duration: 700,
		delay: 100
	})
	.add({
		targets: '.ml11 .letter',
		opacity: [0,1],
		easing: "easeOutExpo",
		duration: 600,
		offset: '-=775',
		delay: (el, i) => 34 * (i+1)
	})
	.add({
		targets: '.ml11',
		opacity: 0,
		duration: 1000,
		easing: "easeOutExpo",
		delay: 1000
	});
</script>