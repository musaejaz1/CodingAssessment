# Game of Life

This coding challenge hsa been performed using PHP 7.0

The basic purpose of this application is to mimick the behaviour of Conway’s Game of Life.

The application is divided into multiple function to make it simplified for anyone looking into.

The universe size can be changed using the const UNIVERSE_SIZE, for now it is set to be 25x25

Methods
1- createUniverse()
  This method is responsible for creating the base universe on the basis of give UNIVERSE_SIZE.

2- makeTheNextUniverse()
  This method creates the next generation of Universe using simple rules as follows.
    a) If the current state is 0/Dead and the cell has 3 live neighbors update the state to 1/Live.
    b) If the current state is 1/Live and the cell has less than 2 live neighbors or more than 3 live neighbors update the state to 2/Dead.
    c) In any other case leave it as it is.
  This method also has the ability to wrap the walls of universe using a flag that is passed in the parameter with a default value of false.
    
3- countNeighbors()
  This method is responsible for computing the sum of neighboring cells.

4- placeOscillator()
  This method is just for fun, because I wanted to see how an oscillator object would look like.

5- placeGliderObject()
  This method is responsible for adding glider object in the center of universe

6- printUniverse()
  This method is responsible for giving us a grid like output of the universe passed in parameter.

