document.getElementById('command-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const command = document.getElementById('command-input').value;
    fetch(`game.php?command=${encodeURIComponent(command)}`)
        .then(response => response.json())
        .then(data => {
            updateBoard(data.board);
            document.getElementById('command-input').value = ''; // vyprázdníme pole pro příkaz
        });
});

// Funkce pro zobrazení herního pole
function updateBoard(board) {
    const gameBoard = document.getElementById('game-board');
    gameBoard.innerHTML = '';
    for (let y = 0; y < board.length; y++) {
        let row = '';
        for (let x = 0; x < board[y].length; x++) {
            row += `<span class="cell">${board[y][x]}</span>`;
        }
        gameBoard.innerHTML += `<div class="row">${row}</div>`;
    }
}

// Inicializace zobrazení
fetch('game.php')
    .then(response => response.json())
    .then(data => {
        updateBoard(data.board);
    });
