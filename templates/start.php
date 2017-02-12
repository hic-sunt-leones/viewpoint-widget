
<div class="container">
	<div class="row">
		<div class="col-md-6">

			<h1><?= $_SESSION['project']['title'] ?></h1>
			
			<p class="lead"><?= nl2br($_SESSION['project']['description']) ?></p>

		</div>
		<div class="col-md-6">


			<?php if(isset($_SESSION['user'])){ ?>


				<div class="startblok">
					<h3>Hallo <?= $_SESSION['user']['username'] ?></h3>

					<p>
					<?= $_SESSION['project']['instruction'] ?>
					</p>

					<p><br />
					<a href="<?= $baseUrl ?>get-task" class="btn btn-primary"> Geef mij maar een taak! </a> <a href="<?= $baseUrl ?>try-task/" class="btn btn-primary"> Oefenen </a>
					</p>
				</div>

			<?php }else{ ?>
				<div class="row loginblock">
					<div class="col-md-6">
						<h2>Log in</h2>

						<form action="<?= $baseUrl ?>user/login" method="post">

						<input class="form-control" placeholder="gebruikersnaam of emailadres" type="text" name="name" /><br />

						<input class="form-control" placeholder="wachtwoord" type="password" name="password" /><br />

						<input class="btn btn-primary" type="submit" value="Log In" />

						</form>
						
					</div>
					<div class="col-md-6">
						<h2>Geen account?</h2>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>
</div>


