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

Optimisation
------------

Quelques optimisations ont été appliquées sur le code de `wookie.hs`.

### ByteString ###

Tout d’abord, des `ByteString` sont utilisées. Elles permettent de recourir à
des fonctions de complexité O(1) pour la plupart.

### Liste inversée ###

La structure contenant les couples `(pièces utilisées, pièces restantes)`
utilise une `List` inversée pour les pièces utilisées. C’est-à-dire que la
suite `[abcd, cdef, efgh]` est stockée sous la forme `[efgh, cdef, abcd]`. Cela
permet de lire le dernier élément de la liste ou d’ajouter un nouvel élément
en O(1).

### Pièces restantes ###

La structure contenant les couples `(pièces utilisées, pièces restantes)`
utilise un `Set` pour les pièces restantes. Cela permet de supprimer des
pièces en O(log n).

### Coincide ###

La fonction la plus utilisée et la plus consommatrice de `wookie.hs` est la
fonction `coincide` :

    COST CENTRE MODULE  %time %alloc

    coincide    Main     89.3   92.8
    next        Main      5.9    6.5
    -+-         Main      4.3    0.5

                                                                    individual     inherited
    COST CENTRE               MODULE                  no.     entries  %time %alloc   %time %alloc

    MAIN                      MAIN                     49           0    0.0    0.0   100.0  100.0
     main                     Main                     99           0    0.0    0.0   100.0  100.0
      main.solutions          Main                    106           1    0.0    0.0   100.0  100.0
       loopUntilComplete      Main                    107          47    0.2    0.0   100.0  100.0
        complete              Main                    116           0    0.1    0.0     0.1    0.0
         remains              Main                    117      376104    0.0    0.0     0.0    0.0
        loopUntilComplete.ss' Main                    108          47    0.0    0.0    99.7  100.0
         nexts                Main                    110           0    0.2    0.1    99.7  100.0
          next                Main                    111      376150    5.9    6.5    99.5   99.9
           -+-                Main                    112     9764492    4.3    0.5    93.6   93.3
            coincide          Main                    113   276308151   89.3   92.8    89.3   92.8
      main.starts             Main                    104           1    0.0    0.0     0.0    0.0
       initSequences          Main                    105           1    0.0    0.0     0.0    0.0
      main.rs                 Main                    103           1    0.0    0.0     0.0    0.0
      assemble                Main                    101           0    0.0    0.0     0.0    0.0

Elle serait donc la fonction à optimiser pour encore améliorer la vitesse :o)
