<div class="container">
    <div class="row">
        <div class="col-md-6">

            <h1><?= $project['title'] ?></h1>

            <p class="lead"><em>Een project van <?= nl2br($project['organisation']) ?></em></p>

            <p class="lead"><?= nl2br($project['description']) ?></p>

        </div>
        <div class="col-md-6">

            <?php if (isset($user)) { ?>

                <div class="startblok">
                    <h3>Hallo <?= $user['username'] ?></h3>

                    <p>
                        <?= $project['instruction'] ?>
                    </p>

                    <p><br/>
                        <a href="<?= $router->pathFor('get-task', ['uuid' => $project['uuid']])?>" class="btn btn-primary"> Geef mij maar
                            een taak! </a>
                        <a href="<?= $router->pathFor('try-task', ['uuid' => $project['uuid']])?>" class="btn btn-primary"> Oefenen </a>
                    </p>
                </div>

            <?php } else { ?>
                <div class="row loginblock">
                    <div class="col-md-6">
                        <h2>Log in</h2>

                        <form action="<?= $router->pathFor('login')?>" method="post">

                            <input class="form-control" placeholder="gebruikersnaam of emailadres" type="text"
                                   name="name"/><br/>

                            <input class="form-control" placeholder="wachtwoord" type="password" name="password"/><br/>
                            <input type="hidden" name="uuid" value="<?=$project['uuid']?>"/><br/>

                            <input class="btn btn-primary" type="submit" value="Log In"/>

                        </form>

                    </div>
                    <div class="col-md-6">
                        <h2>Geen account?</h2>
                        <p>
                            Om mee te doen aan dit project heb je
                            <a target="_blank" href="https://hetvolk.org/register/">een account van hetvolk.org</a> nodig.
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>


