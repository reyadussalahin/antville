function finished(event) {
	console.log("finished btn clicked...");
	event.preventDefault();
	let url = window.getUrl() + "/finished";
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			console.log(xhr.responseText);
			let response = JSON.parse(xhr.responseText);
			
			if(response.status === "success") {
				let taskFinished = document.querySelector(".task-finished");
				let taskExtend = document.querySelector(".task-extend");
				
				let taskAction = document.querySelector(".task-action");
				taskAction.removeChild(taskFinished);
				taskAction.removeChild(taskExtend);

				let taskReopen = document.getElementById("task-reopen-template");
				taskReopen.removeAttribute("id");
				taskAction.appendChild(taskReopen);

				let taskStatusBadge = document.querySelector(".task-status-badge");
				taskStatusBadge.textContent = '';
				let finishedBadge = document.getElementById("task-status-badge-finished-template");
				finishedBadge.removeAttribute("id");
				taskStatusBadge.appendChild(finishedBadge);
			} else {
				alert("error: coudn't set task status to finished...")
			}
		}
	});
	xhr.setRequestHeader("Content-Type", "x-www-url-form-encoded");
	xhr.send();
}

function reopen(event) {
	let extendUtility = document.querySelector(".task-extend-utility");
	extendUtility.hidden = !extendUtility.hidden;
}

function extend() {
	let extendUtility = document.querySelector(".task-extend-utility");
	extendUtility.hidden = !extendUtility.hidden;
}

function edit() {
	let editUtility = document.querySelector(".task-edit-utility");
	editUtility.hidden = !editUtility.hidden;
}

function addMilestone(milestone) {
	let milestoneItem = document.getElementById("task-milestone-item-template").cloneNode(true);
	milestoneItem.id = "m-" + milestone.id;

	milestoneItem.querySelector(".task-milestone-item-created").textContent = window.jsonDateFormatter(milestone.created_at);
	milestoneItem.querySelector(".task-milestone-item-description").textContent = milestone.description;

	let milestoneList = document.querySelector(".task-milestone-list");
	milestoneList.appendChild(milestoneItem);
}

function pullMilestones(event) {
	let id = 0; // default
	let limit = 5; // default

	let lastMilestoneItem = document.querySelector(".task-milestone-list").lastElementChild;
	if(lastMilestoneItem !== null) {
		id = lastMilestoneItem.id.split("-")[1];
	}

	let url = window.getUrl() + "/milestones/pull?id=" + id + "&limit=" + limit;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);

	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			console.log(xhr.responseText);
			let response = JSON.parse(xhr.responseText);
			if(response.status === "success") {
				for(let milestone of response.milestones) {
					addMilestone(milestone);
				}
				if(response.milestones.length < limit) {
					document.querySelector(".task-milestone-list-view-previous").hidden = true;
				}
			} else {
				window.location = window.getOrigin();
			}
		}
	});

	xhr.setRequestHeader("Content-Type", "x-www-url-form-encoded");
	xhr.send();
}

function pullMilestonesOnLoad() {
	let limit = 5;
	let url = window.getUrl() + "/milestones/pull?limit=" + limit;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);

	xhr.addEventListener("readystatechange", function() {
		if(xhr.readyState === 4 && xhr.status === 200) {
			console.log(xhr.responseText);
			let response = JSON.parse(xhr.responseText);
			if(response.status === "success") {
				for(let milestone of response.milestones) {
					addMilestone(milestone);
				}
				if(response.milestones.length === limit) {
					let viewPrevious = document.getElementById("task-milestone-list-view-previous-template");
					viewPrevious.removeAttribute("id");
					let taskMilestoneContentUtility = document.querySelector(".task-milestone-content-utility");
					taskMilestoneContentUtility.appendChild(viewPrevious);
				}
			} else {
				window.location = window.getOrigin();
			}
		}
	});

	xhr.setRequestHeader("Content-Type", "x-www-url-form-encoded");
	xhr.send();
}

window.initTaskShow = function() {
	// onload
	window.addEventListener("load", pullMilestonesOnLoad);

	let milestoneListShowMore = document.querySelector(".task-milestone-list-view-previous");
	milestoneListShowMore.addEventListener("click", pullMilestones);

	// let taskFinished = document.querySelector(".task-finished");
	// if(taskFinished !== null) {
	// 	taskFinished.addEventListener("click", finished);
	// }
	
	let taskReopen = document.querySelector(".task-reopen");
	if(taskReopen !== null) {
		taskReopen.addEventListener("click", reopen);
	}
	let taskExtend = document.querySelector(".task-extend");
	if(taskExtend !== null) {
		taskExtend.addEventListener("click", extend);
	}
	let taskEdit = document.querySelector(".task-edit");
	if(taskEdit !== null) {
		taskEdit.addEventListener("click", edit);
	}
};