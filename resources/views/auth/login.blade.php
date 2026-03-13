@push('styles')
<style>
    .login-box {
        width: 400px;
        animation: fadeInDown 0.8s ease-out;
    }
    .glass-card {
        background: rgba(15, 23, 42, 0.8) !important; /* Darker slate background */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        color: #f8fafc; /* High contrast off-white */
    }
    .login-logo a {
        color: #ffffff !important;
        text-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        font-weight: 800;
        letter-spacing: 3px;
    }
    .login-logo b { color: #fbbf24; } /* Amber/Gold accent */
    
    .form-control {
        background: rgba(30, 41, 59, 0.7) !important;
        border: 1px solid rgba(71, 85, 105, 0.5) !important;
        color: #f1f5f9 !important;
        border-radius: 12px !important;
        padding: 28px 18px !important;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background: rgba(30, 41, 59, 0.9) !important;
        border-color: #fbbf24 !important;
        box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.2) !important;
    }
    .form-control::placeholder { color: #94a3b8; }
    .input-group-text {
        background: rgba(30, 41, 59, 0.7) !important;
        border: 1px solid rgba(71, 85, 105, 0.5) !important;
        border-left: none !important;
        color: #fbbf24 !important;
        border-radius: 0 12px 12px 0 !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        color: #1e1b4b !important; /* Dark text for light button */
        border: none !important;
        border-radius: 12px !important;
        padding: 14px !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(251, 191, 36, 0.5);
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .password-toggle {
        cursor: pointer;
        transition: color 0.2s;
    }
    .password-toggle:hover { color: #f39c12; }
</style>
@endpush

<x-guest-layout>
    <div class="login-box">
        <div class="login-logo mb-4">
            <a href="/"><b>APMS</b> ASHAR PARFUM</a>
        </div>
        
        <div class="card glass-card">
            <div class="card-body login-card-body bg-transparent">
                <h4 class="text-center mb-4 font-weight-bold">Selamat Datang</h4>
                <p class="login-box-msg text-white-50">Silakan login untuk masuk ke dashboard sistem</p>

                @if($errors->any())
                    <div class="alert alert-danger border-0 bg-danger-gradient" style="border-radius: 10px; background: rgba(220, 53, 69, 0.2);">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-4">
                        <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group mb-4">
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye password-toggle" onclick="togglePassword()"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row align-items-center mb-3">
                        <div class="col-7">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember" class="text-white-50 small">Ingat Saya</label>
                            </div>
                        </div>
                        <div class="col-5 text-right">
                            <a href="{{ route('password.request') }}" class="text-warning small">Lupa password?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-sign-in-alt mr-2"></i> MASUK SEKARANG
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-white-50">Powered by APMS v2.0</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.querySelector('.password-toggle');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>