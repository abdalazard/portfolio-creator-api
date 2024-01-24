<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function getPortfolio(Request $request) {

        $user = User::findOrFail($request->id);
        if($user->status->is_published == false || null) {
            return response()->json(['error' => 'Portfolio not published'], 404);
        }
    
        $portfolio = [
            'user' => $user,
            'profile' => $user->profile,
            'projects' => $user->projects,
            'skills' => $user->skills,
            'others' => $user->others,
            'contacts' => $user->contacts
        ];

        return response()->json($portfolio);;

    }
}
