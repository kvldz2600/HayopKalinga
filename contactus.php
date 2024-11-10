<!DOCTYPE html>
<html>
	<head>
		<title>Hayop Kalinga | About Us </title>
		<link rel="stylesheet" type="text/css" href="contactus.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
	</head>
	<body>
		<nav>
			<label class="logo" href = "home.html">Hayop Kalinga</label>
			<ul>
				<li><a class="action" href = "home.php">Home</a></li>
				<li><a class="action" href = "services.php">Services</a></li>
				<li><a class="action" href = "aboutus.php">About Us</a></li>
				<li><a class="active">Contact</a></li>
				<button class="login-popup" type="button" onclick="location.href = 'login.php';"> Login</button>
			</ul>
		</nav>
		<div class="container">
			<h1>Connect with Us</h1>
			<p>We would love to respond to your queries and help you. Feel free to get in touch with us.</p>
				<div class="contact-box">
					<div class="contact-left">
						<h3>Send your request</h3>
						<form>
							<div class="input-row">
								<div class="input-group">
								<label class="contact">Name</label>
								<input type="text" placeholder="Name">
								</div>
								<div class="input-group">
								<label class="contact">Phone</label>
								<input type="tel" placeholder="Phone">
								</div>
							</div>
							<div class="input-row">
							
							<div class="input-group">
								<label class="contact">Email</label>
								<input type="email" placeholder="Email">
								</div>
								<div class="input-group">
								<label class="contact">Subject</label>
								<input type="text" placeholder="Subject">
								</div>
							</div>
							
							<div>
								<label class="contact">Message</label>
								<textarea rows="5" placeholder="Your Message"></textarea>
							</div>
							
							<button type="submit">Send</button>
						</form>
					</div>
					<div class="contact-right">
						<h3>Reach Us</h3>
						<table>
							<tr>
								<td>Email</td>
								<td>hayopkalinga@gmail.com</td>
							</tr>
							<tr>
								<td>Phone</td>
								<td>+639123456789</td>
							</tr>
							<tr>
								<td>Address:</td>
								<td>St st blah blah balh</td>
							</tr>
						</table>
					</div>
			</div>
		</div>
	</body>
</html>