class EventBookingSystem {
    constructor() {
        this.bookings = [];
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.fetchBookings();
            const bookNowButton = document.querySelector('button[onclick="openAddBookingModal()"]');
            if (bookNowButton) {
                bookNowButton.addEventListener('click', () => this.openAddBookingModal());
            }
            const bookingForm = document.getElementById('addBookingForm');
            if (bookingForm) {
                const submitButton = bookingForm.querySelector('button[type="button"]');
                if (submitButton) {
                    submitButton.addEventListener('click', () => this.addOrUpdateBooking());
                }
            }
        });
    }

    fetchBookings() {
        fetch('get_bookings.php')
            .then(response => response.json())
            .then(data => {
                this.bookings = data;
                this.populateBookingTable(data);
            });
    }

    populateBookingTable(bookings) {
        const tableBody = document.getElementById('bookingTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        bookings.forEach(booking => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${booking.id}</td>
                <td>${booking.event_name}</td>
                <td>${booking.booking_date}</td>
                <td>${booking.event_location}</td>
                <td>${booking.client_name}</td>
                <td>${booking.client_contact}</td>
                <td>${booking.payment_status}</td>
                <td>
                    <button onclick="eventBookingSystem.editBooking(${booking.id})">Edit</button>
                    <button onclick="eventBookingSystem.deleteBooking(${booking.id})">Delete</button>
                    <button onclick="eventBookingSystem.viewBooking(${booking.id})">View</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    openAddBookingModal() {
        this.resetBookingForm();
        const modal = document.getElementById('addBookingModal');
        if (modal) {
            modal.style.display = 'block';
        }
    }

    resetBookingForm() {
        const form = document.getElementById('addBookingForm');
        if (form) {
            form.reset();
            const bookingIdInput = form.querySelector('#bookingId');
            if (bookingIdInput) {
                bookingIdInput.value = '';
            }
        }
    }

    addOrUpdateBooking() {
        const eventName = document.getElementById('eventName').value;
        const bookingDate = document.getElementById('bookingDate').value;
        const eventLocation = document.getElementById('eventLocation').value;
        const clientName = document.getElementById('clientName').value;
        const clientContact = document.getElementById('clientContact').value;
        const paymentStatus = document.getElementById('paymentStatus').value;

        if (!this.validateBookingForm()) {
            return;
        }

        const bookingIdInput = document.getElementById('bookingId');
        const isUpdate = bookingIdInput && bookingIdInput.value;
        const booking = {
            id: isUpdate ? parseInt(bookingIdInput.value) : Date.now(),
            event_name: eventName,
            booking_date: bookingDate,
            event_location: eventLocation,
            client_name: clientName,
            client_contact: clientContact,
            payment_status: paymentStatus
        };

        fetch('save_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(booking)
        })
        .then(response => response.json())
        .then(data => {
            this.bookings = data;
            this.populateBookingTable(data);
            this.closeModal('addBookingModal');
            alert(isUpdate ? 'Booking updated successfully!' : 'Booking added successfully!');
        });
    }

    validateBookingForm() {
        const requiredFields = [
            'eventName', 'bookingDate', 'eventLocation', 
            'clientName', 'clientContact', 'paymentStatus'
        ];

        for (let fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                alert(`Please fill in the ${field.previousElementSibling.textContent}`);
                field.focus();
                return false;
            }
        }

        return true;
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    goBack() {
        history.back();
    }
}

const eventBookingSystem = new EventBookingSystem();

window.openAddBookingModal = () => eventBookingSystem.openAddBookingModal();
window.closeModal = (modalId) => eventBookingSystem.closeModal(modalId);
window.goBack = () => eventBookingSystem.goBack();
