<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #2c2c2c;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c2c2c;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
            color: #2c2c2c;
        }
        .header h2 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #008C45;
        }
        .header .subtitle {
            font-size: 11px;
            color: #6c757d;
        }
        .italian-line {
            height: 4px;
            background: linear-gradient(to right, #008C45 33%, #ffffff 33%, #ffffff 66%, #CD212A 66%);
            margin: 10px 0;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .info-row strong {
            color: #6c757d;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-row span {
            font-size: 13px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #2c2c2c;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-ok {
            color: #008C45;
            font-weight: bold;
        }
        .status-problem {
            color: #CD212A;
            font-weight: bold;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 5px 0;
            color: #2c2c2c;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #6c757d;
        }
        .acceptance {
            margin-top: 25px;
            padding: 12px;
            background-color: #e8f5e9;
            border: 1px solid #008C45;
            border-radius: 4px;
            text-align: center;
        }
        .acceptance strong {
            color: #008C45;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SPAZIO ITALIA</h1>
        <div class="italian-line"></div>
        <h2>Relatório de Check-in - Entrada no Espaço</h2>
        <?php if (!empty($opportunity['name'])): ?>
            <div class="subtitle">Oportunidade: <?= htmlspecialchars($opportunity['name']) ?></div>
        <?php endif; ?>
        <?php if (!empty($opportunity['accountName'])): ?>
            <div class="subtitle">Cliente: <?= htmlspecialchars($opportunity['accountName']) ?></div>
        <?php endif; ?>
    </div>

    <div class="info-row">
        <strong>Data:</strong> <span><?= htmlspecialchars($formData['data'] ?? '-') ?></span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <strong>Horário:</strong> <span><?= htmlspecialchars($formData['horario'] ?? '-') ?></span>
    </div>

    <div class="section-title">Condições do Espaço no Check-in</div>

    <table>
        <thead>
            <tr>
                <th style="width: 28%">Item</th>
                <th style="width: 17%">Condição</th>
                <th style="width: 30%">Observações</th>
                <th style="width: 25%">Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $fieldName => $item): ?>
                <?php
                $status = $formData[$fieldName . '_status'] ?? '-';
                $obs = $formData[$fieldName . '_obs'] ?? '';
                $statusClass = ($status === 'OK') ? 'status-ok' : 'status-problem';
                $hasFoto = !empty($fotos[$fieldName]);
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['label']) ?></td>
                    <td class="<?= $statusClass ?>"><?= htmlspecialchars($status) ?></td>
                    <td><?= htmlspecialchars($obs) ?: '-' ?></td>
                    <td><?php if ($hasFoto): ?><img src="<?= $fotos[$fieldName] ?>" style="max-width: 120px; max-height: 80px;"><?php else: ?>-<?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="acceptance">
        <strong>ACEITO</strong> - Confirmado em <?= $submittedAt ?>
    </div>

    <div class="footer">
        Documento gerado automaticamente pelo sistema Spazio Italia Check-in/Check-out.
        <br>Gerado em: <?= $submittedAt ?>
    </div>
</body>
</html>
