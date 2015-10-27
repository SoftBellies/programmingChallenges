module Main where
import System.Environment (getArgs)
import qualified Data.Text as T
import qualified Data.Text.IO as TIO
import Data.Set (Set, notMember, delete, toList, fromList)
import qualified Data.Set as Set
import Data.Maybe (catMaybes)

{-|
A `Reads` is a bit of a sequence
-}
type Reads = T.Text

{-|
A `Sequence` contains a `List` of `Reads` and a `Set` of available `Reads` that
have not yet been included in the sequence.
-}
data Sequence = Sequence { inits :: [Reads], remains :: Set Reads }
                deriving (Eq, Show)

{-|
Assemble a `List` of `Reads` into a `Text`.

    assemble' ["abcd", "cdefg", "fghi"] = "abcdefghi"
-}
assemble' :: [Reads] -> T.Text
assemble' [] = T.empty
assemble' [b] = b
assemble' (a:b:rs) = T.append (T.dropEnd (coincide a b) a) (assemble' (b:rs))

{-|
Assemble the `List` of `Reads` of a `Sequence` into a `Text`.
-}
assemble :: Sequence -> T.Text
assemble = assemble' . inits

{-|
Search for the longest suffix of a first `Reads` which is also the prefix of a
second `Reads` and returns its length.

    coincide "abcde" "cdefgh" = 3
-}
coincide :: Reads -> Reads -> Int
coincide a b | a `T.isPrefixOf` b = T.length a
             | T.null a = 0
             | otherwise = coincide (T.tail a) b

{-|
Returns `True` if the `Sequence` is complete. A `Sequence` is complete if there
is no more `Reads` remaining.
-}
complete :: Sequence -> Bool
complete = Set.null . remains

{-|
Returns `True` if the `Sequence` is invalid. A `Sequence` is invalid if it is not
complete and there is no `Reads` from the remaining ones that can be added.
-}
invalid :: Sequence -> Bool
invalid s = not (complete s) && null (next s)

{-|
Initializes a `List` of `Sequence` with each `Reads` from a `Set`.
-}
initSequences :: Set Reads -> [Sequence]
initSequences sr = [ Sequence [r] (delete r sr) | r <- toList sr ]

{-|
Try to add a `Reads` to a `Sequence`. If the `Reads` is not a member of the `Set`
of remaining `Reads` or it does not coincide with the last `Reads` of the
`Sequence`, it returns `Nothing`. The `Reads` must with at least 3 characters.
-}
(-+-) :: Sequence -> Reads -> Maybe Sequence
(-+-) (Sequence is sr) r
        | notMember r sr = Nothing
        | coincide (last is) r > 2 = Just (Sequence (is ++ [r]) (delete r sr))
        | otherwise = Nothing

{-|
Given a `Sequence`, returns a `List` of `Sequence` which can be constructed
from each remaining `Reads`.
-}
next :: Sequence -> [Sequence]
next s@(Sequence _ sr) = catMaybes [ s -+- r | r <- toList sr ]

{-|
Given a `List` of `Sequence`, returns a `List` of `Sequence` with one more
`Reads` added from the remaining `Reads`. Invalid `Sequence` are filtered out
of the resulting `List`.
-}
nexts :: [Sequence] -> [Sequence]
nexts = filter (not . invalid) . concatMap next

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
    content <- TIO.readFile seqFile
    let rs = T.lines content
        starts = initSequences (fromList rs)
        solutions = loopUntilComplete nexts starts

    putStrLn . T.unpack . assemble . head $ solutions
