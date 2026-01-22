<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Công cụ Trích Xuất Dữ Liệu Moodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #2d3748;
            padding: 1.5rem 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: white;
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            text-transform: uppercase;
            margin: 0;
            text-align: center;
        }

        .header p {
            color: #cbd5e0;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            margin-bottom: 0;
            text-align: center;
        }

        .container-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            max-width: 500px;
            width: 100%;
        }

        .form-title {
            color: #1a202c;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            letter-spacing: -0.01em;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 1.1rem;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background: #edf2f7;
            border-color: #4c63d2;
        }

        .file-input-label.active {
            background: #edf2f7;
            border-color: #4c63d2;
        }

        .file-input-label i {
            font-size: 1.75rem;
            color: #4c63d2;
            margin-right: 0.75rem;
        }

        .file-input-label span {
            font-size: 0.95rem;
            color: #2d3748;
            font-weight: 500;
        }

        input[type="file"] {
            display: none;
        }

        .form-hint {
            color: #718096;
            font-size: 0.825rem;
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        .file-list-container {
            margin-top: 1rem;
            padding: 1rem;
            background: #f7fafc;
            border-radius: 8px;
            border-left: 4px solid #4c63d2;
        }

        .file-list-title {
            color: #2d3748;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .file-list-title i {
            margin-right: 0.5rem;
            color: #4c63d2;
        }

        #fileList {
            list-style: none;
        }

        #fileList li {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            color: #2d3748;
            font-size: 0.875rem;
        }

        #fileList li span:first-child {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            min-width: 24px;
            background: #4c63d2;
            color: white;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.75rem;
            margin-right: 0.75rem;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: #4c63d2;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(76, 99, 210, 0.3);
        }

        .submit-btn:hover {
            background: #3d4dbf;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 99, 210, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .footer {
            background: #2d3748;
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.85rem;
            margin-top: auto;
        }

        @media (max-width: 640px) {
            .form-card {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.25rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .header p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="max-w-6xl mx-auto px-4">
            <h1>Bộ môn KTPM - Khoa CNTT - NTU</h1>
            <p>Công cụ trích xuất Username từ file HTML Moodle Assignment</p>
        </div>
    </header>

    <!-- Main -->
    <div class="container-main">
        <div class="form-card">
            <h2 class="form-title">
                <i class="fas fa-file-upload mr-2"></i>Tải Tệp
            </h2>

            <form action="process.php" method="POST" enctype="multipart/form-data">
                <!-- Excel Upload -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-excel"></i>File Excel Gốc
                    </label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label" for="excelInput">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Chọn file .xls/.xlsx</span>
                        </label>
                        <input type="file" id="excelInput" name="excel" accept=".xls,.xlsx" required>
                    </div>
                    <p class="form-hint">
                        <i class="fas fa-info-circle mr-1"></i>Chọn file danh sách sinh viên cần xử lý
                    </p>
                <div id="excelFileName" style="margin-top: 0.5rem; color: #4c63d2; font-weight: 500; display: none;"></div>
                </div>

                <!-- HTML Files Upload -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-code"></i>Tệp HTML Moodle
                    </label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label" for="htmlfilesInput">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Chọn nhiều file .html/.htm</span>
                        </label>
                        <input type="file" id="htmlfilesInput" name="htmlfiles[]" accept=".html,.htm" multiple required>
                    </div>
                    <p class="form-hint">
                        <i class="fas fa-info-circle mr-1"></i>Mỗi file HTML tạo một cột điểm (10 hoặc 0) trong kết quả
                    </p>

                    <!-- File list -->
                    <div id="fileListContainer" class="file-list-container hidden">
                        <p class="file-list-title">
                            <i class="fas fa-check-circle"></i>Tệp Đã Chọn
                        </p>
                        <ul id="fileList"></ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <i class="fas fa-cogs mr-2"></i>Xử Lý & Tải Excel
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>
            <i class="fas fa-graduation-cap mr-1"></i>Bộ Môn KTPM - Khoa CNTT - Trường Đại Học Nha Trang
            <br>
            &copy; <?php echo date('Y'); ?> | Tất cả quyền được bảo lưu
        </p>
    </footer>

    <script>
        const htmlInput = document.getElementById('htmlfilesInput');
        const excelInput = document.getElementById('excelInput');
        const fileList = document.getElementById('fileList');
        const fileListContainer = document.getElementById('fileListContainer');
        const excelFileName = document.getElementById('excelFileName');
        const htmlLabel = document.querySelector('label[for="htmlfilesInput"]').parentElement;
        const excelLabel = document.querySelector('label[for="excelInput"]').parentElement;

        // Excel file display
        excelInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                excelFileName.textContent = '✓ ' + this.files[0].name;
                excelFileName.style.display = 'block';
                excelLabel.querySelector('.file-input-label').classList.add('active');
            } else {
                excelFileName.style.display = 'none';
                excelLabel.querySelector('.file-input-label').classList.remove('active');
            }
        });

        // HTML files list
        htmlInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            fileList.innerHTML = '';
            
            if (files.length > 0) {
                htmlLabel.querySelector('.file-input-label').classList.add('active');
                files.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span>${index + 1}</span>
                        <span>${file.name}</span>
                    `;
                    fileList.appendChild(li);
                });
                fileListContainer.classList.remove('hidden');
            } else {
                htmlLabel.querySelector('.file-input-label').classList.remove('active');
                fileListContainer.classList.add('hidden');
            }
        });

        // Drag & drop for HTML files
        htmlLabel.addEventListener('dragover', (e) => {
            e.preventDefault();
            htmlLabel.classList.add('bg-blue-50');
            htmlLabel.style.borderColor = '#4c63d2';
        });

        htmlLabel.addEventListener('dragleave', () => {
            htmlLabel.classList.remove('bg-blue-50');
            htmlLabel.style.borderColor = '#cbd5e0';
        });

        htmlLabel.addEventListener('drop', (e) => {
            e.preventDefault();
            htmlLabel.classList.remove('bg-blue-50');
            htmlLabel.style.borderColor = '#cbd5e0';
            htmlInput.files = e.dataTransfer.files;
            htmlInput.dispatchEvent(new Event('change'));
        });

        // Drag & drop for Excel
        excelLabel.addEventListener('dragover', (e) => {
            e.preventDefault();
            excelLabel.classList.add('bg-blue-50');
            excelLabel.style.borderColor = '#4c63d2';
        });

        excelLabel.addEventListener('dragleave', () => {
            excelLabel.classList.remove('bg-blue-50');
            excelLabel.style.borderColor = '#cbd5e0';
        });

        excelLabel.addEventListener('drop', (e) => {
            e.preventDefault();
            excelLabel.classList.remove('bg-blue-50');
            excelLabel.style.borderColor = '#cbd5e0';
            excelInput.files = e.dataTransfer.files;
            excelInput.dispatchEvent(new Event('change'));
        });
    </script>
</body>
</html>
