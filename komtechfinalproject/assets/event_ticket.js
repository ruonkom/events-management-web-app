const tableBody = document.getElementById("ticketTableBody");
const addTicketForm = document.getElementById("addTicketForm");

// Fetch all tickets
function fetchTickets() {
    fetch('ticket_management.php')
        .then(response => response.json())
        .then(tickets => {
            tableBody.innerHTML = '';
            tickets.forEach(ticket => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${ticket.id}</td>
                    <td>${ticket.event_name}</td>
                    <td>${ticket.event_date_time}</td>
                    <td>${ticket.venue}</td>
                    <td>${ticket.price}</td>
                    <td>${ticket.status}</td>
                    <td>${ticket.ticket_holder || 'N/A'}</td>
                    <td>
                        <button onclick="deleteTicket(${ticket.id})">Delete</button>
                        <button onclick="editTicket(${ticket.id}, '${ticket.event_name}', '${ticket.event_date_time}', '${ticket.venue}', ${ticket.price}, '${ticket.status}', '${ticket.ticket_holder || ''}')">Edit</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching tickets:', error));
}

// Add a ticket
addTicketForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(addTicketForm);
    fetch('ticket_management.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            fetchTickets();
            addTicketForm.reset();
        })
        .catch(error => console.error('Error adding ticket:', error));
});

// Delete a ticket
function deleteTicket(id) {
    fetch(`ticket_management.php`, {
        method: 'DELETE',
        body: `id=${id}`
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            fetchTickets();
        })
        .catch(error => console.error('Error deleting ticket:', error));
}

// Edit a ticket (populate form with ticket details)
function editTicket(id, eventName, eventDateTime, venue, price, status, ticketHolder) {
    document.getElementById("eventName").value = eventName;
    document.getElementById("eventDateTime").value = eventDateTime;
    document.getElementById("venue").value = venue;
    document.getElementById("price").value = price;
    document.getElementById("ticketStatus").value = status;
    document.getElementById("ticketHolder").value = ticketHolder;

    addTicketForm.onsubmit = (e) => {
        e.preventDefault();
        const formData = new FormData(addTicketForm);
        formData.append("id", id);
        fetch('ticket_management.php', {
            method: 'PUT',
            body: new URLSearchParams(formData)
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchTickets();
                addTicketForm.reset();
                addTicketForm.onsubmit = addTicketHandler; // Reset to original handler
            })
            .catch(error => console.error('Error updating ticket:', error));
    };
}

// Initialize
fetchTickets();
