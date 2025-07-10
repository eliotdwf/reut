import React from "react";
import "bootstrap-icons/font/bootstrap-icons.css";

const Footer = () => {
  return (
    <footer className="bg-black dark:bg-gray-900 text-gray-950 dark:text-gray-50 border-t border-gray-200 dark:border-gray-700">
      <div className="m-auto max-w-6xl py-5 flex flex-col gap-5">
        <hr className="border-gray-200 dark:border-gray-700 mb-3" />

        <div className="flex flex-wrap justify-around items-start">
          {/* BDE */}
          <div className="flex flex-col items-center justify-start gap-4">
            <h3>
              <a href="https://assos.utc.fr/assos/bde" target="_blank" rel="noopener noreferrer" className="text-xl font-bold text-pink-1">Le BDE</a>
            </h3>
            <div className="flex flex-col gap-2">
              <a href="mailto:bde@assos.utc.fr" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-envelope" />
                <span>bde@assos.utc.fr</span>
              </a>
              <a href="https://www.facebook.com/roger.delacom.5" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-facebook" />
                <span>Roger Delacom</span>
              </a>
              <a href="https://www.instagram.com/bde_utc/" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-instagram" />
                <span>bde_utc</span>
              </a>
              <a href="https://maps.app.goo.gl/Y9sov9nAjvyhSEd79" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-geo-alt" />
                <span>Salle BF E101</span>
              </a>
            </div>
          </div>

          {/* SiMDE */}
          <div className="flex flex-col items-center justify-start gap-4">
            <h3>
              <a href="https://assos.utc.fr/simde/" target="_blank" rel="noopener noreferrer" className="text-xl font-bold text-pink-1">Le SiMDE</a>
            </h3>
            <div className="flex flex-col gap-2">
              <a href="mailto:simde@assos.utc.fr" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-envelope" />
                <span>simde@assos.utc.fr</span>
              </a>
              <a href="https://www.facebook.com/SiMDE.BDE.UTC" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-facebook" />
                <span>Jessy Mde</span>
              </a>
              <a href="https://www.instagram.com/simde_utc/" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-instagram" />
                <span>simde_utc</span>
              </a>
            </div>
          </div>

          {/* RéUT */}
          <div className="flex flex-col items-center justify-start gap-4">
            <h3 className="text-xl font-bold text-pink-1">RéUT</h3>
            <div className="flex flex-col gap-2">
              <a href="#" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-diagram-3" />
                <span>Sitemap</span>
              </a>
              <a href="#" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-list-check" />
                <span>Mentions Légales</span>
              </a>
              <a href="#" className="flex items-center gap-2 hover:text-purple-400 hover:underline">
                <i className="bi bi-shield-check" />
                <span>Politiques de confidentialité</span>
              </a>
            </div>
          </div>
        </div>

        <div className="text-center">
          <p>
            RéUT est développé et maintenu par le SiMDE (Service informatique de la Maison des étudiants).
          </p>
          <p>
            © 2025 Bureau Des Étudiants de l'UTC
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

