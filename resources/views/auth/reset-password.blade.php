<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="row vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-4">
            <p id="message"></p>
            <div class="card">
                <div class="card-header text-center">
                    <h4>Reset Your Password</h4>
                </div>
                <div class="card-body">
                    <form id="resetForm">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="text-center mb-3">
                            <label class="d-block text-start">New Password</label>
                            <input type="password" name="password" id="passwordError" class="form-control">
                        </div>


                        <div class="text-center mb-3">
                            <label class="d-block text-start">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="passwordConfirmationError"
                                class="form-control">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="form-control btn btn-primary btn-sm">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            const response = await fetch('/api/password/reset', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = '';

            if (response.ok) {
                messageDiv.innerHTML = `<div class="alert alert-success mt-2" role="alert">${data.message}</div>`;
                form.reset();
            } else {
                let errorsHtml = '';
                if (data.errors) {
                    for (const key in data.errors) {
                        data.errors[key].forEach(msg => {
                            errorsHtml += `<div>${msg}</div>`;
                        });
                    }
                } else if (data.message) {
                    errorsHtml = data.message;
                    form.reset();
                } else if (data.error) {
                    errorsHtml = data.error;
                    form.reset();
                }

                messageDiv.innerHTML = `<div class="alert alert-danger mt-2" role="alert">${errorsHtml}</div>`;
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
