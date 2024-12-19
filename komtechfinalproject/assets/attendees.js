// script.js
const attendees = [
    { id: '1', name: 'RuonKom', email: 'ruon.kom@gmail.com', status: 'confirmed', ticketType: 'VIP', registrationDate: '2024-03-15', checkedIn: false },
    { id: '2', name: 'Nyalel Ruon', email: 'nyalel.ruon@gmail.com', status: 'pending', ticketType: 'General', registrationDate: '2024-03-16', checkedIn: true },
  ];
  
  const searchInput = document.getElementById('searchInput');
  const filterBtn = document.getElementById('filterBtn');
  const filterOptions = document.getElementById('filterOptions');
  const attendeeTable = document.getElementById('attendeeTable');
  
  let filteredAttendees = [...attendees];
  
  // Attendees
  const renderTable = () => {
    attendeeTable.innerHTML = '';
    filteredAttendees.forEach((attendee) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${attendee.name}</td>
        <td>${attendee.email}</td>
        <td>${attendee.ticketType}</td>
        <td>${attendee.registrationDate}</td>
        <td>${attendee.status}</td>
        <td>${attendee.checkedIn ? 'Yes' : 'No'}</td>
        <td>
          <button ${attendee.checkedIn || attendee.status !== 'confirmed' ? 'disabled' : ''}>
            ${attendee.checkedIn ? 'Checked In' : 'Check In'}
          </button>
        </td>
      `;
      attendeeTable.appendChild(row);
    });
  };
  
  // Filter Handlers
  const handleFilter = (status) => {
    if (status === 'all') {
      filteredAttendees = [...attendees];
    } else {
      filteredAttendees = attendees.filter((a) => a.status === status);
    }
    renderTable();
    filterBtn.textContent = `Status: ${status.charAt(0).toUpperCase() + status.slice(1)}`;
    filterOptions.style.display = 'none';
  };
  
  // Event Listeners
  searchInput.addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    filteredAttendees = attendees.filter((attendee) =>
      attendee.name.toLowerCase().includes(term) || attendee.email.toLowerCase().includes(term)
    );
    renderTable();
  });
  
  filterBtn.addEventListener('click', () => {
    filterOptions.style.display = filterOptions.style.display === 'block' ? 'none' : 'block';
  });
  
  filterOptions.addEventListener('click', (e) => {
    const status = e.target.getAttribute('data-status');
    if (status) handleFilter(status);
  });
  
  // Initialize
  renderTable();
  