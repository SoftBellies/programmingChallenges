Générateur de grilles de Shikaku
================================

Introduction
------------

Nous sommes en 2025. La directrice de la Clinique du Séquoia, Aimée Surprise,
doit faire face à l’ennui de ses seniors. Les abonnements aux différentes revues
de jeux d’esprit plombent le budget de la clinique et ne parviennent pas à
satisfaire une demande sans cesse croissante.

Aimée Surprise a donc décidé de faire produire en masse des grilles de Shikaku.

Le Shikaku
----------

Le Shikaku est un casse-tête logique venant du Japon. Il se joue sur une grille
de W×H cases.

Le but est de réaliser un générateur de grilles de Shikaku.

Format du fichier produit
-------------------------

Le générateur produit un fichier dont la structure est la suivante :

- première ligne : définition de l’aire de jeu

  - 'T' : magic char
  - W : largeur de l’aire de jeu
  - H : hauteur de l’aire de jeu

- lignes suivantes : une ligne par aire

  - S : superficie du bloc ( 2 <= S < W×H )
  - X : abscisse du bloc ( 0 <= X < W )
  - Y : ordonnée du bloc ( 0 <= Y < H )

Notes :

- La grille est divisée en plusieurs rectangles
- la grille doit être entièrement recouverte par les rectangles et deux
  rectangles ne peuvent pas se chevaucher (Somme des S = W×H)
- des nombres apparaissent sur la grille : chaque rectangle doit en contenir
  un et un seul qui indique l’aire du rectangle qui le contient
- 2 < W < 200, 2 < H < 200
- 2 <= Nombre de blocs <= W×H/3

Voici un exemple de fichier :

    T 8 4    -> une grille de 8×4
    6 7 0    -> un bloc de 6 aux coordonnées (7,0)
    6 5 3    -> un bloc de 6 aux coordonnées (5,3)
    12 2 1   -> un bloc de 12 aux coordonnées (2,1)
    4 4 1    -> un bloc de 4 aux coordonnées (4,1)
    4 3 3    -> un bloc de 4 aux coordonnées (3,3)

Cet exemple décrit la grille suivante :

    +---+---+---+---+---+---+---+---+
    |                            6  |
    +   +   +   +   +   +   +   +   +
    |        12      4              |
    +   +   +   +   +   +   +   +   +
    |                               |
    +   +   +   +   +   +   +   +   +
    |            4       6          |
    +---+---+---+---+---+---+---+---+

La solution de cette grille est la suivante :

    +---+---+---+---+---+---+---+---+
    |               |   |        6  |
    +   +   +   +   +   +   +   +   +
    |        12     |4  |           |
    +   +   +   +   +   +---+---+---+ -.
    |               |   |           |  |
    +---+---+---+---+   +   +   +   +  |
    |            4  |   |6          |  |
    +---+---+---+---+---+---+---+---+  +-- bloc
                        .              |
                        `--------------+

Challenge
---------

Écrire un programme générant des grilles de Shikaku en laissant l’utilisateur
spécifier :

- la largeur de la grille voulue
- la hauteur de la grille voulue
- le nombre de blocs à créer sur la grille

Exemple d’appel du programme pour générer une grille de 8×4 contenant 5 blocs :

    shikakugen 8 4 5

Bonus
-----

Le premier challenge correspond au niveau 1.

### Niveau 2 ###

Écrire une version capable d’afficher une version humaine de la grille et du
problème comme ci-dessous:

    +---+---+---+---+---+
    |           |       |
    +---+---+---+---+---+
    |   |       |       |
    +   +   +   +---+---+
    |   |       |       |
    +---+---+---+---+---+
    |       |       |   |
    +   +   +   +   +   +
    |       |       |   |
    +---+---+---+---+---+

    +---+---+---+---+---+
    |    3           2  |
    +   +   +   +   +   +
    |2       4   2      |
    +   +   +   +   +   +
    |            2      |
    +   +   +   +   +   +
    |        4       2  |
    +   +   +   +   +   +
    |    4              |
    +---+---+---+---+---+

### Niveau 3 ###

Écrire un solveur de grille de Shikaku dont l’entrée est le fichier

### Niveau 4 ###

Écrire un générateur de grilles qui ne soient pas uniquement des sous-divisions
de rectangles.

Exemple d’une telle grille :

    +---+---+---+---+
    |           |   |
    +---+---+---+   +
    |   |       |   |
    +   +   +   +   +
    |   |       |   |
    +   +---+---+---+
    |   |           |
    +---+---+---+---+

Note : cela n’est possible qu’à partir de 5 blocs !
