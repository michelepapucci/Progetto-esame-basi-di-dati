<?php
	session_start();
	require 'db_handler.php';
	require 'account.php';

	$logged = false;
	$account = new Account();
	try {
		$pdo = db_connect();
		$logged = $account->loginDaSessione();
	} catch(Exception $e) {
		die($e->getMessage());
	}
?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<?php
			if($logged) {
				echo "<script src='js/del_utente.js'></script>";
			}
		?>
    </head>
    <body class="home">
		<?php include 'navbar.php' ?>
        <p class="consegna">Benvenuto su Bloggolo!</p>
        <p class="consegna_piccola">Bloggolo è una piattaforma che ti permette di creare gratuitamente il tuo blog
            personale, e di condividere
            la tua conoscenza con il mondo! <br/> Il tuo blog verrà inserito in una categoria generale, e per
            specificare
            meglio l'agomento di cui vorrai trattare, potrai creare da zero una sottocategoria (o tema) specifica
            (oppure
            potrai sceglierne una già esistente). <br/> Cosa aspetti, registrati e crea subito il tuo blog! </p>
        <div class="contenitore_box">
            <div class="box_multipli">
                <p class="consegna_piccola">Non ti sei ancora registrato?</p><br/>
                <a class="consegna_media link" href="registrazione.php">Clicca qui!</a>
            </div>
            <div class="box_multipli">
                <p class="consegna_piccola">Hai già un account?</p><br/>
                <a class="consegna_media link" href="login.php">Accedi</a>
            </div>
            <div class="box_multipli">
                <p class="consegna_piccola">Gestisci i tuoi blog!</p>
                <div class = "contenitore_box">
                    <select class="selezione aliceblue" id = "miei_blog" name = "miei_blog">
                        <?php
                        try {
                            $categorie = getCategorie();
                            foreach($categorie as $cat) {
                                echo "<option value = '" . $cat["id_cat"] . "'>" . $cat["nome_cat"] . "</option>";
                            }
                        } catch(Exception $e) {
                            echo "<option disabled>Errore! Categorie non trovate. Contattare webmaster</option>";
                        }
                        ?>
                    </select>
                    <input type = "button" class = "bottone_modifiche aliceblue" value = "Visita">
                </div>
            </div>
        </div>
		<?php
			try {
				$latest_post = getLatestPostIndex();
			} catch(Exception $e) {
				die($e->getMessage());
			}
			if(is_array($latest_post) && count($latest_post) > 0) {
				?>
                <p class="consegna_media">Sbircia gli ultimi post pubblicati sul sito</p>
                <div class="contenitore_box contenitore_verticale">
					<?php
						foreach($latest_post as $p) {
							echo '
                                <div class="box_multipli verticali">
                                    <a class="link consegna_media" href="post.php?id_post=' . $p["id_post"] . '">' . $p["titolo_post"] . '</a>
                                    <p class="consegna_piccola">' . substr($p["testo_post"], 0, 200) . '...</p>
                                    <a class="link" href=blog.php?blog=' . $p["id_blog"] . '>Da "' . $p["titolo_blog"] . '"</a>
                                    <span> - di ' . $p["nome_utente"] . ' - </span>
                                    <span> da ' . $p["nome_tema"] . ' - </span>
                                    <span>  in ' . $p["nome_cat"] . '</span>
                                </div>';
						}
					?>
                </div>
				<?php
			} else {
				echo '<p class="consegna_media">Oh no! Sembra che nessuno abbia ancora scritto un post!</p>';
			}
		?>
    </body>
    </html>

<?php
	$pdo = null;
?>