<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #F8F9FA;
            color: #191414;
        }
        /* Sidebar Custom Styling */
        .sidebar {
            background-color: #FFFFFF;
            border-right: 1px solid #DEE2E6;
            height: 100vh;
            position: fixed;
            width: 240px;
        }
        .nav-link {
            color: #6C757D;
            font-weight: 500;
            transition: 0.3s;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .nav-link:hover, .nav-link.active {
            color: #1DB954;
            background-color: #F0FFF4;
        }
        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 40px;
            padding-bottom: 120px;
        }
        .greeting-card {
            background: linear-gradient(135deg, #1DB954 0%, #191414 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(29, 185, 84, 0.2);
        }
        /* Music Cards */
        .music-card {
            background: #FFFFFF;
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .music-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.08);
        }
        .music-card img {
            border-radius: 8px;
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            margin-bottom: 15px;
        }
        /* Player Bar */
        .player-bar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid #DEE2E6;
            padding: 15px 30px;
            z-index: 1000;
        }
        .play-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #1DB954;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: 0.2s;
        }
        .play-btn:hover {
            transform: scale(1.1);
            background-color: #1ed760;
        }
        .progress { height: 5px; cursor: pointer; }
    </style>
</head>
<body>

    <div class="sidebar d-none d-lg-block p-4">
        <h3 class="fw-bold mb-5" style="color: #1DB954;">MusicHub</h3>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link active">🏠 Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link">🔍 Search</a></li>
            <li class="nav-item"><a href="#" class="nav-link">📚 Your Library</a></li>
        </ul>
        <hr class="my-4">
        <div class="small fw-bold text-uppercase text-muted mb-3">Playlists</div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link">✨ My Top Mix</a></li>
            <li class="nav-item"><a href="#" class="nav-link">☕ Coffee & Jazz</a></li>
            <li class="nav-item"><a href="#" class="nav-link">🏃 Cardio Blast</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="greeting-card">
            <h1 class="display-5 fw-bold">สวัสดีตอนเช้า</h1>
            <p class="lead">เริ่มต้นวันใหม่ด้วยเพลย์ลิสต์ที่คุณชื่นชอบ</p>
            <button class="btn btn-light rounded-pill px-4 mt-2 fw-bold">ฟังตอนนี้</button>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="h3 fw-bold m-0">Made For You</h2>
            <a href="#" class="text-decoration-none text-muted small fw-bold">ดูทั้งหมด</a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            <div class="col">
                <div class="music-card h-100">
                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=300&h=300&fit=crop" alt="Mix 1">
                    <h6 class="fw-bold mb-1">Daily Mix 1</h6>
                    <p class="text-muted small">Taylor Swift, Ariana Grande...</p>
                </div>
            </div>
            <div class="col">
                <div class="music-card h-100">
                    <img src="https://images.unsplash.com/photo-1493225255756-d9584f8606e9?w=300&h=300&fit=crop" alt="Mix 2">
                    <h6 class="fw-bold mb-1">Smooth Jazz</h6>
                    <p class="text-muted small">Relaxes your mind with jazz...</p>
                </div>
            </div>
            <div class="col">
                <div class="music-card h-100">
                    <img src="https://images.unsplash.com/photo-1459749411177-042180ce673c?w=300&h=300&fit=crop" alt="Mix 3">
                    <h6 class="fw-bold mb-1">Rock Classics</h6>
                    <p class="text-muted small">The best of 80s and 90s rock.</p>
                </div>
            </div>
            <div class="col">
                <div class="music-card h-100">
                    <img src="https://images.unsplash.com/photo-1514525253361-bee8718a74a2?w=300&h=300&fit=crop" alt="Mix 4">
                    <h6 class="fw-bold mb-1">Pop Rising</h6>
                    <p class="text-muted small">Catch up with the new hits.</p>
                </div>
            </div>
            <div class="col">
                <div class="music-card h-100">
                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300&h=300&fit=crop" alt="Mix 5">
                    <h6 class="fw-bold mb-1">Focus Flow</h6>
                    <p class="text-muted small">Lo-fi beats for working.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="player-bar d-flex align-items-center justify-content-between shadow-lg">
        <div class="d-flex align-items-center" style="width: 30%;">
            <img src="https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?w=50&h=50&fit=crop" class="rounded me-3 shadow-sm" alt="current">
            <div>
                <div class="fw-bold small">Midnight City</div>
                <div class="text-muted" style="font-size: 12px;">M83</div>
            </div>
        </div>

        <div class="d-flex flex-column align-items-center" style="width: 40%;">
            <div class="d-flex gap-4 align-items-center mb-2">
                <span class="text-muted cursor-pointer">⏮</span>
                <button class="play-btn shadow">▶</button>
                <span class="text-muted cursor-pointer">⏭</span>
            </div>
            <div class="d-flex align-items-center w-100 gap-2">
                <span class="text-muted small">1:24</span>
                <div class="progress flex-grow-1">
                    <div class="progress-bar bg-success" style="width: 45%"></div>
                </div>
                <span class="text-muted small">3:45</span>
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center gap-3" style="width: 30%;">
            <span class="small text-muted">🔊</span>
            <input type="range" class="form-range w-25">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>