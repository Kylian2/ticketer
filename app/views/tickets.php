<main class="admin-dashboard">

<div class="sidebar">
    <h1>Admin Dashboard</h1>
    <div class="sidebar__tools">
        <form method='GET' action="/tickets">
            <select name="f-status" id="f-status">
                <option value="all" <?php echo (!isset($_GET['f-status']) || $_GET['f-status'] === 'all') ? 'selected' : ''; ?>>Tous les statuts</option>
                <option value="new" <?php echo (isset($_GET['f-status']) && $_GET['f-status'] === 'new') ? 'selected' : ''; ?>>Nouveau</option>
                <option value="in_progress" <?php echo (isset($_GET['f-status']) && $_GET['f-status'] === 'in_progress') ? 'selected' : ''; ?>>En cours</option>
                <option value="closed" <?php echo (isset($_GET['f-status']) && $_GET['f-status'] === 'closed') ? 'selected' : ''; ?>>Fermé</option>
            </select>
            <select name="f-priority" id="f-priority">
                <option value="" <?php echo (!isset($_GET['f-priority']) || $_GET['f-priority'] === '') ? 'selected' : ''; ?>>Toutes les priorités</option>
                <option value="high" <?php echo (isset($_GET['f-priority']) && $_GET['f-priority'] === 'high') ? 'selected' : ''; ?>>Haute</option>
                <option value="medium" <?php echo (isset($_GET['f-priority']) && $_GET['f-priority'] === 'medium') ? 'selected' : ''; ?>>Moyenne</option>
                <option value="low" <?php echo (isset($_GET['f-priority']) && $_GET['f-priority'] === 'low') ? 'selected' : ''; ?>>Basse</option>
            </select>
            <select name="f-sort" id="f-sort">
                <option value="date" <?php echo (!isset($_GET['f-sort']) || $_GET['f-sort'] === 'date') ? 'selected' : ''; ?>>Trier par date</option>
                <option value="priority" <?php echo (isset($_GET['f-sort']) && $_GET['f-sort'] === 'priority') ? 'selected' : ''; ?>>Trier par priorité</option>
                <option value="status" <?php echo (isset($_GET['f-sort']) && $_GET['f-sort'] === 'status') ? 'selected' : ''; ?>>Trier par statut</option>
            </select>
            <button><span class="material-symbols-rounded">search</span>Appliquer</button>
        </form>
        <div class="sidebar__indicators">
            <div class="sidebar__indicator sidebar__indicator--new">
                <span>
                    <?php 
                        $count = count(array_filter($tickets, function($ticket) {
                            return $ticket->status === 'new';
                        }));
                        echo $count;
                    ?>
                </span>
                <span>Nouveaux</span>
            </div>
            <div class="sidebar__indicator sidebar__indicator--in-progress">
                <span>
                    <?php 
                        $count = count(array_filter($tickets, function($ticket) {
                            return $ticket->status === 'in_progress';
                        }));
                        echo $count;
                    ?>
                </span>
                <span>En cours</span>
            </div>
            <div class="sidebar__indicator sidebar__indicator--closed">
                <span>
                    <?php 
                        $count = count(array_filter($tickets, function($ticket) {
                            return $ticket->status === 'closed';
                        }));
                        echo $count;
                    ?>
                </span>
                <span>Fermés</span>
            </div>
        </div>
    </div>
    <div class="sidebar__list">
        <?php foreach($tickets as $ticket): ?>
            <div class="ticket-card" hx-get="/ticket/<?php echo $ticket->id;?>" hx-target="#zone">
                <div>
                    <h5><?php echo $ticket->title; ?></h5>
                    <span class="material-symbols-rounded">star</span>
                </div>
                <p class="ticket-card__description"><?php echo $ticket->description; ?></p>
                <div class="ticket-card__details">
                    <?php 
                        switch ($ticket->priority) {
                            case 'high':
                                echo '<span class="ticket-card__priority ticket-card__priority--high">Haute</span>';
                                break;
                            case 'medium':
                                echo '<span class="ticket-card__priority ticket-card__priority--medium">Moyenne</span>';
                                break;
                            case 'low':
                                echo '<span class="ticket-card__priority ticket-card__priority--low">Basse</span>';
                                break;
                        }
                    ?>
                    <span class="ticket-card__date"><?php echo $ticket->created_at; ?></span>
                </div>
                <p class="ticket-card__user"><span class="material-symbols-rounded">person</span><?php echo $ticket->user; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="ticket-details" id="zone">
    <div class="ticket-details__empty">
        <span class="material-symbols-rounded">chat_bubble</span>
        <h2>Selectionnez un ticket</h2>
        <p>Choisissez un ticket dans la liste pour voir les détails</p>
    </div>
</div>

</main>