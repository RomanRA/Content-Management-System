/**
 * Created by Richard on 4/23/2015.
 */
function imgError(image) {
    image.onerror = "";
    image.src = "https://secure.gravatar.com/avatar/7265ea4498d878f5ce9765b91590fcd1?s=35&d=mm";
    return true;
}
