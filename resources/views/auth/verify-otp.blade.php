<!-- resources/views/auth/verify-otp.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - Performance Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1d6988 0%, #0e4b64 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .otp-container {
            background: white;
            border-radius: 32px;
            padding: 48px 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .otp-logo {
            margin-bottom: 24px;
        }

        .otp-logo span {
            font-size: 48px;
        }

        .otp-container h1 {
            color: #1d6988;
            font-size: 28px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .otp-container p {
            color: #6c757d;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .otp-input-wrapper {
            margin-bottom: 24px;
        }

        .otp-input {
            width: 100%;
            padding: 16px;
            font-size: 28px;
            text-align: center;
            letter-spacing: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-weight: bold;
            transition: all 0.3s;
            font-family: monospace;
        }

        .otp-input:focus {
            outline: none;
            border-color: #f4c610;
            box-shadow: 0 0 0 3px rgba(244, 198, 16, 0.1);
        }

        .btn-verify {
            background: #e5222b;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .btn-verify:hover {
            background: #c41e26;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(229, 34, 43, 0.3);
        }

        .resend-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #1d6988;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: underline;
            transition: color 0.3s;
        }

        .resend-btn:hover {
            color: #e5222b;
        }

        .error-message {
            background: #fee;
            border-left: 4px solid #e5222b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            color: #e5222b;
            font-size: 14px;
        }

        .success-message {
            background: #e6f4ea;
            border-left: 4px solid #2b7e3a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            color: #2b7e3a;
            font-size: 14px;
        }

        .timer-text {
            font-size: 13px;
            color: #6c757d;
            margin-top: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #6c757d;
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            color: #1d6988;
        }

        @media (max-width: 480px) {
            .otp-container {
                padding: 32px 24px;
            }
            .otp-input {
                font-size: 22px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-logo">
            <span>🔐</span>
        </div>
        <h1>Verify Your Identity</h1>
        <p>We've sent a One-Time Password (OTP) to your registered <strong>{{ session('otp_destination', 'email address') }}</strong></p>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf
            <div class="otp-input-wrapper">
                <input type="text" 
                       name="otp" 
                       class="otp-input" 
                       placeholder="000000" 
                       maxlength="6" 
                       pattern="[0-9]{6}"
                       autofocus 
                       required>
            </div>
            <button type="submit" class="btn-verify">
                <i class="fas fa-check-circle"></i> Verify OTP
            </button>
        </form>

        <div class="resend-container">
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn">
                    <i class="fas fa-redo-alt"></i> Didn't receive code? Resend OTP
                </button>
            </form>
        </div>

        <div class="timer-text" id="timerText">
            Code expires in <span id="timer">05:00</span>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            ← Back to Login
        </a>
    </div>

    <script>
        // Simple timer for OTP expiry (5 minutes)
        let timeLeft = 300; // 5 minutes in seconds
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                const timerText = document.getElementById('timerText');
                if (timerText) {
                    timerText.innerHTML = '<span style="color: #e5222b;">Code expired. Please request a new OTP.</span>';
                }
                const resendBtn = document.getElementById('resendBtn');
                if (resendBtn) {
                    resendBtn.style.opacity = '1';
                    resendBtn.disabled = false;
                }
            } else {
                timeLeft--;
            }
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Auto-focus and allow only numbers
        const otpInput = document.querySelector('.otp-input');
        if (otpInput) {
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            });
        }
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>