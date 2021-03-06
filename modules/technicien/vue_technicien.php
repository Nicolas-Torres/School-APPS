<?php

require_once 'modules/generique/vue_generique.php';

class VueTechnicien extends VueGenerique
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pageAccueilTech($moduleContent, $url)
    {
        include 'include/inc_breadcrumb.php';
?>
        <section>
            <div class="content-block">
                <div class="container">
                    <section class="row">
                        <nav class="col-lg-3" id="sideNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <a href="/technicien" class="btn btn-nav">Tableau de bord</a>
                                        </div>
                                    </div>
                                </li>
                                <div id="accordion">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button class="btn btn-nav dropdown-toggle" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Votre profil
                                            </button>
                                        </div>
                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                            <a class="dropdown-item" href="/technicien/mes-informations">Mes informations</a>
                                            <a class="dropdown-item" href="/technicien/changer-login">Changer mon login</a>
                                            <a class="dropdown-item" href="/technicien/nouveau-mot-de-passe">Changer mon mot de passe</a>
                                        </div>
                                    </div>
                                </div>
                                <li class="nav-item">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <a href="/technicien/mes-tickets" class="btn btn-nav">Mes Tickets</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                        <div class="col-lg-7">
                            <h1>Votre espace technicien, <?php echo ucfirst($_SESSION['login']); ?> </h1>
                            <?= $moduleContent ?>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    <?php
    }

    public function tableauBord($profil, $stats)
    {
    ?>
        <h3>Mon tableau de bord</h3>
        <section class="row">
            <?php
            $this->afficherProfil($profil);
            $this->statsTickets($stats);
            ?>
        </section>
    <?php
    }

    public function afficherProfil($profil)
    {
    ?>
        <aside class="col-lg-12 p-1 m-2">
            <div class="card">
                <div class="card-header">
                    <h4>Vous</h4>
                </div>
                <div class="card-body">
                    Nom : <?= $profil['nom'] ?> <br>
                    Prénom : <?= $profil['prenom'] ?> <br>
                    E-mail : <?= $profil['emailFacturation'] ?> <br>
                    Téléphone : <?= $profil['telephone'] ?> <br>
                </div>
            </div>
        </aside>
    <?php
    }

    public function statsTickets($stats)
    {
    ?>
        <aside class="col-lg-12 p-1 m-2">
            <div class="card">
                <div class="card-header">
                    <h4>Statistique</h4>
                </div>
                <div class="card-body">
                    <?php
                    foreach ($stats as &$ticket) {
                    ?><h5>Tickets <?= $ticket['etat'] ?> : <?= $ticket['nbr'] ?> </h5>
                    <?php
                    }
                    unset($ticket);
                    ?>
                </div>
            </div>
        </aside>
        <?php
    }

    public function afficheTickets($result)
    {
        ?>
        <div class="m-2 p-2">
            <a href="/technicien/tickets-en-attente" class="btn btn-outline-primary">Tickets en attente</a>
            <a href="/technicien/tickets-fermes" class="btn btn-outline-primary">Tickets fermés</a>
            <a href="/technicien/tickets-en-cours" class="btn btn-outline-primary">Tickets en cours</a>
            <a href="/technicien/tickets-urgent" class="btn btn-outline-primary">Tickets urgents</a>
        </div>

        <?php


        foreach ($result as &$ticket) {
        ?>
            <div class="ticket row card">
                <div class="col-lg card-header">
                    <?php
                        switch ($ticket['etat']) {
                            case 'Urgent':
                                echo '<img class="m-2" src="\..\images\etats\Urgent.png">';
                                break;
                            case 'En cours':
                                echo '<img class="m-2" src="\..\images\etats\En cours.png">';
                                break;
                            case 'Fermé':
                                echo '<img class="m-2" src="\..\images\etats\Fermé.png">';
                                break;
                            case 'En attente':
                                echo '<img class="m-2" src="\..\images\etats\En attente.png">';
                                break;
                        }
                    ?>
                    <h4 class="d-inline"><?= $ticket['intitule'] ?> - N°<?= $ticket['idTicket'] ?></h4>
                </div>
                <div class="col-lg card-body">
                    <p id="explication"><?= $ticket['explication']; ?></p>
                </div>
                <div class="col-lg card-footer">
                    <div class="row">
                        <span class="col-8">
                            État : <?= $ticket['etat']; ?> - Id-Produit : <?= $ticket['idProduit']; ?> - le : <?= $ticket['dateCreation']; ?>
                        </span>
                        <a class="btn lire-plus col-3" href="/technicien/ticket/<?= $ticket['idTicket'] ?>">Voir plus</a>
                    </div>
                </div>
            </div>
        <?php
        }
        unset($ticket);
    }

    public function afficheTicket($ticket, $infoClient, $etats)
    {
        ?>
        <div class="row">
            <aside class="card col-lg-7 p-1 m-2">
                <div class="card-header">
                    <?php
                    switch ($ticket['etat']) {
                        case 'Urgent':
                            echo '<img class="m-2" src="\..\images\etats\Urgent.png">';
                            break;
                        case 'En cours':
                            echo '<img class="m-2" src="\..\images\etats\En cours.png">';
                            break;
                        case 'Fermé':
                            echo '<img class="m-2" src="\..\images\etats\Fermé.png">';
                            break;
                        case 'En attente':
                            echo '<img class="m-2" src="\..\images\etats\En attente.png">';
                            break;
                    }
                    ?>
                    <h4 class="d-inline">
                        <span class="info"> N°<?= $ticket['idTicket'] ?></span>
                        <span class="info"> - <?= $ticket['intitule'] ?></span>
                    </h4>
                </div>
                <div class="col-lg card-body">
                    <p id="explication"><?= $ticket['explication']; ?></p>
                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('explication').style.display = 'inherit'">Lire la suite</button>
                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('explication').style.display = '-webkit-box'">Réduire</button>
                    <div class="col-lg card-body">
                        <form method="post" action="/technicien/ticket/<?= $ticket['idTicket'] ?>/changer-etat" class="input-group">
                            <select class="custom-select form-control" id="inputGroupSelect04" name="nouveauEtat">
                                <?php
                                foreach ($etats as &$etat) {
                                ?> <option value="<?= $etat['idEtat'] ?>"><?= ucfirst($etat['etat']) ?></option>
                                <?php
                                }
                                unset($etat);
                                ?>
                            </select>
                            <div class="input-group-append">
                                <input class="btn btn-outline-success" type="submit" value="Changer l'état">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg card-footer">
                    <a class="btn lire-plus-r" id="btn-chat" href="/technicien/chat/<?= $ticket['idTicket'] ?>">Chat</a>
                </div>
            </aside>
            <aside class="card col-lg-4 p-1 m-2" id="info-client">
                <div class="col-lg card-header">
                    <h4 class="d-inline">Informations du client</h4>
                </div>
                <div class="col-lg card-body">
                    Nom : <?= $infoClient['nom'] ?> <br>
                    Prenom : <?= $infoClient['prenom'] ?> <br>
                    Email : <?= $infoClient['emailFacturation'] ?> <br>
                    Telephone : <?= $infoClient['telephone'] ?> <br>
                </div>
            </aside>
        </div>
    <?php
    }

    public function chat()
    {
    ?>
        <link rel="stylesheet" href="../../css/chat.css">

        <body>
            <header>
                <h1>Chat</h1>
            </header>

            <section class="chat">
                <div class="messages">
                </div>
                <div class="user-inputs">
                    <form id="envoiMessage" method="POST">
                        <input type="text" id="content" name="content" placeholder="Envoyer message">
                        <button type="submit">Envoyer</button>
                    </form>
                </div>
            </section>
            <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
            <script src="../../js/chatbis.js"></script>
        </body> <?php
            }

            public function json($result)
            {
                echo json_encode($result);
            }

            public function nouveauLogin()
            {
                echo '<h3>Changer de Login</h3>
		<hr class="mt-2 mb-4">
		
		<div class="card-panel  lighten-4">
			<form action="/technicien/changer-login" method="POST">
				<div class="row">
					<div class="col-4 form-group">
						<label for="nouveauLogin">Login</label>
						<input name="nouveauLogin" type="text" class="form-control" required pattern="\S+.*" placeholder="Votre nouveau login">
						<button class="btn btn-primary " type="submit" name="action">Valider</button>
					</div>
				</div>
			</form>
		</div>';
            }

            public function loginMisAjour($newLogin)
            {
                ?> <span class="alert-warning">Votre login a bien été mis à jour<br>
            (Lors de votre prochaine connexion il faudra utiliser celui-ci : <?= $newLogin ?>)</span><?php
                                                                                                    }


            public function nouveauMotDePasse()
            {
                echo  '<h3>Changer votre mot de passe</h3>
                        <hr class="mt-2 mb-4">
                        
                        <form action="/technicien/nouveau-mot-de-passe" method="post">
                            <div class="row">
                                <div class="col-4 form-group">
                                    <label for="old_password">Ancien mot de passe</label>
                                    <input type="password" name="old_password" class="form-control" required>
                                </div>
                                <div class="col-4 form-group">
                                    <label for="nouveau_password1">Nouveau mot de passe</label>
                                    <input type="password" name="nouveau_password1" class="form-control" required>
                                </div>
                        
                                <div class="col-4 form-group">
                                    <label for="nouveau_password2">Confirmation mot de passe</label>
                                    <input type="password" name="nouveau_password2" class="form-control" required>
                                </div>		
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>
                            </div>
                        </form>';
                }
}