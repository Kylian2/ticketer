
<div class="blob blob--1"></div>
<div class="blob blob--2"></div>
<div class="blob blob--3"></div>

<main class="new-ticket">

    <div class="new-ticket__header">
        <h1>Centre de Feedback</h1>
        <p>Aidez-nous à améliorer <span class="new-ticket__header__app-name"><?php echo $_ENV["APP_NAME"]; ?></span> en signalant des bugs ou en suggérant des améliorations.</p>
        <?php if (isset($_GET['success'])): ?>
            <p class="message message--success"><span class="material-symbols-rounded">celebration</span> Merci pour votre feedback ! Votre ticket a été créé avec succès.</p>
        <?php endif?>
        <?php if (isset($_GET['error'])): ?>
            <p class="message message--error"><span class="material-symbols-rounded">error</span> <?php echo $_GET['error']; ?></p>
        <?php endif?>
    </div>

    <form action="/ticket" method="POST">
        <div class="new-ticket__user-info">
            <div>
                <label for="user_name">Votre nom</label>
                <input type="text" id="user_name" name="user_name" required placeholder="Entrez votre nom complet">
            </div>
            <div>
                <label for="user_email">Email</label>
                <input type="text" id="user_email" name="user_email" required placeholder="Entrez votre email">
            </div>
        </div>
        <div>
            <label for="ticket_category">Catégorie</label>
            <select id="ticket_category" name="ticket_category" required>
                <option value="" disabled selected>Choisissez une catégorie</option>
                <option value="bug">Bug</option>
                <option value="upgrade">Suggestion d'amélioration</option>
                <option value="feedback">Feedback</option>
                <option value="feature_request">Nouvelle fonctionnalité</option>
            </select>
        </div>
        <div>
            <label for="ticket_title">Titre</label>
            <input type="text" id="ticket_title" name="ticket_title" required placeholder="Court résumé du problème ou de la suggestion">
        </div>
        <div>
            <label for="ticket_description">Description</label>
            <textarea id="ticket_description" name="ticket_description" required placeholder="Décrivez le problème en detail, les étapes pour le reproduire, ou votre suggestion d'amélioration" rows="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>

    </form>

</main>