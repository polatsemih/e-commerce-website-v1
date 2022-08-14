const body = document.querySelector('body');
const loader = document.querySelector('.loader');
window.addEventListener('load', () => {
    loader.classList.add('loading');
});
window.onload = function () {
    body.style.overflowY = 'visible';
    setTimeout(() => {
        loader.classList.add('disable');
    }, 100);
}