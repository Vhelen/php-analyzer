<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Directory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Select a Directory</h2>

    <form method="POST" action="{{ route('analyze') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="directory" class="form-label">Directory</label>
            <!-- Input for selecting a directory (only works in supported browsers) -->
            <input type="file" id="directory" name="directory[]" class="form-control" webkitdirectory directory>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
</html>
