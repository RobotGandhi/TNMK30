function LoadJPG(image, default_src) {
    image.onerror = "LoadDefault(this)";
    image.src = default_src + ".jpg";
    return true;
}

function LoadDefault(image) {
    image.onerror = "";
    image.src = "No_image_avaliable.svg";
    return true;
}