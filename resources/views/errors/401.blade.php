<x-layouts.app>
    <div class="p-4 flex flex-col gap-y-5 rounded-md bg-grey-200 dark:bg-grey-800 text-grey-950 dark:text-grey-50">
        <div class="flex justify-evenly items-center flex-col md:flex-row">
            <div id="light-error-robot" class="w-full h-full flex justify-center">
                <div class="flex justify-center items-center w-full flex-col">
                    <img src="{{ asset('images/401-thief-error-pink.png') }}" alt="Dessin erreur 401"/>
                    <div class="text-center">
                        <p>
                            Vous n’avez pas la permission d’accéder à ce site !
                        </p>
                        <p> {{$exception->getMessage()}} </p>
                        <p>Merci de contacter le <a href="mailto:simde@assos.utc.fr"
                                                    class="text-pink-500 hover:underline">SiMDE</a>
                            si vous pensez qu'il s'agit d'une erreur.
                        </p>
                    </div>
                    <div class="mt-4">
                        <a
                            href="{{ config('services.oauth.logout_url') }}"
                            class="focus:ring-2 focus:outline-none font-medium rounded-lg text-sm px-2.5 py-2 text-center inline-flex items-center text-grey-950 bg-pink-400 hover:brightness-90 focus:ring-pink-500 dark:bg-pink-400 dark:focus:ring-pink-300">
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
