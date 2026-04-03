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
        margin-bottom: 15px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    /* Right Side: Form */
    .login-form-section {
        flex: 1; /* Exactly 50% */
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px;
        box-shadow: -15px 0 40px rgba(0,0,0,0.05);
    }

    .form-wrapper {
        width: 100%;
        max-width: 380px;
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

    .btn-login {
        background: var(--apms-primary);
        color: white;
        border: none;
        display: block;
        margin: 0 auto;
        width: auto;
        min-width: 200px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-login:hover {
        background: var(--apms-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .login-page {
            align-items: center;
            overflow-y: hidden;
        }
        .login-container {
            flex-direction: column;
            height: 100vh;
        }
        .login-branding {
            height: 20vh;
            flex: none;
            padding: 15px;
            justify-content: center;
        }
        .branding-title { font-size: 1.4rem; margin-bottom: 0; }
        .branding-circle-1, .branding-circle-2, .opacity-75 { display: none; }
        
        .login-form-section {
            flex: 1;
            width: 100%;
            padding: 25px 20px;
            border-radius: 25px 25px 0 0;
            margin-top: -20px;
            z-index: 10;
            justify-content: flex-start;
        }
        .form-header h2 { font-size: 1.5rem; margin-bottom: 2px; }
        .form-header p { font-size: 0.85rem; margin-bottom: 15px; }
    }
</style>
@endpush

<x-guest-layout>
    <div class="login-container">
        <!-- Brand Section -->
        <div class="login-branding">
            <div class="branding-circle-1"></div>
            <div class="branding-circle-2"></div>
            <div class="branding-content">
                <h1 class="branding-title">Bantuan Password<br>APMS ASHAR PARFUM</h1>
                <p class="opacity-75">Jangan khawatir kak, mari kita pulihkan akses Anda.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="login-form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Lupa Password</h2>
                    <p>Masukkan email terdaftar kak, kami akan kirim link reset secepatnya.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-success font-weight-bold smaller" :status="session('status')" />

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small border-0 mb-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> Email tidak ditemukan atau salah.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="apms-label">Alamat Email</label>
                        <div class="apms-input-group">
                            <input type="email" name="email" class="apms-input" placeholder="email@gmail.com" value="{{ old('email') }}" required autofocus>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="mt-4 mb-4">
                        <button type="submit" class="btn-login shadow-sm">
                            Kirim Link Reset Kak
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-primary font-weight-bold smaller">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                        </a>
                    </div>
                </form>

                <div class="text-center mt-auto pt-4">
                    <small class="text-muted">Powered by APMS v2.0</small>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
