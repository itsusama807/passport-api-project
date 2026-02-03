<!DOCTYPE html>
<html>

<head>
    <title>Invalid Reset Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="row d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <p id="message" class="mt-2"></p>
            <div class="card">
                <div class="card-body p-4 text-center shadow">
                    <h4 class="text-danger">Invalid or Expired Link</h4>
                    <p>This password reset link is invalid or has been expired.</p>
                    <p>Please request a new password reset.</p>
                    <button id="resendBtn" class="btn btn-primary btn-sm w-100">
                        <span id="btnText">Resend Reset Link</span>
                        <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    document.getElementById('resendBtn').addEventListener('click', async function() {

        const button = this;
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        const msg = document.getElementById('message');

        const email = new URLSearchParams(window.location.search).get('email');

        if (!email) {
            msg.innerHTML = `<div class="alert alert-danger">Email not found</div>`;
            return;
        }

        spinner.classList.remove('d-none');
        btnText.textContent = 'Sending...';
        button.disabled = true;

        try {
            const response = await fetch('/api/password/forgot-email-link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email
                })
            });

            const data = await response.json();

            if (response.ok) {
                showMessage(data.message, 'success');
            } else {
                showMessage(data.message || 'Something went wrong', 'danger');
            }

        } catch (error) {
            showMessage("Network error", "danger");
        }

        spinner.classList.add('d-none');
        btnText.textContent = 'Resend Reset Link';
        button.disabled = false;
    });

    function showMessage(message, type = 'success') {
        const msg = document.getElementById('message');

        msg.innerHTML = `
            <div class="alert alert-${type} mt-2" role="alert" id="alertBox">
                ${message}
            </div>
        `;

        setTimeout(() => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);
    }
</script>
