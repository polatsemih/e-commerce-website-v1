const body = document.querySelector('body');
const loader = document.querySelector('.loader');
window.addEventListener('load', () => {
    loader.classList.add('loading');
});
const notification = document.querySelector('.notification');
window.onload = function () {
    body.style.overflowY = 'visible';
    setTimeout(() => {
        loader.classList.add('disable');
    }, 100);
    setTimeout(() => {
        notification.classList.add('hidden');
        setTimeout(() => {
            notification.classList.add('removed');
        }, 1500);
    }, 10000);
};