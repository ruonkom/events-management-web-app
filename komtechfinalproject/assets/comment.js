document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData();
    formData.append('userName', document.getElementById('userName').value);
    formData.append('commentInput', document.getElementById('commentInput').value);
    formData.append('profilePicture', document.getElementById('profilePicture').files[0]);

    fetch('comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Comment posted successfully!");
            // Reload the comments (this can be optimized)
            loadComments();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your comment.');
    });
});

// Function to load the comments dynamically
function loadComments() {
    fetch('load_comments.php')
    .then(response => response.json())
    .then(data => {
        console.log(data); // Add this line to debug
        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = ''; // Clear the existing comments
        data.comments.forEach(comment => {
            const commentDiv = document.createElement('div');
            commentDiv.innerHTML = `
                <div><strong>${comment.name}</strong> <img src="uploads/${comment.profile_picture}" alt="Profile Picture" width="30"> </div>
                <p>${comment.comment}</p>
                <small>${comment.timestamp}</small>
            `;
            commentsList.appendChild(commentDiv);
        });
    })
    .catch(error => {
        console.error('Error loading comments:', error);
    });
}

// Load comments on page load
window.onload = loadComments;
