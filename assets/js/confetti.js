//doc: https://github.com/catdad/canvas-confetti
function createdConfetti(){
    confetti({particleCount: 100,spread: 100, ticks: 100,origin: { y: 0.4 }});
}

function publishedConfetti(){
    confetti({particleCount: 150,spread: 100,origin: { y: 0.5 }});
    var duration = 2 * 1000;
    var animationEnd = Date.now() + duration;
    var defaults = {particleCount: 50, startVelocity: 30, spread: 360, ticks: 100, zIndex: 0 };

    function randomInRange(min, max) {
    return Math.random() * (max - min) + min;
    }
    var interval = setInterval(function() {
        var timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) {
            return clearInterval(interval);
        }
        var particleCount = 50 * (timeLeft / duration);
        // since particles fall down, start a bit higher than random
        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
    }, 100);
}