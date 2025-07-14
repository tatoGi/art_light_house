<x-admin.admin-layout>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Analytics Dashboard</h1>
        
        @if (!empty($visitsByCountry) && !empty($visitsByCity))
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 px-4 mb-4">
                    <h2 class="text-xl font-bold mb-2">Country-wise Visits</h2>
                    <canvas id="visitsByCountryChart"></canvas>
                </div>
                <div class="w-full md:w-1/2 px-4 mb-4">
                    <h2 class="text-xl font-bold mb-2">City-wise Visits</h2>
                    <canvas id="visitsByCityChart"></canvas>
                </div>
            </div>
        @else
            <p>No analytics data available.</p>
        @endif
    </div>
</x-admin.admin-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
    const visitsByCountry = @json($visitsByCountry);
    const visitsByCity = @json($visitsByCity);

    const countries = visitsByCountry.map(data => data.country);
    const countryVisits = visitsByCountry.map(data => data.total);

    const ctx1 = document.getElementById('visitsByCountryChart').getContext('2d');
    const visitsByCountryChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: countries,
            datasets: [{
                label: 'Visits',
                data: countryVisits,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Visits by Country'
                }
            }
        }
    });

    const cities = visitsByCity.map(data => data.city);
    const cityVisits = visitsByCity.map(data => data.total);

    const ctx2 = document.getElementById('visitsByCityChart').getContext('2d');
    const visitsByCityChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: cities,
            datasets: [{
                label: 'Visits',
                data: cityVisits,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Visits by City'
                }
            }
        }
    });
</script>
