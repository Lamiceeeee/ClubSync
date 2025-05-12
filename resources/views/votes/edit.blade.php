@extends('layouts.app')

@section('title', 'Edit ' . $vote->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-6">Edit Vote</h2>
        
        <form action="{{ route('votes.update', $vote) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Vote Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $vote->title) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $vote->description) }}</textarea>
                </div>
                
                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date/Time</label>
                    <input type="datetime-local" name="end_date" id="end_date" 
                           value="{{ old('end_date', $vote->end_date->format('Y-m-d\TH:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('end_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Existing Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Options</label>
                    <div class="mt-2 space-y-2">
                        @foreach($vote->options as $index => $option)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="existing_options[{{ $option->id }}]" 
                                   value="{{ old('existing_options.' . $option->id, $option->label) }}" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @if($vote->options->count() > 2)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="delete_options[]" value="{{ $option->id }}"
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-red-600">Delete</span>
                            </label>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- New Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Add New Options</label>
                    <div id="options-container" class="mt-2 space-y-2">
                        <!-- Will be populated by JavaScript -->
                    </div>
                    <button type="button" id="add-option" class="mt-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Option
                    </button>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4 flex justify-between">
                    <button type="submit" class="btn btn-primary">
                        Update Vote
                    </button>
                    <a href="{{ route('votes.show', $vote) }}" class="btn btn-secondary">
                        Cancel
                    </a>
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
    let newOptionCount = 0;
    
    addButton.addEventListener('click', function() {
        newOptionCount++;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center space-x-2';
        optionDiv.innerHTML = `
            <input type="text" name="new_options[]" required
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <button type="button" class="remove-option text-red-600 hover:text-red-900">
                Remove
            </button>
        `;
        container.appendChild(optionDiv);
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('div').remove();
        }
    });
});
</script>
@endpush
@endsection