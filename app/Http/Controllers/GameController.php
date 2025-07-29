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
        
        $wallDensity = min(0.15 + ($level * 0.03), 0.35);
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
            $attempts++;
            
        } while (!$this->hasPath($maze, $startX, $startY, $endX, $endY, $width, $height) && $attempts < 10);
        
        if ($attempts >= 10) {
            $maze = $this->createSimpleLevel($width, $height, $level);
        }
        
        $maze[$startY][$startX] = 2;
        $maze[$endY][$endX] = 3;
        
        return $maze;
    }

    private function addWallClusters(&$maze, $width, $height, $level, $startX, $startY, $endX, $endY)
    {
        $numClusters = min($level + 1, 6);
        
        for ($c = 0; $c < $numClusters; $c++) {
            $centerX = rand(3, $width - 4);
            $centerY = rand(3, $height - 4);
            
            if (($centerX >= $startX - 1 && $centerX <= $startX + 1 && $centerY >= $startY - 1 && $centerY <= $startY + 1) ||
                ($centerX >= $endX - 1 && $centerX <= $endX + 1 && $centerY >= $endY - 1 && $centerY <= $endY + 1)) {
                continue;
            }
            
            $clusterSize = rand(2, 3);
            for ($i = 0; $i < $clusterSize; $i++) {
                $x = $centerX + rand(-1, 1);
                $y = $centerY + rand(-1, 1);
                
                if ($x >= 0 && $x < $width && $y >= 0 && $y < $height &&
                    !($x === $startX && $y === $startY) && !($x === $endX && $y === $endY)) {
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

    private function createSimpleLevel($width, $height, $level)
    {
        $maze = array_fill(0, $height, array_fill(0, $width, 0));
        
        $wallCount = min($level * 5, ($width * $height) / 4);
        
        for ($i = 0; $i < $wallCount; $i++) {
            $x = rand(0, $width - 1);
            $y = rand(0, $height - 1);
            
            if (($x === 2 && $y === 2) || ($x === $width - 3 && $y === $height - 3) ||
                abs($x - 2) + abs($y - 2) <= 2 || abs($x - ($width - 3)) + abs($y - ($height - 3)) <= 2) {
                continue;
            }
            
            $maze[$y][$x] = 1;
        }
        
        return $maze;
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
