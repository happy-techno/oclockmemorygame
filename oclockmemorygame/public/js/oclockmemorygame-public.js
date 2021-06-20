/*
Cette Classe est l'objet principale du jeu
*/

class OclockMemoryGame {

	/*
	Le constructeur de la classe
	*/
	constructor() {
	//Nombre de figure de carte sur le plateau
	this.nbCards = 15; //18 Max  
        //Temps en seconde de la durée limite du jeu
	this.timeBarDuration = 10*60;  //secondes		      
        //Permet de tricher pour le debug
	this.cheatMode = false;
		
        //Enregistre le temps au début du jeu
	this.startTime = new Date();
        //Défini si le joueur peut continuer à) jour ou si la partie est terminé = gagnée ou perdue
	this.playing = true;
        //Création des cartes avec un nombre total = 2 x nbCards
	this.createCards();
        //Temps en ms pris pour l'animation des cartes
	this.duration = 250;    //ms
        //Paramètre pour recevoir l'id du timer et pouvoir l'arrêter
	this.clear_progress = 0;
        //Sélection des cartes du plateau
	this.cardsContainer = document.querySelector('.js-cards');
        //Création d'un variable Array pour manipuler les cartes
	this.cards = Array.from(this.cardsContainer.children);
	//Ajout de l'évènement click sur chaque carte et on fait correspondre la méthode flip
        this.cards.forEach(card => {
		card.addEventListener('click', this.flip.bind(this, card));
	});
        //affiche les top score
        this.getBestScores();
        //Mélange des cartes
	this.shuffleCards();
        //Début de la partie
	this.setTimerbar();

	}

    /*
    Cette méthode createCards créer les cartes sur le plateau de jeu
    Pour chaque figure de carte, on créer deux cartes retournées
    Si cheatMode = true alors on affiche le numéro de la carte au dos
    */
	createCards() {
		for (var i = 1; i <= this.nbCards; i++) {
			var cheatTxt = ''; if (this.cheatMode) cheatTxt = i;   //si on veut tricher
			
			//Création des deux cartes
			$("#cardsection").append('<div class="game__card js-card" data-fruit="card' + i + '"><div class="game__back-card">' + cheatTxt + '</div><div class="game__front-card"><div class="card' + i + '"></div></div></div>');
			$("#cardsection").append('<div class="game__card js-card" data-fruit="card' + i + '"><div class="game__back-card">' + cheatTxt + '</div><div class="game__front-card"><div class="card' + i + '"></div></div></div>');
		}
	}

    /*
    Cette méthode winningMessage est utilisée quand la partie est gagnée, cad tte les cartes sont retournées avant la fin du temps = timeBarDuration secondes
    - enregistre le nouveau score en base : setBestScore
    - affiche un message à l'utilisateur
    */
	winningMessage() {
		this.setBestScore();
		
        	alert('Vous avez gagnééééééé!');
	}

    /*
    Cette méthode losingMessage est utilisée quand la partie est perdue, cad tte les cartes ne sont pas retournées avant la fin du temps = timeBarDuration secondes
    - arrête la partie : stopTimerbar
    - affiche un message à l'utilisateur
    */    
	losingMessage() {
		this.stopTimerbar();
		
        	alert('Vous avez perduuuuuuu!');
	}

    /*
    Cette méthode stopTimerbar est utilisée pour arrêter la partie
    - on remet la progresse bar à zéro
    - on stop le timer de la partie : clearTimeout
    - on set la variable playing à false pour empêcher l'utilisateur de continuer à retourner des cartes
    */     
	stopTimerbar() {
		$(".progress_bar div").css({
			"width": "0px",
			"-webkit-transition": ""
		});
		
        clearTimeout(this.clear_progress);

		this.playing = false;
	}

    /*
    Cette méthode setTimerbar est utilisée pour lancer la partie
    - on démarre la progression de la bar en timeBarDuration secondes
    - on démarre un timer qui s'il n'est pas arrêté avant le temps de timeBarDuration secondes, appelera la méthode losingMessage
    */     
	setTimerbar() {
		$(".progress_bar div").css({
			"width": "100%",
			"-webkit-transition": this.timeBarDuration + "s linear"
		});

		this.clear_progress = setTimeout(() => { 
			this.losingMessage();
		}, this.timeBarDuration * 1000)
	}

    /*
    Cette méthode shuffleCards est utilisée pour mélanger les cartes du plateau
    - on retourne toutes les cartes face visible en supprimant le style has-match
    - on mélange les cartes aléatoirement avec order
    */     
	shuffleCards() {
		this.cards.forEach(card => {
			const randomNumber = Math.floor(Math.random() * this.cards.length) + 1;
			card.classList.remove('has-match');
            		card.style.order = `${randomNumber}`;
		});
	}

    /*
    Cette méthode checkAllCards est utilisée pour vérifier les cartes du plateau
    - si toutes les cartes sont retournées (contient le style has-match) alors c'est que la partie est gagnée et alors : 
        - on stop la partie
        - on appelle la methode winningMessage
    */     
	checkAllCards() {
		if (!this.cards.every(card => card.classList.contains('has-match'))) return;

        	//stop la partie
        	this.stopTimerbar();

        	//on utilise un timer pour laisser le temps de retourner la carte avant d'afficher le message à l'utilisateur
		setTimeout(() => {
			this.winningMessage();
		}, this.duration);
	}


    /*
    Cette méthode checkIfMatch est utilisée pour vérifier si les deux cartes retournées matches sur le même fruit/images
    Elle prend en agrument les deux cartes retournées avec le style flipped.
    Si les deux cartes match, alors :
        - on supprime le style flipped et on ajoute celui has-match
        - on vérifie si l'utilisateur a gagné la partie avec la méthode checkAllCards
    Si les cartes ne match, alors :
        - on supprime le style flipped

    */    
	checkIfMatch([firstCard, secondCard]) {
		if (firstCard.dataset.fruit === secondCard.dataset.fruit) {
			firstCard.classList.remove('flipped');
			secondCard.classList.remove('flipped');
            
            		//les deux cartes match et resteront retournées
			firstCard.classList.add('has-match');
			secondCard.classList.add('has-match');

			this.checkAllCards();
		} else {
			//on utilise un timer pour laisser le temps de retourner la carte, l'utilisateur peut voir que c'est KO avant de remettre les cartes face cachée
            		setTimeout(() => {
				firstCard.classList.remove('flipped');
				secondCard.classList.remove('flipped');
			}, this.duration*2);
		}
	}

    /*
    Cette méthode flip est appellée quand l'utilisateur click sur une carte et veut la retournée
    Chaque carte bénéficie de cet évènement grâce à la fonction addEventListener utilisée dans le constructeur de la classe du jeu
    Après un click :
    - on ajoute le style flipped pour retourner la carte
    - on check si il existe deux cartes retournées (style = flipped) sur le plateau : cards
        - si deux cartes sont retournées alors on appalle la méthode pour vérifier si elles match : checkIfMatch
    */     
	flip(selectedCard) {
		//Si le jeu est terminé, les cartes ne sont plus retournables
        	if (!this.playing) return;
		
		//retourne la carte
        	selectedCard.classList.add('flipped');

        	//deux cartes retournées ?
		const flippedCards = this.cards.filter(card => card.classList.contains('flipped'));
		if (flippedCards.length === 2) {
			this.checkIfMatch(flippedCards);
		}
	}

    /*
    Cette méthode getBestScores est utilisée pour récupérer et afficher les derniers top scores
    */    
	getBestScores() {
		//Récupération de la liste des scores depuis la base en Ajax
		var url = ajax_get_scores; //.../wp-json/omg_ws/v1/getscores

		$.ajax({
			url: url,
			type: "GET",			  
		}).done(function(response) {
			//On ajoute le score dan la liste dynamiquement avec append au niveau de l'élement #scorelist
			$.each(response, function(i, obj) {	
				$("#scorelist").append('<li>'+obj.score+'</li>');
			});	

			if(response.length==0)$("#scorelist").append('<li>Aucun score pour le moment.</li>');
			
		}).fail(function (jqXHR, textStatus, errorThrown) { 
			console.warn(jqXHR);
			console.warn(textStatus);
		});
		

	}

    /*
    Cette méthode setBestScore est utilisée pour enregistrer le temps en base si la partie est gagnée
    */     
	setBestScore() {
		var now = new Date();
		var timeDiff = now.getTime() - this.startTime.getTime();
		timeDiff = Math.round(timeDiff / 1000);
		console.log('timeDiff/score=' + timeDiff);

		//Si la partie est gagnée on ajoute le score en base en Ajax
		var url = ajax_set_score + '?score=' + timeDiff; //.../wp-json/omg_ws/v1/ajax_set_score?score=10
		
		$.ajax({
			url: url,
			type: "GET",			  
		}).done(function(response) {
			//Rien a faire en particulier			
		}).fail(function (jqXHR, textStatus, errorThrown) { 
			console.warn(jqXHR);
			console.warn(textStatus);
		});

	}

}
