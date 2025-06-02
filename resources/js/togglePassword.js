const btns = document.querySelectorAll("#toggle-password");

const loadToogles = function () {
    btns?.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            console.log(e.target);
            console.log(e.target.closest("input"));
        });
    });
};

["hashchange", "load"].forEach((event) => {
    window.addEventListener(event, loadToogles);
});
