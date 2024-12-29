<?php
// index.php
$pageTitle = "TalentMatcher UPF - Accueil";
include 'header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero text-center my-5" style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 60px 20px;">
        <h1 style="font-size: 3rem; font-weight: bold;">Bienvenue sur Portifica</h1>
        <p style="font-size: 1.25rem; margin-top: 20px;">La plateforme qui connecte les talents de l'Université Privée de Fès avec les meilleures opportunités professionnelles.</p>
        <a href="login.php" class="cta-button btn btn-lg btn-light mt-4" style="padding: 15px 30px; font-size: 1.2rem; border-radius: 30px; text-shadow: 0px 1px 2px rgba(0,0,0,0.3);">Commencer maintenant</a>
    </section>

    <!-- Statistics Section -->
    <section class="statistics py-5" style="background: #f8f9fa;">
        <h2 class="text-center mb-4" style="font-weight: bold;">Quelques chiffres clés</h2>
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 2rem; font-weight: bold; color: #007bff;"><span class="count" data-target="150">0</span>+</h3>
                        <p>Entreprises partenaires</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 2rem; font-weight: bold; color: #007bff;"><span class="count" data-target="5000">0</span>+</h3>
                        <p>Étudiants inscrits</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 2rem; font-weight: bold; color: #007bff;"><span class="count" data-target="2000">0</span>+</h3>
                        <p>CV disponibles</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 2rem; font-weight: bold; color: #007bff;"><span class="count" data-target="100">0</span>+</h3>
                        <p>Offres d'emploi actives</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features py-5" style="background: white;">
        <h2 class="text-center mb-4" style="font-weight: bold;">Pourquoi utiliser Portifica ?</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 1.5rem; font-weight: bold;">Gestion efficace des CV</h3>
                        <p>Simplifiez le processus de recrutement en centralisant et organisant tous les CV des étudiants et diplômés de l'UPF.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 1.5rem; font-weight: bold;">Recherche ciblée</h3>
                        <p>Trouvez rapidement les candidats correspondant aux compétences spécifiques recherchées par votre entreprise.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                        <h3 style="font-size: 1.5rem; font-weight: bold;">Gain de temps</h3>
                        <p>Réduisez considérablement le temps consacré à la sélection des CV grâce à notre système de filtrage avancé.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- User Types Section -->
    <section class="user-types text-center py-5" style="background: #f8f9fa;">
        <h2 style="font-weight: bold;">Êtes-vous...</h2>
        <div class="user-type-buttons py-3">
            <a href="login.php?type=student" class="btn btn-primary mx-2" style="padding: 15px 30px; font-size: 1.2rem; border-radius: 30px;">Un étudiant</a>
            <a href="login.php?type=recruiter" class="btn btn-primary mx-2" style="padding: 15px 30px; font-size: 1.2rem; border-radius: 30px;">Un recruteur</a>
        </div>
    </section>
    <section class="features py-9" style="background: white;">
    <!-- Partners Section -->
        
        <p></p>
        <br>
        <div class="moving-images-wrapper">
            <div class="moving-images-container">
                <div class="partner-logo">
                    <img src="capgemini.jpg" alt="Capgemini">
                </div>
                <div class="partner-logo">
                    <img src="alten.png" alt="Alten">
                </div>
                <div class="partner-logo">
                    <img src="cgi.png" alt="CGI">
                </div>
                <div class="partner-logo">
                    <img src="cnexia.png" alt="Cnexia">
                </div>
                <div class="partner-logo">
                    <img src="dxc.png" alt="DXC">
                </div>
                <div class="partner-logo">
                    <img src="alstom.png" alt="DXC">
                </div>
                <div class="partner-logo">
                    <img src="attijari.png" alt="DXC">
                </div>
            </div>
        </div>
 
    </section>
</main>
<style>
  /* Conteneur global */
.partners-section {
    background-color: #f8f9fa;
    padding: 20px 0;
    text-align: center;
}.partners {
    background-color: white !important;
}


/* Wrapper pour masquer le débordement */
.moving-images-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    height: 100px; /* Ajustez la hauteur si nécessaire */
}

/* Conteneur pour les logos */
.moving-images-container {
    display: flex;
    align-items: center;
    justify-content: space-around;
    position: relative;
    animation: slideImages 15s linear infinite;
}

/* Style des images */
.partner-logo img {
    max-height: 70px; /* Fixez une hauteur maximale pour toutes les images */
    width: auto; /* Conserve le ratio d'aspect des images */
    display: block;
    margin: auto; /* Centre l'image dans le conteneur */
}

/* Animation pour le défilement */
@keyframes slideImages {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

</style>
<script>
// Animation pour les chiffres clés
const counters = document.querySelectorAll('.count');
counters.forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;

        const increment = target / 100;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 30);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});
</script>

<?php include 'footer.php'; ?>
