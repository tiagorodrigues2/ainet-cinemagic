import "./bootstrap";

let a = () => {
    document.addEventListener("DOMContentLoaded", () => {
        const user_dropdown = document.getElementById("user-dropdown");
        const user_dropdown_content = document.getElementById(
            "user-dropdown-content"
        );

        if (user_dropdown && user_dropdown_content) {
            user_dropdown.addEventListener("click", () => {
                user_dropdown_content.classList.toggle("hidden");
            });
        }
    });
};

a();
