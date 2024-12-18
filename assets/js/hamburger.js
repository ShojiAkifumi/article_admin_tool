window.onload = function () {
    document.getElementById('hamburger').addEventListener('click', function () {
        document.getElementById('side-bar').classList.toggle('open');
        document.getElementById('hamburger').classList.toggle('open');
    });
};