#Intelligence Artificielle
Ce défi consiste à développer une intelligence artificielle... au morpion

On fera s'affronter vos IA lors d'un tournoi.

Vous devez vous débrouiller pour que votre application soit accessible en http
ou https (pour ceux qui n'auraient nulle part où déposer leur script sur le
web, je prêterai un morceau d'un serveur dédié... bref on s'arrangera).

## Fonctionnement des duels
Une première version du code source du programme arbitre est déposée dans le
dossier du challenge (rien de secret).

Les IA ne communiquent pas directement entre elles. C'est un programme
"arbitre" qui fera des requêtes http(s) vers vos scripts.

Il interrogera chaucun leur tour les deux combattants en faisant une requête
http(s) avec quelques paramètres GET


## Spécifications GET et retours
Votre programme n'a pas à gérer une partie entière de morpion, juste un tour.

Le programme arbitre fait une requête construite de la manière suivante:

```
https://votreUrl/?you=O&0-0=&0-1=O&0-2=X&1-0=X&1-1=X&1-2=O&2-0=O&2-1=&2-2=X
```
Le paramètre GET you vous indique quel est votre symbole dans la grille de
morpion. classiquement "X" ou "O"

Pour chaque case de la grille de morpion, un paramètre GET correspondant à ses
coordonnées vous est indiqué. Il peut être : 

* votre symbole (ce qui signifie que vous avez déjà joué là)
* le symbole de votre adversaire. Il a déjà joué dans cette case
* rien, la case est disponible.

Votre programme choisit la case sur laquelle il souhaite jouer compte tenu de
la grille et retourne ses coordonnées.

Le résultat de la requête http(s) ne peut qu'être: "0-0" ou "0-1" ou "0-2" ou
"1-0" etc.

Un joueur (une IA) qui répond autre chose (au caractère près) ou qui répond
avec les coordonnées d'une case déjà jouée perd la partie.

Si les paramètres GET envoyés par le programme arbitre sont incohérents, votre
IA peut insulter l'arbitre comme valeur de retour.

##Tester votre programme
Vous pouvez tester le programme arbitre à l'adresse
http://morpionMaster.tinad.fr (il détaille le résultat de chaque partie et
permet un débug de vos retours).

Vous selectionnez les bots qui doivent s'affronter et cliquez sur fight.

Pour que votre IA soit ajoutée dans les listes, suivez les instructions données sur la page sus-nommée

Les deux scripts PHP (IA débile et l'arbitre index.php) sont dans le dossier Master.

##Gagnants
L'affrontement aura lieu lundi 16 novembre 2016.

Je modifierai le programme arbitre pour qu'il génère des parties entre deux
adversaires

* Jusqu'à ce qu'un des deux adversaires ait 3 manches gagnées de plus que
  l'autre.
* Au bout de 200 parties si l'écart au score est inférieur à 3, les candidats
  sont déclarés ex-aequo.
* Le gagnant du concours de rapidité au challenge de programmation a un
  avantage d'une partie automatiquement gagnée en début de script.
* En cas d'ex-aequo... on verra, à coup de shifumi.

