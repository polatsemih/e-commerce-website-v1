const body = document.querySelector('body');
const loader = document.querySelector('.loader');
window.addEventListener('load', () => {
    loader.classList.add('loading');
});
const notification = document.querySelector('.notification');
const actionInputs = document.querySelectorAll('.input-action');
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
    actionInputs.forEach(actionInput => {
        let inputHasValue = false;
        if (actionInput === document.activeElement) {
            inputHasValue = true;
        }
        if (actionInput && actionInput.value) {
            inputHasValue = true;
        }
        if (inputHasValue) {
            actionInput.parentElement.lastElementChild.classList.add('active');
        }
    });
};
window.addEventListener('scroll', () => {
    notification.classList.toggle('sticky', window.scrollY > 0);
});
actionInputs.forEach(actionInput => {
    actionInput.addEventListener(('focusin'), () => {
        actionInput.parentElement.lastElementChild.classList.add('active');
    });
    actionInput.addEventListener(('focusout'), () => {
        let inputHasValue = false;
        if (actionInput && actionInput.value) {
            inputHasValue = true;
        }
        if (!inputHasValue) {
            actionInput.parentElement.lastElementChild.classList.remove('active');
        }
    });
});
const inputActionLabels = document.querySelectorAll('.input-action-label');
for (let index = 0; index < inputActionLabels.length; index++) {
    inputActionLabels[index].addEventListener('click', () => {
        actionInputs[index].parentElement.lastElementChild.classList.add('active');
        actionInputs[index].focus();
    });
}