package witap

import (
	"fmt"
	"strings"
)

type Resolver struct {
	Lines   []string
	Tryouts map[Position]bool
}

type Position struct{ X, Y int }

func (p *Position) Up() Position    { return Position{X: p.X, Y: p.Y - 1} }
func (p *Position) Down() Position  { return Position{X: p.X, Y: p.Y + 1} }
func (p *Position) Left() Position  { return Position{X: p.X - 1, Y: p.Y} }
func (p *Position) Right() Position { return Position{X: p.X + 1, Y: p.Y} }

func NewResolverFromString(theMap string) Resolver {
	return Resolver{
		Lines:   strings.Split(theMap, "\n"),
		Tryouts: make(map[Position]bool),
	}
}

func (r *Resolver) GetStartPosition() (Position, error) {
	for y, line := range r.Lines {
		if pos := strings.Index(line, "S"); pos != -1 {
			return Position{pos, y}, nil
		}
	}
	return Position{-1, -1}, fmt.Errorf("No such starting point")
}

func (r *Resolver) Run() (string, error) {
	startPos, err := r.GetStartPosition()
	if err != nil {
		return "", err
	}

	fmt.Println("===============================")
	r.PrintMap()
	fmt.Println("")

	return r.Step(startPos, Position{-1, -1})
}

func (r *Resolver) PrintMap() {
	fmt.Println(strings.Join(r.Lines, "\n"))
}

func (p *Position) Continue(lastPos Position) Position {
	return Position{
		X: 2*p.X - lastPos.X,
		Y: 2*p.Y - lastPos.Y,
	}
}

func (r *Resolver) GetChar(position Position) string {
	return string(r.Lines[position.Y][position.X])
}

func (p *Position) AllDirections() []Position {
	return []Position{
		p.Up(), p.Down(), p.Left(), p.Right(),
	}
}

func (r *Resolver) Step(curPos, lastPos Position) (string, error) {
	if curPos.X < 0 || curPos.Y < 0 || curPos.Y >= len(r.Lines) || curPos.X >= len(r.Lines[curPos.Y]) {
		return "", fmt.Errorf("Invalid position")
	}
	if _, exists := r.Tryouts[curPos]; exists {
		return "", fmt.Errorf("Location already tried")
	}

	r.Tryouts[curPos] = true

	letter := r.GetChar(curPos)

	// fmt.Println(curPos, r.GetChar(curPos))

	switch letter {
	case "S":
		if lastPos.X != -1 {
			return "", fmt.Errorf("'S' must be the first position")
		}
	case "+":
		if lastPos.X == -1 {
			return "", fmt.Errorf("Cannot do this symbol after a start")
		}
	case "V", ">", "<", "^":
		if r.GetChar(lastPos) == "+" {
			return "", fmt.Errorf("Cannot finish just after a '+'")
		}
	}

	switch letter {
	case "S", "+":
		for _, nextPos := range curPos.AllDirections() {
			if nextPos == lastPos {
				continue
			}
			letter, err := r.Step(nextPos, curPos)
			if err == nil {
				return letter, err
			}
		}
		return "", fmt.Errorf("Nothing to do, cannot continue")
	case "-", "|":
		return r.Step(curPos.Continue(lastPos), curPos)
	case "V":
		return r.Step(curPos.Down(), curPos)
	case ">":
		return r.Step(curPos.Right(), curPos)
	case "<":
		return r.Step(curPos.Left(), curPos)
	case "^":
		return r.Step(curPos.Up(), curPos)
	case " ":
		return "", fmt.Errorf("Invalid path, cannot continue")
	default:
		return letter, nil
	}
}
