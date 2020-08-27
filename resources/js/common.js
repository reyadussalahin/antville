window.getOrigin = function () {
	let origin = window.location.origin;
	if(origin[origin.length - 1] === "/") {
		origin = origin.substr(0, origin.length - 1);
	}
	return origin;
}

window.getUrl = function () {
	let url = window.location.toString();
	if(url[url.length - 1] === "/") {
		url = url.substr(0, url.length - 1);
	}
	return url;
}


window.jsonDateFormatter = function(jsonDate) {
	let date = new Date(jsonDate);

	date = new Date(date.getTime() - 6 * 3600 * 1000) // minus 6 hours = (6 * 3600,000) milliseconds, cause js automatically add six hours by
	// acknowledging system +6 hours in BD time
	// it's a very bad solution, I'll fix it later
	// but to do this I've to store data in database as UTC time not BD Time
	
	let Y = date.getFullYear();
	let m = date.getMonth() + 1; // cause, it returns "0-11"
	let d = date.getDate();
	
	let H = date.getHours();
	let i = date.getMinutes();
	let s = date.getSeconds();

	console.log("date: " + jsonDate);
	console.log("H: " + H);
	console.log(new Date(0).toString());

	console.log(date.getTime());

	console.log(date.toUTCString());

	return Y + "-" + m + "-" + d + " " + H + ":" + i + ":" + s;
}