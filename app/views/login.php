<div class="login">
    <div class="login__background">
        <div class="login__blob login__blob--1"></div>
        <div class="login__blob login__blob--2"></div>
    </div>

    <div class="login__container">
        <div class="login__card">
            <div class="login__header">
                <div class="login__logo">
                    <span class="material-symbols-rounded">security</span>
                </div>
                <h1 class="login__title">Beta Testing Portal</h1>
                <p class="login__subtitle">Connectez-vous pour accéder au dashboard</p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials'): ?>
                <div class="login__message login__message--error">
                    <p><span class="material-symbols-rounded">error</span> Mot de passe ou adresse email incorrect.</p>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form class="login__form" method="POST" action="/login">
                <div class="login__field">
                    <label for="email" class="login__label">Adresse email</label>
                    <div class="login__input-wrapper">
                        <span class="material-symbols-rounded login__input-icon">mail</span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="login__input"
                            placeholder="votre@email.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required
                        >
                    </div>
                </div>

                <div class="login__field">
                    <label for="password" class="login__label">Mot de passe</label>
                    <div class="login__input-wrapper">
                        <span class="material-symbols-rounded login__input-icon">lock</span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="login__input login__input--password"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button type="submit" name="login" class="login__button login__button--primary">
                    <span class="login__button-text">Se connecter</span>
                    <span class="material-symbols-rounded login__button-icon">arrow_forward</span>
                </button>
            </form>

            <div class="login__signup">
                <p class="login__signup-text">
                    Pas encore de compte ? 
                    <a href="#" class="login__signup-link">Demander un accès</a>
                </p>
            </div>
        </div>
    </div>
</div>