function LoadJPG(image, default_src) {
    var test = new Image();
    var avaliable_image = true;
    test.onerror = "avaliable_image = false";
    test.URL = default_src + ".jpg";
    if (avaliable_image) {
        return test.URL;
    } else {
        return "No_image_avaliable.svg";
    }
}