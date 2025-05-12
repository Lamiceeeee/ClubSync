@extends('layouts.app')

@section('title', 'All Votes')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Current Votes</h1>
            <p class="mt-2 text-sm text-gray-700">All active voting sessions</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('votes.create') }}" class="btn btn-primary">
                Create New Vote
            </a>
        </div>
    </div>

    <div class="mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Title</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Club</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ends At</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($votes as $vote)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                        <a href="{{ route('votes.show', $vote) }}" class="hover:text-indigo-600">
                            {{ $vote->title }}
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ $vote->club->name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ $vote->end_date->format('M d, Y H:i') }}
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex space-x-2">
                            <a href="{{ route('votes.edit', $vote) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('votes.destroy', $vote) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">
                        No votes found. Create your first vote!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $votes->links() }}
    </div>
</div>
@endsection