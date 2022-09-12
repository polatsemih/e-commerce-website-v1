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
const actionOptions = document.getElementById('action-options');
const btnBackToTop = document.getElementById('back-to-top');
window.addEventListener('scroll', () => {
    notificationWrapper.classList.toggle('sticky', window.scrollY > 0);
    document.querySelector('.header-wrapper').classList.toggle('sticky', window.scrollY > 0);
    actionOptions.classList.toggle('sticky', window.scrollY > 0);
    btnBackToTop.classList.toggle("active", window.scrollY > 500);
});
btnBackToTop.addEventListener('click', (e) => {
    e.preventDefault();
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});
const headerSearchContainer = document.getElementById('header-search-container');
const headerNav = document.getElementById('header-nav');
document.getElementById('toggler-icon').addEventListener('click', (e) => {
    e.preventDefault();
    if (!headerNav.classList.contains('active')) {
        if (headerSearchContainer.classList.contains('active')) {
            closeSearch();
        }
        if (!bodyElement.classList.contains('noscroll')) {
            bodyElement.classList.add('noscroll');
        }
        if (!headerNav.classList.contains('active')) {
            headerNav.classList.add('active');
        }
    } else {
        if (bodyElement.classList.contains('noscroll')) {
            bodyElement.classList.remove('noscroll');
        }
        if (headerNav.classList.contains('active')) {
            headerNav.classList.remove('active')
        }
    }
});
const actionOpenIcon = document.getElementById('action-open-icon');
const actionTriangle = document.getElementById('action-triangle');
actionOpenIcon.addEventListener('mouseover', (e) => {
    e.preventDefault();
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
actionOpenIcon.addEventListener('mouseleave', (e) => {
    e.preventDefault();
    if (actionOptions.classList.contains('active')) {
        actionOptions.classList.remove('active');
    }
    if (actionTriangle.classList.contains('active')) {
        actionTriangle.classList.remove('active');
    }
    if (actionOpenIcon.classList.contains('scaled')) {
        actionOpenIcon.classList.remove('scaled');
    }
});
actionOptions.addEventListener('mouseover', (e) => {
    e.preventDefault();
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
actionOptions.addEventListener('mouseleave', (e) => {
    e.preventDefault();
    if (actionOptions.classList.contains('active')) {
        actionOptions.classList.remove('active');
    }
    if (actionTriangle.classList.contains('active')) {
        actionTriangle.classList.remove('active');
    }
    if (actionOpenIcon.classList.contains('scaled')) {
        actionOpenIcon.classList.remove('scaled');
    }
});
const searchIconPrimary = document.getElementById('header-search-icon');
const purgatoryIcons = document.querySelectorAll('.purgatory-icon');
function openSearch() {
    if (!bodyElement.classList.contains('noscroll')) {
        bodyElement.classList.add('noscroll');
    }
    if (headerNav.classList.contains('active')) {
        headerNav.classList.remove('active');
    }
    if (!searchIconPrimary.classList.contains('fa-times') && !searchIconPrimary.classList.contains('search-close-icon') && searchIconPrimary.classList.contains('fa-search')) {
        searchIconPrimary.classList.add('fa-times', 'search-close-icon');
        searchIconPrimary.classList.remove('fa-search');
        searchIconPrimary.setAttribute('title', 'Kapat');
    }
    purgatoryIcons.forEach(purgatoryIcon => {
        if (!purgatoryIcon.classList.contains('disable')) {
            purgatoryIcon.classList.add('disable');
        }
    });
    if (!headerSearchContainer.classList.contains('active')) {
        headerSearchContainer.classList.add('active');
    }
    setTimeout(() => {
        document.getElementById('input-search').focus();
    }, 300);
};
function closeSearch() {
    if (bodyElement.classList.contains('noscroll')) {
        bodyElement.classList.remove('noscroll');
    }
    if (headerNav.classList.contains('active')) {
        headerNav.classList.remove('active');
    }
    if (searchIconPrimary.classList.contains('fa-times') && searchIconPrimary.classList.contains('search-close-icon') && !searchIconPrimary.classList.contains('fa-search')) {
        searchIconPrimary.classList.remove('fa-times', 'search-close-icon');
        searchIconPrimary.classList.add('fa-search');
        searchIconPrimary.setAttribute('title', 'Ara');
    }
    purgatoryIcons.forEach(purgatoryIcon => {
        if (purgatoryIcon.classList.contains('disable')) {
            purgatoryIcon.classList.remove('disable');
        }
    });
    if (headerSearchContainer.classList.contains('active')) {
        headerSearchContainer.classList.remove('active');
    }
};
searchIconPrimary.addEventListener('click', (e) => {
    e.preventDefault();
    if (!headerSearchContainer.classList.contains('active')) {
        openSearch();
    } else {
        closeSearch();
    }
});
console.log('%cSemih Polat', 'font-size: 50px;font-weight: bold;color: #6466ec;text-shadow: 9px 9px 0 #6466ec32;');
console.log('%cFull Stack Web Developer', 'font-size: 25px;color: #6466ec;');
console.log('%ccontact => ' + '%cpolatsemih@protonmail.com', 'font-size: 20px;color: #aaaaaa;', 'font-size: 20px;color: #ffffff;');