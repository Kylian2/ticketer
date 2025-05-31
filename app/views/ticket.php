<div class="ticket">
    <?php if (isset($ticket)): ?>
    <section  class="ticket__section ticket__section--header">
        <h2 class="ticket__title"><?php echo $ticket->title; ?></h2>
        <p id="request-message"></p>
        <div class="ticket__controls">
            <div class="ticket__control">
                <label for="status">Statut</label>
                <select name="status" id="status" hx-patch="/ticket/<?php echo $ticket->id; ?>" hx-trigger="change" hx-include="#status" hx-target="#request-message" >
                    <option value="new" <?php echo ($ticket->status === 'new' ? 'selected' : ''); ?>>Nouveau</option>
                    <option value="in_progress" <?php echo ($ticket->status === 'in_progress' ? 'selected' : ''); ?>>En cours</option>
                    <option value="closed" <?php echo ($ticket->status === 'closed' ? 'selected' : ''); ?>>Fermé</option>
                </select>
            </div>
            <div class="ticket__control" hx-patch="/ticket/<?php echo $ticket->id; ?>" hx-trigger="change" hx-include="#priority" hx-target="#request-message" >
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
                <?php 
                switch ($ticket->category) {
                    case 'feature_request':
                        echo 'Nouvelle fonctionnalité';
                        break;
                    case 'support':
                        echo 'Support technique';
                        break;
                    case 'feedback':
                        echo 'Feedback';
                        break;
                    case 'bug':
                        echo 'Bug';
                        break;
                    case 'upgrade':
                        echo 'Amélioration';
                        break;
                    default:
                        echo $ticket->category;
                }
                ?>
            </p>
        </div>
    </section>
    <section class="ticket__section">
        <h4 class="ticket__description-title">Description</h4>
        <p class="ticket__description-content"><?php echo $ticket->description ?></p>
    </section>
    <section class="ticket__section ticket__notes">
        <h4 class="ticket__notes-title">Notes administrateur</h4>
        <input type="text" id="note" name="note" class="ticket__notes-input" placeholder="Ajouter des notes internes...">
        <button hx-post="/ticket/<?php echo $ticket->id; ?>/note" hx-trigger="click" hx-include="#note" hx-target=".ticket__notes-list" hx-swap="beforeend">
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
            <textarea class="ticket__reply-input" name="message" id="message" placeholder="Écrire votre réponse..."></textarea>
            <button class="btn" hx-post="/ticket/<?php echo $ticket->id; ?>/message" hx-trigger="click" hx-include="#message" hx-target=".ticket__responses-list" hx-swap="beforeend">
                <span class="material-symbols-rounded">mail</span>
                Envoyer par email
            </button>
        </div>
    </section>
    <?php else: ?>
        <p class="ticket__error">Aucun ticket trouvé.</p>
    <?php endif; ?>
</div>
<script>
  document.body.addEventListener('htmx:afterRequest', (event) => {
    if (event.target.matches('button[hx-post*="/message"]')) {
      const messageInput = document.querySelector('#message');
      if (messageInput) {
        messageInput.value = '';
      }
    }

    if (event.target.matches('button[hx-post*="/note"]')) {
      const noteInput = document.querySelector('#note');
      if (noteInput) {
        noteInput.value = '';
      }
    }
  });
</script>