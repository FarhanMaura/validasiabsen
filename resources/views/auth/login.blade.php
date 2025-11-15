<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-wrapper {
            width: 100%;
            min-height: 100vh;
            background-color: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .green-top-graphic {
            height: 300px;
            position: relative;
            background-color: #56ab2f;
            overflow: hidden;
        }

        .graphic-content {
            position: absolute;
            inset: 0;
            background-image: url('logoman1.jpg');
            background-size: cover;
            background-position: center;
        }

        .white-wave {
            position: absolute;
            bottom: -40px;
            width: 100%;
            height: 110px;
            background: #fff;
            border-top-left-radius: 60% 100%;
            border-top-right-radius: 60% 100%;
        }

        .content-area {
            flex-grow: 1;
            padding: 55px 60px;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 14px;
            border-bottom: 2px solid #dcdcdc;
        }

        .input-group svg {
            width: 25px;
            height: 25px;
            color: #999;
            margin-right: 16px;
        }

        .input-group input {
            flex-grow: 1;
            border: none;
            outline: none;
            background: none;
            font-size: 1.25rem;
            padding: 12px 0;
            color: #333;
        }

        .input-group input::placeholder {
            color: #b7b7b7;
        }

        .main-button {
            width: 100%;
            background: #56ab2f;
            color: white;
            padding: 18px;
            font-size: 1.3rem;
            font-weight: 600;
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(86,171,47,0.25);
            cursor: pointer;
            transition: 0.25s;
        }

        .main-button:hover {
            background: #4caf50;
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(86,171,47,0.35);
        }

        .secondary-link {
            margin-top: 30px;
            text-align: center;
            color: #56ab2f;
            font-size: 1rem;
            font-weight: 500;
            display: block;
        }

        .social-login-icons {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 26px;
        }

        .social-login-icons a {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #56ab2f;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: 0.25s;
        }

        .social-login-icons a:hover {
            transform: translateY(-3px);
            background: #eaffea;
        }

        .social-login-icons svg {
            width: 28px;
            height: 28px;
        }

        @media (max-width: 480px) {
            .content-area {
                padding: 40px 28px;
            }
        }
    </style>

    <div class="container-wrapper">
        <div class="green-top-graphic">
            <div class="graphic-content"></div>
            <div class="white-wave"></div>
        </div>

        <div class="content-area">
            <div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}" required autofocus
                               placeholder="E-mail address" />
                        <x-input-error :messages="$errors->get('email')"
                                       class="absolute left-0 right-0 top-full mt-1 text-xs text-red-600" />
                    </div>

                    <div class="input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3h8z" />
                        </svg>
                        <input id="password" type="password" name="password"
                               required placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')"
                                       class="absolute left-0 right-0 top-full mt-1 text-xs text-red-600" />
                    </div>

                    <button type="submit" class="main-button">LOG IN</button>

                    <a class="secondary-link" href="{{ route('register') }}">
                        Create new account
                    </a>
                </form>
            </div>

            <div class="social-login-icons">
                <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1.5 14h3v-2h-3v2zm0-4h3V6h-3v6z"></path></svg></a>
                <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 7a1 1 0 110-2 1 1 0 010 2zm4 0a1 1 0 110-2 1 1 0 010 2z"></path></svg></a>
                <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm2.25 15h-4.5a.75.75 0 010-1.5h4.5a.75.75 0 010 1.5zm-1.5-3H12a.75.75 0 010-1.5h.75a.75.75 0 010 1.5z"></path></svg></a>
            </div>
        </div>
    </div>
</x-guest-layout>
