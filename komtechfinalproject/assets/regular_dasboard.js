// Function to handle section navigation
function showSection(sectionId) {
    // Select all sections within the main element
    const sections = document.querySelectorAll('main section');
    
    // Hide all sections
    sections.forEach(section => {
        section.classList.add('hidden'); // Ensure the 'hidden' class hides sections
    });
    
    // Display the selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.remove('hidden');
    }
}

// Dynamically load content into placeholders (example for vendors section)
function loadDynamicContent() {
    // Example dynamic content
    document.getElementById('Vendors').innerText = "42"; // Replace with actual data
    document.getElementById('lastPurchaseDate').innerText = "2024-12-09"; // Replace with actual data
}

// Add event listeners to navigation links
document.addEventListener('DOMContentLoaded', () => {
    // Load dynamic content on page load
    loadDynamicContent();
    
    // Attach event listeners to navigation links
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default link navigation
            const sectionId = event.target.getAttribute('onclick').match(/'(.*?)'/)[1];
            showSection(sectionId);
        });
    });
});
