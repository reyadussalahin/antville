function addTodoItem(todo) {
	let todoItem = document.getElementById("todo-item-template").cloneNode(true);
	todoItem.id = "t-" + todo.id;
	
	todoItem.querySelector(".todo-item-link").href = window.getOrigin() + "/tasks/" + todo.id;
	todoItem.querySelector(".todo-item-created-at").textContent = window.jsonDateFormatter(todo.created_at);
	todoItem.querySelector(".todo-item-title").textContent = todo.title;
	todoItem.querySelector(".todo-item-expected-within").textContent = todo.expected_within;
	
	let todoList = document.querySelector(".todo-list");
	todoList.appendChild(todoItem);
}

function addFinishedItem(finished) {
	let finishedItem = document.getElementById("finished-item-template").cloneNode(true);
	finishedItem.id = "t-" + finished.id;
	
	finishedItem.querySelector(".finished-item-link").href = window.getOrigin() + "/tasks/" + finished.id;
	finishedItem.querySelector(".finished-item-title").textContent = finished.title;
	finishedItem.querySelector(".finished-item-finished-at").textContent = finished.finished_at;
	
	let finishedList = document.querySelector(".finished-list");
	finishedList.appendChild(finishedItem);	
}

function addUnfinishedItem(unfinished) {
	let unfinishedItem = document.getElementById("unfinished-item-template").cloneNode(true);
	unfinishedItem.id = "t-" + unfinished.id;
	
	unfinishedItem.querySelector(".unfinished-item-link").href = window.getOrigin() + "/tasks/" + unfinished.id;
	unfinishedItem.querySelector(".unfinished-item-title").textContent = unfinished.title;
	unfinishedItem.querySelector(".unfinished-item-expected-within").textContent = unfinished.expected_within;
	
	let unfinishedList = document.querySelector(".unfinished-list");
	unfinishedList.appendChild(unfinishedItem);
}



function pullTodoItems(event) {
	let type = "todo";
	let id = 0; // default
	let limit = 5;

	let lastTodoItem = document.querySelector(".todo-list").lastElementChild;
	if(lastTodoItem !== null) {
		id = lastTodoItem.id.split("-")[1];
	}
	let url = window.getOrigin() + "/tasks/pull?type=" + type + "&id=" + id + "&limit=" + limit;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			for(let item of response.todo) {
				addTodoItem(item);
			}
			if(response.todo.length < limit) {
				document.querySelector(".todo-list-show-more").hidden = true;
			}
		}
	});
	xhr.setRequestHeader("Content-Type", "x-www-form-url-encoded");
	xhr.send();
}

function pullFinishedtems(event) {
	let type = "finished";
	let id = 0; // default
	let limit = 5;

	let lastFinishedItem = document.querySelector(".finished-list").lastElementChild;
	if(lastFinishedItem !== null) {
		id = lastFinishedItem.id.split("-")[1];
	}
	let url = window.getOrigin() + "/tasks/pull?type=" + type + "&id=" + id + "&limit=" + limit;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			for(let item of response.finished) {
				addFinishedItem(item);
			}
			if(response.finished.length < limit) {
				document.querySelector(".finished-list-show-more").hidden = true;
			}
		}
	});
	xhr.setRequestHeader("Content-Type", "x-www-form-url-encoded");
	xhr.send();
}

function pullUnfinishedItems(event) {
	let type = "unfinished";
	let id = 0; // default
	let limit = 5;

	let lastUnfinishedItem = document.querySelector(".unfinished-list").lastElementChild;
	if(lastUnfinishedItem !== null) {
		id = lastUnfinishedItem.id.split("-")[1];
	}
	let url = window.getOrigin() + "/tasks/pull?type=" + type + "&id=" + id + "&limit=" + limit;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			for(let item of response.unfinished) {
				addUnfinishedItem(item);
			}
			if(response.unfinished.length < limit) {
				document.querySelector(".unfinished-list-show-more").hidden = true;
			}
		}
	});
	xhr.setRequestHeader("Content-Type", "x-www-form-url-encoded");
	xhr.send();
}

function pullItemsOnLoad() {
	let limit = 5;
	let url = window.getOrigin() + "/tasks/pull?limit=" + limit;
	// parameter, just use the default declared in the backend
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			// console.log(xhr.responseText);
			let response = JSON.parse(xhr.responseText);
			for(let item of response.todo) {
				addTodoItem(item);
			}
			for(let item of response.finished) {
				addFinishedItem(item);
			}
			for(let item of response.unfinished) {
				addUnfinishedItem(item);
			}

			if(response.todo.length === 0) {
				let todoListEmptyMsg = document.getElementById("todo-list-empty-msg-template");
				todoListEmptyMsg.removeAttribute("id");
				document.querySelector(".todo-content").appendChild(todoListEmptyMsg);
			} else if(response.todo.length === limit) {
				let todoListShowMore = document.getElementById("todo-list-show-more-template");
				todoListShowMore.removeAttribute("id");
				document.querySelector(".todo-content").appendChild(todoListShowMore);
			}

			if(response.finished.length === 0) {
				let finishedListEmptyMsg = document.getElementById("finished-list-empty-msg-template");
				finishedListEmptyMsg.removeAttribute("id");
				document.querySelector(".finished-content").appendChild(finishedListEmptyMsg);
			} else if(response.finished.length === limit) {
				let finishedListShowMore = document.getElementById("finished-list-show-more-template");
				finishedListShowMore.removeAttribute("id");
				document.querySelector(".finished-content").appendChild(finishedListShowMore);
			}

			if(response.unfinished.length === 0) {
				let unfinishedListEmptyMsg = document.getElementById("unfinished-list-empty-msg-template");
				unfinishedListEmptyMsg.removeAttribute("id");
				document.querySelector(".unfinished-content").appendChild(unfinishedListEmptyMsg);
			} else if(response.unfinished.length === limit) {
				let unfinishedListShowMore = document.getElementById("unfinished-list-show-more-template");
				unfinishedListShowMore.removeAttribute("id");
				document.querySelector(".unfinished-content").appendChild(unfinishedListShowMore);
			}
		}
	});
	xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
	xhr.send();
}

window.initTaskIndex = function () {
	window.addEventListener("load", pullItemsOnLoad);

	let todoListShowMore = document.querySelector(".todo-list-show-more");
	todoListShowMore.addEventListener("click", pullTodoItems);

	let finishedListShowMore = document.querySelector(".finished-list-show-more");
	finishedListShowMore.addEventListener("click", pullFinishedtems);

	let unfinishedListShowMore = document.querySelector(".unfinished-list-show-more");
	unfinishedListShowMore.addEventListener("click", pullUnfinishedItems);
}
