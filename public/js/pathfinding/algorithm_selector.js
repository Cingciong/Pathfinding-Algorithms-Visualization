import { bfs } from './algorithm/bfs.js';
import { dfs } from './algorithm/dfs.js';
import { aStar } from './algorithm/aStar.js';

export function findPath(algorithm, array) {
    const startNodeValue = 2;
    const endNodeValue = 3;
    let {startNode, endNode} = checkStartEndNodes(array, startNodeValue, endNodeValue);

    if (!startNode || !endNode) {
        console.log('Start node or end node not found in the array');
        return { maps: [], path: [] }; // Return a default object when start or end node is not found
    }

    switch (algorithm) {
        case 'bfs':
            return bfs(array, startNode, endNode);
        case 'dfs':
            return bfs(array, startNode, endNode);
        case 'aStar':
            return aStar(array, startNode, endNode);
        default:
            throw new Error(`Unknown algorithm: ${algorithm}`);
    }
}

function checkStartEndNodes(array, startNodeValue, endNodeValue) {
    let startNode = null;
    let endNode = null;

    for (let i = 0; i < array.length; i++) {
        for (let j = 0; j < array[i].length; j++) {
            if (array[i][j] === startNodeValue) {
                startNode = {x: i, y: j};
            } else if (array[i][j] === endNodeValue) {
                endNode = {x: i, y: j};
            }
        }
    }

    return {startNode, endNode};
}
