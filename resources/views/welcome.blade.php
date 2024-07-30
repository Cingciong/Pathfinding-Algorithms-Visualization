<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <script>

        let mapSize = 25;
        let screenWidth = window.innerWidth;
        let screenHeight = window.innerHeight;
        let elementSize = screenHeight/25;
        let screenWidthByElement = Math.ceil(screenWidth/elementSize);
        const redColor = 'red'
        const blueColor ='white'
        const greenColor = 'green'
        const yellowColor = 'yellow'

        const end=3
        const start=2
        const wall = 1
        const free =0
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
    <script type="module">
        import { bfs } from '{{ asset('js/pathfinding/bfs.js') }}';

        function findPath(selectedPathfinder, array) {
            bfs(array);
        }

        // Your existing JavaScript code
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
            <option value="pathfinder1">Pathfinder 1</option>
            <option value="pathfinder2">Pathfinder 2</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</section>


<script >


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

    createGrid()

    function findPath(selectedPathfinder, array) {
        bfs(array);
    }
    document.getElementById('startButton').addEventListener('click', function(){
        document.getElementById('startButton').addEventListener('click', function() {
            let selectedPathfinder = document.getElementById('pathfinders').value;
            findPath(selectedPathfinder, array);
        });
    })
    document.getElementById('resetButton').addEventListener('click', function() {
        array = array.map(row => row.map(() => 0));
        updateColors();
    });
    // Event listener for the "Add start" button
    document.getElementById('addStartButton').addEventListener('click', function() {
        mode = 'addingStart';
    });
    // Event listener for the "Add end" button
    document.getElementById('addEndButton').addEventListener('click', function() {
        mode = 'addingEnd';
    });
    document.addEventListener('mousedown', function(event) {
        isDragging = true;
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
                let x = elementUnderPointer.getAttribute('data-x');
                let y = elementUnderPointer.getAttribute('data-y');
                array[x][y] = wall;
                updateColors();
            }
        }
    });

    // Event listener for the blocks
    document.querySelectorAll(className).forEach(item => {
        item.addEventListener('click', event => {
            // Get the x and y coordinates from the data attributes
            let x = event.target.getAttribute('data-x');
            let y = event.target.getAttribute('data-y');

            // If the mode is "addingStart", set the start block
            if (mode === 'addingStart') {
                if (startBlock) {
                    // Reset the previous start block to free
                    array[startBlock.x][startBlock.y] = free;
                }
                startBlock = {x, y};
                array[x][y] = start;
                mode = null; // Reset the mode
            }

            // If the mode is "addingEnd", set the end block
            else if (mode === 'addingEnd') {
                if (endBlock) {
                    // Reset the previous end block to free
                    array[endBlock.x][endBlock.y] = free;
                }
                endBlock = {x, y};
                array[x][y] = end;
                mode = null; // Reset the mode
            }
            else if (array[x][y] === start) {
                array[x][y] = free;
                startBlock = null;
            } else if (array[x][y] === end) {
                array[x][y] = free;
                endBlock = null;
            } else if (array[x][y] === free) {
                array[x][y] = wall;
            } else if (array[x][y] === wall) {
                array[x][y] = free;
            }

            // If the mode is not "addingStart" or "addingEnd", toggle tahe block
            else {
                array[x][y] = !array[x][y];
            }

            // Update the colors
            updateColors();
        });
    });

    // Update the updateColors function to handle the start and end blocks
    function updateColors() {
        document.querySelectorAll(className).forEach(item => {
            let x = item.getAttribute('data-x');
            let y = item.getAttribute('data-y');
            if (array[x][y] === wall) {
                item.style.backgroundColor = redColor;
            } else if (array[x][y] === start) {
                item.style.backgroundColor = greenColor;
            } else if (array[x][y] === end) {
                item.style.backgroundColor = yellowColor;
            } else {
                item.style.backgroundColor = blueColor;
            }
        });
    }
    // Initial color update
    updateColors();

</script>


</body>
</html>
