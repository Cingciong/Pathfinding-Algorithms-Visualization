<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <script>

        let mapSize = 23;
        let screenWidth = window.innerWidth;
        let screenHeight = window.innerHeight;
        let elementSize = screenHeight/mapSize;
        let screenWidthByElement = Math.ceil(screenWidth/elementSize);



        const pathNode = 6;
        const currently_visted = 5;
        const visited = 4;
        const end = 3;
        const start = 2;
        const wall = 1;
        const free = 0;

        const className = '.'+'knot'

        let startBlock = null;
        let endBlock = null;
        let mode = null;
        let array = new Array(mapSize);
        let isDragging = false;
        for (let i = 0; i < mapSize; i++) {
            array[i] = new Array(screenWidthByElement).fill(0);
        }

    </script>


    <title>Pathfinding</title>
</head>




<body class="font-sans antialiased">
<section class="flex items-center justify-center flex-col "  x-data="variables()">

    <div class="section"></div>

    <div class="flex flex-row gap-5 fixed left-0 top-0 bg-white bg-opacity-50">
        <button id="addStartButton">Add start</button>
        <button id="addEndButton">Add end</button>
        <button id="resetButton">Reset</button>
        <button id="startButton">Start</button>
        <select id="pathfinders">
            <option value="bfs">bfs</option>
            <option value="dfs">dfs</option>
            <option value="aStar">A*</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</section>


<script type="module">
    import { findPath } from '/js/pathfinding/algorithm_selector.js';



    function createGrid(){
        let container = document.querySelector('section'); // Replace 'section' with the selector for your container

        for (let i = 0; i < mapSize; i++) {
            let row = document.createElement('rect');
            row.className = 'flex flex-row';

            for (let j = 0; j < screenWidthByElement; j++) {
                let cell = document.createElement('div');
                cell.className = 'knot border border-gray-400';
                cell.setAttribute('data-x', i);
                cell.setAttribute('data-y', j);
                row.appendChild(cell);
            }

            container.appendChild(row);
        }

        let elements = document.querySelectorAll('.knot');
        elements.forEach(element => {
            element.style.width = `${elementSize}px`;
            element.style.height = `${elementSize}px`;
        });
    }
    function updateColors() {
        document.querySelectorAll(className).forEach(item => {
            let x = item.getAttribute('data-x');
            let y = item.getAttribute('data-y');
            Array.from(item.classList).forEach(cls => {
                if (cls !== 'knot' && cls !== 'border' && cls !== 'border-gray-400') {
                    item.classList.remove(cls);
                }
            });
            if (array[x][y] === wall) {
                item.classList.add('bg-[#808080]');
            } else if (array[x][y] === start) {
                item.classList.add('bg-[#37c453]');
            } else if (array[x][y] === end) {
                item.classList.add('bg-[#c43737]');
            } else if (array[x][y] === currently_visted) {
                item.classList.add('bg-[#98fb98]');
            } else if (array[x][y] === visited) {
                item.classList.add('bg-[#37bbc4]');
            } else if (array[x][y] === pathNode) {
                item.classList.add('bg-[#b189d6]');
            } else {
                item.classList.add('bg-white');
            }
        });
    }



    document.getElementById('startButton').addEventListener('click', async function() {
        let selectedPathfinder = document.getElementById('pathfinders').value;
        let { maps, path } = await findPath(selectedPathfinder, array); // Add await here

        if (!maps || !path) {
            console.log('No path or map found');
            return;
        }
        console.log('Path found:', path);
        console.log('Maps:', maps);
        let map = maps;
        for (let i = 0; i < map.length; i++) {
            array = map[i];
            await new Promise(resolve => setTimeout(resolve, 1));
            updateColors();
        }
        for (let i = 1; i < path.length-1; i++) {
            let x = path[i].x;
            let y = path[i].y;
            array[x][y] = pathNode;
            updateColors();
            await new Promise(resolve => setTimeout(resolve, 1));
        }
    });

    document.getElementById('resetButton').addEventListener('click', function() {
        array = array.map(row => row.map(() => 0));
        updateColors();
    });
    document.getElementById('addStartButton').addEventListener('click', function() {
        mode = 'addingStart';
    });
    document.getElementById('addEndButton').addEventListener('click', function() {
        mode = 'addingEnd';
    });

    document.addEventListener('mousedown', function(event) {
        let x = event.clientX;
        let y = event.clientY;
        let elementUnderPointer = document.elementFromPoint(x, y);
        if (elementUnderPointer && elementUnderPointer.classList.contains('knot')) {
            let x = parseInt(elementUnderPointer.getAttribute('data-x'));
            let y = parseInt(elementUnderPointer.getAttribute('data-y'));
            if (mode === 'addingStart') {
                if (startBlock) {
                    array[startBlock.x][startBlock.y] = free;
                }
                startBlock = {x, y};
                array[x][y] = start;
                updateColors();
                mode = null; // Reset the mode after setting the start node
            } else if (mode === 'addingEnd') {
                if (endBlock) {
                    array[endBlock.x][endBlock.y] = free;
                }
                endBlock = {x, y};
                array[x][y] = end;
                updateColors();
                mode = null; // Reset the mode after setting the end node
            } else {
                isDragging = true;
            }
        }
    });
    document.addEventListener('mouseup', function() {
        isDragging = false;
    });
    document.addEventListener('mousemove', function(event) {
        if (isDragging) {
            let x = event.clientX;
            let y = event.clientY;
            let elementUnderPointer = document.elementFromPoint(x, y);
            if (elementUnderPointer && elementUnderPointer.classList.contains('knot')) {
                let x = parseInt(elementUnderPointer.getAttribute('data-x'));
                let y = parseInt(elementUnderPointer.getAttribute('data-y'));
                array[x][y] = wall;
                updateColors();
            }
        } else if (mode === 'addingStart' || mode === 'addingEnd') {
            // Do nothing, just move the start or end node with the cursor
        }
    });

    updateColors();
    createGrid()



</script>


</body>
</html>
