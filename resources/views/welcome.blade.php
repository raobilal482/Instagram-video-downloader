<!DOCTYPE html>
<html>
<head>
    <title>Instagram Video Downloader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Download Instagram Videos</h1>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('download.video') }}">
            @csrf
            <div class="mb-3">
                <label for="url" class="form-label">Paste Instagram Video URL</label>
                <input type="text" name="url" id="url" class="form-control" placeholder="e.g., https://www.instagram.com/p/xyz/" required>
            </div>
            <button type="submit" class="btn btn-primary">Download Video</button>
        </form>
    </div>
</body>
</html>
