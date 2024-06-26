const bodyElement = document.querySelector('body');
const loaderWrapper = document.querySelector('.loader-wrapper');
const notificationWrapper = document.querySelector('.notification-wrapper');
window.onload = function () {
    if (bodyElement.classList.contains('noscroll')) {
        bodyElement.classList.remove('noscroll');
    }
    if (!loaderWrapper.classList.contains('hidden')) {
        loaderWrapper.classList.add('hidden');
    }
    setTimeout(() => {
        if (!notificationWrapper.classList.contains('hidden')) {
            notificationWrapper.classList.add('hidden');
        }
        setTimeout(() => {
            notificationWrapper.remove();
        }, 1500);
    }, 10000);
};
// NAVBAR SETTINGS
const navbar = document.querySelector('.navbar');
const adminPanel = document.querySelector('.admin-panel');
// NAVBAR DESKTOP SETTINGS
document.querySelector('.navbar-desktop-toggler').addEventListener('click', () => {
    adminPanel.classList.toggle('toggler-active');
    navbar.classList.toggle('toggler-active');
});
// NAVBAR MOBILE SETTINGS
document.querySelector('.navbar-mobile-toggler').addEventListener('click', () => {
    navbar.classList.add('toggler-active');
    // setTimeout(() => {
    //     adminPanel.classList.add('toggler-active');
    // }, 400);
    // Burayısil
    adminPanel.classList.add('toggler-active');
});
document.querySelector('.navbar-mobile-close-toggler').addEventListener('click', () => {
    navbar.classList.remove('toggler-active');
    adminPanel.classList.remove('toggler-active');
});
// NAVBAR DROPDOWN SETTINGS
document.querySelector('.dropdown-toggler').addEventListener('click', () => {
    document.querySelector('.dropdown-toggler-icon').classList.toggle('rotate');
    document.querySelector('.dropdown-menu').classList.toggle('active');
});
// HEADER SETTINGS
document.querySelector('.user-profile').addEventListener('click', () => {
    document.querySelector('.dropdown-profile-menu').classList.toggle('active');
});
// FOOTER SETTINGS
const btnBackToTop = document.querySelector('.btn-backtotop');
window.addEventListener('scroll', () => {
    btnBackToTop.classList.toggle('active', window.scrollY > 100);
});
btnBackToTop.addEventListener('click', () => {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});