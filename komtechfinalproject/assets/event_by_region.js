// Access the data passed from the PHP script
if (typeof regionData !== "undefined") {
    // Extract labels and data
    const labels = regionData.map(region => region.region_name);
    const data = regionData.map(region => region.events_planned);

    // Create the chart
    const ctx = document.getElementById('eventChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Events by Region',
                data: data,
                backgroundColor: [
                    '#FF6384', // Region 1
                    '#36A2EB', // Region 2
                    '#FFCE56', // Region 3
                    '#4BC0C0', // Region 4
                    '#9966FF'  // Region 5
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw} events`;
                        }
                    }
                }
            }
        }
    });
} else {
    console.error("Region data .");
}
