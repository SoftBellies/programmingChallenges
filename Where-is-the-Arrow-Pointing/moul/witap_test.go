package witap

import (
	"fmt"
	"testing"

	. "github.com/smartystreets/goconvey/convey"
)

var inputs = []string{
	`
d  S------+    b
          |
          |
   c      +--->a
`, `
S-----+---a->c
      |
      V
      b
`, `
a S      s
  |      |
  V      V
  b      c
`, `
d s<+S+--V
    |||  Q
    -++
`, `
d s-+   +-S  +--+
    +-->b |  |  |
     |  | +--+  |
     +--+ A<----+
`, `
S-----+
|     +-^
+---+->B
    +---^
`,
	`
   +---+
   |   |
   +---S---+
       |   |
       +++++>A
       +++++>B
       +++++>C
         |
         >Y
`,
	`
     +-------+  +-------+--+-------+  +------+
     |       |  |  +-+  |  |       |  |      ++
     |  +----+  |  | |  |  |  +----+  |  +-+  |
     |  |       |  | |  |  |  |       |  | |  |
     |  +--+    |  +-+ ++  |  +--+    |  | |  |
S----+     |    |   +--+   |     |    |  | |  +--->B
     |  +--+    |  +-+ ++  |  +--+    |  | |  |
     |  |       |  | |  |  |  |       |  | |  |
     |  |       |  | |  |  |  +----+  |  +-+  |
     |  |       |  | |  |  |       |  |      ++
     +--+-------+--+ +--+  +-------+--+------+
`,
	`
  +--S--+
O<|  |  |>O
  +-+++-+
  +-+++-+
  +-+++-+
O<|  |  |>O
  +->E<-+
`,
	`
6-----+
|     +-^
+---+->B
    +---^
`,
	`
abc
de
`,
	`
S-S-S-+
|     +-^
+---+->B
    +---^
`,
	`
S-----+
|     |
+-----+
`,
	`

   S

`,
}
var outputs = []string{
	"a", "b", "b", "Q", "A", "B", "Y", "B", "E", "", "", "", "", "", "",
}

var errs = []error{
	nil, nil, nil, nil, nil, nil, nil, nil, nil,
	fmt.Errorf("No such starting point"),
	fmt.Errorf("No such starting point"),
	fmt.Errorf("Multiple starting points"),
	fmt.Errorf("Nothing to do, cannot continue"),
	fmt.Errorf("Nothing to do, cannot continue"),
}

func ExampleResolver_a() {
	resolver := NewResolverFromString(inputs[0])
	fmt.Println(resolver)
	// fixme: add output
}

func TestResolver(t *testing.T) {
	Convey("Testing resolver", t, func() {
		for idx, input := range inputs {
			Convey(fmt.Sprintf("input %d", idx+1), func() {
				resolver := NewResolverFromString(input[1 : len(input)-1])
				output, err := resolver.Run()
				So(err, ShouldResemble, errs[idx])
				So(output, ShouldEqual, outputs[idx])
			})
		}
	})
}
