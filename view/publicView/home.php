
    <?php require_once "./../view/publicView/inc/header.php" ?>

    <!-- Google Fonts for Luxury Chocolatier Typography -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Alex+Brush&amp;family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&amp;family=Montserrat:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
<!-- Tailwind CSS v3 with Plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              gold: '#D4AF37',
              goldLight: '#E2C992',
              cream: '#FBF5EC',
              creamMuted: '#E7DEC8',
              espresso: '#1A0E08',
              cocoaDark: '#1E120C',
              cocoaCard: '#27170F',
              borderGold: 'rgba(212, 175, 55, 0.28)'
            }
          },
          fontFamily: {
            script: ['"Alex Brush"', 'cursive'],
            serif: ['"Cormorant Garamond"', 'serif'],
            sans: ['Montserrat', 'sans-serif']
          }
        }
      }
    }
  </script>
<!-- Custom Typography & Atmospheric Styles -->
<style data-purpose="custom-theme">
    .font-brand-script {
      font-family: 'Alex Brush', cursive;
    }
    .hero-bg-overlay {
      background-image: linear-gradient(to right, rgba(16, 9, 6, 0.88) 0%, rgba(20, 11, 7, 0.72) 48%, rgba(24, 13, 8, 0.85) 100%), url('https://lh3.googleusercontent.com/aida-public/AB6AXuAcoxgJLnyUKpU12bNwN0DDYL5BaHuU9nyAYjur8eTOtcpzhsDdvZpy5mXPotTe3gqLnTL8W5_pQs2gz-Z1RONM9X4i19KPhtGPeAh7F5V3xbb8dQ1r_pGGMvSyrdFLeFXBgXM7peTUAFsHHqjzydf6cckZluQrByI9Nm8O1NsGr5kF0Hd59hkkazhuSWqAbu735EDuVW6EjFhRSCkZRkfGrJM_Zi5Mvjo-Ad4HeNydwE6htDIW6gPo6bRL-tiBJZgTcw');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>
<body class="bg-[#180E09] text-[#E7DEC8] font-sans antialiased selection:bg-[#D4AF37] selection:text-[#1A0E08] min-h-screen">
<!-- BEGIN: MainHeader -->
<!-- END: MainHeader -->
<!-- BEGIN: HeroSection -->
<!-- END: HeroSection -->
<!-- BEGIN: Top3RecipesSection -->
<section class="relative py-24 px-6 md:px-12 bg-gradient-to-b from-[#180E09] via-[#21130D] to-[#1E120C]" data-purpose="recipes-showcase" id="recettes">
<div class="max-w-7xl mx-auto">
<!-- Section Header -->
<div class="text-center max-w-3xl mx-auto mb-16">
<span class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-semibold mb-3 block">Sélection Signature</span>
<h2 class="font-serif text-4xl sm:text-5xl text-[#FBF5EC] font-light tracking-wide mb-4">
          Notre Top 3 des recettes
        </h2>
<div class="flex items-center justify-center space-x-3 mb-4">
<span class="h-px w-12 bg-[#D4AF37]/50"></span>
<i class="fa-solid fa-cookie-bite text-[#D4AF37] text-xs"></i>
<span class="h-px w-12 bg-[#D4AF37]/50"></span>
</div>
<p class="text-[#E7DEC8]/80 text-base md:text-lg font-light">
          Découvrez les trois recettes préférées de notre communauté.
        </p>
</div>
<!-- 3 Recipe Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
<!-- Recipe Card 1 -->
<article class="bg-[#27170F] rounded-2xl overflow-hidden border border-[#523321]/60 hover:border-[#D4AF37]/70 shadow-2xl flex flex-col group transition-all duration-300 hover:-translate-y-1.5">
<!-- Card Thumbnail with Ranking Badge -->
<div class="relative h-64 overflow-hidden bg-[#1E120C]">
<img alt="Gourmet dark chocolate fondant lava cake on artisanal ceramic plate" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBDj6EsqLaSnWy9yIHDfbcrd6fd68IgSZcriE-S1Chq6H_aOFR_U9c9wjrnBXos1u_S_CncTV9cfSa14e9_iumez5bt3XUaa4QLnJNOw3Z1H3K56UznyKXA_HnUy4OF2qNQPptrJXnNjxzwhoLjzTIW5nqomrDXZz6i5gQFClrhiR1KWgvRgMfpYsSSuFTKFEdUJlOpr0eluE_bKfSBQ56j_czE4Ta_4TBlmBCJL2rifqMfJn2Zdu2C"/>
<div class="absolute inset-0 bg-gradient-to-t from-[#27170F] via-transparent to-transparent opacity-80"></div>
<!-- Badge -->
<div class="absolute top-4 left-4 backdrop-blur-md bg-[#160D08]/85 border border-[#D4AF37]/50 rounded-full px-3.5 py-1 text-xs text-[#E2C992] font-medium tracking-wide shadow-lg">
              1<sup class="text-[10px]">er</sup> • Coup de cœur
            </div>
</div>
<!-- Card Content Body -->
<div class="p-6 md:p-7 flex flex-col flex-1 justify-between">
<div>
<div class="text-xs uppercase tracking-wider text-[#D4AF37] mb-2 flex items-center gap-1.5 font-medium">
<i class="fa-regular fa-clock text-[11px]"></i> 25 min • Facile
              </div>
<h3 class="font-serif text-2xl text-[#FBF5EC] font-normal mb-3 group-hover:text-[#E2C992] transition-colors">
                Fondant Noir Cœur Coulant
              </h3>
<p class="text-[#E7DEC8]/75 text-sm font-light leading-relaxed mb-6">
                Un cœur onctueux et coulant au chocolat noir 72% de plantation, relevé d'une pointe de fleur de sel.
              </p>
</div>
<!-- Action Button -->
<div class="pt-2">
<a class="w-full inline-flex items-center justify-center py-2.5 px-5 rounded-full border border-[#D4AF37]/60 text-[#FBF5EC] hover:bg-[#D4AF37] hover:text-[#180E09] text-xs font-medium tracking-wider transition-all duration-300" href="#recette-fondant">
                Voir la recette
              </a>
</div>
</div>
</article>
<!-- Recipe Card 2 -->
<article class="bg-[#27170F] rounded-2xl overflow-hidden border border-[#523321]/60 hover:border-[#D4AF37]/70 shadow-2xl flex flex-col group transition-all duration-300 hover:-translate-y-1.5">
<!-- Card Thumbnail with Ranking Badge -->
<div class="relative h-64 overflow-hidden bg-[#1E120C]">
<img alt="Artisanal handcrafted chocolate truffles rolled in cocoa and gold leaf flakes" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDys4OTH0mCZHuaYu-rUCveOYzOweq6IdhWEoWMh-X_9t_FTJM6ebDiuREJynozADX0NkjyvDYfHr4uhOwI45OkjeUr-pGKLkRBN6kfaIOxue4Ln6hXBgVpwhwpEJK7NlwvIPfrN_R3WVXhLc332RQbbS0QH08xcTB4feupz7jEPhtXqikwYdDtDq5yIv8QKTkPbx8stkd0bhFdcY46ZTUl6uGKInTCRDS7UdhP3xNEsJPH7sig-pld"/>
<div class="absolute inset-0 bg-gradient-to-t from-[#27170F] via-transparent to-transparent opacity-80"></div>
<!-- Badge -->
<div class="absolute top-4 left-4 backdrop-blur-md bg-[#160D08]/85 border border-[#D4AF37]/50 rounded-full px-3.5 py-1 text-xs text-[#E2C992] font-medium tracking-wide shadow-lg">
              2<sup class="text-[10px]">e</sup> • Classique
            </div>
</div>
<!-- Card Content Body -->
<div class="p-6 md:p-7 flex flex-col flex-1 justify-between">
<div>
<div class="text-xs uppercase tracking-wider text-[#D4AF37] mb-2 flex items-center gap-1.5 font-medium">
<i class="fa-regular fa-clock text-[11px]"></i> 40 min • Intermédiaire
              </div>
<h3 class="font-serif text-2xl text-[#FBF5EC] font-normal mb-3 group-hover:text-[#E2C992] transition-colors">
                Truffes Pur Cacao &amp; Feuille d'Or
              </h3>
<p class="text-[#E7DEC8]/75 text-sm font-light leading-relaxed mb-6">
                Ganache soyeuse infusée à la vanille bourbon de Madagascar, poudrée de cacao brut ambré.
              </p>
</div>
<!-- Action Button -->
<div class="pt-2">
<a class="w-full inline-flex items-center justify-center py-2.5 px-5 rounded-full border border-[#D4AF37]/60 text-[#FBF5EC] hover:bg-[#D4AF37] hover:text-[#180E09] text-xs font-medium tracking-wider transition-all duration-300" href="#recette-truffes">
                Voir la recette
              </a>
</div>
</div>
</article>
<!-- Recipe Card 3 -->
<article class="bg-[#27170F] rounded-2xl overflow-hidden border border-[#523321]/60 hover:border-[#D4AF37]/70 shadow-2xl flex flex-col group transition-all duration-300 hover:-translate-y-1.5">
<!-- Card Thumbnail with Ranking Badge -->
<div class="relative h-64 overflow-hidden bg-[#1E120C]">
<img alt="Velvety hot chocolate in a rustic ceramic cup with cinnamon stick and dark chocolate curls" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCZmbbTZYf7URk1ntCFDC0jvoGlXU_kf40WO2LZ4llxwErV7IoYqTEUFaZAkDg_8Zc1mWTVfVLREdyNjezQonL7SNAh_XlbJoMl0jENu79EKm5gk4-aaLLKek21N1iI6r3JeZuNn0lZkxG2N9nqkbQXXq-w_pADPUWFIX3-DfiMNAjZJC25MHUB6RcUHI8_1uuX_u3UDkGjusUm1k6KuW6EjhoXsZS4lLYvkOLRvRfdKpGsh4rJmipS"/>
<div class="absolute inset-0 bg-gradient-to-t from-[#27170F] via-transparent to-transparent opacity-80"></div>
<!-- Badge -->
<div class="absolute top-4 left-4 backdrop-blur-md bg-[#160D08]/85 border border-[#D4AF37]/50 rounded-full px-3.5 py-1 text-xs text-[#E2C992] font-medium tracking-wide shadow-lg">
              3<sup class="text-[10px]">e</sup> • Réconfort
            </div>
</div>
<!-- Card Content Body -->
<div class="p-6 md:p-7 flex flex-col flex-1 justify-between">
<div>
<div class="text-xs uppercase tracking-wider text-[#D4AF37] mb-2 flex items-center gap-1.5 font-medium">
<i class="fa-regular fa-clock text-[11px]"></i> 15 min • Très facile
              </div>
<h3 class="font-serif text-2xl text-[#FBF5EC] font-normal mb-3 group-hover:text-[#E2C992] transition-colors">
                Chocolat Chaud à l'Ancienne
              </h3>
<p class="text-[#E7DEC8]/75 text-sm font-light leading-relaxed mb-6">
                Chocolat fondu au lait entier fermier, bâton de cannelle de Ceylan et volutes de chocolat noir râpé.
              </p>
</div>
<!-- Action Button -->
<div class="pt-2">
<a class="w-full inline-flex items-center justify-center py-2.5 px-5 rounded-full border border-[#D4AF37]/60 text-[#FBF5EC] hover:bg-[#D4AF37] hover:text-[#180E09] text-xs font-medium tracking-wider transition-all duration-300" href="#recette-chocolat-chaud">
                Voir la recette
              </a>
</div>
</div>
</article>
</div>
</div>
</section>
<!-- END: Top3RecipesSection -->
<!-- BEGIN: MainFooter -->
<!-- END: MainFooter -->

    <?php require_once "./../view/publicView/inc/footer.php" ?>
    <script src="./js/scripts.js"></script>
</body>

</html>