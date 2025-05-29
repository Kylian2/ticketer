<div class="ticket">
    <section class="ticket__section ticket__section--header">
        <h2 class="ticket__title"><?php echo $ticket->title; ?></h2>
        <div class="ticket__controls">
            <div class="ticket__control">
                <label for="status">Statut</label>
                <select name="status" id="status">
                    <option value="new" <?php echo ($ticket->status === 'new' ? 'selected' : ''); ?>>Nouveau</option>
                    <option value="in_progress" <?php echo ($ticket->status === 'in_progress' ? 'selected' : ''); ?>>En cours</option>
                    <option value="closed" <?php echo ($ticket->status === 'closed' ? 'selected' : ''); ?>>Fermé</option>
                </select>
            </div>
            <div class="ticket__control">
                <label for="priority">Priorité</label>
                <select name="priority" id="priority">
                    <option value="high" <?php echo ($ticket->priority === 'high' ? 'selected' : ''); ?>>Haute</option>
                    <option value="medium"<?php echo ($ticket->priority === 'medium' ? 'selected' : ''); ?>>Moyenne</option>
                    <option value="low"<?php echo ($ticket->priority === 'low' ? 'selected' : ''); ?>>Basse</option>
                </select>
            </div>
        </div>
        <!--<div class="ticket__relevance">
            <input type="checkbox" id="pertinent">
            <label for="pertinent">Marquer comme pertinent</label>
        </div>-->
        <div class="ticket__details">
            <p class="ticket__details-item">
                <span class="material-symbols-rounded">person</span>
                <?php echo $ticket->user.' ('.$ticket->userObject->email.')'; ?>
            </p>
            <p class="ticket__details-item">
                <span class="material-symbols-rounded">calendar_today</span>
                <?php echo $ticket->created_at; ?>
            </p>
            <p class="ticket__details-item">
                <span class="material-symbols-rounded">local_offer</span>
                <?php echo ucfirst($ticket->category); ?>
            </p>
        </div>
    </section>
    <section class="ticket__section">
        <h4 class="ticket__description-title">Description</h4>
        <p class="ticket__description-content"><?php echo $ticket->description ?></p>
    </section>
    <section class="ticket__section ticket__notes">
        <h4 class="ticket__notes-title">Notes administrateur</h4>
        <input type="text" class="ticket__notes-input" placeholder="Ajouter des notes internes...">
        <button>
            Ajouter
        </button>

        <div class="ticket__notes-list">
            <?php foreach($notes as $note): ?>
                <div class="ticket__note">
                    <div class="ticket__note-header">
                        <span class="ticket__note-author"><?php echo $note->author;?></span>
                        <span class="ticket__note-date"><?php echo $note->created_at;?></span>
                    </div>
                    <p class="ticket__note-content"><?php echo $note->content;?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="ticket__section ticket__section--responses">
        <h4 class="ticket__responses-title">Réponses envoyées</h4>
        <div class="ticket__responses-list">
            <?php foreach($messages as $message): ?>

                <div class="ticket__response">
                    <div class="ticket__response-header">
                        <span class="ticket__response-author"><?php echo $message->author;?></span>
                        <span class="ticket__response-date"><?php echo $message->created_at;?></span>
                    </div>
                    <p class="ticket__response-content"><?php echo $message->content;?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="ticket__reply">
            <h4 class="ticket__reply-title">Envoyer une réponse</h4>
            <textarea class="ticket__reply-input" placeholder="Écrire votre réponse..."></textarea>
            <button class="btn">
                <span class="material-symbols-rounded">mail</span>
                Envoyer par email
            </button>
        </div>
    </section>
</div>