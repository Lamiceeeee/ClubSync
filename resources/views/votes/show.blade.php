@extends('layouts.app')

@section('title', $vote->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $vote->title }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Created by {{ $vote->creator->name }} for {{ $vote->club->name }}
            </p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-500">Description</h4>
                <p class="mt-1 text-sm text-gray-900">{{ $vote->description ?? 'No description provided' }}</p>
            </div>
            
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-500">Voting Ends</h4>
                <p class="mt-1 text-sm text-gray-900">{{ $vote->end_date->format('F j, Y \a\t g:i A') }}</p>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-3">Options</h4>
                <div class="space-y-4">
                    @foreach($vote->options as $option)
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                        <span class="text-sm font-medium text-gray-900">{{ $option->label }}</span>
                        <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">
                            {{ $option->participations->count() }} votes
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            @if(!$vote->hasEnded() && !$userHasVoted)
            <form action="{{ route('votes.participate', $vote) }}" method="POST" class="mt-8">
                @csrf
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Cast Your Vote</label>
                    @foreach($vote->options as $option)
                    <div class="flex items-center">
                        <input id="option-{{ $option->id }}" name="option_id" type="radio" value="{{ $option->id }}"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <label for="option-{{ $option->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                            {{ $option->label }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="mt-4 btn btn-primary">
                    Submit Vote
                </button>
            </form>
            @elseif($userHasVoted)
            <div class="mt-6 p-4 bg-green-50 rounded-md">
                <p class="text-sm font-medium text-green-800">✓ You've already voted in this poll</p>
            </div>
            @else
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <p class="text-sm font-medium text-gray-800">This vote has ended</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection