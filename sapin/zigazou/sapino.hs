module Main where
import System.Environment
b=tail$scanl(+)1[y|x<-[6,8..],y<-[x,x]]
s w h=reverse[replicate w' '*'|w'<-take h[w, w-2..]]
d n=concat[s w$i+3|(w,i)<-zip b[1..n]]
q n=replicate n$replicate(n+1-mod n 2)'|'
c m s=replicate(div(m-length s)2)' '++s
t n=concat$(fmap.fmap)(c(b!!(n-1)))[d n,q n]
main = do
 a:_<-getArgs
 mapM_ putStrLn.t$read a