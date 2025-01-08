<?php

$boardSize = 10;
$board = array_fill(0, $boardSize * $boardSize, '');
$karel = ['x' => 0, 'y' => 0, 'direction' => 0]; 

function turnLeft(&$karel) {
    $karel['direction']--;
    if ($karel['direction'] < 0) $karel['direction'] = 3;
}

function turnRight(&$karel) {
    $karel['direction']++;
    if ($karel['direction'] > 3) $karel['direction'] = 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commands'])) {
    $commands = strtoupper($_POST['commands']);
    $commandsArray = explode("\n", $commands);

    foreach ($commandsArray as $command) {
        $command = trim($command);
        $parts = explode(' ', $command);
        $action = $parts[0];
        $param = isset($parts[1]) ? $parts[1] : null;
        $steps = $param ? (int)$param : 1;

        switch ($action) {
            case 'KROK':
                for ($i = 0; $i < $steps; $i++) {
                    if ($karel['direction'] === 0 && $karel['x'] < $boardSize - 1) $karel['x']++;
                    else if ($karel['direction'] === 1 && $karel['y'] < $boardSize - 1) $karel['y']++;
                    else if ($karel['direction'] === 2 && $karel['x'] > 0) $karel['x']--;
                    else if ($karel['direction'] === 3 && $karel['y'] > 0) $karel['y']--;
                }
                break;
            case 'VLEVOBOK':
                for ($i = 0; $i < $steps; $i++) {
                    turnLeft($karel);
                }
                break;
            case 'VPRAVOBOK':
                for ($i = 0; $i < $steps; $i++) {
                    turnRight($karel);
                }
                break;
            case 'POLOZ':
                $index = $karel['y'] * $boardSize + $karel['x'];
                $board[$index] = $param;
                break;
            case 'RESET':
                $board = array_fill(0, $boardSize * $boardSize, ''); 
                $karel = ['x' => 0, 'y' => 0, 'direction' => 0]; 
                break;
        }
    }
}

function renderBoard($board, $karel, $boardSize) {
    for ($y = 0; $y < $boardSize; $y++) {
        for ($x = 0; $x < $boardSize; $x++) {
            $index = $y * $boardSize + $x;
            echo '<div class="cell' . ($x == $karel['x'] && $y == $karel['y'] ? ' karel' : '') . '">' .
                 ($x == $karel['x'] && $y == $karel['y'] ? 'K' : $board[$index]) .
                 '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karel - Varianta 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #game-board {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            grid-template-rows: repeat(10, 40px);
            gap: 2px;
        }
        .cell {
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .karel {
            background-color: red;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Karel - Varianta 2</h1>
    <form action="index.php" method="POST">
        <textarea name="commands" rows="10" cols="30" placeholder="Zadejte příkazy..."></textarea><br>
        <button type="submit">Spustit</button>
    </form>

    <div id="game-board">
        <?php
            renderBoard($board, $karel, $boardSize);
        ?>
    </div>
</body>
</html>
