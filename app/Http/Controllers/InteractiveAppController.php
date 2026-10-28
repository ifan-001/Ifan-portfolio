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

        return view('apps.index', compact('apps', 'portfolio'));
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
