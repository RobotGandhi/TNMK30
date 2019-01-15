function FixImage(image) {
	if (image.src.substr(image.src.length - 4) == ".jpg") {
		return "No_image_available.svg";
	} else {
		var URL = image.src.substr(0, image.src.length - 4);
		URL += ".jpg";
		return URL;
	}
}