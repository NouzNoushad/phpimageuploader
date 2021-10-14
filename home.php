<?php

$errors = ['upload' => '', 'error' => ''];
if(isset($_POST['submit-upload'])){

	if(empty($_FILES['file']['name'])){
		$errors['upload'] = 'please choose an image from the file';
	}
	else{

		$file = $_FILES['file'];
		$fileName = $_FILES['file']['name'];
		$fileTmpName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$fileError = $_FILES['file']['error'];

		$fileExt = explode('.', $fileName);
		$fileRealExt = strtolower(end($fileExt));
		
		$arrayExt = ['jpg', 'jpeg', 'png'];
		if(in_array($fileRealExt, $arrayExt)){
			if($fileError !== 0){
				$errors['error'] = 'File upload error';
			}
			else{
				if($fileSize > 1000000){
					$errors['error'] = 'Unhandled File size';
				}
				else{
					$fileUniqueName = uniqid('', true) . '.' . $fileRealExt;
					$fileDestination = 'uploads/' . $fileUniqueName;
					move_uploaded_file($fileTmpName, $fileDestination);
				}
			}
		}
	}
}


?>

<?php session_start() ?>
<?php if(isset($_SESSION['name'])): ?>

<?php include('./templates/header.php') ?>

<div class="container">
<div class="row my-5 py-5">
	<div class="col-md-8 m-auto">
		<div class="card card-body border-success">
			<form action="<?= $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
				<div class="row justify-content-center">
					<?php if(empty($fileUniqueName)): ?>
						<div class="col-md-10 mt-4">
							<small class="text-success">Please upload an Image</small>
						</div>
					<?php else: ?>
					<div class="col-md-10 mt-4">
						<img src="uploads/<?= $fileUniqueName ?? '' ?>" alt="<?= $fileUniqueName ?? '' ?>" width=705>
					</div>
					<?php endif ?>
					<div class="col-md-10 mt-4">
						<?php if($errors['upload']): ?>
							<input type="file" name="file" class="form-control border-danger">
							<small class="text-danger"><?= $errors['upload']?></small>
						<?php else: ?>
							<input type="file" name="file" class="form-control border-success">
						<?php endif ?>
					</div>
					<div class="col-md-10 mt-3 mb-4">
						<button type="submit" name="submit-upload" class="btn btn-success form-control">Upload</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>

<?php include('./templates/footer.php') ?>

<?php else: ?>

	<?php include('./templates/header.php') ?>

		<div class="container">
			<div class="row my-5 py-5">
			<div class="col-md-7 m-auto">
				<div class="card card-body border-success">
					<form action="<?= $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
						<div class="row justify-content-center">
							<div class="col-md-10 mt-3 mb-4">
								<h5 class=" text-success">Please Login First & Upload Images</h5>
							</div>
							<div class="col-md-10 mt-3 mb-4">
								<a href="login.php" class="btn btn-success form-control">Login</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include('./templates/footer.php') ?>

<?php endif ?>