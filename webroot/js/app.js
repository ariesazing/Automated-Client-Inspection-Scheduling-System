function url_path(path) {
    // Automatically detect base URL based on current location
    const project = window.location.pathname.split('/')[1];
    const base = window.location.origin + '/' + project;
    return base + path;
}

const BASE_URL = url_path('');
const JS_URL = BASE_URL + '/js/';

function loadjs(filename) {
    var fileref = document.createElement('script');
    fileref.type = "text/javascript";
    fileref.src = filename;
    document.head.appendChild(fileref);
}
