<div class="container" id="task">
    <div class="row">
        <div class="col-md-6">

            <?php if ($task['item']['title'] != "") { ?>
                <h3><?= $task['item']['title'] ?></h3>
            <?php } ?>

            <?php if ($task['item']['image'] != ""){ ?>
            <?php if ($task['item']['uri'] != ""){ ?>
            <a href="<?= $task['item']['uri'] ?>" target="_blank">
                <?php }else{ ?>
                <a href="<?= $task['item']['image'] ?>" target="_blank">
                    <?php } ?>
                    <img id="photo" src="<?= $task['item']['image'] ?>" style="width:100%"
                         alt="klik opent afbeelding in nieuw venster"/>
                </a>
                <?php } ?>

                <?php if ($task['item']['description'] != "") { ?>
                    <p class="lead"><?= $task['item']['description'] ?></p>
                <?php } ?>

                <?php if (count($task['item']['data'])) { ?>
                    <?php
                    if (! is_array($task['item']['data'])) {
                        $task['item']['data'] = json_decode($task['item']['data'], true);
                    }
                    ?>
                    <table class="table">
                        <?php foreach ($task['item']['data'] as $key => $value) { ?>
                            <tr>
                                <th><?= $key ?></th>
                                <td><?= $value ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>


        </div>
        <div class="col-md-6">

            <h3><?= $task['question'] ?></h3>

            <div id="map-container">
                <div id="map">
                </div>

                <div id="slider-container" style="<?php if(empty($task['angle'])) { echo "display:none;"; } ?>">
                    <label for="slider">hoek:</label>
                    <input id="slider" type="range" min="1" max="120" step="1" value="50">
                    <span id="angle"></span>
                </div>
            </div>


            <form id="task-form" action="<?= $router->pathFor('save-task', ['uuid' => $project['uuid']])?>" method="post">
                <input class="form-control" type="hidden" name="itemId" id="itemId" value="<?= $task['item']['id'] ?>"/>
                <input class="form-control" type="hidden" name="targetPoint" id="targetPoint"/>
                <input class="form-control" type="hidden" name="cameraPoint" id="cameraPoint"/>
                <input class="form-control" type="hidden" name="fieldOfView" id="fieldOfView"/>
                <?php /*
		    	<textarea class="form-control" name="fieldOfView" id="fieldOfView"></textarea><br />
          */ ?>
            </form>


            <button id="save-button" class="btn btn-primary">taak opslaan</button>
            <button id="skip-button" class="btn btn-default">taak overslaan</button>

            <?php if ($task['description'] != "") { ?>
                <p>
                    <?= $task['description'] ?>
                </p>
            <?php } ?>

            <?php if (!empty($project['instructionUrl'])) { ?>
            <p>Een uitgebreide instructie en handige tips vind je in <a href="<?= $project['instructionUrl']?>">de handleiding</a></p>
            <?php } ?>


        </div>
    </div>
</div>

<script>

    var map = L.map('map', {
        center: [<?= $task['mapLatLon'] ?>],
        zoom: <?= $task['mapZoomLevel'] ?>,
        minZoom: 6,
        maxZoom: 18,
        scrollWheelZoom: false
    });

    var baseLayer = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var cameraPoint = [<?= $task['mapLonLat'] ?>]
    var targetPoint = [<?= $task['mapLonLat'] ?>]
    var points = {
        type: 'Feature',
        properties: {
            angle: 85
        },
        geometry: {
            type: 'GeometryCollection',
            geometries: [
                {
                    type: 'Point',
                    coordinates: targetPoint
                },
                {
                    type: 'Point',
                    coordinates: cameraPoint
                }
            ]
        }
    }

    var options = {
        cameraIcon: L.icon({
            iconUrl: '<?= $baseUrl ?>assets/img/camera.svg',
            iconSize: [38, 38],
            iconAnchor: [19, 19]
        }),

        targetIcon: L.icon({
            iconUrl: '<?= $baseUrl ?>assets/img/target.svg',
            iconSize: [180, 32],
            iconAnchor: [90, 16]
        })
    }

    var geotagPhotoCamera = L.GeotagPhoto.camera(points, options).addTo(map)
        .on('change', function (event) {
            updateSidebar()
        })
        .on('input', function (event) {
            updateSidebar()
        });

    var outputElement = document.getElementById('output');

    function updateSidebar() {
        var fieldOfView = geotagPhotoCamera.getFieldOfView()
        //console.log(JSON.stringify(fieldOfView, null, 2))
        //outputElement.innerHTML = JSON.stringify(fieldOfView, null, 2)
        //hljs.highlightBlock(outputElement)
        document.getElementById("fieldOfView").value = JSON.stringify(fieldOfView, null, 2)
        var cameraPoint = geotagPhotoCamera.getCameraPoint()
        document.getElementById("cameraPoint").value = JSON.stringify(cameraPoint, null, 2)
        var targetPoint = geotagPhotoCamera.getTargetPoint()
        document.getElementById("targetPoint").value = JSON.stringify(targetPoint, null, 2)

    }

    var slider = document.getElementById('slider');
    var angleText = document.getElementById('angle');

    function updateAngle() {
        var angle = parseInt(slider.value)
        geotagPhotoCamera.setAngle(angle)
        angleText.innerHTML = angle + '°'
        updateSidebar()
    }

    slider.addEventListener('input', updateAngle);
    updateSidebar();
    updateAngle();

    $(document).ready(function () {

        $("#save-button").click(function () {
            $("#task-form").submit();
        });

        $("#skip-button").click(function () {
            // $.post( "/skip-task", $( "#task-form" ).serialize() );
            window.location = "<?= $router->pathFor('skip-task', ['uuid' => $project['uuid']])?>?itemId=" + $('#itemId').val();
        });

    });

</script>


