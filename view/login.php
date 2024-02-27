<?php
require __DIR__ . '../../util/snippet.php';
require __DIR__ . '../../includes/db_conn.php';
require __DIR__ . '../../util/functions.php';
?>

<style>
    body {
        padding: 0;
        margin: 0;
        width: 100%;
    }
</style>

<body>
    <div class="container" style="width: fit-content; padding-top: 80px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
            <h4 style="font-weight: bold;">| Login</h4>
        </div>
        <div style="margin-top: 30px">
            <form id="loginForm" role="form" method="post" enctype="multipart/form-data">
                <div class="form-outline mb-4">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" required class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" required class="form-control" />
                </div>
                <div class="text-center">
                    <button type="button" id="loginBtn" class="btn btn-primary btn-block mb-4">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginBtn").click(function() {
                var username = $("#username").val();
                var password = $("#password").val();

                $.ajax({
                    url: './api/api_login.php',
                    type: 'POST',
                    data: {
                        action: 'submit',
                        username: username,
                        password: password
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.log('Response:', xhr.responseText);
                        alert('Error logging in');
                    }
                });
            });
        });
    </script>
</body>