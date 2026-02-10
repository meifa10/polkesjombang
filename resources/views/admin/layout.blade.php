<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Polkes Jombang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #1e7e34, #28a745);
            color: #fff;
            padding: 20px;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            font-weight: 600;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.2);
        }

        .logout-btn {
            margin-top: 30px;
            width: 100%;
            background: transparent;
            border: none;
            padding: 0;
        }

        .logout-btn button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(220,53,69,.15);
            color: #fff;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-btn button:hover {
            background: rgba(220,53,69,.35);
        }


        /* CONTENT */
        .content {
            flex: 1;
            padding: 25px;
        }

        .topbar {
            background: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            margin: 0;
            font-size: 18px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
        }
    </style>
</head>
<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>ADMIN POLKES</h3>

        <a href="{{ route('admin.dashboard') }}" class="active">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="#">
            <i class="fas fa-users"></i> Daftar Pasien
        </a>
        <a href="#">
            <i class="fas fa-stethoscope"></i> Pemeriksaan Pasien
        </a>
        <a href="#">
            <i class="fas fa-prescription"></i> Tulis Resep
        </a>
        <a href="#">
            <i class="fas fa-notes-medical"></i> Rekam Medis Digital
        </a>
        <a href="#">
            <i class="fas fa-cog"></i> Pengaturan
        </a>

        <form method="POST" action="{{ route('logout') }}" class="logout-btn">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>
