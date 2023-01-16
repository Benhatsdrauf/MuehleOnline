let possibleMoves = [
    [1, 7],            // 0
    [0, 2, 9],        // 1
    [1, 3],           // 2
    [2, 4, 11],       // 3
    [3, 5],           // 4
    [4, 6, 13],       // 5
    [5, 7],           // 6
    [0, 6, 15],       // 7
    [9, 15],          // 8
    [8, 10, 17],      // 9
    [9, 11],         // 10
    [3, 10, 12, 19], // 11
    [11, 13],        // 12
    [5, 12, 14, 21], // 13
    [13, 15],        // 14
    [7, 8, 14, 23],  // 15
    [17, 23],        // 16
    [9, 16, 18],     // 17
    [17, 19],        // 18
    [11, 18, 20],    // 19
    [19, 21],        // 20
    [13, 20, 22],    // 21
    [21, 23],        // 22
    [15, 16, 22]     // 23
]

let allPossibleMills = [
    [0, 1, 2],
    [8, 9, 10],
    [16, 17, 18],
    [7, 15, 23],
    [19, 11, 3],
    [22, 21, 20],
    [14, 13, 12],
    [6, 5, 4],
    [0, 7, 6],
    [8, 15, 14],
    [16, 23, 22],
    [1, 9, 17],
    [21, 13, 5],
    [18, 19, 20],
    [10, 11, 12],
    [2, 3, 4]
]

export function isMovePossible(oldPos, newPos) {
    return possibleMoves[oldPos].includes(newPos);
}

export function isPositionOccupied(newPos, allOccupiedPositions) // allCurrentPositions = array
{
    return allOccupiedPositions.includes(newPos);
}
/**
 * @param {Array} currentPositions 
 * @returns {Boolean}
 */
export function CheckForMill(playerOccupiedPositions) // CurrentPositions = array von einer Farbe
{
    return allPossibleMills.some(mills => mills.every(stone => playerOccupiedPositions.includes(stone)));
}

export function Move(newPos, oldPos, playerOccupiedPositions) {
    playerOccupiedPositions.remove(oldPos);
    playerOccupiedPositions.add(newPos);
}



