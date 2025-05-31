<section class="settings">
    <h1>Paramètres</h1>
    <p>Éditez vos préférences ou mettez à jour les paramètres de votre compte</p>

    <div id="infos">

    </div>

    <section class="settings__section">
        <h2>Compte</h2>
        <p>Modifier les paramètres de votre compte</p>
        <div class="settings__section__password">
            <label for="password">Mot de passe</label> 
            <form class="settings__section__password__form" hx-patch="/users?type=password" hx-trigger="submit" hx-include="#old-password, #new-password, #confirm-password" hx-target="#infos">
                <input type="password" name="old-password" id="old-password" placeholder="Ancien mot de passe" required>
                <div>
                    <input type="password" name="new-password" id="new-password" placeholder="Nouveau mot de passe" required>
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirmer le nouveau mot de passe" required>
                </div>
                <button>Modifier</button>
            </form>
        </div>
        <div class="settings__section__email">
            <label for="password">Email</label> 
            <form class="settings__section__email__form" hx-patch="/users?type=email" hx-trigger="submit" hx-include="#email" hx-target="#infos">
                <div>
                    <input type="email" name="email" id="email" placeholder="<?php echo $user->email; ?>" required value="<?php echo $user->email; ?>">
                </div>
                <button>Modifier</button>
            </form>
        </div>
    </section>

</section>
