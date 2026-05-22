document.addEventListener("DOMContentLoaded", function () {

    // Delete Department
    document.querySelectorAll(".delete-department-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-department-id").value = this.getAttribute("data-id");
        });
    });

    // Delete Instructor
    document.querySelectorAll(".delete-instructor-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-instructor-id").value = this.getAttribute("data-id");
        });
    });

    // Delete Program
    document.querySelectorAll(".delete-program-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-program-id").value = this.getAttribute("data-id");
        });
    });

    // Delete Year level
    document.querySelectorAll(".delete-year-level-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-year-level-id").value = this.getAttribute("data-id");
        });
    });

    // Delete School Year
    document.querySelectorAll(".delete-school-year-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-school-year-id").value = this.getAttribute("data-id");
        });
    }
    );

     // Delete Section
    document.querySelectorAll(".delete-section-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("delete-section-id").value = this.getAttribute("data-id");
        });
    });

    //Delete Record
    document.querySelectorAll(".delete-record-btn").forEach(button => {
        button.addEventListener("click", function(){
            document.getElementById("delete-record-id").value = this.getAttribute("data-id");
        });
    });



    // Password Visibility Toggles
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


    togglePasswordVisibility("toggle-department-password", "confirm-department-password");
    togglePasswordVisibility("toggle-program-password", "confirm-program-password");
    togglePasswordVisibility("toggle-year-level-password", "confirm-year-level-password");
    togglePasswordVisibility("toggle-school-year-password", "confirm-school-year-password");
    togglePasswordVisibility("toggle-section-password", "confirm-section-password");
    togglePasswordVisibility("toggle-instructor-password", "confirm-instructor-password");
    togglePasswordVisibility("toggle-student-password", "confirm-student-password");
});
