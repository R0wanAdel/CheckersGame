# 🕹️ Checkers Game (PHP)

A simple **two-player Checkers game** built using **pure PHP**, HTML, and CSS.  
The game saves its progress in a JSON file, so players can continue even after refreshing the page.

---

## 🎮 Features

- 🧩 **Two-player turn-based gameplay**
- 💾 **Persistent state** saved in `board_state.json`
- ♟️ **Automatic initial board setup** (12 white and 12 black pieces)
- 🔁 **Restart option** to start a new game
- ❌ **Piece removal** for captured pieces
- 💬 **Turn display and feedback messages**

---

## 🧱 How It Works

- When the page is first loaded, the script checks for a file named `board_state.json`.  
  If it doesn’t exist, it creates one and initializes the board.
- The state file stores:
  ```json
  {
    "turn": "W",
    "board": {
      "0,0": "", "0,1": "B", ...
    }
  }
## 🖥️ Gameplay Instructions

1. Open the PHP file in your local server (e.g., XAMPP, WAMP, or PHP built-in server):
```php -S localhost:8000```
2. Go to http://localhost:8000
3. The board will appear with white and black pieces.
4. To move a piece:
Enter the from and to positions using column letters (A–H) and row numbers (0–7).
Example: Move from A6 to B5
5. To remove a captured piece, enter its position in the Remove a Captured Piece form.
6. Use the Restart Game button to reset the board.
## Requirements

- PHP 7.4 or higher

- Local web server (XAMPP, WAMP, or PHP CLI)

- Browser to run the interface

