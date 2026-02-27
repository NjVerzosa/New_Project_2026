document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

document.onkeydown = function (e) {
    if (
        e.ctrlKey &&
        (e.keyCode === 67 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 83)
    ) {
        return false;
    } else {
        return true;
    }
};