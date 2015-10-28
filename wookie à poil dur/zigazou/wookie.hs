module Main where
import System.Environment (getArgs)
import qualified Data.ByteString.Char8 as B
import Data.Set (Set, delete, toList, fromList)
import qualified Data.Set as Set
import Data.Maybe (catMaybes)
import Data.List (find)

{-|
Taille minimum pour le recouvrement des `Reads`
-}
minbase :: Int
minbase = 3

{-|
A `Reads` is a bit of a sequence
-}
type Reads = B.ByteString

{-|
A `Sequence` contains a `List` of `Reads` and a `Set` of available `Reads` that
have not yet been included in the sequence. Optimization: inits is a reverse
`List`, thus adding an element or getting the last element is O(1).
-}
data Sequence = Sequence { inits :: [Reads], remains :: Set Reads }
                deriving (Eq, Show)

{-|
Assemble a `List` of `Reads` into a `Text`.

    assemble' ["abcd", "cdefg", "fghi"] = "abcdefghi"
-}
assemble' :: [Reads] -> B.ByteString
assemble' [] = B.empty
assemble' [b] = b
assemble' (a:b:rs) = B.append (B.take (B.length a - coincide a b) a)
                              (assemble' (b:rs))

{-|
Assemble the `List` of `Reads` of a `Sequence` into a `Text`.
-}
assemble :: Sequence -> B.ByteString
assemble = assemble' . reverse . inits

{-|
Search for the longest suffix of a first `Reads` which is also the prefix of a
second `Reads` and returns its length.

    coincide "abcde" "cdefgh" = 3
-}
coincide :: Reads -> Reads -> Int
coincide a b | a `B.isPrefixOf` b = B.length a
             | B.null a = 0
             | otherwise = coincide (B.tail a) b

{-|
Returns `True` if the `Sequence` is complete. A `Sequence` is complete if there
is no more `Reads` remaining.
-}
complete :: Sequence -> Bool
complete = Set.null . remains

{-|
Returns `True` if the `Sequence` is valid. A `Sequence` is valid if it is
complete or if there is `Reads` from the remaining ones that can be added.
-}
valid :: Sequence -> Bool
valid s = complete s || next s /= []

{-|
Initializes a `List` of `Sequence` with each `Reads` from a `Set`.
-}
initSequences :: Set Reads -> [Sequence]
initSequences sr = [ Sequence [r] (delete r sr) | r <- toList sr ]

{-|
Try to add a `Reads` to a `Sequence`. The `Reads` must be a member of remaining
`Reads`. If the `Reads` does not coincide with the last `Reads` of the
`Sequence`, it returns `Nothing`. The `Reads` must with at least 3 characters.
-}
(-+-) :: Sequence -> Reads -> Maybe Sequence
(-+-) (Sequence is sr) r
        | coincide (head is) r >= minbase = Just (Sequence (r:is) (delete r sr))
        | otherwise = Nothing

{-|
Given a `Sequence`, returns a `List` of `Sequence` which can be constructed
from each remaining `Reads`.
-}
next :: Sequence -> [Sequence]
next s@(Sequence _ sr) = catMaybes [ s -+- r | r <- toList sr ]

{-|
Given a `List` of `Sequence`, returns a `List` of `Sequence` with one more
`Reads` added from the remaining `Reads`.
-}
nexts :: [Sequence] -> [Sequence]
nexts = concatMap next

{-|
Repeat the `nexts` action until there is a complete `Sequence`.
-}
loopUntilComplete :: ([Sequence] -> [Sequence]) -> [Sequence] -> [Sequence]
loopUntilComplete _ [] = []
loopUntilComplete f ss | any complete ss' = ss'
                       | otherwise = loopUntilComplete f ss'
                       where ss' = f ss

main :: IO ()
main = do
    (seqFile:_) <- getArgs
    content <- B.readFile seqFile
    let rs = B.lines content
        starts = initSequences (fromList rs)
        solutions = loopUntilComplete nexts starts

    putStrLn . B.unpack . assemble . head $ solutions
