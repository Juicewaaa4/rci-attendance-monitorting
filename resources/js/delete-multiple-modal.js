const recordsPerPage = 5;
let selectedRecordIds = [];
let selectedRecordNames = [];
let currentPage = 1;

// Handle individual checkbox changes
document.querySelectorAll('.student-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const recordId = this.value;
        const recordName = this.getAttribute('data-name');

        if (this.checked) {
            selectedRecordIds.push(recordId);
            selectedRecordNames.push(recordName);
        } else {
            selectedRecordIds = selectedRecordIds.filter(id => id !== recordId);
            selectedRecordNames = selectedRecordNames.filter(name => name !== recordName);
        }

        updateSelectAllCheckboxState();
    });
});

// Handle "Select All" checkbox
document.getElementById('select-all-checkbox').addEventListener('change', function () {
    const isChecked = this.checked;

    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
        const recordId = checkbox.value;
        const recordName = checkbox.getAttribute('data-name');

        if (isChecked) {
            if (!selectedRecordIds.includes(recordId)) {
                selectedRecordIds.push(recordId);
                selectedRecordNames.push(recordName);
            }
        } else {
            selectedRecordIds = [];
            selectedRecordNames = [];
        }
    });
});

// Update "Select All" checkbox state
function updateSelectAllCheckboxState() {
    const totalCheckboxes = document.querySelectorAll('.student-checkbox').length;
    const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked').length;

    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
}

// Display selected records with pagination
function displaySelectedRecords() {
    const startIndex = (currentPage - 1) * recordsPerPage;
    const endIndex = Math.min(startIndex + recordsPerPage, selectedRecordNames.length);
    const recordsToDisplay = selectedRecordNames.slice(startIndex, endIndex);

    const recordsList = document.getElementById('selected-records-list');
    recordsList.innerHTML = recordsToDisplay.map(name => `<p>${name}</p>`).join('');

    generatePaginationControls();
}

// Generate pagination controls
function generatePaginationControls() {
    const paginationControls = document.getElementById('pagination-controls');
    paginationControls.innerHTML = '';

    const totalPages = Math.ceil(selectedRecordNames.length / recordsPerPage);

    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.classList.add('btn', 'btn-outline-secondary', 'mx-1');
        pageButton.addEventListener('click', function () {
            currentPage = i;
            displaySelectedRecords();
        });

        if (i === currentPage) {
            pageButton.classList.add('btn-primary');
        }

        paginationControls.appendChild(pageButton);
    }
}

// Handle "Delete Multiple" button click
document.getElementById('delete-multiple-btn').addEventListener('click', function () {
    if (selectedRecordIds.length === 0) {
        Swal.fire({
            title: 'No Record Selected',
            text: 'Please select at least one record before deleting.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
    } else {
        document.getElementById('delete-multiple-record-ids').value = JSON.stringify(selectedRecordIds);

        displaySelectedRecords();

        const modal = new bootstrap.Modal(document.getElementById('delete-multiple-student-modal'));
        modal.show();
    }
});

// Password Visibility Toggles (previous code)
function togglePasswordVisibility(toggleId, passwordId) {
    document.getElementById(toggleId).addEventListener("click", function () {
        let passwordField = document.getElementById(passwordId);
        let icon = this.querySelector("i");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("bx-hide");
            icon.classList.add("bx-show");
        } else {
            passwordField.type = "password";
            icon.classList.remove("bx-show");
            icon.classList.add("bx-hide");
        }
    });
}

togglePasswordVisibility("toggle-multiple-student-password", "confirm-multiple-student-password");
