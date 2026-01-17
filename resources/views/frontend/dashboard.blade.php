<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tenant Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }
        .card { border-radius: 15px; }
        .badge-approved { background: #28a745; }
        .badge-pending { background: #ffc107; color:#000; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-4">
    <a class="btn btn-warning" href="/">Visit Website</a>
    <span class="navbar-brand">🏢 Tenant Dashboard</span>
    <button class="btn btn-light btn-sm" onclick="logout()">Logout</button>
</nav>

<div class="container mt-4">

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Total Bookings</h6>
                <h3 id="totalBooking">0</h3>
            </div>
        </div>
    </div>

    <!-- Booking List -->
    <div class="card">
        <div class="card-header fw-bold">
            📋 My Booked Apartments
        </div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Apartment</th>
                    <th>Rent</th>
                    <th>Status</th>
                    <th>Booked At</th>
                </tr>
                </thead>
                <tbody id="bookingTable">
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const token = localStorage.getItem('tenant_token');

        if (!token) {
            window.location.href = "/tenant/login";
        }

        async function loadBookings() {
            try {
                let res = await axios.get('/api/v1/bookings', {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                let bookings = res.data.data;
                document.getElementById('totalBooking').innerText = bookings.length;

                let rows = '';
                bookings.forEach((b, index) => {
                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${b.apartment.name}</td>
                            <td>৳ ${b.apartment.rent}</td>
                            <td>
                                <span class="badge ${b.status === 'approved' ? 'badge-approved' : 'badge-pending'}">
                                    ${b.status}
                                </span>
                            </td>
                            <td>${new Date(b.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                });

                document.getElementById('bookingTable').innerHTML =
                    rows || `<tr><td colspan="5" class="text-center">No bookings found</td></tr>`;

            } catch (e) {
                console.error(e);
                alert('Failed to load bookings');
            }
        }

    function logout() {
        axios.post('/api/v1/logout', {}, {
            headers: {
                Authorization: 'Bearer ' + token
            }
        }).then(() => {
            localStorage.removeItem('tenant_token');
            window.location.href = "/tenant/login";
        });
    }

    loadBookings();
    </script>
</body>
</html>