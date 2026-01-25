<?= view('front/layouts/header', compact('title', 'metaTitle', 'metaDescription', 'metaKeywords', 'canonicalUrl', 'ogType', 'ogUrl', 'ogTitle', 'ogDescription', 'ogImage', 'twitterUrl', 'twitterTitle', 'twitterDescription', 'twitterImage')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<style>
    .test-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }
    .test-content {
        padding: 60px 0;
    }
    .test-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    .status-warning {
        background: #fff3cd;
        color: #856404;
    }
    .meta-tag {
        background: #f7fafc;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 10px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        word-break: break-all;
    }
</style>

<!-- Hero Section -->
<section class="test-hero">
    <div class="container">
        <h1>🧪 Test Open Graph & Facebook Sharing</h1>
        <p class="lead mt-3">Cette page teste les balises Open Graph pour le partage sur les réseaux sociaux</p>
    </div>
</section>

<!-- Test Content -->
<section class="test-content">
    <div class="container">
        
        <!-- Status Card -->
        <div class="test-card">
            <h2>📊 Status</h2>
            <p><span class="status-badge status-success">✅ Balises Open Graph activées</span></p>
            <p class="text-muted mt-3">Faites "Afficher le code source" de cette page pour voir les balises <code>&lt;meta property="og:..."&gt;</code> dans le <code>&lt;head&gt;</code></p>
        </div>

        <!-- Meta Tags Card -->
        <div class="test-card">
            <h2>🏷️ Balises Open Graph de cette page</h2>
            
            <div class="meta-tag">
                <strong>og:type:</strong> <?= esc($ogType) ?>
            </div>
            
            <div class="meta-tag">
                <strong>og:url:</strong> <?= esc($ogUrl) ?>
            </div>
            
            <div class="meta-tag">
                <strong>og:title:</strong> <?= esc($ogTitle) ?>
            </div>
            
            <div class="meta-tag">
                <strong>og:description:</strong> <?= esc($ogDescription) ?>
            </div>
            
            <div class="meta-tag">
                <strong>og:image:</strong> <?= esc($ogImage) ?>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="test-card">
            <h2>🔍 Comment tester</h2>
            <ol class="mt-3">
                <li class="mb-3">
                    <strong>Vérifier que Facebook peut accéder :</strong>
                    <br>
                    <a href="https://developers.facebook.com/tools/debug/" target="_blank" class="btn btn-primary mt-2">
                        <i class="bi bi-facebook"></i> Facebook Sharing Debugger
                    </a>
                    <br>
                    <small class="text-muted">Colle cette URL : <code><?= current_url() ?></code></small>
                </li>
                
                <li class="mb-3">
                    <strong>Vérifier le User Agent :</strong>
                    <br>
                    <a href="<?= base_url('social-bot-test') ?>" target="_blank" class="btn btn-secondary mt-2">
                        <i class="bi bi-bug"></i> Tester le User Agent
                    </a>
                </li>
                
                <li class="mb-3">
                    <strong>Tester un vrai jeu :</strong>
                    <br>
                    <a href="<?= base_url('games/1') ?>" class="btn btn-success mt-2">
                        <i class="bi bi-controller"></i> Voir le jeu "Mala Toeunsa"
                    </a>
                </li>
            </ol>
        </div>

        <!-- Troubleshooting Card -->
        <div class="test-card">
            <h2>⚠️ Si Facebook retourne une erreur 403</h2>
            <ul class="mt-3">
                <li>✅ Le fichier <code>robots.txt</code> autorise maintenant <code>facebookexternalhit</code></li>
                <li>✅ Le filtre CodeIgniter autorise les bots sociaux</li>
                <li>✅ Le fichier <code>.htaccess</code> a été mis à jour</li>
                <li>⚠️ Vérifie les paramètres de ton hébergeur (firewall, mod_security, restrictions IP)</li>
                <li>⚠️ Contacte ton hébergeur si le problème persiste</li>
            </ul>
        </div>

    </div>
</section>

<?= view('front/layouts/footer') ?>
