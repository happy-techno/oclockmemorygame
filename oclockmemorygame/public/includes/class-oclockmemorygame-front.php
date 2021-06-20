<?php

/**
 * La front HTML du jeu
 *
 * @link       https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * @since      1.0.0
 *
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/public/includes
 * @author     Yohann Joyeux <yohann.joyeux@gmail.com>
 */

class Oclock_Memory_Game {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public static function display_game() {
		//Display the HTML
		?>
		<script>
			$( document ).ready(function() {
			const game = new OclockMemoryGame();
			});
		</script> 
		<main class="game">
			<section class="game__cards js-cards" id="cardsection">			
			</section>        
		</main>
		<div class="progress_bar"><div></div></div>
		<div class="best_scores">
		<span>Meilleurs scores (secondes):</span>
		<ol id="scorelist"></ol>
		</div>

		<?php
	}

}