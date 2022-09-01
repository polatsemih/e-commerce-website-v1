const arrowLeft = document.getElementById('arrow-left');
const arrowRight = document.getElementById('arrow-right');
const homeItemCols = document.querySelectorAll('.section-home-items .col');
var relevantSliderTotalNumber = 5;
if (matchMedia('(max-width: 767px)').matches) {
    relevantSliderTotalNumber = 2;
} else if (matchMedia('(max-width: 1199px)').matches) {
    relevantSliderTotalNumber = 4;
}
var relevantItemsSlideNumber = 0;
var colPositiveCheck = homeItemCols.length - relevantSliderTotalNumber;
if (colPositiveCheck > 0) {
    var relavantItemsSlideLimit = colPositiveCheck * 100;
    arrowRight.addEventListener('click', (e) => {
        e.preventDefault();
        if (relevantItemsSlideNumber == relavantItemsSlideLimit) {
            relevantItemsSlideNumber = 0;
        } else {
            relevantItemsSlideNumber += 100;
        }
        homeItemCols.forEach(homeItemCol => {
            homeItemCol.style.transform = 'translateX(-' + relevantItemsSlideNumber + '%)';
        });
    });
    arrowLeft.addEventListener('click', (e) => {
        e.preventDefault();
        if (relevantItemsSlideNumber == 0) {
            relevantItemsSlideNumber = relavantItemsSlideLimit;
        } else {
            relevantItemsSlideNumber -= 100;
        }
        homeItemCols.forEach(homeItemCol => {
            homeItemCol.style.transform = 'translateX(-' + relevantItemsSlideNumber + '%)';
        });
    });
} else {
    if (!arrowRight.classList.contains('disable') && !arrowLeft.classList.contains('disable')) {
        arrowRight.classList.add('disable');
        arrowLeft.classList.add('disable');
    }
}