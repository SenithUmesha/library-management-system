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
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="post" enctype="multipart/form-data">
                        <div class="form-outline mb-4">
                            <label class="form-label">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" required class="form-control" />
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirmPassword" id="confirmPassword" required class="form-control" />
                        </div>
                        <div class="text-center">
                            <button type="button" id="changePasswordBtn" class="btn btn-primary btn-block mb-4">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script>
        $(document).ready(function() {
            $("#changePasswordBtn").click(function() {
                $("#changePasswordModal").modal('show');
            });

            $("#changePasswordBtn").click(function() {
                var newPassword = $("#newPassword").val();
                var confirmPassword = $("#confirmPassword").val();

                $.ajax({
                    url: './api/api_change_password.php',
                    type: 'POST',
                    data: {
                        action: 'changePassword',
                        newPassword: newPassword,
                        confirmPassword: confirmPassword
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert('Password changed successfully');
                            $("#changePasswordModal").modal('hide');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.log('Response:', xhr.responseText);
                        alert('Error changing password');
                    }
                });
            });
        });
    </script>
</body>