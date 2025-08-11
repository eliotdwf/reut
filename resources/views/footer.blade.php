<style>
    .site-footer {
        color: #000;
        border-top: 1px solid #ddd;
        padding: 40px 20px;
        font-family: sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: auto;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .footer-columns {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 20px;
    }

    .footer-column {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        text-align: center;
        min-width: 200px;
    }

    .footer-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #ff66b2;
        text-decoration: none;
    }

    a.footer-title:hover {
        text-decoration: underline;
    }

    .footer-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #000;
        text-decoration: none;
    }

    .footer-link:hover {
        color: #ff66b2;
        text-decoration: underline;
    }

    .footer-icon {
        vertical-align: middle;
    }

    .footer-note {
        text-align: center;
        font-size: 0.9rem;
        color: #424242;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-columns">
            <div class="footer-column">
                <h3>
                    <a href="https://assos.utc.fr/assos/bde" target="_blank" rel="noopener noreferrer" class="footer-title">Le BDE</a>
                </h3>
                <div class="footer-links">
                    <a href="mailto:bde@assos.utc.fr" class="footer-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="footer-icon" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                        <span>bde@assos.utc.fr</span>
                    </a>
                    <a href="https://www.facebook.com/roger.delacom.5" class="footer-link">
                        <i class="bi bi-facebook"></i>
                        <span>Roger Delacom</span>
                    </a>
                    <a href="https://www.instagram.com/bde_utc/" class="footer-link">
                        <i class="bi bi-instagram"></i>
                        <span>bde_utc</span>
                    </a>
                    <a href="https://maps.app.goo.gl/Y9sov9nAjvyhSEd79" class="footer-link">
                        <i class="bi bi-geo-alt"></i>
                        <span>Salle BF E101</span>
                    </a>
                </div>
            </div>

            <div class="footer-column">
                <h3>
                    <a href="https://assos.utc.fr/simde/" target="_blank" rel="noopener noreferrer" class="footer-title">Le SiMDE</a>
                </h3>
                <div class="footer-links">
                    <a href="mailto:simde@assos.utc.fr" class="footer-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="footer-icon" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                        <span>simde@assos.utc.fr</span>
                    </a>
                    <a href="https://www.facebook.com/SiMDE.BDE.UTC" class="footer-link">
                        <i class="bi bi-facebook"></i>
                        <span>Jessy Mde</span>
                    </a>
                    <a href="https://www.instagram.com/simde_utc/" class="footer-link">
                        <i class="bi bi-instagram"></i>
                        <span>simde_utc</span>
                    </a>
                    <a href="https://assos.utc.fr/simde" class="footer-link">
                        <i class="bi bi-globe"></i>
                        <span>Site du SiMDE</span>
                    </a>
                </div>
            </div>

            <div class="footer-column">
                <h3 class="footer-title">RéUT</h3>
                <div class="footer-links">
                    <a href="{{\App\Filament\Pages\Calendar::getUrl(panel: 'main')}}" class="footer-link">
                        <i class="bi bi-calendar4-week"></i>
                        <span>Calendrier</span>
                    </a>
                    <a href="/mentions-legales" class="footer-link">
                        <i class="bi bi-list-check"></i>
                        <span>Mentions Légales</span>
                    </a>
                    <a href="/politique-confidentialite" class="footer-link">
                        <i class="bi bi-shield-check"></i>
                        <span>Politiques de confidentialité</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-note">
            <p>RéUT est développé et maintenu par le SiMDE (Service informatique de la Maison des étudiants).</p>
            <p>© 2025 Bureau Des Étudiants de l'UTC</p>
        </div>
    </div>
</footer>
