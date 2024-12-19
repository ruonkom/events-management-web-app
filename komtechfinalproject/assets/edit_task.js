function editTask(id, updatedTask) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_task.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (this.status === 200) {
            console.log("Task updated:", this.responseText);
            displayTasks(); // Refresh task list
        }
    };
    xhr.send("id=" + id + "&task=" + encodeURIComponent(updatedTask));
}
