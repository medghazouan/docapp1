<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification de mot de passe - HoldingDocs</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap');
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            line-height: 1.6;
            color: #171717;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header img {
            max-height: 80px;
            margin-right: 20px;
        }
        .header-title {
            color: #171717;
            font-weight: 600;
            font-size: 24px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            color: #171717;
        }
        .temp-password {
            background-color: #f0f0f0;
            border: 2px solid #a49672;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #171717;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
            letter-spacing: 4px;
            user-select: all;
            cursor: text;
        }
        .login-btn {
            display: block;
            width: 200px;
            margin: 25px auto;
            padding: 12px;
            background-color: #a49672;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #8c7d5f;
        }
        .footer {
            background-color: #a49672;
            color: #171717;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
        .security-note {
            background-color: #f9f9f9;
            border-left: 4px solid #a49672;
            padding: 15px;
            margin-top: 20px;
            font-style: italic;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/menaraHoldingLogo.png') }}" alt="Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><rect width=\'40\' height=\'40\' fill=\'%23a49672\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23171717\'>MH</text></svg>'">
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            
            <p>Vous avez récemment demandé un mot de passe. Veuillez trouver ci-dessous votre mot de passe temporaire :</p>
            
            <div class="temp-password">{{ $tempPassword }}</div>
            
            <div class="security-note">
                Pour des raisons de sécurité, nous vous recommandons de ne partager pas ce mot de passe.
            </div>
            
            <p>Si vous n'avez pas demandé ce mot de passe !, veuillez contacter notre service support.</p>
            
            <p>Cordialement,<br>L'équipe Menara Holding</p>
            <a href="{{ route('login') }}" class="login-btn">Se connecter</a>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Menara Holding. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>