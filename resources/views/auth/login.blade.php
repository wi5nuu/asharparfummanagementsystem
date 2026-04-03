@push('styles')
<style>
    :root {
        --apms-primary: #3b82f6;
        --apms-primary-dark: #2563eb;
        --apms-slate: #1e293b;
    }

    .login-container {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    /* Left Side: Branding */
    .login-branding {
        flex: 1; /* Exactly 50% */
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .branding-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 500px;
    }

    .branding-circle-1 {
        position: absolute;
        width: 700px;
        height: 700px;
        border: 70px solid rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -150px;
        left: -150px;
    }

    .branding-circle-2 {
        position: absolute;
        width: 500px;
        height: 500px;
        border: 50px solid rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        bottom: -100px;
        right: -100px;
    }

    .branding-title {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 25px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    /* Right Side: Form */
    .login-form-section {
        flex: 1; /* Exactly 50% */
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; /* Center the form content */
        padding: 60px;
        box-shadow: -15px 0 40px rgba(0,0,0,0.05);
    }

    .form-wrapper {
        width: 100%;
        max-width: 380px; /* Capped width for better focus */
    }

    .form-header h2 {
        font-weight: 700;
        color: var(--apms-slate);
        margin-bottom: 5px;
    }

    .form-header p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 35px;
    }

    .apms-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        display: block;
    }

    .apms-input-group {
        position: relative;
        margin-bottom: 24px;
    }

    .apms-input-group i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 5;
    }

    .apms-input {
        width: 100%;
        padding: 12px 15px;
        padding-right: 45px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s;
        outline: none;
    }

    .apms-input:focus {
        border-color: var(--apms-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-utility {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        font-size: 0.85rem;
    }

    .btn-login {
        background: var(--apms-primary);
        color: white;
        border: none;
        display: block;
        margin: 0 auto;
        width: 160px; /* Reduced width */
        padding: 10px; /* Reduced padding */
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem; /* Slightly smaller text */
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-login:hover {
        background: var(--apms-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .policy-text {
        font-size: 0.75rem;
        color: #94a3b8;
        text-align: center;
        margin-top: 15px;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .login-page {
            align-items: center; /* Center vertically on mobile */
            overflow-y: hidden; /* Prevent scroll if possible */
        }
        .login-container {
            flex-direction: column;
            height: 100vh;
        }
        .login-branding {
            height: 20vh; /* Shorter branding section */
            flex: none;
            padding: 15px;
            justify-content: center;
        }
        .branding-title { font-size: 1.4rem; margin-bottom: 0; }
        .branding-circle-1, .branding-circle-2, .opacity-75 { display: none; }
        
        .login-form-section {
            flex: 1;
            width: 100%;
            padding: 25px 20px; /* Tighter padding */
            border-radius: 25px 25px 0 0;
            margin-top: -20px;
            z-index: 10;
            justify-content: flex-start; /* Start from top to fit everything */
        }
        .form-header h2 { font-size: 1.5rem; margin-bottom: 2px; }
        .form-header p { font-size: 0.85rem; margin-bottom: 15px; }
        .apms-input-group { margin-bottom: 10px; }
        .form-utility { margin-bottom: 15px; }
        .policy-text { margin-top: 12px; font-size: 0.7rem; }
        .btn-login { padding: 12px; }
    }
</style>

<x-guest-layout>
    <div class="login-container">
        <!-- Brand Section -->
        <div class="login-branding">
            <div class="branding-circle-1"></div>
            <div class="branding-circle-2"></div>
            <div class="branding-content">
                <h1 class="branding-title">Welcome to<br>APMS ASHAR PARFUM</h1>
                <p class="opacity-75">Sistem Manajemen Inventori & Penjualan Parfum Otomatis.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="login-form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Login</h2>
                    <p>Selamat datang kak</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small border-0 mb-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> Email atau Password salah.
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post" id="loginForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="apms-label">User Email</label>
                        <div class="apms-input-group">
                            <input type="email" name="email" class="apms-input" placeholder="email@gmail.com" value="{{ old('email') }}" required autofocus>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="apms-label">Password</label>
                        <div class="apms-input-group">
                            <input type="password" name="password" id="passwordInput" class="apms-input" placeholder="••••••••" required>
                            <i class="fas fa-eye-slash" id="toggleIcon" style="cursor: pointer;" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="form-utility">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label font-weight-normal text-muted" for="remember">Remember Me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-primary font-weight-bold">Forget Password?</a>
                    </div>

                    <button type="submit" class="btn-login shadow-sm">
                        Login
                    </button>

                    <p class="policy-text">
                        Dengan masuk, Anda menyetujui <a href="#" class="text-primary font-weight-bold">Ketentuan Layanan</a> <br> dan <a href="#" class="text-primary font-weight-bold">Kebijakan Privasi</a> kami.
                    </p>
                </form>

                <div class="text-center mt-5">
                    <small class="text-muted">Powered by APMS v2.0</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</x-guest-layout>