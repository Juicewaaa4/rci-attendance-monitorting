const studentsPerPage = 5;
let selectedStudents = [];
let currentPage = 1;

// Handle "Edit Selected" button click
document.getElementById('edit-selected-btn').addEventListener('click', function (event) {
    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        event.preventDefault();
        Swal.fire({
            title: 'No Student Selected',
            text: 'Please select at least one student before editing.',
            icon: 'info',
            backdrop: false,
            confirmButtonColor: '#3085d6',
            timer: 10000,
            timerProgressBar: true,
        });
    } else {
        const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
        document.getElementById('selected-student-ids').value = selectedIds.join(',');

        const modal = new bootstrap.Modal(document.getElementById('edit-multiple-student-modal'));
        modal.show();
    }
});

// Handle "Select All" checkbox
const selectAllCheckbox = document.getElementById('select-all-checkbox');
const tableBody = document.querySelector('tbody');

selectAllCheckbox.addEventListener('change', function () {
    const isChecked = this.checked;

    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    selectedStudents = isChecked
        ? Array.from(document.querySelectorAll('.student-checkbox')).map(cb => ({
              id: cb.value,
              name: cb.getAttribute('data-name'),
          }))
        : [];

    currentPage = 1;
    updateStudentDisplay();
    generatePagination();
});

// Handle individual checkbox changes
tableBody.addEventListener('change', function (e) {
    if (e.target.classList.contains('student-checkbox')) {
        const checkbox = e.target;
        const student = {
            id: checkbox.value,
            name: checkbox.getAttribute('data-name'),
        };

        if (checkbox.checked) {
            selectedStudents.push(student);
        } else {
            selectedStudents = selectedStudents.filter(s => s.id !== student.id);
        }

        updateSelectAllCheckboxState();
        currentPage = 1;
        updateStudentDisplay();
        generatePagination();
    }
});

// Update "Select All" checkbox state
function updateSelectAllCheckboxState() {
    const totalCheckboxes = document.querySelectorAll('.student-checkbox').length;
    const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked').length;

    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
}

// Populate selected students when modal opens
document.getElementById('edit-multiple-student-modal').addEventListener('shown.bs.modal', function () {
    selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => ({
        id: cb.value,
        name: cb.getAttribute('data-name'),
    }));

    currentPage = 1;
    updateStudentDisplay();
    generatePagination();
});

// Update student display with pagination
function updateStudentDisplay() {
    const ul = document.getElementById('selected-student-names-display');
    ul.innerHTML = '';

    const start = (currentPage - 1) * studentsPerPage;
    const end = Math.min(start + studentsPerPage, selectedStudents.length);
    const studentsToDisplay = selectedStudents.slice(start, end);

    studentsToDisplay.forEach(student => {
        const li = document.createElement('li');
        li.classList.add('list-group-item');
        li.textContent = student.name;
        ul.appendChild(li);
    });

    document.getElementById('selected-student-ids').value = selectedStudents.map(s => s.id).join(',');
}

// Generate pagination controls
function generatePagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    const totalPages = Math.ceil(selectedStudents.length / studentsPerPage);

    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === currentPage ? ' active' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.textContent = i;
        a.href = '#';

        a.addEventListener('click', function (e) {
            e.preventDefault();
            currentPage = i;
            updateStudentDisplay();
            generatePagination();
        });

        li.appendChild(a);
        pagination.appendChild(li);
    }
}