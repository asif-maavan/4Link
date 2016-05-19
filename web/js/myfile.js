function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#prifile-image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }

//    var reader = new FileReader();
//
//    reader.onload = function (e) {
//        // get loaded data and render thumbnail.
//        document.getElementById("prifile-image").src = e.target.result;
//    };
//
//    // read the image file as a data URL.
//    reader.readAsDataURL($('#userform-profile_picture').ffiles[0]);

}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}