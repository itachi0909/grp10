<?php
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$book_name = "";
	$book_author = "";
	$book_no = "";
	$student_id="";
	$user="";
	$issue_date="";
	$query = "select issued_books.book_name,issued_books.book_no,issued_book.author_name from issued_books left join book on issued_book.book_no = books.book_id";
	// $query_run = mysqli_query($connection,$query);
	// while ($row = mysqli_fetch_assoc($query_run)){
	// 	$book_name = $row['book_name'];
	// 	$book_author = $row['book_author'];
	// 	$book_no = $row['book_no'];
	// 	$student_id = $row['student_id'];
    //     $user = $row['user'];
	// 	$issue_date = $row['issue_date'];
	// }
?>
<!DOCTYPE html>
<html>

<head>
	<title>Issued Books</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
	<script type="text/javascript" src="bootstrap-4.4.1/js/juqery_latest.js"></script>
	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="admin/style.css">
	<link rel="stylesheet" href="issue_form.css">
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
			</div>
			<font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name'];?></strong></span></font>
			<font style="color: white"><span><strong>Email: <?php echo $_SESSION['email'];?></strong></font>
			<ul class="nav navbar-nav navbar-right">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="view_profile.php">View Profile</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="change_password.php">Change Password</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav><br>
	<span>
		<marquee class="text1">This is library mangement system. Library opens at 8:00 AM and close at 8:00 PM</marquee>
	</span><br><br>

	<div>
		<button class="open-button" onclick="openForm()">Book Issue Form</button><br>

		<div class="form-popup" id="myForm">
			<form action="view_issued_book.php" method="POST"  class="form-container">
				<h3>ISSUE BOOK FORM</h3>
				<div class="form-element">
					<form class="">
						<div class="row g-3">
					<div class="form-group col-md-6">
						<label for="book_name">Book Name:</label>
						<input type="text" name="book_name" class="form-control" required>
					</div>
					<div class="form-group col-md-6">
						<label for="book_author">Author ID:</label>
						<select class="form-control" name="book_author">
							<option>-Select author-</option>
							<?php  
								$connection = mysqli_connect("localhost","root","");
								$db = mysqli_select_db($connection,"lms");
								$query = "select author_name from authors";
								$query_run = mysqli_query($connection,$query);
								while($row = mysqli_fetch_assoc($query_run)){
									?>
									<option><?php echo $row['author_name'];?></option>
									<?php
								}
							?>
						</select>
						<!--<input type="text" name="book_author" class="form-control" required> -->
					</div>
							</div>
							<div class="row g-3">
					<div class="form-group col-md-6">
						<label for="book_no">Book Number:</label>
						<input type="text" name="book_no" class="form-control" required>
					</div>
					<div class="form-group col-md-3">
						<label for="student_id">Student ID:</label>
						<input type="text" name="student_id" class="form-control" required>
					</div>
					<div class="form-group col-md-3">
								<label for="inputState" class="form-label">User</label><br>
								<select id="inputState" class="form-select" name="user" class="user" required>
									<option>Teacher</option>
									<option>Student</option>
								</select>
							</div>
						</div>
							</div>
					<div class="form-group">
						<label for="issue_date">Issue Date:</label>
						<input type="text" name="issue_date" class="form-control" value="<?php echo date("yy-m-d");?>" required>
					</div>
					<button type="submit" name="issue_book" class="btn btn-primary">Issue Book</button>
					<button type="button" class="btn cancel" onclick="closeForm()">
							Close
						</button>
					</form>
							</div>
					<!-- <div class="col-12">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="gridCheck"
                />
                <label class="form-check-label" for="gridCheck">
                  Check me out
                </label>
              </div>
              </div> -->
					<div class="col-12">
						<br>
						<!-- <button type="submit" name="view_issued_book" class="btn btn-primary">Submit</button> -->
						
					</div>
				</div>
			</form>
		</div>
	</div>
	<br><br>
	<div class="bg1"> <br>
		<center>
			<h4>Issued Book's Detail</h4><br>
		</center>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form>
					<table class="table-bordered" width="900px" style="text-align: center">
						<tr>
							<th>Name</th>
							<th>Author</th>
							<th>Number</th>
						</tr>

						<?php
						$query_run = mysqli_query($connection,$query);
						while ($row = mysqli_fetch_assoc($query_run)){
							// $book_name = $row['book_name'];
							// $book_author = $row['book_author'];
							// $book_no = $row['book_no'];
							?>
						<!-- <tr>
							<td><?php echo $row['book_name'];?></td>
							<td><?php echo $row['book_author'];?></td>
							<td><?php echo $row['book_no'];?></td>
						</tr> -->

						<?php
						}
					?>
					</table>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<br><br>
	</div>

	<script>
		function openForm() {
			document.getElementById("myForm").style.display = "block";
		}

		function closeForm() {
			document.getElementById("myForm").style.display = "none";
		}
	</script>
</body>

</html>

<?php
	if(isset($_POST['issue_book']))
	{
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$query = "insert into issued_books values(null,$_POST[book_no],'$_POST[book_name]','$_POST[book_author]',$_POST[student_id],'$_POST[user]','$_POST[issue_date]')";
		$query_run = mysqli_query($connection,$query);
		#header("Location:admin_dashboard.php");
	}
?>