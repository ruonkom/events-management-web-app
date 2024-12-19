// Function to filter events based on dropdown selection
function filterEvents() {
  const selectedEvent = document.getElementById('event-types').value;
  const pricingList = document.getElementById('pricing-list').getElementsByTagName('li');

  // Loop through the pricing list and display only matching events
  Array.from(pricingList).forEach((eventItem) => {
    if (selectedEvent === 'all' || eventItem.classList.contains(selectedEvent)) {
      eventItem.style.display = 'block';
    } else {
      eventItem.style.display = 'none';
    }
  });

  // Fetch data from the backend dynamically for events if needed
  fetch(`getEvents.php?event_type=${selectedEvent}`)
    .then((response) => response.json())
    .then((data) => {
      // Process and display additional data if required
      console.log(data);
      if (data.success) {
        // Optionally update pricing or display new events dynamically
        alert('Events loaded dynamically');
      }
    })
    .catch((error) => {
      console.error('Error fetching event data:', error);
    });
}
