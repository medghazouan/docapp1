@extends('layouts.auth')

@section('content')
<div id="container">
    <div class="row">
        <div class="col-md-6 branding-section">
            <!-- Logo Placeholder -->
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            </div>
            
            <h1 class="system-title">Système de Demande de Documents en Ligne</h1>
            <p class="system-subtitle">Rapide, facile et sécurisé - Le service documentaire de Menara Holding</p>
        </div>
        
        <div id="box" class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="login-card">
                <div class="card-header text-center">
                    <h2>Vérification d'ID</h2>
                    <div class="tab-underline"></div>
                </div>
                <div class="card-body">
                    <h4 class="text-center mb-3">ENTREZ VOTRE IDENTIFIANT</h4>
                    <p class="text-center text-muted mb-4">Un mot de passe temporaire vous sera envoyé par email</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('check.id') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="idUtilisateur" class="form-label">ID Utilisateur</label>
                            <input type="text" class="form-control" id="idUtilisateur" name="idUtilisateur" required>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-connect">Obtenir le mot de passe</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <hr class="divider">
                        <a href="{{ route('login') }}" class="forgot-password-link">
                            <i class="fas fa-key me-2"></i>J'ai deja le Mot de passe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Website | © Copyright 2025 Holding Menara | Système de Demande de Documents en Ligne</p>
    </div>
</div>

<style>
    .forgot-password-link {
    color: #b8a369;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-password-link:hover {
    color: #a89355;
    text-decoration: none;
}
/* Main Container */
#container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 0;
    margin: 0;
    max-width: 100%;
}

.row {
    margin: 0;
    flex-grow: 1;
}

/* Left Side - Branding Section */
.branding-section {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 2rem;
    background-image: url('{{ asset('images/home.png') }}');
    background-size: cover;
    background-position: center;
    position: relative;
    border-radius: 0 0 40px 0;
}

.branding-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 0;
}

.logo-container {
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
}

.logo {
    width: 300px;
    height: 300px;
}

.system-title {
    color: #b8a369;
    font-size: 28px;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.system-subtitle {
    color: #333;
    font-size: 16px;
    position: relative;
    z-index: 1;
}

/* Right Side - Login Form */
.login-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 100%;
    max-width: 450px;
    margin: 2rem;
}

.card-header {
    border-bottom: none;
    padding-bottom: 0;
    background-color: transparent;
}

.card-header h2 {
    color: #b8a369;
    font-weight: 500;
}

.tab-underline {
    width: 50px;
    height: 3px;
    background-color: #b8a369;
    margin: 10px auto;
}

h4 {
    color: #b8a369;
    font-weight: 500;
}

.form-label {
    color: #555;
    font-weight: 500;
}

.form-control {
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 5px;
}

.btn-connect {
    background-color: #b8a369;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 25px;
    font-weight: 500;
    text-transform: lowercase;
}

.btn-connect:hover {
    background-color: #a89355;
}

/* Footer */
.footer {
    text-align: center;
    padding: 1rem;
    font-size: 12px;
    color: #777;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }
    
    .branding-section {
        height: 40vh;
    }
    
    .logo {
        width: 100px;
        height: 100px;
    }
    
    .login-card {
        margin: 1rem auto;
    }
}
</style>
@endsection