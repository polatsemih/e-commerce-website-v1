const filters = document.querySelector('.filters');
window.addEventListener('scroll', () => {
    filters.classList.toggle('sticky', window.scrollY > 0);
});
const togglers = document.querySelectorAll('.dropdown-toggler');
const icons = document.querySelectorAll('.dropdown-icon');
const menus = document.querySelectorAll('.dropdown-menu');
togglers.forEach((toggler, i) => {
    toggler.addEventListener('click', () => {
        icons[i].classList.toggle('rotate');
        menus[i].classList.toggle('active');
        togglers[i].classList.toggle('active');
    });
});
document.querySelector('.filters-toggle').addEventListener('click', () => {
    document.querySelector('.filters').classList.toggle('active');
});