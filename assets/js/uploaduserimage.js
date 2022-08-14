// const imageInputs = document.querySelectorAll('.item-image-input');
// const uploadedImages = document.querySelectorAll('.uploaded-image');
// imageInputs.forEach((imageInput, i) => {
//     imageInput.onchange = function() {
//         var reader = new FileReader();
//         reader.readAsDataURL(this.files[0]);
//         reader.onload = function(e) {
//             uploadedImages[i].src = e.target.result;
//         };
//     };
// });

const imageInputs = document.querySelectorAll('.item-image-input');
const uploadedImages = document.querySelectorAll('.uploaded-image');
imageInputs.forEach((imageInput, i) => {
    imageInput.onchange = function () {
        var item = this.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(item);
        reader.name = item.name;
        reader.size = item.size;
        reader.onload = function (event) {
            var img = new Image();
            img.src = event.target.result;
            img.name = event.target.name;
            img.size = event.target.size;
            img.onload = function (el) {
                var elem = document.createElement('canvas');
                elem.width = 100;
                elem.height = 100;
                // elem.height = el.target.height * scaleFactor;
                var ctx = elem.getContext('2d');
                ctx.drawImage(el.target, 0, 0, elem.width, elem.height);
                var srcEncoded = ctx.canvas.toDataURL('image/png', 1);
                uploadedImages[i].src = srcEncoded;
            }
        }
    };
});