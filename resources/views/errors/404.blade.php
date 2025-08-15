<x-layouts.app>
    <div class="p-4 flex flex-col gap-y-5 rounded-md bg-grey-200 dark:bg-grey-800 text-grey-950 dark:text-grey-50">
        <div class="flex justify-evenly items-center flex-col md:flex-row">
            <div id="light-error-robot" class="w-full h-full flex justify-center">
                <div class="flex justify-center items-center w-full flex-col">
                    <img src="{{ asset('images/404-robot-error-pink.png') }}" alt="Dessin erreur 404"/>
                    <p class="text-center"> Il semblerait que la page que tu recherches n'existe pas !
                        <br/>
                        Merci de contacter le <a href="mailto:simde@assos.utc.fr" class="text-pink-500 hover:underline">SiMDE</a>
                        si tu penses qu'il s'agit d'une erreur.
                    </p>
                    <div class="mt-4">
                        <a href="/"
                           class="focus:ring-2 focus:outline-none font-medium rounded-lg text-sm px-2.5 py-2 text-center inline-flex items-center text-grey-950 bg-pink-400 hover:brightness-90 focus:ring-pink-500 dark:bg-pink-400 dark:focus:ring-pink-300">
                            <span>Retour à l'accueil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
