const currentlyVistedValue = 5;
const visitedValue = 4;
const endNodeValue = 3;
const startNodeValue = 2;
const wall = 1;
const free = 0;

export function aStar(array, startNode, endNode) {
    let openSet = [];
    let closedSet = [];
    let parent = [];
    let maps = [];
    let currentlyVisited = [];
    let gScore = [];
    let fScore = [];

    for (let i = 0; i < array.length; i++) {
        gScore[i] = Array(array[0].length).fill(Infinity);
        fScore[i] = Array(array[0].length).fill(Infinity);
        parent[i] = Array(array[0].length).fill(null);
        currentlyVisited[i] = Array(array[0].length).fill(false);
    }

    openSet.push(startNode);
    gScore[startNode.x][startNode.y] = 0;
    fScore[startNode.x][startNode.y] = heuristic(startNode, endNode);

    while (openSet.length > 0) {
        let currentNode = lowestFScoreNode(openSet, fScore);
        currentlyVisited[currentNode.x][currentNode.y] = true;


        if (currentNode.x === endNode.x && currentNode.y === endNode.y) {
            let path = [];
            let node = endNode;
            while (node !== null) {
                path.push(node);
                node = parent[node.x][node.y];
            }
            path.reverse();
            return { maps, path };
        }

        openSet = openSet.filter(node => node.x !== currentNode.x || node.y !== currentNode.y);
        closedSet.push(currentNode);
        let dx = [-1, 0, 1, 0, -1, -1, 1, 1];
        let dy = [0, 1, 0, -1, -1, 1, -1, 1];

        for (let i = 0; i < 8; i++) {
            let newX = currentNode.x + dx[i];
            let newY = currentNode.y + dy[i];

            // Check if the move is diagonal and if so, whether it crosses a wall
            if (i >= 4 && (
                (newX - dx[i - 4] >= 0 && newY >= 0 && array[newX - dx[i - 4]][newY] === wall) ||
                (newX >= 0 && newY - dy[i - 4] >= 0 && array[newX][newY - dy[i - 4]] === wall) ||
                (newX - dx[i - 3] >= 0 && newY >= 0 && array[newX - dx[i - 3]][newY] === wall) ||
                (newX >= 0 && newY - dy[i - 3] >= 0 && array[newX][newY - dy[i - 3]] === wall)
            )) {
                continue;
            }

            if (newX >= 0 && newX < array.length && newY >= 0 && newY < array[0].length && array[newX][newY] !== wall && !closedSet.some(node => node.x === newX && node.y === newY) && !currentlyVisited[newX][newY]) {
                let tentativeGScore = gScore[currentNode.x][currentNode.y] + 1;

                if (!openSet.some(node => node.x === newX && node.y === newY)) {
                    openSet.push({x: newX, y: newY});
                } else if (tentativeGScore >= gScore[newX][newY]) {
                    continue;
                }

                parent[newX][newY] = currentNode;
                gScore[newX][newY] = tentativeGScore;
                fScore[newX][newY] = gScore[newX][newY] + heuristic({x: newX, y: newY}, endNode);
            }
        }

        let newMap = array.map((row, i) => row.map((value, j) => {
            if (value === wall) return wall;
            if (value === startNodeValue && i === startNode.x && j === startNode.y) return startNodeValue;
            if (value === endNodeValue && i === endNode.x && j === endNode.y) return endNodeValue;
            if (currentlyVisited[i][j]) return currentlyVistedValue; // Add this line to mark the currently visited nodes
            if (closedSet.some(node => node.x === i && node.y === j)) return visitedValue;
            return free;
        }));
        maps.push(newMap);
    }

    return { maps, path: [] };
}

function heuristic(node1, node2) {
    let d1 = Math.abs(node2.x - node1.x);
    let d2 = Math.abs(node2.y - node1.y);
    return d1 + d2;
}

function lowestFScoreNode(openSet, fScore) {
    let lowest = openSet[0];
    for (let node of openSet) {
        if (fScore[node.x][node.y] < fScore[lowest.x][lowest.y]) {
            lowest = node;
        }
    }
    return lowest;
}
