<!---------footer pour le fond marron------->
<footer class="bg-[#2F1B14] px-16 py-2 font-sans">

      <!------ligne de séparation----->
      <hr class="mt-4 mb-6 border-white" />
      <div class="w-full grid grid-cols-2 md:grid-cols-5 lg:grid-cols-5 gap-y-12 gap-x-12 text-center">
            <!--les colonnes -->
            <!-- Logo en image -->
            <img src="./assets/logo.svg" alt="Maison Rosalie" class="h-24 mx-auto">
            <div class="space-y-6">
                  <!--Colonne 1 : navigation-->
                  <h3 class="text-[#C9A45C] font-bold mb-6">Navigation</h3>
                  <ul class="space-y-4 text-white">
                        <li><a href="./?pages=home"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">
                                    Accueil</a></li>
                        <li><a href="./?pages=about"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">
                                    À propos</a></li>
                        <li><a href="./?pages=recettes"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">
                                    Recettes</a></li>
                        <li><a href="./?pages=contact"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">
                                    Contact</a></li>
                  </ul>
            </div>
            <!--Colonne 2 : mentions légales-->
            <div class="space-y-6">
                  <h3 class="text-[#C9A45C] font-bold mb-6">Mention Legal</h3>
                  <!-- Les liens ouvrent le modal des mentions légales (voir legalModal.php).
                          data-legal-open = clé du document à afficher. type="button" car ce
                          sont des boutons d'action (pas de vraie navigation). -->
                  <ul class="space-y-4 text-white">
                        <li><button type="button" data-legal-open="privacy"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">Politique de confidentialité</button></li>
                        <li><button type="button" data-legal-open="cookies"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">Politique des cookies</button></li>
                        <li><button type="button" data-legal-open="gdpr"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">Droits GDPR</button></li>
                        <li><button type="button" data-legal-open="data"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">Traitement des données</button></li>
                        <li><button type="button" data-legal-open="terms"
                                    class="hover:text-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded transition-all">Conditions d'utilisation</button></li>
                  </ul>
            </div>
            <!--Colonne 3 :  réseaux sociaux-->
            <div class="space-y-6">
                  <h3 class="text-[#C9A45C] font-bold mb-6"> Réseaux sociaux</h3>
                  <p class="text-white mb-4"> Suivez nous </p>
                  <!--Icônes dorées -->
                  <ul class="flex justify-center gap-4 text-[#C9A45C] text-xl">
                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  </ul>
            </div>



            <!-- ================= NEWSLETTER (version compacte) ================= -->
            <div class="space-y-6">
                  <h3 class="text-[#C9A45C] font-bold mb-6">
                        <i class="fa-regular fa-envelope mr-2"></i> Newsletter
                  </h3>
                  <p class="text-white text-sm">Recevez nos nouvelles recettes chaque mois.</p>
                  <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Votre adresse email"
                              class="w-full bg-transparent border border-[#C9A45C] text-white placeholder-[#BFA58A] px-3 py-1.5 text-sm">
                        <button type="submit" class="bg-[#C9A45C] text-[#2F1B14] font-bold px-4 py-1.5 text-sm">
                              S'inscrire
                        </button>
                  </form>
            </div>



      </div>
      </div>
      <!------ligne de séparation----->
      <hr class="mt-6 mb-3 border-white" />
      <!-----Copyright, centré ---->
      <p class="text-center text-[#BFA58A] mb-3">
            &lt&lt © 2026 Maison Rosalie - Tous droits réservés. &gt&gt
      </p>

</footer>
<!-- Modal des mentions légales, ouvert par les liens de la colonne "Mention Legal" ci-dessus -->
<?php require_once __DIR__ . "/legalModal.php" ?>