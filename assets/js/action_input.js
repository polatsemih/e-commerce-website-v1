const actionInputs = document.querySelectorAll('.input-action');
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