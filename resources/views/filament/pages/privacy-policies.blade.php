<x-filament-panels::page>
    <style>
        a {
            color: #ff66b2;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        ul {
            list-style-position: inside;
        }
    </style>
    <small>Date de la dernière mise à jour : 15/08/2025</small>

    <p>Le texte ci-dessous a pour but de décrire la politique de protection des données renseignées sur le site actuel
        (RéUT).</p>
    <div>
        <h2 class="font-bold text-l">Données collectées</h2>
        <p>Lorsque vous vous connectez à RéUT, vous devez passer par le portail d'authentification SiMDE (OAuth), qui
            utilise lui-même le portail d'authentification UTC (CAS). Cette authentification permet au site RéUT de
            collecter et stocker les données utilisateur suivantes :</p>
        <ul class="list-disc ml-6">
            <li>Nom et prénom</li>
            <li>Adresse Email de l’UTC (xxx@xxx.utc.fr)</li>
            <li>Liste des associations de la fédération BDE-UTC auxquelles l'utilisateur est inscrit (avec le rôle de
                l'utilisateur au sein de chaque association)
            </li>
            <li>Statut de cotisation au BDE-UTC (information non sauvegardée, uniquement traitée au moment de la connexion)</li>
        </ul>
        <p>Ces informations sont nécessaires pour permettre à un utilisateur de réserver une salle pour une association
            dont
            il est membre. Les rôles sont utilisés pour déterminer les permissions de l'utilisateur sur la site
            RéUT.</p>
        <p>Pour des raisons d'assurances, la cotisation au BDE-UTC est obligatoire pour pouvoir réserver une salle via RéUT.</p>
        <p>Les informations collectées à la connexion et les réservations de salles sont stockées au maximum un an.</p>
        <p>Afin de pouvoir supprimer automatiquement les utilisateurs inactifs depuis plus d'un an, la date de dernière connexion est également sauvegardée.</p>
        <p>Les informations de réservations de salles ne sont pas privées, elles sont visibles par les membres des
            Pôles, du
            BDE et du SiMDE qui gèrent le site RéUT.</p>
    </div>
    <div>
        <h3 class="italic"><i>Sous-traitants au sens du RGPD pour ce traitement</i></h3>
        <ul class="list-disc ml-6">
            <li>Président⸱e⸱s du SiMDE (voir le <a href="https://assos.utc.fr/assos/simde">trombinoscope du SiMDE</a>
                pour
                connaître le responsable actuel)
            </li>
        </ul>
    </div>

    <div>
        <h3 class="italic">SiMDE et administrateurs</h3>
        <p>L'ensemble des services est hébergé sur l'infrastructure du SiMDE, commission du BDE-UTC.</p>
        <p>Les administrateurs de RéUT sont des membres bénévoles du SiMDE. Pour consulter la liste des administrateurs,
            vous pouvez contacter le SiMDE à l’adresse <a href="mailto:simde@assos.utc.fr"
                                                          class="text-purple-400 hover:underline">simde@assos.utc.fr</a>
            ou
            consulter le <a href="https://assos.utc.fr/assos/simde">trombinoscope de l'association.</a></p>
        <p>Les administrateurs de RéUT ont un accès total aux données stockées.</p>
        <p>Pour des questions de maintenance et de sécurité, les administrateurs peuvent consulter et rectifier les
            données
            collectées.</p>
    </div>

    <p>Conformément à la loi informatique et libertés du 6 janvier 1978, et au Règlement Général sur la Protection
        des
        Données (RGPD), vous disposez d'un droit de consultation, de rectification et de suppression des
        informations
        vous concernant. Pour cela, vous pouvez contacter le SiMDE à l'adresse <a href="mailto:simde@assos.utc.fr"
                                                                                  class="text-purple-400 hover:underline">simde@assos.utc.fr</a>.
    </p>

    <div>
        <h2 class="font-bold text-l">Cookies</h2>
        <p>Ce site n'utilise pas de traceur. Les seuls cookies utilisés servent à maintenir la session en cours.</p>
    </div>

</x-filament-panels::page>
