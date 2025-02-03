<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atendimento - APPD</title>
    <style>
        html,
        body {
            width: 21cm;
            height: 29.7cm;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #f8f8f8;
        }

        .header img {
            width: 80px;
            position: absolute;
            left: 30px;
            top: 15px;
        }

        .header p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 700;
        }

        .color-lines {
            display: flex;
            flex-direction: column;
        }

        .color-lines .yellow {
            height: 5px;
            background-color: #FFD700;
            width: 100%;
        }

        .color-lines .black {
            height: 5px;
            background-color: #000;
            width: 100%;
        }

        .code {
            text-align: right;
            font-size: 15px;
            margin-top: 10px;
            margin-right: 40px;
        }

        .content h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 55px;
        }

        .content .description {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            margin-left: 40px;
            margin-right: 40px;
        }

        .confidentiality {}

        .footer {
            background-color: #f8f8f8;
            border-top: 2px solid #000;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            z-index: 10;
        }

        .footer p {
            margin: 5px 0;
            font-size: 12px;
            padding: 10px;
            color: #666;
        }

        .signature {
            margin-top: 130px;
            text-align: center;
            white-space: nowrap;
        }

        .date {
            bottom: 65px;
            left: 50%;
            position: fixed;
            transform: translateX(-50%);
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('img/logo.svg') }}" alt="Logo APPD">
        <div style="margin-left: 80px;">
            <p>
                Associação Paraense das Pessoas com Deficiência - A.P.P.D. <br>
                Fundada em 26.11.1981, declarada de utilidade pública Municipal - Lei nº 7.549 de 18.12.91<br>
                Estadual Lei nº 5.565 de 27.10.89 - Federal - Lei nº 91 - Decreto 50.517 de 17.12.91<br>
                CNAS nº 28985.000439/94-79 - Filantropia: Resolução 040 de 09.04.98<br>
                CNPJ: 04.704.797/0001-69
            </p>
        </div>
    </div>
    <div class="color-lines">
        <div class="yellow"></div>
        <div class="black"></div>
    </div>
    <h4 class="code">#<b>{{ $atendimento->id }}</b></h4>
    <div class="content">
        <h2>
            {{ $atendimento->declaracao['titulo'] }}
        </h2>
        <div class="description">
            {!! @$atendimento->declaracao['descricao'] !!}
        </div>

        <div class="confidentiality">
            <p style="font-size: 12px; margin-top: 20px; text-align: justify; margin-left: 40px; margin-right: 40px;">
                <strong>Confidencialidade:</strong> Os dados contidos nesta ficha são sigilosos e destinados
                exclusivamente ao registro e acompanhamento dos atendimentos individuais realizados pela APPD, em
                conformidade com as diretrizes do Sistema Único de Assistência Social (SUAS).
            </p>
        </div>

        <div class="signature">
            <p>
                _______________________________________________________<br><br>
                <strong>
                    {{ $atendimento?->getNome() }}<br>
                </strong>
            </p>
        </div>
    </div>
    <div class="date">
        @php
            setlocale(LC_TIME, 'pt_BR.utf8'); // Define o idioma para português
            $date = \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y');
        @endphp
        <p><strong>Belém - PA, {{ $date }}</strong></p>
    </div>
    <div class="footer">
        <div class="color-lines">
            <div class="yellow"></div>
            <div class="black"></div>
        </div>
        <p>
            Av. Magalhães Barata, Passagem Alberto Engelhard (Vila Teta), 213 - São Brás<br>
            Fone/Fax: (091) 3249-4849 - CEP: 66040-520 - Belém - PA
        </p>
    </div>
</body>

</html>
