<main class="admin-dashboard">

<div class="sidebar">
    <h1>Admin Dashboard</h1>
    <div class="sidebar__tools">
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