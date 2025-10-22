<?php include 'layouts/header.php'; ?>

<div class="container mt-5" class="d-flex justify-content-center align-items-center flex-column text-center">
    <h1 class="container mb-4">Qual é o seu signo? Vamos descobrir!</h1>
</div>

    <?php
    libxml_use_internal_errors(true);


    if (!isset($_POST['data_nascimento']) || empty($_POST['data_nascimento'])) {
        echo '<div class="alert alert-warning" role="alert">Data de nascimento não encontrada! Por favor, tente novamente.</div>';
        echo '<a href="index.php" class="btn btn-secondary mt-3">Voltar</a>';
        exit;
    }


    $data_nascimento_str = $_POST['data_nascimento'];
    $data_nascimento_obj = DateTime::createFromFormat('Y-m-d', $data_nascimento_str);

    if (!$data_nascimento_obj) {
        echo '<div class="alert alert-danger" role="alert">Formato de data inválido. Use o formato AAAA-MM-DD.</div>';
        echo '<a href="index.php" class="btn btn-secondary mt-3">Voltar</a>';
        exit;
    }


    $signos = simplexml_load_file("signos.xml");
    if (!$signos) {
        echo '<div class="alert alert-danger" role="alert">Erro ao carregar os dados dos signos.</div>';
        echo '<a href="index.php" class="btn btn-secondary mt-3">Voltar</a>';
        exit;
    }

    function validarSignosXML(SimpleXMLElement $signos): array {
        $erros = [];
        foreach ($signos->signo as $signo) {
            $nome = (string)$signo->signoNome;
            $inicio = trim((string)$signo->dataInicio);
            $fim = trim((string)$signo->dataFim);

            $inicio_dt = DateTime::createFromFormat('m-d', $inicio);
            $fim_dt = DateTime::createFromFormat('m-d', $fim);

            if (!$inicio_dt) {
                $erros[] = "Signo '{$nome}' tem data_inicio inválida: '{$inicio}'";
            }
            if (!$fim_dt) {
                $erros[] = "Signo '{$nome}' tem data_fim inválida: '{$fim}'";
            }
        }
        return $erros;
    }

    $erros_xml = validarSignosXML($signos);
    if (!empty($erros_xml)) {
        echo '<div class="alert alert-danger"><strong>Erros encontrados no XML:</strong><ul>';
        foreach ($erros_xml as $erro) {
            echo "<li>{$erro}</li>";
        }
        echo '</ul></div>';
        echo '<a href="index.php" class="btn btn-secondary mt-3">Voltar</a>';
        exit;
    }

    function determinarSigno(DateTime $data_nascimento, SimpleXMLElement $signos): ?SimpleXMLElement {
        $ano_base = 2000;
        $data_format = DateTime::createFromFormat('m-d', $data_nascimento->format('m-d'));
        $data_format->setDate($ano_base, (int)$data_format->format('m'), (int)$data_format->format('d'));

        foreach ($signos->signo as $signo) {
            $inicio = DateTime::createFromFormat('m-d', (string)$signo->dataInicio);
            $fim = DateTime::createFromFormat('m-d', (string)$signo->dataFim);

            if (!$inicio || !$fim) continue;

            $inicio->setDate($ano_base, (int)$inicio->format('m'), (int)$inicio->format('d'));
            $fim->setDate($ano_base, (int)$fim->format('m'), (int)$fim->format('d'));

            if ($inicio <= $fim) {
                if ($data_format >= $inicio && $data_format <= $fim) {
                    return $signo;
                }
            } else {
                $fim->modify('+1 year');
                if ($data_format >= $inicio || $data_format <= $fim) {
                    return $signo;
                }
            }
        }
        return null;
    }

    $signo_resultado = determinarSigno($data_nascimento_obj, $signos);

    if ($signo_resultado) {
        $nome = htmlspecialchars((string)$signo_resultado->signoNome);
        $descricao = htmlspecialchars((string)$signo_resultado->descricao);
        $periodo = htmlspecialchars((string)$signo_resultado->dataInicio) . ' a ' . htmlspecialchars((string)$signo_resultado->dataFim);

        echo '<div class="card shadow-lg p-3 bg-light" >';
        echo '<div class="card-body text-center">';
        echo "<h2 class='card-title text-primary'>Seu signo é: {$nome}</h2>";
        echo "<p class='lead'>Período: {$periodo}</p>";
        echo "<hr>";
        echo "<p class='card-text fs-5'>{$descricao}</p>";
        echo '</div></div>';
    } else {
        echo "<div class='alert alert-danger' role='alert'>Não foi possível determinar seu signo. Verifique a data informada.</div>";
    }

    echo '<a href="index.php" class="btn btn-secondary mt-3">Voltar</a>';
    ?>
</div>