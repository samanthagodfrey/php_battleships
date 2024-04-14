# Battleships

## Play the game

1. It's assumed that your machine in set up to do PHP development
2. Clone the project
3. In your terminal do `php -S localhost:8000`
4. Go to "http://localhost:8000/battleships.html" in your browser
5. Start playing
6. Refresh the page to start a new game

## Summary of the game

- The computer randomly chooses the location of two single-cell "ships" on a board of 8 by 8 cells
- The user has 20 guesses to find the two ships
- The user enters a co-ordinate, for example `3,5`, and the computer locates the nearest ship to that co-ordinate and tells them they're "hot" if they're 1 to 2 cells away, "warm" if they're 3 to 4 cells away, or "cold" if they're further away. As an example, `3,5` is three cells away from `2,7` because (3 - 2) + (7 - 5) = 3, so they'd be told they were "warm".
- If the user correctly guesses a ship's location, they're told they've got a hit and that ship is removed from the board. The game ends when both ships have been hit by the user, or the user has used up their 20 guesses.

## Note

It seems I didn't know my x-axis from my y-axis when I initially built this so they're around the wrong way - whoops!
