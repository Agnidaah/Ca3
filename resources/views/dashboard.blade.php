<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Container for the entire page -->
    <div class="page-container">
        <!-- Top navigation bar -->
        <div class="navbar">
            <h1>Grocery Inventory Management System</h1> <!-- Heading in the center -->
            <a href="/">Dashboard</a>
        </div>
        @include('template1')
    </div>
</body>
</html>
