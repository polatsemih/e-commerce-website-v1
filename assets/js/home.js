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

// Back To Top Settings
const btnBackToTop = document.querySelector('.footer-back-to-top');
window.addEventListener('scroll', () => {
    notification.classList.toggle('sticky', window.scrollY > 0);
    btnBackToTop.classList.toggle("active", window.scrollY > 500);
});
btnBackToTop.addEventListener('click', () => {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});