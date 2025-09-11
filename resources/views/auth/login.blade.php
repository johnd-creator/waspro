<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            min-height: 600px;
            display: flex;
        }
        
        .login-left {
            flex: 0.9;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 50px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .login-right {
            flex: 1.1;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .brand-logo {
             font-size: 2.2rem;
             font-weight: 700;
             margin-bottom: 1.2rem;
             position: relative;
             z-index: 1;
         }
         
         .logo-container {
             display: flex;
             align-items: center;
             justify-content: center;
         }
         
         .logo-image {
             max-height: 80px;
             max-width: 200px;
             object-fit: contain;
         }
         
         .logo-placeholder {
             display: flex;
             align-items: center;
             font-size: 2.2rem;
             font-weight: 700;
             gap: 0.5rem;
         }
         
         /* Show logo image when available, hide placeholder */
         .logo-image:not([src="/path/to/your/logo.png"]) {
             display: block !important;
         }
         
         .logo-image:not([src="/path/to/your/logo.png"]) + .logo-placeholder {
             display: none;
         }
        
        .brand-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1.8rem;
            position: relative;
            z-index: 1;
            line-height: 1.4;
            text-align: center;
        }
        
        .feature-list {
            list-style: none;
            position: relative;
            z-index: 1;
            margin-top: 0.5rem;
        }
        
        .feature-list li {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .feature-list i {
            margin-right: 0.75rem;
            color: #3498db;
            width: 16px;
            text-align: center;
        }
        
        .login-form h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .login-form .subtitle {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating > label {
            color: #6c757d;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .form-check {
            margin-bottom: 1.5rem;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #764ba2;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
                min-height: auto;
            }
            
            .login-left {
                flex: 1;
                padding: 35px 25px;
                text-align: center;
            }
            
            .login-right {
                flex: 1;
                padding: 35px 25px;
            }
            
            .brand-logo {
                font-size: 1.8rem;
            }
            
            .logo-placeholder {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
             <div class="brand-logo">
                 <!-- Logo placeholder - replace with your actual logo -->
                 <div class="logo-container">

                     <img src="/path/to/your/logo.png" alt="WASPRO Logo" class="logo-image" style="display: none;">
                     <div class="logo-placeholder">
                         <i class="fas fa-recycle"></i> WASPRO
                     </div>
                 </div>
             </div>
            <div class="brand-subtitle">
                Sistem Manajemen Pengelolaan Limbah
            </div>
            <ul class="feature-list">
                <li>
                    <i class="fas fa-shield-alt"></i>
                    <span>Keamanan Data Terjamin</span>
                </li>
                <li>
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard Analytics</span>
                </li>
                <li>
                    <i class="fas fa-users"></i>
                    <span>Multi-User Management</span>
                </li>
                <li>
                    <i class="fas fa-mobile-alt"></i>
                    <span>Responsive Design</span>
                </li>
            </ul>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-form">
                <div class="logo-container mb-2 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image" style="width: 100%; height: auto; margin: 0 auto 10px auto; display: block; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
                <div class="welcome-section mb-3 text-center">
                    <h2 class="mb-2">Selamat Datang</h2>
                    <p class="subtitle text-muted">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating">
                        <input type="email" 
                               class="form-control @error('email_address') is-invalid @enderror" 
                               id="email_address" 
                               name="email_address" 
                               placeholder="name@example.com"
                               value="{{ old('email_address') }}" 
                               required 
                               autocomplete="email" 
                               autofocus>
                        <label for="email_address">
                            <i class="fas fa-envelope me-2"></i>Alamat Email
                        </label>
                        @error('email_address')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required 
                               autocomplete="current-password">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        @error('password')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>

                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
