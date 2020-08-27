require('./bootstrap');



function initApp() {
	let origin = window.getOrigin();
	let url = window.getUrl();

	// registering event listeners

	// registering event listeners for index/home page
	if(url === origin || url === origin + "/index" || url === origin + "/home") {
		window.initTaskIndex();
	} else if(url === origin + "/tasks/create") {
		window.initTaskCreate();
	} else if(url.indexOf(origin + "/tasks") === 0) {
		window.initTaskShow();
	}
}

initApp();
