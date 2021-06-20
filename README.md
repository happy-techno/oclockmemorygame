# oclockmemorygame

== installation ==

Depuis wordpress console d'admin :
- Installer le plugin 
- Activer le plugin 
- créer une page et insérer le shortcode [omg]

Vous pouvez jouer depuis la page.


== Configuration ==

Vous pouvez changer les paramètres de jeux depuis le fichier javascript :
https://github.com/happy-techno/oclockmemorygame/blob/main/oclockmemorygame/public/js/oclockmemorygame-public.js

#Nombre de figure de carte sur le plateau 18 Max

this.nbCards = 15;  
  
#Temps en seconde de la durée limite du jeu

this.timeBarDuration = 10*60;	      
  
#Permet de tricher pour le debug

this.cheatMode = false;

