const boardSize = 10;
const board = document.getElementById('game-board');
const commandsInput = document.getElementById('commands');
let karel = { x: 0, y: 0, direction: 0 };

for (let i = 0; i < boardSize * boardSize; i++) {
    const cell = document.createElement('div');
    cell.classList.add('cell');
    cell.dataset.content = ''; 
    board.appendChild(cell);
}

function updateBoard() {
    document.querySelectorAll('.cell').forEach((cell, index) => {
        cell.textContent = cell.dataset.content;
        cell.classList.remove('karel');
        if (index === karel.y * boardSize + karel.x) {
            cell.classList.add('karel');
            cell.textContent = 'K';
        }
    });
}

function turnLeft() {
    karel.direction--;
    if (karel.direction < 0) karel.direction = 3;
}

function turnRight() {
    karel.direction++;
    if (karel.direction > 3) karel.direction = 0;
}

function executeCommands(commands) {
    commands.split('\n').forEach(cmd => {
        const [action, param] = cmd.trim().toUpperCase().split(' ');
        const steps = parseInt(param) || 1;
        switch (action) {
            case 'KROK':
                for (let i = 0; i < steps; i++) {
                    if (karel.direction === 0 && karel.x < boardSize - 1) karel.x++;
                    else if (karel.direction === 1 && karel.y < boardSize - 1) karel.y++;
                    else if (karel.direction === 2 && karel.x > 0) karel.x--;
                    else if (karel.direction === 3 && karel.y > 0) karel.y--;
                }
                break;
            case 'VLEVOBOK':
                for (let i = 0; i < steps; i++) {
                    turnLeft();
                }
                break;
            case 'VPRAVOBOK':
                for (let i = 0; i < steps; i++) {
                    turnRight();
                }
                break;
            case 'POLOZ':
                const index = karel.y * boardSize + karel.x;
                board.children[index].dataset.content = param; 
                break;
            case 'RESET':
                karel = { x: 0, y: 0, direction: 0 };
                Array.from(board.children).forEach(cell => {
                    cell.dataset.content = '';
                    cell.textContent = '';
                });
                break;
        }
        updateBoard();
    });
}

document.getElementById('execute').addEventListener('click', () => {
    executeCommands(commandsInput.value);
});

updateBoard();
