<html>
<head>
	<title>MarionetteRouter Test</title>

	<style>
	nav li.logout {
		display: none;
	}
	</style>
</head>
<body>

	<header>
		<h1>MarionetteRouter Test</h1>

		<nav>
			<ul>
				<li class="home"><a href="/" data-route="home">Home</a></li>
				<li class="users"><a href="/users" data-route="users_list">Users</a></li>
				<li class="users-alias"><a href="/some-alias" data-route="users_alias">Users Alias</a></li>
				<li class="user_42"><a href="/users/42" data-route="user_show" data-id="42">User #42</a></li>
				<li class="login"><a href="/login" data-route="login">Login</a></li>
				<li class="logout"><a href="/logout" data-route="logout">Logout</a></li>
			</ul>
		</nav>

		<div class="user"></div>
	</header>

	<div id="main"></div>


	<!-- Include Libs -->
	<script type="text/javascript" src="/libs/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="/libs/backbone/underscore-min.js"></script>
	<script type="text/javascript" src="/libs/backbone/backbone-min.js"></script>
	<script type="text/javascript" src="/libs/backbone/backbone.marionette.min.js"></script>

	<!-- Include MarionetteRouter -->
	<script type="text/javascript" src="/src/backbone.marionette.router.js"></script>

	<script type="text/javascript">
	(function() {
		"use strict";

		var App = new Backbone.Marionette.Application(),
			Router = Backbone.MarionetteRouter;

		window.App = App;
		App.Router = Router;

		App.user = null;

		Router.map(function() {
			
			this.route("home", {
				"path": "/",
				"action": function() {
					$("#main").html("Current page: Home");
				}
			});

			this.route("users_list", {
				"path": "/users",
				"action": function() {
					$("#main").html("Current page: Users");
				}
			});

			this.route("users_alias", {
				"path": "/some-alias",
				"action": "users_list"
			});

			this.route("user_show", {
				"path": "/users/:id",
				"authed": true,
				"action": function(userId) {
					$("#main").html("Current page: User #" + userId);
				}
			});

			this.route("login", {
				"path": "/login",
				"authed": false,
				"action": function() {
					App.user = prompt("Enter your name :", "JS Ninja");

					if (App.user != null) {
						$(".user").html("Current user : " + App.user);
						$("nav .login").hide().siblings(".logout").show();

						App.Router.authed = true;

						_.defer(function() {
							App.Router.go("home");
						});
					}
				}
			});

			this.route("logout", {
				"path": "/logout",
				"authed": true,
				"action": function() {
					if (App.user != null) {
						$(".user").html("");
						$("nav .logout").hide().siblings(".login").show();

						App.user = null;

						App.Router.authed = false;

						_.defer(function() {
							App.Router.go("home");
						});
					}
				}
			});

		});


		App.MenuView = Backbone.Marionette.ItemView.extend({
			"events": {
				"click a": "navigate"
			},

			"initialize": function() {
				console.log("[App.MenuView.initialize] init the menu view");
			},

			"navigate": function(e) {
				e.preventDefault();
				
				var $el = $(e.target);

				var route = $el.attr("data-route"),
					id = $el.attr("data-id");

				if (id !== undefined) {
					App.Router.go(route, [id]);
				} else {
					App.Router.go(route);
				}
			},

			"render": function() {}
		});


		$(function() {
			App.start();
			Router.start(App);

			var menu = new App.MenuView({
				"el": $("header nav")
			});
		});
	})();
	</script>
</body>
</html>