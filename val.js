document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    function showError(container, message) {
        container.classList.add("error-block");
        let error = container.querySelector(".error-message");
        if (!error) {
            error = document.createElement("div");
            error.className = "error-message";
            container.appendChild(error);
        }
        error.textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll(".error-block").forEach(el => el.classList.remove("error-block"));
        document.querySelectorAll(".error-message").forEach(el => el.remove());
    }

    form.addEventListener("submit", function (e) {
        clearErrors();
        let valid = true;

        // Проверка выбора фамилии
        const q1Block = form.querySelector(".question-block:first-child");
        const q1 = form.querySelector("select[name='q1_self']");
        if (!q1.value) {
            showError(q1Block, "Пожалуйста, выберите свою фамилию.");
            valid = false;
        }

        // Проверка вопросов 2,3,4 (не более 3 вариантов)
        ["q2_choices[]", "q3_choices[]", "q4_choices[]"].forEach(name => {
            const block = form.querySelector(`input[name='${name}']`).closest(".question-block");
            const checkboxes = form.querySelectorAll(`input[name='${name}']:checked`);
            if (checkboxes.length > 3) {
                showError(block, "Можно выбрать максимум 3 варианта.");
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
});
