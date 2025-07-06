<!-- Body principale -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="GRAPHIC/style.css" type="text/css" rel="stylesheet"/>
    <title>Index</title>
</head>
<body class="body">
    <?php include("header.php"); ?> <!-- Include il file header.php per la barra di navigazione -->

    <!-- Parte chi sono -->
    <div class="chisono" id="chisono">
        <h1 class="nome" data-aos="fade-right" data-aos-duration="2000">Angelo <br> Iandolo</h1>
        <img src="IMG/Angelo.jpg" alt="fotopers" class="fotopers" id="fotopers" data-aos="flip-left" data-aos-duration="1500">
        <p class="autodescrizione" id="autodescrizione" data-aos="fade-left" data-aos-duration="1500">
            Perito informatico con una solida
            esperienza in ambito sistemistico e help
            desk, unita a competenze trasversali in
            programmazione e sviluppo full stack.
            Tecnicamente preparato sia lato
            hardware che software, possiedo un
            approccio metodico e orientato
            all'efficienza. Attualmente sto
            approfondendo lo sviluppo web
            moderno attraverso un corso full stack,
            arricchendo il mio bagaglio già esteso in
            SQL, JavaScript, Java, PHP, CSS/SCSS e
            HTML.
            Cerco un contesto professionale serio,
            rispettoso e ben organizzato dove poter
            crescere costantemente e dare un
            contributo concreto, grazie al mio spirito
            pratico, alla curiosità tecnica e alla
            capacità di affrontare le sfide con calma
            e determinazione.
        </p>
    </div>

    <!-- Parte portfolio -->
    <div class="portfolio" id="portfolio">
        <h2 class="titolo-portfolio" data-aos="flip-up" data-aos-duration="2000">Portfolio</h2>
        <p class="descrizione-portfolio" data-aos="flip-up" data-aos-duration="2000">
            Qui puoi trovare alcuni dei miei progetti più significativi, che mostrano le mie competenze in programmazione e sviluppo web.
        </p>
        <div class="progetti">
            <div class="progetto" data-aos="flip-up" data-aos-duration="2000">
                <h3>Progetto 1</h3>
                <p>Descrizione del progetto 1.</p>
            </div>
            <div class="progetto" data-aos="flip-up" data-aos-duration="2000">
                <h3>Progetto 2</h3>
                <p>Descrizione del progetto 2.</p>
            </div>
            <div class="progetto" data-aos="flip-up" data-aos-duration="2000">
                <h3>Progetto 3</h3>
                <p>Descrizione del progetto 3.</p>
            </div>
        </div>
    </div>

        <!-- Competenze e contatti -->
    <div class="containerInferiore" id="containerInferiore">
            <div class="competenze" data-aos="flip-right" data-aos-duration="2000">
                <h3>Competenze</h3>
                <ul>
                    <li>SQL</li>
                    <li>JavaScript</li>
                    <li>Java</li>
                    <li>PHP</li>
                    <li>CSS/SCSS</li>
                    <li>HTML</li>
                </ul>
            </div>
            <div class="contact-form"  data-aos="flip-left" data-aos-duration="2000">
                <h2>Contatti</h2>            
                <form class="contactForm" id="contactForm" action="#" method="POST" >
                    <div class="form-group">
                        <label for="nome">Nome <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="messaggio">Messaggio <span class="required">*</span></label>
                        <textarea id="messaggio" name="messaggio" placeholder="Scrivi qui il tuo messaggio..." required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Invia Messaggio</button>
                </form>
            </div>
       
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            const messaggio = document.getElementById('messaggio').value.trim();
            
            if (!nome || !email || !messaggio) {
                alert('Tutti i campi sono obbligatori!');
                return;
            }
            
            // Validazione email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Inserisci un indirizzo email valido!');
                return;
            }
            
            alert('Messaggio inviato con successo!');
            // Qui puoi aggiungere la logica per inviare i dati al server
            
            // Reset del form
            this.reset();
        });
    </script>
    </div> 


        
    
<?php include("footer.php"); ?>


  