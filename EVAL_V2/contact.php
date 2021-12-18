<?php
    $titre = 'Contact';
    include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Nous contacter
                </h1>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email"></label>
                    <input type="text" id="email" placeholder="Votre adresse mail" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-pen"></i>
                    <label for="subject"></label>
                    <input type="text" id="subject" placeholder="Sujet du message" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-comment-alt"></i>
                    <label for="contact-content"></label>
                    <textarea id="contact-content" placeholder="Votre message" required></textarea>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer">

                <article class="help-connect">
                    <a href="index.php">Se connecter</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>