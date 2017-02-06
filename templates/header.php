<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $_SESSION['project']['title'] ?></title>

<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-geotag-photo/dist/Leaflet.GeotagPhoto.css" />
<script src="https://unpkg.com/leaflet-geotag-photo/dist/Leaflet.GeotagPhoto.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>


<!-- BOOTSTRAP Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" media="all" href="<?= $baseUrl ?>public/assets/css/style.css" />

</head>

<body>

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
            <a class="navbar-brand" href="<?= $baseUrl ?>">
                <?php if(isset($_SESSION['project']['title'])){ ?>
                    <?= $_SESSION['project']['title'] ?>
                <?php }else{ ?>
                    Home
                <?php } ?>
                </a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Projecten
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ path('projects-overview') }}">alle projecten</a></li>
                        <li><a href="{{ path('myprojects-overview') }}">mijn projecten</a></li>
                        <li><a href="{{ path('myprojects-new') }}">nieuw project maken</a></li>
                    </ul>
                </li>
                -->


                <li class=""><a href="{{ path('faq') }}">Handleiding</a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['user'])){ ?>
                    <li><a href="<?= $baseUrl ?>user/logout"><?php echo $_SESSION['user']['username']; ?> uitloggen </a></li>
                <?php }else{ ?>
                    <li><a href="<?= $baseUrl ?>"><?php echo $_SESSION['user']['username']; ?> inloggen? </a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
