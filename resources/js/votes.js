// Handle dynamic vote options
document.addEventListener('DOMContentLoaded', function() {
    const optionsContainer = document.getElementById('options-container');
    
    // Add option
    document.getElementById('add-option')?.addEventListener('click', function() {
        const optionCount = optionsContainer.querySelectorAll('.option-group').length;
        const newOption = createOptionElement(optionCount + 1);
        optionsContainer.appendChild(newOption);
    });
    
    // Remove option delegate
    optionsContainer?.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            if (optionsContainer.querySelectorAll('.option-group').length > 2) {
                e.target.closest('.option-group').remove();
            } else {
                alert('Minimum 2 options required');
            }
        }
    });
    
    function createOptionElement(number) {
        const div = document.createElement('div');
        div.className = 'option-group mb-3';
        div.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" 
                       name="options[]" 
                       placeholder="Option ${number}" 
                       required>
                <button type="button" class="btn btn-outline-danger remove-option">
                    Supprimer
                </button>
            </div>
        `;
        return div;
    }
});