<?php

namespace App\Http\Controllers;

use App\Services\PortfolioAssistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Answer a visitor question with the built-in portfolio assistant.
     */
    public function ask(Request $request, PortfolioAssistant $assistant)
    {
        $validated = $request->validate([
            'question' => 'required|string|min:1|max:500',
        ]);

        return response()->json($assistant->answer($validated['question']));
    }
}
