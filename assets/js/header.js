// Header Sticky Settings
const header = document.querySelector('.header-container');
window.addEventListener('scroll', () => {
    header.classList.toggle('sticky', window.scrollY > 0);
});

// Header Mobile Settings
const togglerIcon = document.querySelector('.toggler-icon');
togglerIcon.addEventListener('click', () => {
    if (!headerNav.classList.contains('active')) {
        if (headerSearch.classList.contains('active')) {
            closeSearch();
            if (!headerNav.classList.contains('active')) {
                headerNav.classList.add('active');
            }
        } else {
            if (!body.classList.contains('noscroll')) {
                body.classList.add('noscroll');
            }
            if (!headerNav.classList.contains('active')) {
                headerNav.classList.add('active');
            }
        }
    } else {
        if (body.classList.contains('noscroll')) {
            body.classList.remove('noscroll');
        }
        headerNav.classList.remove('active');
    }
});

// Header Search Settings
const searchIconPrimary = document.querySelector('.header-search-icon');
const headerSearch = document.querySelector('.header-search-container');
const headerNav = document.querySelector('.header-nav');
const inputSearch = document.querySelector('.input-search');
const purgatoryIcons = document.querySelectorAll('.purgatory-icons');
function openSearch() {
    searchIconPrimary.classList.remove('fa-search');
    searchIconPrimary.classList.add('fa-times', 'search-close-icon');
    searchIconPrimary.setAttribute('title', 'Kapat');
    headerNav.classList.add('disable');
    purgatoryIcons.forEach(purgatoryIcon => {
        purgatoryIcon.classList.add('disable');
    });
    if (!body.classList.contains('noscroll')) {
        body.classList.add('noscroll');
    }
    headerSearch.classList.remove('disable');
    setTimeout(() => {
        headerSearch.classList.add('active');
    }, 1);
    setTimeout(() => {
        inputSearch.focus();
    }, 300);
};
function closeSearch() {
    searchIconPrimary.classList.remove('fa-times', 'search-close-icon');
    searchIconPrimary.classList.add('fa-search');
    searchIconPrimary.setAttribute('title', 'Ara');
    headerNav.classList.remove('disable');
    purgatoryIcons.forEach(purgatoryIcon => {
        purgatoryIcon.classList.remove('disable');
    });
    headerSearch.classList.remove('active');
    setTimeout(() => {
        headerSearch.classList.add('disable');
    }, 400);
};
searchIconPrimary.addEventListener('click', () => {
    if (headerNav.classList.contains('active')) {
        headerNav.classList.remove('active');
        openSearch();
    } else if (headerSearch.classList.contains('active')) {
        if (body.classList.contains('noscroll')) {
            body.classList.remove('noscroll');
        }
        closeSearch();
    } else {
        openSearch();
    }
});

// Action Icon Settings
const actionOpenIcon = document.querySelector('.action-open-icon');
const actionOptions = document.querySelector('.action-options');
actionOpenIcon.addEventListener('mouseover', () => {
    if (!actionOpenIcon.classList.contains('scaled') && !actionOptions.classList.contains('active')) {
        actionOpenIcon.classList.add('scaled');
        actionOptions.classList.add('active');
    }
});
actionOpenIcon.addEventListener('mouseleave', () => {
    if (actionOpenIcon.classList.contains('scaled') && actionOptions.classList.contains('active')) {
        actionOpenIcon.classList.remove('scaled');
        actionOptions.classList.remove('active');
    }
});
actionOptions.addEventListener('mouseover', () => {
    if (!actionOpenIcon.classList.contains('scaled') && !actionOptions.classList.contains('active')) {
        actionOpenIcon.classList.add('scaled');
        actionOptions.classList.add('active');
    }
});
actionOptions.addEventListener('mouseleave', () => {
    if (actionOpenIcon.classList.contains('scaled') && actionOptions.classList.contains('active')) {
        actionOpenIcon.classList.remove('scaled');
        actionOptions.classList.remove('active');
    }
});