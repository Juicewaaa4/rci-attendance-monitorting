document.addEventListener("DOMContentLoaded", function () {
    
    // Edit Year Level Modal
    document.querySelectorAll(".edit-year-level-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-year-level-id").value = this.getAttribute("data-id");
            document.getElementById("edit-year-level-name").value = this.getAttribute("data-name");
            document.getElementById("edit-year-level-description").value = this.getAttribute("data-description");
        });
    });

    // Edit School Year Modal
    document.querySelectorAll(".edit-school-year-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-school-year-id").value = this.getAttribute("data-id");
            document.getElementById("edit-school-year-name").value = this.getAttribute("data-name");
            document.getElementById("edit-school-year-description").value = this.getAttribute("data-description");
            document.getElementById("edit-school-year-code").value = this.getAttribute("data-code");
            // document.getElementById("edit-school-year-start").value = this.getAttribute("data-start");
            // document.getElementById("edit-school-year-end").value = this.getAttribute("data-end");
        });
    });

    // Edit Section Modal
    document.querySelectorAll(".edit-section-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-section-id").value = this.getAttribute("data-id");
            document.getElementById("edit-section-name").value = this.getAttribute("data-name");
            document.getElementById("edit-section-description").value = this.getAttribute("data-description");
            document.getElementById("edit-section-code").value = this.getAttribute("data-code");
            document.getElementById("edit-section-department").value = this.getAttribute("data-department-id");
            document.getElementById("edit-section-program").value = this.getAttribute("data-program-id");
            document.getElementById("edit-section-year-level").value = this.getAttribute("data-year-level-id");
        });
    });

    // Edit Program Modal
    document.querySelectorAll(".edit-program-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-program-id").value = this.getAttribute("data-id");
            document.getElementById("edit-program-name").value = this.getAttribute("data-name");
            document.getElementById("edit-program-description").value = this.getAttribute("data-description");
            document.getElementById("edit-program-code").value = this.getAttribute("data-code");
            document.getElementById("edit-program-department").value = this.getAttribute("data-department-id");
        });
    });

    // Edit Department Modal
    document.querySelectorAll(".edit-department-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-id").value = this.getAttribute("data-id");
            document.getElementById("edit-name").value = this.getAttribute("data-name");
            document.getElementById("edit-description").value = this.getAttribute("data-description");
            document.getElementById("edit-code").value = this.getAttribute("data-code");
        });
    });

    // Edit Student Modal
    document.querySelectorAll(".edit-student-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-record-id").value = this.getAttribute("data-record-id");
            document.getElementById("edit-student-id").value = this.getAttribute("data-id");
            document.getElementById("edit-student-first-name").value = this.getAttribute("data-first-name");
            document.getElementById("edit-student-last-name").value = this.getAttribute("data-last-name");
            document.getElementById("edit-student-middle-name").value = this.getAttribute("data-middle-name");
            document.getElementById("edit-student-section").value = this.getAttribute("data-section-id");
            document.getElementById("edit-student-rfid").value = this.getAttribute("data-rfid");
            document.getElementById("edit-student-school-year").value = this.getAttribute("data-school-year-id");
            document.getElementById("edit-student-year-level").value = this.getAttribute("data-year-level-id");
            document.getElementById("edit-student-department").value = this.getAttribute("data-department-id");
            document.getElementById("edit-student-program").value = this.getAttribute("data-program-id");
        });
    });
});
