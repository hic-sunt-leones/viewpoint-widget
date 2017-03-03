<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if (isset($project)) { ?>
            <?= $project['title'] ?> --
        <?php } ?>
        hetvolk.org widget
    </title>

    <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geotag-photo/dist/Leaflet.GeotagPhoto.css"/>
    <script src="https://unpkg.com/leaflet-geotag-photo/dist/Leaflet.GeotagPhoto.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>


    <!-- BOOTSTRAP Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" media="all" href="<?= $baseUrl ?>assets/css/style.css"/>

    <script src="<?= $baseUrl ?>js/pdx.js" type="application/javascript"></script>
    <script type="application/javascript">
        $(document).ready(function () {

            <?php
            if (isset($flash['notice'])):
            foreach($flash['notice'] as $msg):?>
            alertMessage('<?= $msg?>', 'alert-success');
            <?php
            endforeach;
            endif;

            if (isset($flash['error'])):
            foreach($flash['error'] as $msg):?>
            alertMessage('<?= $msg?>', 'alert-danger');
            <?php
            endforeach;
            endif;
            ?>

        });
    </script>

</head>

<body id="top">

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php if (isset($project)): ?>
                <a class="navbar-brand" href="<?= $router->pathFor('start', ['uuid' => $project['uuid']])?>">
                    <?= $project['title'] ?>
                </a>
            <?php else: ?>
                <a class="navbar-brand" href="<?= $router->pathFor('home')?>">Home</a>
            <?php endif; ?>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <?php
                if (isset($project['instructionUrl'])): ?>
                    <li class=""><a target="_blank" href="<?= $project['instructionUrl'] ?>">Handleiding</a>
                    </li>
                <?php endif ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($user)) { ?>
                    <li><a href="<?= $router->pathFor('logout', ['uuid' => $project['uuid']])?>"><?php echo $user['username']; ?>
                            uitloggen </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<?= $data['content'] ?>

<div id="footer">
    <?php if ($debug): ?>
        <pre>
<?php print_r($_SESSION); ?>
</pre>
    <?php endif; ?>
</div>


<!-- BOOTSTRAP Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

</body>
</html>
