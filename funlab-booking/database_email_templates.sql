-- Table pour les templates d'emails
CREATE TABLE IF NOT EXISTS email_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL COMMENT 'Nom technique du template',
    subject VARCHAR(255) NOT NULL COMMENT 'Sujet de l\'email',
    description TEXT COMMENT 'Description du template',
    body TEXT NOT NULL COMMENT 'Corps HTML du template',
    variables TEXT COMMENT 'Variables disponibles (JSON)',
    isActive TINYINT(1) DEFAULT 1,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Templates par défaut
INSERT INTO email_templates (name, subject, description, body, variables) VALUES
(
    'booking_confirmation',
    'Confirmation de réservation - {{reference}}',
    'Email envoyé après une réservation confirmée',
    '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .info-box { background: white; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 FunLab Booking</h1>
            <h2>Réservation Confirmée !</h2>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{customerName}}</strong>,</p>
            <p>Nous avons le plaisir de confirmer votre réservation :</p>
            
            <div class="info-box">
                <h3>📋 Détails de votre réservation</h3>
                <p><strong>Référence :</strong> {{reference}}</p>
                <p><strong>Jeu :</strong> {{gameName}}</p>
                <p><strong>Date :</strong> {{bookingDate}}</p>
                <p><strong>Heure :</strong> {{bookingTime}}</p>
                <p><strong>Nombre de joueurs :</strong> {{numberOfPlayers}}</p>
                <p><strong>Montant :</strong> {{totalAmount}} TND</p>
            </div>
            
            <center>
                <a href="{{qrCodeLink}}" class="button">📱 Télécharger mon billet</a>
            </center>
            
            <div class="info-box">
                <h3>📍 Informations pratiques</h3>
                <p><strong>Adresse :</strong> {{address}}</p>
                <p><strong>Arrivée :</strong> Merci d''arriver 10 minutes avant l''heure prévue</p>
                <p><strong>Contact :</strong> {{phone}}</p>
            </div>
            
            <p>À très bientôt chez FunLab ! 🎉</p>
        </div>
        <div class="footer">
            <p>FunLab Booking - {{siteName}}</p>
            <p>{{siteUrl}}</p>
        </div>
    </div>
</body>
</html>',
    '["customerName", "reference", "gameName", "bookingDate", "bookingTime", "numberOfPlayers", "totalAmount", "qrCodeLink", "address", "phone", "siteName", "siteUrl"]'
),
(
    'verification_code',
    'Code de vérification - {{siteName}}',
    'Email avec code de vérification à 6 chiffres',
    '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; text-align: center; }
        .code { font-size: 36px; font-weight: bold; letter-spacing: 10px; color: #667eea; background: white; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Vérification de compte</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{customerName}}</strong>,</p>
            <p>Votre code de vérification est :</p>
            <div class="code">{{verificationCode}}</div>
            <p>Ce code est valide pendant <strong>15 minutes</strong>.</p>
            <p style="color: #999; font-size: 12px;">Si vous n''avez pas demandé ce code, ignorez cet email.</p>
        </div>
        <div class="footer">
            <p>{{siteName}}</p>
        </div>
    </div>
</body>
</html>',
    '["customerName", "verificationCode", "siteName"]'
),
(
    'booking_reminder',
    'Rappel - Votre réservation demain chez FunLab',
    'Rappel envoyé 24h avant la réservation',
    '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; }
        .alert { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Rappel de réservation</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{customerName}}</strong>,</p>
            <div class="alert">
                <strong>⚠️ Votre réservation est prévue demain !</strong>
            </div>
            <p><strong>Jeu :</strong> {{gameName}}</p>
            <p><strong>Date :</strong> {{bookingDate}}</p>
            <p><strong>Heure :</strong> {{bookingTime}}</p>
            <p><strong>Référence :</strong> {{reference}}</p>
            
            <p>N''oubliez pas d''arriver <strong>10 minutes avant</strong> pour le briefing !</p>
            
            <center>
                <a href="{{qrCodeLink}}" class="button">Voir mon billet</a>
            </center>
            
            <p>À demain ! 🎉</p>
        </div>
        <div class="footer">
            <p>{{siteName}} - {{siteUrl}}</p>
        </div>
    </div>
</body>
</html>',
    '["customerName", "gameName", "bookingDate", "bookingTime", "reference", "qrCodeLink", "siteName", "siteUrl"]'
),
(
    'booking_cancellation',
    'Annulation de réservation - {{reference}}',
    'Email envoyé lors de l''annulation d''une réservation',
    '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; }
        .info-box { background: white; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❌ Réservation annulée</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{customerName}}</strong>,</p>
            <p>Votre réservation a été annulée avec succès.</p>
            
            <div class="info-box">
                <p><strong>Référence :</strong> {{reference}}</p>
                <p><strong>Jeu :</strong> {{gameName}}</p>
                <p><strong>Date :</strong> {{bookingDate}}</p>
                <p><strong>Montant remboursé :</strong> {{refundAmount}} TND</p>
            </div>
            
            <p>Le remboursement sera effectué sous 5-10 jours ouvrés sur votre moyen de paiement.</p>
            
            <center>
                <a href="{{siteUrl}}" class="button">Faire une nouvelle réservation</a>
            </center>
        </div>
        <div class="footer">
            <p>{{siteName}} - {{siteUrl}}</p>
        </div>
    </div>
</body>
</html>',
    '["customerName", "reference", "gameName", "bookingDate", "refundAmount", "siteName", "siteUrl"]'
);
