<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;

class GameController extends Controller
{
    public function index()
    {
        return view('game.index');
    }

    public function generateMaze($level = 1)
    {
        $size = 20; // Constant size for all levels
        
        $maze = $this->createLevel($size, $size, $level);
        
        return response()->json([
            'maze' => $maze,
            'level' => $level,
            'size' => $size
        ]);
    }

    private function createLevel($width, $height, $level)
    {
        $maze = array_fill(0, $height, array_fill(0, $width, 0));
        
        $startX = 2;
        $startY = 2;
        $endX = $width - 3;
        $endY = $height - 3;
        
        // Create a maze with moderate wall density that's still solvable
        $wallDensity = min(0.25 + ($level * 0.03), 0.40);
        $numWalls = (int)($width * $height * $wallDensity);
        
        $attempts = 0;
        do {
            $maze = array_fill(0, $height, array_fill(0, $width, 0));
            
            for ($i = 0; $i < $numWalls; $i++) {
                $x = rand(0, $width - 1);
                $y = rand(0, $height - 1);
                
                if (($x === $startX && $y === $startY) || ($x === $endX && $y === $endY)) {
                    continue;
                }
                
                $maze[$y][$x] = 1;
            }
            
            $this->addWallClusters($maze, $width, $height, $level, $startX, $startY, $endX, $endY);
            $this->createMazeStructure($maze, $width, $height, $level, $startX, $startY, $endX, $endY);
            $attempts++;
            
        } while (!$this->hasPath($maze, $startX, $startY, $endX, $endY, $width, $height) && $attempts < 25);
        
        if ($attempts >= 25) {
            // If we can't generate a solvable maze, create a guaranteed solvable one
            $maze = $this->createGuaranteedSolvableMaze($width, $height, $level);
        }
        
        $maze[$startY][$startX] = 2;
        $maze[$endY][$endX] = 3;
        
        return $maze;
    }

    private function addWallClusters(&$maze, $width, $height, $level, $startX, $startY, $endX, $endY)
    {
        // More and larger wall clusters for extreme difficulty
        $numClusters = min($level * 3 + 5, 15);
        
        for ($c = 0; $c < $numClusters; $c++) {
            $centerX = rand(3, $width - 4);
            $centerY = rand(3, $height - 4);
            
            if (($centerX >= $startX - 1 && $centerX <= $startX + 1 && $centerY >= $startY - 1 && $centerY <= $startY + 1) ||
                ($centerX >= $endX - 1 && $centerX <= $endX + 1 && $centerY >= $endY - 1 && $centerY <= $endY + 1)) {
                continue;
            }
            
            // Much larger cluster sizes
            $clusterSize = rand(4, 8);
            for ($i = 0; $i < $clusterSize; $i++) {
                $x = $centerX + rand(-2, 2);
                $y = $centerY + rand(-2, 2);
                
                if ($x >= 0 && $x < $width && $y >= 0 && $y < $height &&
                    !($x === $startX && $y === $startY) && !($x === $endX && $y === $endY)) {
                    $maze[$y][$x] = 1;
                }
            }
        }
        
        // Add maze corridors - create long wall lines to force specific paths
        $this->addMazeCorridors($maze, $width, $height, $level, $startX, $startY, $endX, $endY);
    }
    
    private function addMazeCorridors(&$maze, $width, $height, $level, $startX, $startY, $endX, $endY)
    {
        $numCorridors = min($level * 2 + 3, 8);
        
        for ($c = 0; $c < $numCorridors; $c++) {
            $horizontal = rand(0, 1);
            
            if ($horizontal) {
                // Horizontal wall line
                $y = rand(4, $height - 5);
                $startWallX = rand(2, $width - 8);
                $endWallX = min($startWallX + rand(4, 8), $width - 2);
                
                for ($x = $startWallX; $x <= $endWallX; $x++) {
                    if (!($x === $startX && $y === $startY) && !($x === $endX && $y === $endY)) {
                        $maze[$y][$x] = 1;
                    }
                }
            } else {
                // Vertical wall line
                $x = rand(4, $width - 5);
                $startWallY = rand(2, $height - 8);
                $endWallY = min($startWallY + rand(4, 8), $height - 2);
                
                for ($y = $startWallY; $y <= $endWallY; $y++) {
                    if (!($x === $startX && $y === $startY) && !($x === $endX && $y === $endY)) {
                        $maze[$y][$x] = 1;
                    }
                }
            }
        }
    }
    
    private function createMazeStructure(&$maze, $width, $height, $level, $startX, $startY, $endX, $endY)
    {
        // Create a true labyrinth structure for ALL levels
        
        // Add perimeter walls to create maze boundaries
        for ($x = 0; $x < $width; $x++) {
            if (!($x === $startX && 0 === $startY) && !($x === $endX && 0 === $endY)) {
                $maze[0][$x] = 1;
            }
            if (!($x === $startX && ($height-1) === $startY) && !($x === $endX && ($height-1) === $endY)) {
                $maze[$height-1][$x] = 1;
            }
        }
        for ($y = 0; $y < $height; $y++) {
            if (!(0 === $startX && $y === $startY) && !(0 === $endX && $y === $endY)) {
                $maze[$y][0] = 1;
            }
            if (!(($width-1) === $startX && $y === $startY) && !(($width-1) === $endX && $y === $endY)) {
                $maze[$y][$width-1] = 1;
            }
        }
        
        // Create internal maze structure - moderate internal walls
        $numInternalWalls = min($level * 20 + 40, 120);
        
        for ($i = 0; $i < $numInternalWalls; $i++) {
            $x = rand(1, $width - 2);
            $y = rand(1, $height - 2);
            
            // Don't place walls at start or end
            if (($x === $startX && $y === $startY) || ($x === $endX && $y === $endY)) {
                continue;
            }
            
            // Don't completely block start or end areas
            if ((abs($x - $startX) <= 1 && abs($y - $startY) <= 1) || 
                (abs($x - $endX) <= 1 && abs($y - $endY) <= 1)) {
                continue;
            }
            
            $maze[$y][$x] = 1;
        }
        
        // Add maze grid pattern to create more structured labyrinth
        $this->addMazeGrid($maze, $width, $height, $level, $startX, $startY, $endX, $endY);
    }
    
    private function addMazeGrid(&$maze, $width, $height, $level, $startX, $startY, $endX, $endY)
    {
        // Create a grid-like maze pattern for all levels
        $gridSpacing = max(3 - floor($level / 2), 2); // Tighter grid on higher levels
        
        // Vertical grid lines
        for ($x = $gridSpacing; $x < $width - 1; $x += $gridSpacing) {
            for ($y = 1; $y < $height - 1; $y++) {
                if (($x === $startX && $y === $startY) || ($x === $endX && $y === $endY)) {
                    continue;
                }
                
                // Leave some gaps in the grid to create paths
                if (rand(0, 100) < 50) { // 50% chance of wall
                    $maze[$y][$x] = 1;
                }
            }
        }
        
        // Horizontal grid lines
        for ($y = $gridSpacing; $y < $height - 1; $y += $gridSpacing) {
            for ($x = 1; $x < $width - 1; $x++) {
                if (($x === $startX && $y === $startY) || ($x === $endX && $y === $endY)) {
                    continue;
                }
                
                // Leave some gaps in the grid to create paths
                if (rand(0, 100) < 50) { // 50% chance of wall
                    $maze[$y][$x] = 1;
                }
            }
        }
    }

    private function hasPath($maze, $startX, $startY, $endX, $endY, $width, $height)
    {
        $visited = array_fill(0, $height, array_fill(0, $width, false));
        return $this->dfs($maze, $startX, $startY, $endX, $endY, $visited, $width, $height);
    }

    private function dfs($maze, $x, $y, $endX, $endY, &$visited, $width, $height)
    {
        if ($x < 0 || $x >= $width || $y < 0 || $y >= $height || $visited[$y][$x] || $maze[$y][$x] === 1) {
            return false;
        }
        
        if ($x === $endX && $y === $endY) {
            return true;
        }
        
        $visited[$y][$x] = true;
        
        return $this->dfs($maze, $x + 1, $y, $endX, $endY, $visited, $width, $height) ||
               $this->dfs($maze, $x - 1, $y, $endX, $endY, $visited, $width, $height) ||
               $this->dfs($maze, $x, $y + 1, $endX, $endY, $visited, $width, $height) ||
               $this->dfs($maze, $x, $y - 1, $endX, $endY, $visited, $width, $height);
    }

    private function createGuaranteedSolvableMaze($width, $height, $level)
    {
        $maze = array_fill(0, $height, array_fill(0, $width, 0)); // Start with all open
        $startX = 2;
        $startY = 2;
        $endX = $width - 3;
        $endY = $height - 3;
        
        // First, create a guaranteed path from start to end
        $this->ensureMainPath($maze, $startX, $startY, $endX, $endY, $width, $height);
        
        // Now add walls carefully, checking solvability after each addition
        $wallDensity = min(0.25 + ($level * 0.03), 0.35);
        $targetWalls = (int)($width * $height * $wallDensity);
        $wallsAdded = 0;
        $maxAttempts = $targetWalls * 3; // Give ourselves room for failed attempts
        
        for ($attempt = 0; $attempt < $maxAttempts && $wallsAdded < $targetWalls; $attempt++) {
            $x = rand(0, $width - 1);
            $y = rand(0, $height - 1);
            
            // Don't block start or end
            if (($x === $startX && $y === $startY) || ($x === $endX && $y === $endY)) {
                continue;
            }
            
            // Don't place on existing walls
            if ($maze[$y][$x] === 1) {
                continue;
            }
            
            // Try placing the wall
            $maze[$y][$x] = 1;
            
            // Check if maze is still solvable
            if ($this->hasPath($maze, $startX, $startY, $endX, $endY, $width, $height)) {
                $wallsAdded++;
            } else {
                // Remove the wall if it breaks solvability
                $maze[$y][$x] = 0;
            }
        }
        
        return $maze;
    }
    
    private function ensureMainPath(&$maze, $startX, $startY, $endX, $endY, $width, $height)
    {
        // Clear a simple path to guarantee solvability
        $x = $startX;
        $y = $startY;
        
        // Move horizontally first
        while ($x !== $endX) {
            $maze[$y][$x] = 0;
            $x += ($x < $endX) ? 1 : -1;
        }
        
        // Then move vertically
        while ($y !== $endY) {
            $maze[$y][$x] = 0;
            $y += ($y < $endY) ? 1 : -1;
        }
        
        // Ensure end is clear
        $maze[$endY][$endX] = 0;
    }
    
    private function carvePath(&$maze, $startX, $startY, $endX, $endY, $width, $height)
    {
        $x = $startX;
        $y = $startY;
        
        // Carve path towards goal
        while ($x !== $endX || $y !== $endY) {
            $maze[$y][$x] = 0; // Clear path
            
            // Move towards target with some randomness
            $dx = 0;
            $dy = 0;
            
            if ($x < $endX && rand(0, 100) < 70) $dx = 1;
            elseif ($x > $endX && rand(0, 100) < 70) $dx = -1;
            
            if ($y < $endY && rand(0, 100) < 70) $dy = 1;
            elseif ($y > $endY && rand(0, 100) < 70) $dy = -1;
            
            // If no movement chosen, pick random direction
            if ($dx === 0 && $dy === 0) {
                $directions = [[-1,0], [1,0], [0,-1], [0,1]];
                $dir = $directions[rand(0, 3)];
                $dx = $dir[0];
                $dy = $dir[1];
            }
            
            $newX = max(0, min($width - 1, $x + $dx));
            $newY = max(0, min($height - 1, $y + $dy));
            
            $x = $newX;
            $y = $newY;
        }
        
        $maze[$endY][$endX] = 0; // Ensure end is clear
    }

    public function submitScore(Request $request)
    {
        $request->validate([
            'player_name' => 'required|string|max:50',
            'github_username' => 'nullable|string|max:50',
            'total_time' => 'required|numeric|min:0',
            'levels_completed' => 'integer|min:1|max:5'
        ]);

        $score = Score::create([
            'player_name' => $request->player_name,
            'github_username' => $request->github_username,
            'total_time' => $request->total_time,
            'levels_completed' => $request->levels_completed ?? 7
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Score submitted successfully!',
            'score' => $score
        ]);
    }

    public function getLeaderboard()
    {
        $scores = Score::orderBy('total_time', 'asc')
            ->take(10)
            ->get();

        return response()->json($scores);
    }

    public function getAvailableVillains()
    {
        $villainsPath = public_path('images/villains');
        $villainCount = 0;
        
        if (is_dir($villainsPath)) {
            for ($i = 1; $i <= 10; $i++) {
                if (file_exists($villainsPath . "/villain_{$i}.png")) {
                    $villainCount++;
                }
            }
        }
        
        return response()->json(['count' => $villainCount]);
    }
}
