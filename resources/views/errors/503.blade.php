<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Sedang Dalam Pemeliharaan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .maintenance-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }

        .maintenance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .maintenance-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .maintenance-icon svg {
            width: 60px;
            height: 60px;
            fill: white;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .message {
            font-size: 1rem;
            color: #718096;
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .feature {
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .feature h3 {
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .feature p {
            color: #718096;
            font-size: 0.9rem;
        }

        .contact-info {
            background: #edf2f7;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
        }

        .contact-info h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .contact-info p {
            color: #4a5568;
            margin-bottom: 8px;
        }

        .refresh-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .subtitle {
                font-size: 1.1rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/>
            </svg>
        </div>
        
        <h1>Aplikasi Sedang Dalam Pemeliharaan</h1>
        
        <p class="subtitle">
            Kami sedang melakukan pemeliharaan sistem untuk meningkatkan kualitas layanan
        </p>
        
        <p class="message">
            Mohon maaf atas ketidaknyamanan ini. Tim teknis kami sedang bekerja untuk memastikan aplikasi berjalan dengan optimal. Silakan coba lagi dalam beberapa saat.
        </p>
        
        <div class="features">
            <div class="feature">
                <h3>🔧 Pemeliharaan Rutin</h3>
                <p>Kami melakukan update sistem dan perbaikan untuk performa yang lebih baik</p>
            </div>
            <div class="feature">
                <h3>🛡️ Keamanan Data</h3>
                <p>Data Anda tetap aman selama proses pemeliharaan berlangsung</p>
            </div>
            <div class="feature">
                <h3>⚡ Peningkatan Performa</h3>
                <p>Setelah pemeliharaan, aplikasi akan berjalan lebih cepat dan stabil</p>
            </div>
        </div>
        
        <div class="contact-info">
            <h3>📞 Butuh Bantuan?</h3>
            <p><strong>Tim Support:</strong> ubohbsr.sis@plnindonesiapower.co.id</p>
            <p><strong>Whatsapp:</strong> 081293577787</p>
            <p><strong>Jam Operasional:</strong> Senin - Jumat, 08:00 - 17:00 WIB</p>
        </div>
        
        <button class="refresh-btn" onclick="window.location.reload()">
            🔄 Muat Ulang Halaman
        </button>
        
        <script>
            // Auto refresh setiap 30 detik
            setTimeout(function() {
                window.location.reload();
            }, 30000);
            
            // Tampilkan waktu terakhir refresh
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            console.log('Halaman dimuat pada: ' + timeString);
        </script>
    </div>
</body>
</html>