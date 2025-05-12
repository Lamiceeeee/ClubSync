<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\VoteOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'club_id' => 'required|exists:clubs,id',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255'
        ]);

        // Create the vote
        $vote = Vote::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'end_date' => $validatedData['end_date'],
            'club_id' => $validatedData['club_id'],
            'user_id' => Auth::id() // Set the current user as creator
        ]);

        // Create vote options
        foreach ($validatedData['options'] as $optionText) {
            VoteOption::create([
                'vote_id' => $vote->id,
                'label' => $optionText
            ]);
        }

        return redirect()
               ->route('votes.show', $vote)
               ->with('success', 'Vote created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vote $vote)
    {
        //
    }
}
