<?php
$filename = "board_state.json";
$size = 8;
$letters = range('A', 'H');
$message = "";

// Initialize board if not exists
if (!file_exists($filename)) {
    $state = [
        "turn" => "W",
        "board" => []
    ];
    
    // Create empty board
    foreach (range(0, 7) as $row) {
        foreach (range(0, 7) as $col) {
            $state["board"]["$row,$col"] = "";
        }
    }

    // Place 12 black and 12 white pieces
    foreach (range(0, 7) as $row) {
        foreach (range(0, 7) as $col) {
            if (($row + $col) % 2 == 1) {
                if ($row < 3) $state["board"]["$row,$col"] = "B";
                elseif ($row > 4) $state["board"]["$row,$col"] = "W";
            }
        }
    }

    file_put_contents($filename, json_encode($state));
}

$state = json_decode(file_get_contents($filename), true);

// Handle move form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["from_row"])) {
    $from = $_POST["from_row"] . "," . array_search(strtoupper($_POST["from_col"]), $letters);
    $to = $_POST["to_row"] . "," . array_search(strtoupper($_POST["to_col"]), $letters);
    $turn = $state["turn"];
    $board = &$state["board"];

    if ($board[$from] === $turn && $board[$to] === "") {
        $board[$to] = $board[$from];
        $board[$from] = "";
        $state["turn"] = $turn === "W" ? "B" : "W";
        $message = "Move successful.";
    } else {
        $message = "Invalid move. Try again.";
    }

    file_put_contents($filename, json_encode($state));
}

// Handle remove form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["rem_row"])) {
    $remove = $_POST["rem_row"] . "," . array_search(strtoupper($_POST["rem_col"]), $letters);
    if (isset($state["board"][$remove])) {
        $state["board"][$remove] = "";
        $message = "Piece removed at $remove.";
        file_put_contents($filename, json_encode($state));
    }
}

function drawBoard($board)
{
    $letters = range('A', 'H');
    echo "<table border='1' style='border-collapse: collapse; margin: auto;'>";

    // Header row with A–H
    echo "<tr><td></td>";
    foreach ($letters as $letter) {
        echo "<td style='width: 50px; height: 30px; text-align: center; font-weight: bold;'>$letter</td>";
    }
    echo "</tr>";

    for ($row = 0; $row < 8; $row++) {
        echo "<tr>";
        // Row number at the beginning
        echo "<td style='width: 30px; text-align: center; font-weight: bold;'>$row</td>";
        
        for ($col = 0; $col < 8; $col++) {
            $color = ($row + $col) % 2 == 0 ? "white" : "brown";
            $cell = "$row,$col";
            $piece = $board[$cell];
            $display = "";

            if ($piece == "W") $display = "<span style='font-size: 40px; color: white;'>●</span>";
            elseif ($piece == "B") $display = "<span style='font-size: 40px; color: black;'>●</span>";

            // Show position name 
            $label = "<div style='font-size:10px;color:gray;position:absolute;top:2px;left:2px;'>{$letters[$col]}$row</div>";
            
            echo "<td style='position: relative; width: 50px; height: 50px; text-align: center; background-color: $color;'>$label $display</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}

// Handle restart game
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["restart"])) {
    if (file_exists($filename)) {
        unlink($filename); // Delete the saved game file
    }
    $message = "Game restarted! Reloading page...";
    
    // Redirect to refresh the page and initialize a new game
    header("Refresh: 1; url=" . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkers Game</title>
    <style>
        body { text-align: center; font-family: Arial; }
        input[type="text"], input[type="number"] { width: 40px; margin: 5px; }
    </style>
</head>
<body>

<h1>Checkers Game</h1>
<p><strong>Current Turn:</strong> <?= $state["turn"] === "W" ? "White" : "Black" ?></p>
<p style="color: green;"><?= $message ?></p>

<?php drawBoard($state["board"]); ?>

<h2>Move a Piece</h2>
<form method="post">
    From: 
    <input type="text" name="from_col" placeholder="A" maxlength="1" required>
    <input type="number" name="from_row" placeholder="6" min="0" max="7" required>
    To: 
    <input type="text" name="to_col" placeholder="B" maxlength="1" required>
    <input type="number" name="to_row" placeholder="5" min="0" max="7" required>
    <input type="submit" value="Move" style="background-color: #ff4444; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
</form>

<h2>Remove a Captured Piece</h2>
<form method="post">
    Position: 
    <input type="text" name="rem_col" placeholder="C" maxlength="1" required>
    <input type="number" name="rem_row" placeholder="4" min="0" max="7" required>
    <input type="submit" value="Remove" style="background-color: #ff4444; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
</form>

<h2>Game Controls</h2>
<form method="post">
    <input type="hidden" name="restart" value="1">
    <input type="submit" value="Restart Game" style="background-color: #ff4444; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
</form>

</body>
</html>
