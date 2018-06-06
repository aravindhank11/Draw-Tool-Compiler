# Draw-Tool-Compiler
A Simple Regular Language built on top of D3 math JS to draw 2D graphs and plot vectors, implicit, explicit and parametric functions.

## Tools and Languages Used
1. XAMPP
2. JavaScript
3. PHP
> Requires active Internet Connection

> All the codes must be a part of 'xampp/htdocs' and navigate to localhost/final/home.html in browser to work on the language**

## Syntax Used

1. **To plot a graph**(first line of code always): 
    graph name='GRAPH NAME' 

2. **To plot a function**:
    function f(x)='FUNCTION IN TERMS OF x' color='COLOR'

3. **To plot an implicit function**:
    implicit-function f(x,y)='FUNCTION IN TERMS OF x AND y' color='COLOR'

4. **To plot a parametric function**:
    parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'

5. **To plot derivative to a function**:
    derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION' d-color='COLOR'

6. **To plot points**:
    points  [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]

7. **To plot shapes**:
    polylines [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]

8. **To plot a vector**:
    vector [x1,y1] [x0,y0](This is the offset and is optional)
    
## Sample Input
```
graph name='GRAPH NAME'
vector [1,2]
function f(x)='x*x' color='yellow'
vector [1,2] [4,5]
implicit-function f(x,y)='x*x + y*y' color='green'
derivative-to-function f(x)='x^4' color='red' df(x)='4*x^3'
points [1,2] [2,3] [4,5]
polylines [1,-3] [3,0] [6,4] [1,-3]
parametric-function x='cos(t)' y='sin(t)' color='blue'
```

## Future Work
1. Improving the existing functionality in terms of speed.
2. Adding more features to draw the figures and mathematical models in all possible ways that can be visualized by humans.
3. Adding features to animate and move the graphs.
4. Adding support to 3D graphs.
