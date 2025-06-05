export const loadToogles = function () {
    const btns = document.querySelectorAll("#toggle-password");
    let isShown = false;

    btns?.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            isShown = !isShown;

            btn.innerHTML = isShown
                ? `<i
                    class="ti ti-eye pointer-events-none"></i>`
                : `<i
                    class="ti ti-eye-closed pointer-events-none"></i>`;

            const input = btn.nextElementSibling;
            input.type = isShown ? "text" : "password";
        });
    });
};
loadToogles();
