const currentlyVistedValue = 5;
const visitedValue = 4;
const endNodeValue = 3;
const startNodeValue = 2;
const wall = 1;
const free = 0;
export function dfs(array, startNode, endNode) {
    let stack = [];
    let visited = [];
    let parent = [];
    let maps = [];
    let currentlyVisited = [];

    for (let i = 0; i < array.length; i++) {
        visited[i] = Array(array[0].length).fill(false);
        parent[i] = Array(array[0].length).fill(null);
        currentlyVisited[i] = Array(array[0].length).fill(false);
    }

    stack.push(startNode);
    visited[startNode.x][startNode.y] = true;

    while (stack.length > 0) {
        let currentNode = stack.pop();
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

        let dx = [-1, 0, 1, 0];
        let dy = [0, 1, 0, -1];

        for (let i = 0; i < 4; i++) {
            let newX = currentNode.x + dx[i];
            let newY = currentNode.y + dy[i];

            if (newX >= 0 && newX < array.length && newY >= 0 && newY < array[0].length && array[newX][newY] !== wall && !visited[newX][newY]) {
                stack.push({x: newX, y: newY});
                visited[newX][newY] = true;
                parent[newX][newY] = currentNode;
            }
        }

        let newMap = array.map((row, i) => row.map((value, j) => {
            if (value === wall) return wall;
            if (value === startNodeValue && i === startNode.x && j === startNode.y) return startNodeValue;
            if (value === endNodeValue && i === endNode.x && j === endNode.y) return endNodeValue;
            if (currentlyVisited[i][j]) return currentlyVistedValue;
            if (visited[i][j]) return visitedValue;
            return free;
        }));
        maps.push(newMap);
    }

    return { maps, path: [] };
}
