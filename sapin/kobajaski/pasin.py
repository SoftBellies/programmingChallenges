#!/usr/bin/env python

import sys
import numpy as np


base = [['*'],['***'],['*****'],['*******']]


def addStars(s):
	c= s
	c += [['*'+c[-1][0]+'*']]
	return c

def addStarPos(s,pos,n):
	s[pos] = ['*'*n+s[pos][0]+'*'*n]
	return s

def feuille(taille,k):
	if taille > 1:
		b = feuille(taille-1,k)
		if np.mod(taille,2) == 1:
			k+=taille/2
		if np.mod(taille,2) == 0 and taille != 2:
			k+=taille/2-1
		b += []
		for i in range(taille):
			tmp1 =[b[-taille]]
			b += tmp1
			b=addStarPos(b,-1,k)
		for i in range(3):
			b=addStars(b)
		return b
	return base 


def tronc(taille):
	p = []
	for i in range(taille):
		if np.mod(taille,2) == 0: 
			x = '|'*(taille+1)
		else:
			x = '|'*(taille)
		p += [x]
	return p
if __name__ == "__main__":
	
	
	if len(sys.argv) != 2:
		print "error input"
		exit(1)
	else:
		ordre = int(sys.argv[1])
	if ordre == 0:
		print
		exit(1)
	f= feuille(ordre,0)
	t = tronc(ordre)
	a =0
	k=0
	c = ''
	for i in range(len(f)):
		c =''
		for j in range(len(f[-1][0])/2-len(f[i][0])/2):
			c+= ' '
		print c+ f[i][0]
	for i in range(len(t)):
		c =''
		for j in range(len(f[-1][0])/2-len(t[i][0])/2-ordre/2):
			c+= ' '
		print c+t[i]
