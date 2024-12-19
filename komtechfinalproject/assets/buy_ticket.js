document.addEventListener("DOMContentLoaded", () => {
  const ticketPrice = 50;
  const ticketCountInput = document.getElementById("ticketCount");
  const totalPriceElement = document.getElementById("totalPrice");
  const bookingForm = document.getElementById("bookingForm");
  const bookingTableBody = document.getElementById("bookingTableBody");
  const downloadTicketButton = document.getElementById("downloadTicket");

  let bookings = [];
  let bookingId = 1;

  // Update Total Price
  ticketCountInput.addEventListener("input", () => {
    const ticketCount = parseInt(ticketCountInput.value) || 0;
    totalPriceElement.textContent = ticketCount * ticketPrice;
  });
  document.getElementById('ticketCount').addEventListener('input', function() {
    const ticketCount = this.value;
    const pricePerTicket = 50;
    document.getElementById('totalPrice').innerText = ticketCount * pricePerTicket;
});


  // Handle Booking Submission
  bookingForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const fullName = document.getElementById("fullName").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value || "N/A";
    const ticketCount = parseInt(ticketCountInput.value);
    const totalPrice = ticketCount * ticketPrice;

    const booking = {
      id: bookingId++,
      eventName: "Tech Summit 2024",
      date: "January 20, 2024",
      location: "Accra International Conference Center",
      clientName: fullName,
      contact: phone,
      tickets: ticketCount,
      totalPrice: `$${totalPrice}`,
    };

    bookings.push(booking);
    renderBookings();

    // Ticket Download Content
    const ticketContent = `
      Event: Tech Summit 2024
      Name: ${fullName}
      Email: ${email}
      Phone: ${phone}
      Tickets: ${ticketCount}
      Total Price: $${totalPrice}
    `;

    downloadTicketButton.style.display = "block";
    downloadTicketButton.onclick = () => {
      const blob = new Blob([ticketContent], { type: "text/plain" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "ticket.txt";
      a.click();
      URL.revokeObjectURL(url);
    };

    bookingForm.reset();
    totalPriceElement.textContent = "0";
  });

  // Render Booking History
  function renderBookings() {
    bookingTableBody.innerHTML = "";
    bookings.forEach((booking) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${booking.id}</td>
        <td>${booking.eventName}</td>
        <td>${booking.date}</td>
        <td>${booking.location}</td>
        <td>${booking.clientName}</td>
        <td>${booking.contact}</td>
        <td>${booking.tickets}</td>
        <td>${booking.totalPrice}</td>
        <td>
          <button class="action" onclick="editBooking(${booking.id})">Edit</button>
          <button class="action" onclick="deleteBooking(${booking.id})">Delete</button>
        </td>
      `;
      bookingTableBody.appendChild(row);
    });
  }

  // Delete Booking
  window.deleteBooking = (id) => {
    bookings = bookings.filter((booking) => booking.id !== id);
    renderBookings();
  };

  // Edit Booking
  window.editBooking = (id) => {
    const booking = bookings.find((booking) => booking.id === id);
    if (booking) {
      document.getElementById("fullName").value = booking.clientName;
      document.getElementById("email").value = booking.email;
      document.getElementById("phone").value = booking.contact;
      document.getElementById("ticketCount").value = booking.tickets;
      totalPriceElement.textContent = parseInt(booking.tickets) * ticketPrice;

      deleteBooking(id);
    }
  };
});
