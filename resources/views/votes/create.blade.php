@extends('layouts.app')

@section('title', 'Create New Vote')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-6">Create New Vote</h2>
        
        <form action="{{ route('votes.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Vote Title</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                
                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date/Time</label>
                    <input type="datetime-local" name="end_date" id="end_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('end_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Club Selection -->
                <div>
                    <label for="club_id" class="block text-sm font-medium text-gray-700">Club</label>
                    <select name="club_id" id="club_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select a Club</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}">{{ $club->name }}</option>
                        @endforeach
                    </select>
                    @error('club_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Vote Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vote Options</label>
                    <div id="options-container" class="mt-2 space-y-2">
                        <div class="option-group flex items-center space-x-2">
                            <input type="text" name="options[]" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="option-group flex items-center space-x-2">
                            <input type="text" name="options[]" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <button type="button" id="add-option" class="mt-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Option
                    </button>
                    @error('options')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="btn btn-primary">
                        Create Vote
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('options-container');
    const addButton = document.getElementById('add-option');
    
    addButton.addEventListener('click', function() {
        const optionCount = container.querySelectorAll('.option-group').length + 1;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option-group flex items-center space-x-2 mt-2';
        optionDiv.innerHTML = `
            <input type="text" name="options[]" required
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <button type="button" class="remove-option text-red-600 hover:text-red-900">
                Remove
            </button>
        `;
        container.appendChild(optionDiv);
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            if (container.querySelectorAll('.option-group').length > 2) {
                e.target.closest('.option-group').remove();
            } else {
                alert('Minimum 2 options required');
            }
        }
    });
});
</script>
@endpush
@endsection