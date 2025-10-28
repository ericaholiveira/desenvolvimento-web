<?php include('layouts/header.php'); ?>

<body class="text-white" style="background: url('assets/imgs/fundo-signos.jpg') center/cover no-repeat; min-height: 100vh;">
    
    <div class="d-flex justify-content-center align-items-center flex-column text-center" style="min-height: 100vh;">
        <div class="container">
            <header class="mb-4">
                <h1 class="display-4 text-primary">Qual é o seu signo?</h1>
                <p class="lead">
                    Áries♈ Touro♉ Gêmeos♊ Câncer♋ Leão♌ Virgem♍ Libra♎ Escorpião♏ Sagitário♐ Capricórnio♑ Aquário♒ Peixes♓
                </p>
            </header>

            <div class="card bg-light text-dark p-4 shadow-lg">
                <div class="mx-auto" style="max-width: 400px;">
                <h2 class="mb-3">Descubra o seu signo</h2>
                <form id="signo-form" method="POST" action="show_zodiac_sign.php" >
                    <div class="mb-3">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Descobrir</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
