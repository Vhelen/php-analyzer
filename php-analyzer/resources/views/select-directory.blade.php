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

    <form id="directoryForm" method="POST" action="{{ route('find-php-files') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="directory" class="form-label">Directory</label>
            <!-- Input for selecting a directory (only works in supported browsers) -->
            <input type="file" id="directory" class="form-control" webkitdirectory directory>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <script>
        document.getElementById('directoryForm').addEventListener('submit', function(event) {
            const input = document.getElementById('directory');
            if (!input.files.length) {
                alert('Please select a directory.');
                event.preventDefault();
                return;
            }

            // Convert the directory input to a JSON array of file paths
            let fileList = [];
            for (let i = 0; i < input.files.length; i++) {
                fileList.push(input.files[i].webkitRelativePath);
            }

            // Send the directory data via a hidden input
            const formData = new FormData(this);
            formData.append('directory', JSON.stringify(fileList));

            // Override the default form submission to use Fetch API
            event.preventDefault();
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('PHP files:', data);
                // Handle the response (e.g., display the files in the browser)
                alert('PHP files found: ' + JSON.stringify(data));
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
