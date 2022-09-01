const headerCartContainer = document.getElementById('header-cart-container');
const headerCartOpenIcon = document.getElementById('header-cart-open-icon');
const cartClose = document.getElementById('cart-close');
window.addEventListener('scroll', () => {
    headerCartContainer.classList.toggle('sticky', window.scrollY > 0);
});
headerCartOpenIcon.addEventListener('mouseover', (e) => {
    e.preventDefault();
    if (!headerCartOpenIcon.classList.contains('scaled')) {
        headerCartOpenIcon.classList.add('scaled');
    }
    if (headerCartContainer.classList.contains('hidden')) {
        headerCartContainer.classList.remove('hidden');
    }
});
cartClose.addEventListener('click', (e) => {
    e.preventDefault();
    if (headerCartOpenIcon.classList.contains('scaled')) {
        headerCartOpenIcon.classList.remove('scaled');
    }
    if (!headerCartContainer.classList.contains('hidden')) {
        headerCartContainer.classList.add('hidden');
    }
});
actionOpenIcon.addEventListener('mouseover', (e) => {
    e.preventDefault();
    if (headerCartOpenIcon.classList.contains('scaled')) {
        headerCartOpenIcon.classList.remove('scaled');
    }
    if (!headerCartContainer.classList.contains('hidden')) {
        headerCartContainer.classList.add('hidden');
    }
    if (!actionOptions.classList.contains('active')) {
        actionOptions.classList.add('active');
    }
    if (!actionTriangle.classList.contains('active')) {
        actionTriangle.classList.add('active');
    }
    if (!actionOpenIcon.classList.contains('scaled')) {
        actionOpenIcon.classList.add('scaled');
    }
});