#!/usr/bin/env python

import sys, math

#tronc : n*n '|' centres sous les cones
#arbre : n cones, chacun de i + 3 lignes, chaque ligne fait 2 '*' de plus que la precedente
#chaque ligne 1 du cone i contient 2*floor(i/2) '*' de moins que la precedente
#cone 1 : la ligne 1 a 1 '*' et ainsi de suite
#ligne i + 3 du cone i : f(i)
#ligne 1 du cone i : f(i - 1) - 2*floor(i/2)
#ligne i + 3 du cone i : f(i - 1) - 2*floor(i/2) + 2(i + 2)
#f(1) = 7, f(n) ?

n = int(sys.argv[1])
if n == 0:
	print ''
	sys.exit(0)
decal=6
width = 7
widths = [3]
widths.append(width)
countLigne=0;
for i in range(2, n + 1):
	width = int(width - 2*(i/2) + 2*(i + 2))
	widths.append(width)

#boucle par blocs de lignes
for i in range(1, n + 1):
	stars = widths[i] - 2*(i + 2)
	spaces = widths[-1]/2 - stars/2

	#boucle par ligne
	for j in range(i + 2):
		for l in range(1, stars):
		  for k in range(1, stars):
			  printout= "[" + str((l + spaces) * decal)  +"," + str((k + spaces) * decal) + "," + str(countLigne*decal) + "],"
			  print printout
		stars += 2
		spaces -= 1
		countLigne +=1
	for l in range(1, stars):
	  for k in range(1, stars):
		  printout= "[" + str((l + spaces) * decal)  +","  + str((k + spaces) * decal) + "," + str(countLigne*decal) + "],"
		  print printout
        countLigne +=1
#for i in range(n):
#	spaces = (widths[-1]/2 - n/2)
#	printout = spaces * ' ' + n * '|'
#	if n%2 == 0:
#		printout += '|'
#	print printout
