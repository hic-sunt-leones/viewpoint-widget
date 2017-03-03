
<div id="thanks" class="container">
	<div class="row">
		<div class="col-md-4">

			

		</div>
		<div class="col-md-4">
			<h1>Opgeslagen. Thanks!</h1>

			<a href="<?= $router->pathFor('get-task', ['uuid' => $project['uuid']])?>">
			<img style="width: 100%;" src="<?= $baseUrl ?>assets/img/thanks/thanks<?= $randomThanks ?>.jpg" alt="een fijne afbeelding bij wijze van dank" />
			</a>

			<h1><a href="<?= $router->pathFor('get-task', ['uuid' => $project['uuid']])?>">volgende</a></h1>

		</div>
		<div class="col-md-4">

			

		</div>
	</div>
</div>


