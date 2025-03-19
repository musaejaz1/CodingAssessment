<?php
/**
 * File Name: GameOfLife.php
 *
 * This file contains the code to mimick behavious of game of life.
 *
 * @author Musa Ejaz
 * @version 1.0
 */

/**
 * The Default Universe size for game of life
 *
 * @const
 */
const UNIVERSE_SIZE = 25; // this will generate a grid of given number

/**
 * This constant represent the unicode to print a solid block like character to represent life cell in universe.
 *
 * @const
 */
const LIVE_CELL = "\u{25A0}";

/**
 * This constant represent the unicode to print a hollow block like character to represent dead cell in universe.
 *
 * @const
 */
const DEAD_CELL = "\u{25A1}";

/**
 * This Function is responsible for creating the base universe structure
 * the Game of Life
 *
 * @return array $universe 2D Array with the base universe structure as per the UNIVERSE_SIZE defined globally.
 */
function createUniverse(): array
{
    /* Setup */
    $universe = array();
    $row = array();

    /* Main Logic */
    for($i= 0; $i< UNIVERSE_SIZE; $i++){
        for($j= 0; $j< UNIVERSE_SIZE; $j++){
//            $row[] = rand(0,1); // To add random lives in the universe
            $row[] = 0;  // initializing with no lives because we will put a glider object later on.
        }
        $universe[]=$row;
        $row = array();
    }

    return $universe;
}

/**
 * This function is responsible for the main computation and works
 * towards creating the new universe to represent the next generation.
 *
 * We can make the walls wrapped around to make it act like a round.
 * OR
 * We can make the walls act as edges and ignore the walls to maintain the state.
 * this can be done with the $wrapWalls boolean param
 *
 * The rules for computing the new value can be simplified into only 2 rules
 *      1- If the current state is 0/Dead and the cell has 3 live neighbors update the state to 1/Live
 *      2- If the current state is 1/Live and the cell has less than 2 live neighbors or more than 3 live neighbors update the state to 2/Dead
 *      3- In any other case leave it as it is.
 *
 * @param  array $universe  The universe under the current state.
 * @param  bool  $wrapWalls Flag for making the walls wrapable for infinite universe effect.
 * @return array            The new universe representing the new generation.
 */
function makeTheNextUniverse($universe, $wrapWalls = false){
    $newCol = array();
    $newRow = array();
    for($i= 0; $i < UNIVERSE_SIZE; $i++){
        for($j= 0; $j < UNIVERSE_SIZE; $j++){
            $currentState = $universe[$i][$j];

//          If we want to make the walls act as edges and ignore the walls to maintain the state.
            if( !$wrapWalls && ( $i == 0 || $j == 0 || $i == UNIVERSE_SIZE - 1 || $j == UNIVERSE_SIZE - 1 ) ){
                $newRow[] = $currentState;
                continue;
            }

            $liveNeighborCount = countNeighbors($universe, $i, $j, $wrapWalls);
            if ($currentState == 0 && $liveNeighborCount == 3) {
                $newRow[] = 1;
            } elseif ($currentState == 1 && ($liveNeighborCount < 2 || $liveNeighborCount > 3)) {
                $newRow[] = 0;
            } else {
                $newRow[] = $currentState;
            }
        }
        $newCol[]=$newRow;
        $newRow = array();
    }
    $newUniverse = $newCol;
    return $newUniverse;
}

/**
 * This function is responsible for computing the sum of neighboring cells
 * because there are 2 states, the cell can either be
 *      - live - 1
 *      - dead - 0
 * So getting the sum of all the cells surrounding the cell under consideration will give us the number of live cells
 *
 * This method is capable of handling the wrapping of the walls for an infinite universe effect.
 * Wrapping can still be handled in the parent function makeTheNextUniverse()
 *
 * @param  array $universe Universe for which we need the computation.
 * @param  int   $x        Position of cell on x-axis.
 * @param  int   $y        Position of cell on y-axis.
 *
 * @return int             Count of live neighbors for the cell provided in params.
 */
function countNeighbors( $universe, $x, $y, $wrapWalls ){
    $sum = 0;

    // To set the next value of edge to the other end in order to wrap the walls
    if( $wrapWalls){
        $prevCol = $x - 1 < 0 ? UNIVERSE_SIZE - 1 : $x - 1;
        $nextCol = $x + 1 >= UNIVERSE_SIZE ? 0 : $x + 1;
        $prevRow = $y - 1 < 0 ? UNIVERSE_SIZE - 1 : $y - 1;
        $nextRow = $y + 1 >= UNIVERSE_SIZE ? 0 : $y + 1;
    }
    else{
        $prevCol = $x - 1;
        $nextCol = $x + 1;
        $prevRow = $y - 1;
        $nextRow = $y + 1;
    }


    $sum += $universe[$prevCol][$prevRow];
    $sum += $universe[$prevCol][$y];
    $sum += $universe[$prevCol][$nextRow];

    $sum += $universe[$nextCol][$prevRow];
    $sum += $universe[$nextCol][$y];
    $sum += $universe[$nextCol][$nextRow];

    $sum += $universe[$x][$prevRow];
    $sum += $universe[$x][$nextRow];

    return $sum;
}

/**
 * This is just for fun, I wanted to see how an oscillator object would look like.
 * This is not including in the Main Execution but can be added just by calling the function.
 * This function is responsible for adding oscillating object in the center of universe.
 *
 * @param  array $universe Universe in which we need to add the oscillating object.
 * @return array       Updated universe with the oscillating object in the center.
 */
function placeOscillator( $universe ){
    $mid = intdiv(UNIVERSE_SIZE, 4);
    $universe [ $mid ][ $mid - 1 ] = 1;
    $universe [ $mid ][ $mid + 1 ] = 1;
    $universe [ $mid ][ $mid ] = 1;

    return $universe;
}

/**
 * This function is responsible for adding glider object in the center of universe
 *
 * @param  array $universe Universe in which we need to add the glider object.
 * @return array       Updated universe with the glider object in the center.
 */
function placeGliderObject( $universe ){

    $mid = intdiv( UNIVERSE_SIZE, 2 );

    $universe[ $mid ][ $mid + 1 ] = 1;
    $universe[ $mid + 1 ][ $mid + 2 ] = 1;
    $universe[ $mid + 2 ][ $mid ] = 1;
    $universe[ $mid + 2 ][ $mid + 1 ] = 1;
    $universe[ $mid + 2 ][ $mid + 2 ] = 1;

    return $universe;
}

/**
 * This function is responsible for printing the universe in parameters
 *
 * @param array $universe The universe also known as the universe in Game of life.
 */
function printUniverse ( $universe ){
    system('clear'); // use 'cls' for Windows based terminal
    foreach($universe as $col){
        foreach($col as $cell){
            echo $cell ? LIVE_CELL." " : DEAD_CELL." ";
        }
        echo PHP_EOL;
    }
}

//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
///////////////////// MAIN EXECUTION BLOCK ///////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////

//Create base universe
$universe = createUniverse();

//Place a glider object
$universe = placeGliderObject($universe);

//Place an oscillator object
//$universe = placeOscillator($universe);

//Print the universe
printUniverse($universe);

//Move through the generations
$number_of_generations = 39; // Number of generations can be increased or decreased by this value.
for ( $count = 0; $count< $number_of_generations; $count++){
    $nextUniverse = makeTheNextUniverse($universe, false); //compute the next generation of universe
    printUniverse($nextUniverse); // print the next generation of universe
    $universe = $nextUniverse; //  for iteration, we move the new universe to base universe to compute the next generation of universe
    usleep(100000); // Pause for 0.1 seconds, this can be modified to reduce or increase the speed of generations
}



