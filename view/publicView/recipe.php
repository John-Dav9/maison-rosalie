 <?php require_once "./../view/publicView/inc/menuPublicView.php" ?>
      <!--=========Fond créme pour toute la section ================-->
     <section id="recettes" class="bg-[#FBF5E6] text-[#2F1B14] pb-16">
       
       <!-- ===  ===========  En-tete de la page recette  ===-->
         <div class="text-center py-20">
      <a href="#" class="text-[#B8860B] underline font-['Playfair_Display']">L'atelier Maison Rosalie</a>
      <h2 class="text-5xl font-bold font-['Playfair_Display'] mt-2">
         <span class="text-[#C9A45C] text-xl">◆</span> Recettes au chocolat
      </h2>
      <p class="text-sm mt-4">
         apprenez nos recettes délicieuses et trop bonne qu'on peut<br>
         manger quand on a faim ou quoi tu vois.
      </p>
   </div>

        <!--  === ==========Boutons des catégories ========= -->
        <!--============= Le bouton foncé = la catégorie choisie ============-->
         <div class="flex justify-center gap-5 mb-10">
             <button class="categorie bg-[#2F1B14] text-white px-8 py-1 font-['Playfair_Display']"> Ganache</button>
             <button class="categorie bg-[#F8EDD3] px-8 py-1 font-['Playfair_Display']">Praline</button>
             <button class="categorie bg-[#F8EDD3] px-8 py-1 font-['Playfair_Display']">Truffe</button>
        </div>

       <!-- ================= CARTE DE LA RECETTE ================= -->
   <article class="max-w-4xl mx-auto bg-white border border-[#C9A45C] rounded-lg">

      <!-- -----============== Partie du haut : image + infos =============----- -->
      <div class="grid grid-cols-2 gap-8 p-6">

         <!--================Image de la recette ================-->
         <img src="./assets/ganache-chocolat-thermomix-800x600.webp" alt="Ganache noir 85%" class="w-full h-72 object-cover border border-[#C9A45C] mt-8">

         <!-- ==============Infos de la recette ============-->
         <div>
            <!--============= Étiquette Moyen, en haut à droite============== -->
            <p class="text-right">
               <span class="bg-[#F8EDD3] px-6 py-2 font-bold font-['Playfair_Display']">Moyen</span>
            </p>

            <!-- ==============Titre ==============-->
            <h3 class="text-3xl font-bold font-['Playfair_Display'] mt-4">Ganache noir 85%</h3>

            <!--============== Description + lien "Lire plus" =============-->
            <p class="text-xs mt-2">
               lorem ipsum dolor sit amet consectetur 
               adipisicing elit. Quisquam, quod. Lorem ipsum 
               dolor sit amet consectetur adipisicing elit. Quisquam, quod. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.
               <a href="#" class="text-[#B8860B] underline">Lire plus</a>
            </p>

            <!-- ===========Note : 4 étoiles rouges sur 5 ================-->
            <p class="text-red-600 text-lg mt-2">
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star text-gray-300"></i>
            </p>

           <!-- ==============Les 3 cases : temps de préparation, temps total, difficulté ==================-->
            <div class="grid grid-cols-3 gap-3 mt-4 text-center">
               <div class="border border-[#C9A45C] py-3">
                  <p class="text-xs">TEMPS PREP</p>
                  <p class="font-bold font-['Playfair_Display']">20 Mins</p>
               </div>
               <div class="border border-[#C9A45C] py-3">
                  <p class="text-xs">TEMPS TOT</p>
                  <p class="font-bold font-['Playfair_Display']">120 Mins</p>
               </div>
               <div class="border border-[#C9A45C] py-3">
                  <p class="text-xs">DIFFICULTÉ</p>
                  <p class="font-bold font-['Playfair_Display']">Moyen</p>
               </div>
            </div>

            <!-- ====== Portions : boutons =========- et + -->
            <div class="flex justify-between items-center bg-[#F8EDD3] border border-[#C9A45C] p-4 mt-6">
               <div>
                  <p class="text-xs font-bold">RENDEMENT DE LA RECETTE / PORTIONS</p>
                  <p class="text-xs mt-2">Cliquez pour changer les portions</p>
               </div>
               <div class="flex items-center gap-2 bg-white border border-[#C9A45C] rounded-full px-2 py-1">
                  <button id="moins" class="bg-[#F8EDD3] px-2">−</button>
                  <span class="font-bold font-['Playfair_Display']"><span id="portions">4</span> Pers</span>
                  <button id="plus" class="bg-[#F8EDD3] px-2">+</button>
               </div>
            </div>
         </div>
      </div>
         <!-- =========Les Ingredients =======---->
          <section class="border-t border-[#C9A45C] p-6">
            <!------ ====== Titre + ajusté pour x personnes ============  --->
                <div class= "flex justify-between items-center mb-4">
                    <h3 class="text-3xl font-bold font-['Playfair_Display']">
                      <i class="fa-solid fa-basket-shopping text-[#C9A45C]>text-xl"></i>Les ingrédients </h3>
                    <p class="text-sm">ajusté pour <b><span id="portions-texte">4</span>personnes</b></p>
                </div>
            <!------ ======Carrousel : les cartes defilent de gauche à droit =========----->
             <div class="min-w-36 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
               <img src="./assets/chocolat.jpg" alt="Chocolat noir" class="h-16 w-full object-cover rounded">
               <p class="font-bold font-['Playfair_Display'] text-sm mt-3">70% Chocolat noir</p>
               <p class="text-xs text-[#B8860B] mt-1"><span class="quantite" data-quantite="200">200</span>g</p>
            </div>

            <div class="min-w-36 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
               <img src="./assets/beurre.jpg" alt="Beurre" class="h-16 w-full object-cover rounded">
               <p class="font-bold font-['Playfair_Display'] text-sm mt-3">Beurre</p>
               <p class="text-xs text-[#B8860B] mt-1"><span class="quantite" data-quantite="120">120</span>g</p>
            </div>

            <div class="min-w-36 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
               <img src="./assets/sucre.jpg" alt="Sucre" class="h-16 w-full object-cover rounded">
               <p class="font-bold font-['Playfair_Display'] text-sm mt-3">Sucre</p>
               <p class="text-xs text-[#B8860B] mt-1"><span class="quantite" data-quantite="120">120</span>g</p>
            </div>

            <div class="min-w-36 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
               <img src="./assets/oeufs.jpg" alt="Œufs" class="h-16 w-full object-cover rounded">
               <p class="font-bold font-['Playfair_Display'] text-sm mt-3">Œufs</p>
               <p class="text-xs text-[#B8860B] mt-1"><span class="quantite" data-quantite="4">4</span></p>
            </div>

            <div class="min-w-36 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
               <img src="./assets/cacao.jpg" alt="Cacao en poudre" class="h-16 w-full object-cover rounded">
               <p class="font-bold font-['Playfair_Display'] text-sm mt-3">Cacao en poudre</p>
               <p class="text-xs text-[#B8860B] mt-1"><span class="quantite" data-quantite="30">30</span>g</p>
            </div>



            

          </section>




    <script src="./js/scripts.js"></script>

</body>

</html>