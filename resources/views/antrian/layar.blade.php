<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Layar Antrian - Kantor Desa Selokromo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            50% { box-shadow: 0 0 0 20px rgba(34, 197, 94, 0); }
        }
        .blinking { animation: blink 1s ease-in-out infinite; }
        .slide-in { animation: slideIn 0.5s ease-out; }
        .pulse-effect { animation: pulse 1.5s infinite; }
        .antrian-card { transition: all 0.3s ease; }

        body {
            background: linear-gradient(135deg, #2d6a4f 0%, #1b4332 100%);
            min-height: 100vh;
            height: 100vh;
            overflow: hidden;
        }

        .layar-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }

        .queue-grid {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            min-height: 0;
        }

        .queue-list {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            max-height: 100%;
        }

        .queue-list::-webkit-scrollbar {
            width: 6px;
        }

        .queue-list::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .queue-list::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }

        .queue-list::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .header-title { font-size: 1.8rem; }
        .current-called { padding: 0.75rem; margin-bottom: 0.75rem; }
        .nomor-antrian { font-size: 4rem; }

        @media (min-width: 768px) {
            .header-title { font-size: 2.2rem; }
            .nomor-antrian { font-size: 5rem; }
        }

        @media (min-width: 1920px) {
            .header-title { font-size: 2.5rem; }
            .nomor-antrian { font-size: 6rem; }
        }

        @media print {
            .no-print { display: none !important; }
        }

        .btn-kembali {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 9999px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #2d6a4f;
            transition: all 0.2s ease;
            border: 1px solid rgba(45, 106, 79, 0.2);
        }

        .btn-kembali:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-sound {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 9999px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-sound-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-sound:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="font-sans">
    <div class="layar-container">
        <!-- Header -->
        <div class="text-center mb-3">
            <h1 class="header-title font-bold text-white mb-1">
                <i class="fas fa-ticket-alt"></i> LAYAR ANTRIAN
            </h1>
            <p class="text-white text-sm">Kantor Desa Selokromo</p>
            <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-3 py-0.5 mt-1">
                <span id="current-time" class="text-white font-semibold text-xs"></span>
            </div>
        </div>

        <!-- Current Queue -->
        <div class="current-called mb-3">
            <div class="bg-white rounded-xl shadow-xl p-3 text-center">
                <p class="text-gray-500 text-xs font-medium mb-1">SEDANG DILAYANI</p>
                <p class="nomor-antrian font-bold text-green-600 blinking" id="nomor-sekarang">-</p>
                <p class="text-sm text-gray-700 font-medium mt-1" id="nama-sekarang">-</p>
                <p class="text-xs text-gray-500" id="keperluan-sekarang">-</p>
                <div class="mt-2">
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-bell mr-1"></i> Silakan menuju loket
                    </span>
                </div>
            </div>
        </div>

        <!-- Grid Queue (2 kolom) -->
        <div class="queue-grid">
            <!-- Waiting Queue -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col">
                <div class="bg-yellow-500 text-white px-3 py-2">
                    <h2 class="font-bold text-sm">
                        <i class="fas fa-clock mr-1"></i> Antrian Menunggu
                    </h2>
                    <p class="text-xs opacity-90">Total <span id="total-menunggu">0</span> orang</p>
                </div>
                <div class="queue-list p-2" id="waiting-list">
                    <div class="text-center text-gray-500 py-4 text-xs">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p class="mt-1">Memuat data...</p>
                    </div>
                </div>
            </div>

            <!-- Completed Queue -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col">
                <div class="bg-blue-500 text-white px-3 py-2">
                    <h2 class="font-bold text-sm">
                        <i class="fas fa-check-circle mr-1"></i> Selesai Dilayani
                    </h2>
                </div>
                <div class="queue-list p-2" id="completed-list">
                    <div class="text-center text-gray-500 py-4 text-xs">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p class="mt-1">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer - Tanpa Tombol untuk Publik -->
        @if(request()->has('admin'))
        <!-- Footer dengan Tombol (Khusus Admin) -->
        <div class="mt-3 flex justify-between items-center">
            <div class="text-left">
                <button id="kembali-btn" class="btn-kembali inline-flex items-center no-print">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </button>
            </div>
            <div class="text-center text-white text-xs flex-1">
                <p>
                    <i class="fas fa-print"></i> Harap simpan nomor
                    &nbsp;|&nbsp;
                    <i class="fas fa-bell"></i> Perhatikan panggilan
                    &nbsp;|&nbsp;
                    <i class="fas fa-clock"></i> Update 2 detik
                </p>
                <p class="text-green-200 text-xs mt-1">
                    Kantor Desa Selokromo - Melayani dengan Hati
                </p>
            </div>
            <div class="text-right">
                <button id="enable-sound-btn" class="btn-sound inline-flex items-center no-print">
                    <i class="fas fa-volume-up mr-1"></i> Suara
                </button>
            </div>
        </div>
        @else
        <!-- Footer Tanpa Tombol (Untuk Publik) -->
        <div class="mt-3 text-center text-white text-xs">
            <p>
                <i class="fas fa-print"></i> Harap simpan nomor antrian
                &nbsp;|&nbsp;
                <i class="fas fa-bell"></i> Perhatikan panggilan dari loket
                &nbsp;|&nbsp;
                <i class="fas fa-clock"></i> Update otomatis setiap 2 detik
            </p>
            <p class="text-green-200 text-xs mt-1">
                Kantor Desa Selokromo - Melayani dengan Hati
            </p>
        </div>
        @endif
    </div>

    <script>
        let soundEnabled = false;
        let audioContext = null;
        let lastNomorAntrian = '';

        function enableSound() {
            try {
                if (window.AudioContext || window.webkitAudioContext) {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    audioContext = new AudioCtx();
                    const testOscillator = audioContext.createOscillator();
                    const testGain = audioContext.createGain();
                    testOscillator.connect(testGain);
                    testGain.connect(audioContext.destination);
                    testOscillator.frequency.value = 440;
                    testGain.gain.value = 0.1;
                    testOscillator.start();
                    testGain.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.5);
                    testOscillator.stop(audioContext.currentTime + 0.5);
                    soundEnabled = true;

                    const soundBtn = document.getElementById('enable-sound-btn');
                    if (soundBtn) {
                        soundBtn.classList.add('btn-sound-active');
                        soundBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Aktif';
                    }
                    console.log('Suara berhasil diaktifkan');
                } else {
                    soundEnabled = true;
                }
            } catch(e) {
                console.log('Error mengaktifkan suara:', e);
                soundEnabled = true;
            }
        }

        function playCallSound() {
            if (!soundEnabled) return;
            try {
                if (audioContext) {
                    if (audioContext.state === 'suspended') audioContext.resume();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    oscillator.frequency.value = 880;
                    gainNode.gain.value = 0.3;
                    oscillator.start();
                    gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 1);
                    oscillator.stop(audioContext.currentTime + 1);
                }
            } catch(e) { console.log('Sound error:', e); }
        }

        function loadData() {
            $.get('/api/antrian-layar', function(data) {
                if (data.antrian_sekarang) {
                    const nomorBaru = data.antrian_sekarang.nomor_antrian;
                    if (lastNomorAntrian !== nomorBaru && lastNomorAntrian !== '') {
                        $('#nomor-sekarang').addClass('pulse-effect');
                        playCallSound();
                        setTimeout(() => $('#nomor-sekarang').removeClass('pulse-effect'), 1500);
                    }
                    lastNomorAntrian = nomorBaru;
                    $('#nomor-sekarang').text(nomorBaru);
                    $('#nama-sekarang').text(data.antrian_sekarang.nama);
                    $('#keperluan-sekarang').text(data.antrian_sekarang.keperluan);
                } else {
                    $('#nomor-sekarang').text('-');
                    $('#nama-sekarang').text('Tidak ada antrian');
                    $('#keperluan-sekarang').text('-');
                }
                $('#total-menunggu').text(data.total_menunggu);

                let waitingHtml = '';
                if (data.antrian_menunggu.length > 0) {
                    data.antrian_menunggu.forEach((antrian, index) => {
                        waitingHtml += `<div class="antrian-card bg-gray-50 rounded-lg p-2 mb-2 border-l-4 border-yellow-500 slide-in">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-base font-bold text-yellow-600">${antrian.nomor_antrian}</span>
                                    <p class="text-xs text-gray-700 font-semibold">${antrian.nama}</p>
                                    <p class="text-xs text-gray-500">${antrian.keperluan}</p>
                                </div>
                                <div><span class="inline-block bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full text-xs">#${index + 1}</span></div>
                            </div>
                        </div>`;
                    });
                } else {
                    waitingHtml = `<div class="text-center text-gray-500 py-4"><i class="fas fa-inbox text-2xl"></i><p class="mt-1 text-xs">Tidak ada antrian</p></div>`;
                }
                $('#waiting-list').html(waitingHtml);

                let completedHtml = '';
                if (data.antrian_selesai.length > 0) {
                    data.antrian_selesai.forEach(antrian => {
                        completedHtml += `<div class="bg-green-50 rounded-lg p-1 mb-1 border-l-4 border-green-500">
                            <div class="flex justify-between items-center">
                                <div><span class="font-bold text-green-600 text-sm">${antrian.nomor_antrian}</span><span class="text-xs text-gray-600 ml-1">${antrian.nama}</span></div>
                                <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            </div>
                        </div>`;
                    });
                } else {
                    completedHtml = `<div class="text-center text-gray-500 py-4"><i class="fas fa-history text-2xl"></i><p class="mt-1 text-xs">Belum ada antrian selesai</p></div>`;
                }
                $('#completed-list').html(completedHtml);
            }).fail(function(error) { console.log('Error:', error); });
        }

        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            $('#current-time').text(now.toLocaleDateString('id-ID', options));
        }

        $(document).ready(function() {
            loadData();
            updateTime();
            setInterval(loadData, 2000);
            setInterval(updateTime, 1000);

            // Tombol kembali (hanya jika ada tombolnya)
            $('#kembali-btn').on('click', function() {
                window.history.back();
            });

            // Tombol suara (hanya jika ada tombolnya)
            $('#enable-sound-btn').on('click', function() {
                enableSound();
            });
        });
    </script>
</body>
</html>