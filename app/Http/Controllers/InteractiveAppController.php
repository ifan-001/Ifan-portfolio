<?php

namespace App\Http\Controllers;

use App\Models\InteractiveApp;
use App\Services\PortfolioDataService;
use Illuminate\Http\Request;

class InteractiveAppController extends Controller
{
    public function __construct(
        protected PortfolioDataService $portfolioService
    ) {}

    /**
     * Display the Interactive Apps & Tools directory.
     */
    public function index(Request $request)
    {
        $apps = InteractiveApp::active()->get();
        $portfolio = $this->portfolioService->getPortfolioData();

        $games = self::games();
        $tools = self::tools();

        return view('apps.index', compact('apps', 'portfolio', 'games', 'tools'));
    }

    /**
     * Puzzle games catalogue (LinkedIn-style daily puzzles and classics).
     */
    public static function games(): array
    {
        return [
            'queens' => ['name' => 'Queens', 'icon' => '♛', 'kind' => 'Logic puzzle', 'tagline' => 'Place one queen in every row, column and colour region. Queens may not touch, not even diagonally.',
                'rules' => ['Exactly one queen in each row, each column and each colour region.', 'Two queens can never touch, including diagonally.', 'Tap once to mark ×, twice for a queen, three times to clear.', 'Clashing queens turn red.']],
            'tango' => ['name' => 'Tango', 'icon' => '☀', 'kind' => 'Logic puzzle', 'tagline' => 'Fill the grid with suns and moons so every row and column is balanced and no three match in a row.',
                'rules' => ['Every row and column has 3 suns and 3 moons.', 'No more than 2 of the same symbol next to each other.', '= means the two cells match, × means they are opposite.', 'Grey cells are given and cannot change. Tap to cycle sun, moon, empty.']],
            'zip' => ['name' => 'Zip', 'icon' => '➰', 'kind' => 'Path puzzle', 'tagline' => 'Draw one continuous path that fills every cell and passes the numbers in order.',
                'rules' => ['Start on 1 and drag through neighbouring cells (no diagonals).', 'Visit the numbers in order: 1, 2, 3...', 'Fill every single cell and finish on the last number.', 'Drag back over your path or press Undo to step back.']],
            'memory' => ['name' => 'Dev Memory', 'icon' => '🧠', 'kind' => 'Memory game', 'tagline' => 'Flip cards and match all the developer pairs in as few moves as you can.',
                'rules' => ['Flip two cards per move.', 'Matching pairs stay open.', 'Find all 8 pairs. Fewer moves and less time is better.']],
            '2048' => ['name' => '2048', 'icon' => '🔢', 'kind' => 'Number puzzle', 'tagline' => 'Slide and merge tiles to reach 2048.',
                'rules' => ['Use arrow keys, WASD or swipe.', 'Tiles with the same number merge into one.', 'Reach the 2048 tile to win. The game ends when no moves are left.']],
        ];
    }

    public static function tools(): array
    {
        return [
            'json' => ['name' => 'JSON Formatter', 'icon' => '{ }'],
            'password' => ['name' => 'Password Generator', 'icon' => '🔐'],
            'qr' => ['name' => 'QR Code Maker', 'icon' => '▦'],
            'words' => ['name' => 'Word Counter', 'icon' => '¶'],
            'encode' => ['name' => 'Base64 / URL', 'icon' => '⇄'],
            'contrast' => ['name' => 'Contrast Checker', 'icon' => '◐'],
            'time' => ['name' => 'Timestamp', 'icon' => '⏱'],
        ];
    }

    public function game(string $game)
    {
        $games = self::games();
        abort_unless(isset($games[$game]), 404);
        $portfolio = $this->portfolioService->getPortfolioData();

        return view('apps.game', ['game' => $game, 'meta' => $games[$game], 'games' => $games, 'portfolio' => $portfolio]);
    }

    public function toolkit()
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        return view('apps.toolkit', ['tools' => self::tools(), 'portfolio' => $portfolio]);
    }

    /**
     * Interactive Live Resume & CV Builder tool.
     */
    public function resumeBuilder()
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        return view('apps.resume_builder', compact('portfolio'));
    }

    /**
     * Interactive Developer Game: Code Breaker Cyber Defense.
     */
    public function cyberGame()
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        return view('apps.cyber_game', compact('portfolio'));
    }

    /**
     * DoctorQR Emergency Medical ID Generator.
     */
    public function qrMedical()
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        return view('apps.qr_medical', compact('portfolio'));
    }
}
