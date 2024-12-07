<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Kết quả thanh toán</h1>
        @if(isset($response['message']))
            <div class="alert alert-success text-center">
                {{ $response['message'] }}
            </div>
        @endif

        @if(isset($response['error']))
            <div class="alert alert-danger text-center">
                {{ $response['error'] }}
            </div>
        @endif
    </div>
</body>

</html>
