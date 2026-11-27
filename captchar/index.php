<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác thực Captcha</title>
    <style>
        :root {
            --primary: #635bff;
            --primary-dark: #4f46e5;
            --text: #172033;
            --muted: #718096;
            --border: #e6e9f0;
            --surface: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--text);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at 15% 15%, rgba(99, 91, 255, 0.14), transparent 28rem),
                radial-gradient(circle at 85% 85%, rgba(56, 189, 248, 0.12), transparent 24rem),
                #f5f7fb;
        }

        .card {
            width: min(100%, 560px);
            overflow: hidden;
            border: 1px solid rgba(230, 233, 240, 0.9);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 24px 60px rgba(31, 41, 55, 0.12);
        }

        .card-header {
            padding: 26px 30px 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            letter-spacing: -0.02em;
        }

        .card-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        #mainform {
            padding: 24px 30px 30px;
        }

        .field {
            margin-bottom: 20px;
        }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        label {
            font-size: 14px;
            font-weight: 700;
        }

        .counter {
            color: var(--muted);
            font-size: 12px;
        }

        textarea,
        input[type="text"] {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            background: var(--surface);
            color: var(--text);
            font: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
            padding: 12px 14px;
            line-height: 1.55;
        }

        input[type="text"] {
            height: 46px;
            padding: 0 14px;
        }

        textarea:focus,
        input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 91, 255, 0.11);
        }

        .captcha-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
        }

        .captcha-image {
            height: 46px;
            border: 1px solid var(--border);
            border-radius: 10px;
            object-fit: fill;
            background: #fff;
        }

        .reload {
            height: 46px;
            width: 46px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            font-size: 20px;
            transition: 0.2s;
        }

        .reload:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
            transform: rotate(20deg);
        }

        .status {
            min-height: 20px;
            margin: -4px 0 12px;
            font-size: 13px;
        }

        .status.error {
            color: #dc2626;
        }

        .status.success {
            color: #16a34a;
        }

        #submit {
            width: 100%;
            height: 48px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(99, 91, 255, 0.24);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
        }

        #submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(99, 91, 255, 0.28);
        }

        #submit:disabled {
            cursor: wait;
            opacity: 0.65;
        }

        @media (max-width: 520px) {
            body {
                padding: 12px;
            }

            .card-header,
            #mainform {
                padding-left: 20px;
                padding-right: 20px;
            }

            .captcha-row {
                grid-template-columns: minmax(0, 1fr) auto;
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <header class="card-header">
            <h1>Kiểm tra nội dung</h1>
            <p>Nhập nội dung và mã xác thực để tiếp tục.</p>
        </header>

        <form id="mainform" method="post" action="">
            <div class="field">
                <div class="label-row">
                    <label for="content">Nội dung</label>
                    <span class="counter"><span id="char-count">0</span> ký tự</span>
                </div>
                <textarea id="content" name="content" rows="5" placeholder="Nhập nội dung tại đây..."></textarea>
            </div>

            <div class="field">
                <label for="captcha">Mã Captcha</label>
                <div class="captcha-row" style="margin-top:8px">
                    <img src="image.php" id="img-captcha" class="captcha-image" alt="Mã captcha">
                    <button type="button" class="reload" id="reload-captcha" title="Tải lại captcha" aria-label="Tải lại captcha">&#8635;</button>
                </div>
                <input type="text" id="captcha" name="captcha" autocomplete="off" placeholder="Nhập mã hiển thị" style="margin-top:12px">
            </div>

            <div id="status" class="status" role="status" aria-live="polite"></div>
            <button id="submit" type="submit">Lưu nội dung</button>
        </form>
    </main>

    <script>
        const form = document.getElementById('mainform');
        const content = document.getElementById('content');
        const captcha = document.getElementById('captcha');
        const captchaImage = document.getElementById('img-captcha');
        const submitButton = document.getElementById('submit');
        const status = document.getElementById('status');

        function reloadCaptcha() {
            captchaImage.src = 'image.php?rand=' + Math.random();
            captcha.value = '';
            captcha.focus();
        }

        document.getElementById('reload-captcha').addEventListener('click', reloadCaptcha);

        content.addEventListener('input', function () {
            document.getElementById('char-count').textContent = content.value.length;
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            status.className = 'status';

            if (content.value.trim() === '') {
                status.textContent = 'Bạn chưa nhập nội dung.';
                status.classList.add('error');
                content.focus();
                return;
            }

            if (captcha.value.trim() === '') {
                status.textContent = 'Bạn chưa nhập captcha.';
                status.classList.add('error');
                captcha.focus();
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Đang kiểm tra...';

            const data = new URLSearchParams({
                content: content.value,
                captcha: captcha.value
            });

            try {
                const response = await fetch('ajax_validate.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: data
                });
                const result = await response.json();

                if (!Object.prototype.hasOwnProperty.call(result, 'error')) {
                    status.textContent = 'Kết quả trả về không phù hợp.';
                    status.classList.add('error');
                } else if (result.error === 'success') {
                    status.textContent = 'Kiểm tra thành công.';
                    status.classList.add('success');
                } else {
                    const messages = [];
                    if (result.content) messages.push(result.content);
                    if (result.captcha) messages.push(result.captcha);
                    status.textContent = messages.join(' ');
                    status.classList.add('error');
                    if (result.captcha) reloadCaptcha();
                }
            } catch (error) {
                status.textContent = 'Có lỗi xảy ra trong quá trình xử lý.';
                status.classList.add('error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Lưu nội dung';
            }
        });
    </script>
</body>
</html>

