// function formDataProcessor() {

// }

function handleForm(event) {
	event.preventDefault();

	let title = document.getElementById("title").value;
	let description = document.getElementById("description").value;
	let time = document.getElementById("expected-within-time").value;
	let date = document.getElementById("expected-within-date").value;

	// console.log("date: " + date);

	if(title === "" || description === "" || time === "" || date === "") {
		return;
	}

	let data = "title=" + title;
	data += "&description=" + description;
	data += "&expected-within" + date + " " + time;

	let url = window.getOrigin() + "/tasks/store";
	let xhr = new XMLHttpRequest();
	xhr.open("POST", utr);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			console.log(xhr.responseText);
		}
	});
	xhr.setRequestHeader("Content-Type", "x-www-form-url-encoded");
	xhr.send(data);
}

window.initTaskCreate = function() {
	console.log("inside init tasks/create...");

	let submit = document.getElementById("submit");
	// we're not considering the event handler for submit for now
	// we'll consider it later
	// submit.addEventListener("click", handleForm);
}