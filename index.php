<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Extract Username & Email</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 flex flex-col items-center justify-start">

    <!-- Header -->
    <header class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 shadow-md">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-bold uppercase tracking-wide">Bộ môn KTPM - Khoa CNTT - NTU</h1>
            <p class="text-sm mt-1">Công cụ trích xuất Username & Email từ file HTML Moodle Assignment</p>
        </div>
    </header>

    <!-- Main Form -->
    <main class="flex-grow w-full max-w-xl mt-10 px-4">
        <form action="process.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-lg space-y-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 text-center mb-4">Tải lên file HTML đã tải từ Moodle Assignment</h2>

            <div class="flex flex-col space-y-2">
                <label class="text-gray-600 font-medium">Chọn file HTML:</label>
                <input type="file" name="htmlfile" accept=".html,.htm" required
                    class="file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-500 file:text-white hover:file:bg-blue-600 text-sm text-gray-700 w-full" />
            </div>

            <div class="flex justify-between pt-4">
                <button name="format" value="csv"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Tải về CSV
                </button>
                <button name="format" value="xlsx"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Tải về Excel
                </button>
            </div>
        </form>
    </main>

    <footer class="w-full text-center text-sm text-gray-500 mt-12 py-4">
        &copy; <?php echo date('Y'); ?> Bộ môn KTPM - Trường Đại học Nha Trang
    </footer>
</body>
</html>
