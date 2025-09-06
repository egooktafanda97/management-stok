<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Responsive Page</title>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');
    </style>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #fff;
            width: 100%;
            overflow-x: hidden;
            clear: *
        }

        input[type=text],
        input[type=password],
        button {
            padding: 12px;
            border: 1px solid #333;
            border-radius: 3px !important;
            outline: none;
        }

        header {
            width: 100%;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            /* box-shadow: 0 2px 4px rgba(0,0,0,0.1); */
            position: absolute;
            top: 0;
            margin-bottom: 30px;
        }

        .logo img {
            height: 40px;
            margin-left: 25px;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: #555;
            font-size: 16px;
        }

        .time,
        .date {
            margin-right: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            margin-top: 6rem;
        }

        .left-section,
        .right-section {
            flex: 1;
            margin: 1rem;
        }

        .left-section h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .left-section p {
            margin-bottom: 1.5rem;
        }

        .buttons {
            display: flex;
            margin-bottom: 1.5rem;
            gap: 20px;
        }

        button {
            padding: 0.75rem 1.5rem;
            border: none;
            margin-right: 1rem;
            cursor: pointer !important;
            font-size: 1rem;
        }

        .new-meeting {
            background-color: #4285f4;
            color: white;
        }

        .join-meeting {
            background-color: #f1f3f4;
            color: black;
        }

        .learn-more {
            color: #4285f4;
            text-decoration: none;
        }

        .right-section img {
            width: 100%;
            max-width: 300px;
            display: block;
            margin-bottom: 1rem;
        }

        .try-it {
            background-color: #4285f4;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        h1,
        h2 {
            font-weight: normal;
        }

        p {
            color: #777
        }

        @media (max-width: 768px) {
            body {
                width: 100%;
                overflow-x: hidden;
            }

            main {
                flex-direction: column;
                /* width:100%; */
                overflow-x: hidden;
                height: auto;
                overflow: hidden;
            }

            .left-section,
            .right-section {
                /* margin: 0.5rem; */
            }

            .buttons {
                display: flex;
                flex-direction: column;
                flex: 1
            }
        }

        button:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            {{-- <img src="https://oncard.id/assets/png/icon.png" alt="Logo Oncard"> --}}
        </div>
        <div class="user-info">
            <span class="time" id="datetime"></span>
        </div>
    </header>
    <main>
        <section class="left-section">
            <h1>Selamat Datang di sitem management stok</h1>
            <p>
                Silakan masuk untuk melanjutkan. Jika Anda belum memiliki akun, silakan hubungi administrator untuk
                membuat akun.
            </p>


            <form action="{{ url('postlogin') }}" class="row g-3 buttons" method="POST">
                {{ csrf_field() }}
                <div class="col-12">
                    <input class="form-control" id="username" name="username" placeholder="Masukkan Username"
                        type="text">
                </div>
                <div class="col-12">
                    <div class="input-group" id="show_hide_password">
                        <input class="form-control border-end-0" id="password" name="password"
                            placeholder="Masukkan Password" type="password">
                        <a class="input-group-text bg-transparent" href="javascript:;"><i class='bx bx-hide'></i></a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-grid">
                        <button class="try-it" style="" type="submit"><i
                                class="bx bxs-lock-open"></i>Login</button>
                    </div>
                </div>
            </form>
        </section>
        <section class="right-section">
            {{-- <img src="{{ asset('admin') }}/assets/images/login.gif}}" /> --}}
            {{-- <img src="background-image.png" alt="Background Image">
            <h2>Hilangkan suara bising di latar belakang</h2>
            <p>Peredam bising yang cerdas menghilangkan suara bising di latar belakang saat Anda sedang melakukan presentasi</p>
            <button class="try-it">Mulai uji coba</button> --}}
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
    <script>
        moment.locale('id'); // Set locale to Indonesian

        function updateTime() {
            const now = moment();
            const timeString = now.format('HH.mm • dddd, D MMMM');
            document.getElementById('datetime').innerText = timeString;
        }

        // Update the time immediately and then every minute
        updateTime();
        setInterval(updateTime, 1000); // 60000 milliseconds = 1 minute
    </script>
</body>

</html>
