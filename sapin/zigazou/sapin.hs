{- |
Module      : Sapin
Description : Print a pine
Copyright   : (c) Frédéric BISSON, 2015
License     : GPL-3
Maintainer  : zigazou@free.fr
Stability   : experimental
Portability : POSIX

                   ______________________________
                  *                              |
                 ***   This is a stair           |
                *****                            |
  base ------->*******___________________________|
                *****                            |  Branches
               *******                           |
              *********   This is another stair  |
             ***********                         |
  base ---->*************________________________|
                 |||
                 |||     This is the pied

-}
module Main where
import System.Environment (getArgs)

{-|
Draw a pine stair of specific base width and height.
-}
stair :: Int -> Int -> [String]
stair width height =
    reverse [ replicate width' '*' | width' <- take height [width, width-2..] ]

{-|
Gives list of the base width of each stair.

Returned list looks something like [7,13,21,29,39,49,61,73,87,101...]

Note:

- [ xx | x <- [6, 8..], xx <- [x, x] ] = [6,6,8,8,10,10,12,12,14,14...]
-}
baseWidths :: [Int]
baseWidths = tail $ scanl (+) 1 [ xx | x <- [6, 8..], xx <- [x, x] ]

{-|
Draws the branches of a pine
-}
branchs :: Int -> [String]
branchs n =
    concat [ stair width (i + 3) | (width, i) <- zip baseWidths [1..n] ]

{-|
Draws the pied of a pine

(n + 1 - mod n 2) is a trick to always have an odd number. 1 - n mod 2 =

- 1 if n is even
- 0 if n is odd
-}
pied :: Int -> [String]
pied n = replicate n $ replicate (n + 1 - mod n 2) '|'

{-|
Centers a string according to a width
-}
center :: Int -> String -> String
center width s = replicate (div (width - length s) 2) ' ' ++ s

{-|
Draws a complete (branches and pied) and centered pine

(baseWidths !! (n - 1)) gives the maximum width of the pine (the largest
base width)
-}
sapin :: Int -> [String]
sapin n = concat
        $ (fmap . fmap) (center (baseWidths !! (n - 1))) [branchs n, pied n]

{-|
Print a pine
-}
printSapin :: Int -> IO ()
printSapin = mapM_ putStrLn . sapin

main :: IO ()
main = do
    arg:_ <- getArgs
    printSapin $ read arg