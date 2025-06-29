<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">FitTrack</div>
            <div style="font-size: 0.8rem; color: #aaa;">Admin Panel</div>
        </div>
        <ul class="sidebar-menu">
            
            <li>
                <a href="{{ route('settings') }}">
                    <i class="material-icons">settings</i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons">logout</i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Dashboard</h1>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="user-avatar">
                    <i class="material-icons">person</i>
                </div>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <div class="card-title">Total Pengguna</div>
                <div class="card-value">{{ $totalUsers }}</div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="material-icons">fitness_center</i>
                </div>
                <div class="card-title">Program Latihan</div>
                <div class="card-value">{{ $totalPrograms }}</div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="material-icons">forum</i>
                </div>
                <div class="card-title">Posting Komunitas</div>
                <div class="card-value">{{ $totalCommunities }}</div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="material-icons">event_available</i>
                </div>
                <div class="card-title">Workout Hari Ini</div>
                <div class="card-value">{{ $workoutsToday }}</div>
            </div>
        </div>

        <div class="recent-section">
            <div class="section-header">
                <h2 class="section-title">Pengguna Terbaru</h2>
                <button class="view-all" onclick="alert('Fitur akan segera tersedia')">Lihat Semua</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Bergabung</th>
                        <th>No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>{{ $user->phone_number }} </td>
                     
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #aaa;">Tidak ada pengguna terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Programs -->
        <div class="recent-section">
            <div class="section-header">
                <h2 class="section-title">Program Latihan Terbaru</h2>
                <button class="view-all" onclick="alert('Fitur akan segera tersedia')">Lihat Semua</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Kategori</th>
                        <th>Tanggal Dibuat</th>
                        <th>Creator</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPrograms as $program)
                    <tr>
                        <td>{{ $program->nama_program }}</td>
                        <td>{{ $program->kategori_program ? 'Tools Weight' : 'Body Weight' }}</td>
                        <td>{{ $program->created_at->format('d M Y') }}</td>
                        <td>{{ $program->user->username ?? 'Admin' }}</td>
                       
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #aaa;">Tidak ada program terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Movements -->
        <div class="recent-section">
            <div class="section-header">
                <h2 class="section-title">Gerakan Terbaru</h2>
                <button class="view-all" onclick="alert('Fitur akan segera tersedia')">Lihat Semua</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Gerakan</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentGerakan as $gerakan)
                    <tr>
                        <td>{{ $gerakan->nama_gerakan }}</td>   
                        <td>{{ $gerakan->user->username }}</td>
                        <td>{{ $gerakan->created_at-> format ('d M Y')}}</td>
                     
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #aaa;">Tidak ada gerakan terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
</body>
</html>