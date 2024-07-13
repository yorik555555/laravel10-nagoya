<!-- resources/views/admin/admin.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ここにCSSや他のヘッダー情報を追加 -->
</head>
<body>
    <header>
        <!-- 管理者用のヘッダー部分 -->
        <h1>Admin Dashboard</h1>
        <!-- ナビゲーションや他の管理者用のリンクを追加 -->
    </header>

    <main>
        <!-- コンテンツの主要な部分 -->
        @yield('content')
    </main>

    <footer>
        <!-- フッターの内容を追加 -->
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>

    <!-- ここに必要なJavaScriptファイルを追加 -->
</body>
</html>
