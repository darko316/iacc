<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	
	<title>Restaurar una base de datos</title>
	<link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.min.css">
</head>
<body>
<div class="container">
	<h1 class="text-center" style="margin-top:30px;"> Restaurar una base de datos </h1>
	<hr>
	<div class="row justify-content-center">
		<div class="col-sm-6">
			<?php
				if(isset($_SESSION['error'])){
					?>
					<div class="alert alert-danger text-center">
						<?php echo $_SESSION['error']; ?>
					</div>
					<?php
 
					unset($_SESSION['error']);
				}
 
				if(isset($_SESSION['success'])){
					?>
					<div class="alert alert-success text-center">
						<?php echo $_SESSION['success']; ?>
					</div>
					<?php
 
					unset($_SESSION['success']);
				}
			?>
			<div class="card">
				<div class="card-body">
					<h3>Credenciales de la base de datos</h3>
					<br>
					<form method="POST" action="restore.php" enctype="multipart/form-data">
					    <div class="form-group row">
					     	<label for="server" class="col-sm-3 col-form-label">Servidor</label>
					      	<div class="col-sm-9">
					        	<input type="text" class="form-control" id="server" name="server" placeholder="Ejemplo: 'localhost'" required>
					      	</div>
					    </div>
					    <div class="form-group row">
					      	<label for="username" class="col-sm-3 col-form-label">Usuario</label>
					      	<div class="col-sm-9">
					        	<input type="text" class="form-control" id="username" name="username" placeholder="Ejemplo: 'root'" required>
					      	</div>
					    </div>
					    <div class="form-group row">
					      	<label for="password" class="col-sm-3 col-form-label">Contraseña</label>
					      	<div class="col-sm-9">
					        	<input type="text" class="form-control" id="password" name="password" placeholder="db password">
					      	</div>
					    </div>
					    <div class="form-group row">
					      	<label for="dbname" class="col-sm-3 col-form-label">Dase de datos</label>
					      	<div class="col-sm-9">
					        	<input type="text" class="form-control" id="dbname" name="dbname" placeholder="base de datos que deseas restaurar" required>
					      	</div>
					    </div>
					    <div class="form-group row">
					      	<label for="sql" class="col-sm-3 col-form-label">Archivo</label>
					      	<div class="col-sm-9">
					        	<input type="file" class="form-control-file" id="sql" name="sql" placeholder="base de datos que deseas restaurar" required>
					      	</div>
					    </div>
					    <button type="submit" class="btn btn-primary" name="restore">Restaurar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>