Wookie à poil dur selon Zigazou
===============================

Voici la solution de Zigazou au programming challenge du Wookie à poil dur.

Compilation
-----------

    ghc -O2 wookie.hs

Utilisation
-----------

    ./wookie sequence.txt

Algorithme
----------

L’algorithme suivi est naïf mais fonctionne bien sur nos machines mordernes.

Le principe consiste à partir de chaque Reads constituant la séquence finale et
de les monter comme un puzzle en regardant les pièces qui peuvent s’enficher sur
la dernière pièce.

Partons d’un exemple basique :

    abcd
    efgh
    cdef
    ghij

La chaîne à reconstituer est donc `abcdefghij`
    
Pour conserver l’état en cours de chacune des recherches, on travaille sur des
couples (séquence en cours, pièces restantes).

On partira avec les valeurs initiales suivantes :

    [ ( [abcd], [efgh, cdef, ghij] )
    , ( [efgh], [abcd, cdef, ghij] )
    , ( [cdef], [abcd, efgh, ghij] )
    , ( [ghij], [abcd, efgh, cdef] )
    ]
        
Ce travail est effectué par la fonction `initSequences`.

Pour chacun de ces couples, on va tester chacune des pièces restantes :

    ( [abcd], [efgh, cdef, ghij] )
    
donne donc
    
    [ ( [abcd, efgh], [cdef, ghij] )
    , ( [abcd, cdef], [efgh, ghij] )
    , ( [abcd, ghij], [efgh, cdef] )
    ]

Sur la liste obtenue, on va virer les séquences invalides :

    [ ( [abcd, efgh], [cdef, ghij] ) -- invalide, abcd et efgh ne coïncident pas
    , ( [abcd, cdef], [efgh, ghij] ) -- valide !
    , ( [abcd, ghij], [efgh, cdef] ) -- invalide, abcd et ghij ne coïncident pas
    ]

et nous donne

    [ ( [abcd, cdef], [efgh, ghij] ) ]

Quand on applique cela à toutes les séquences initiales, on obtient les listes :

    [ ( [abcd, cdef], [efgh, ghij] )
    , ( [efgh, ghij], [abcd, cdef] )
    , ( [cdef, efgh], [abcd, ghij] )
    ]

Note : les 4 couples n’ont donné naissance qu’à 3 couples car la séquence finale
ne peut pas commencer par ghij (il n’y a aucun morceau qui permet de prolonger).

À ce stade, on repère s’il existe une séquence complète (une séquence n’ayant
plus de morceaux restants). Si ce n’est pas le cas, on répète cette étape
jusqu’à trouver la séquence complète.

Considérations
--------------

`wookie.hs` ne prend en compte que les morceaux se recouvrant avec au moins 3
bases. Par exemple `abcdef` et `defghi` coïncident mais pas `abcdef` et `efghij`.
Ce comportement peut être changé en modifiant `minbase`.

L’algorithme fonctionne uniquement s’il existe au moins une solution utilisant
tous les morceaux.