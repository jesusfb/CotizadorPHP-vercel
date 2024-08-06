<?php require_once 'app/config.php' ?>;
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <style type="text/css">
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        table {
            font-size: x-small;
            margin-bottom: 25px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray;
        }

        .blue_bg {
            background-color: #1f2ade;
            color: white;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <table width="100%">
        <tr>
            <td valign="top"><img src="<?php echo 'assets/img/logo.png'; ?>" alt="Logo cotización" width="70" />
            </td>
            <td align="right">
                <h4 style="color:red;"><?php echo sprintf("#%s", $d->number); ?></h4>
                <h4><?php echo date("Y-m-d") ?></h4>
                <h2>Cotización</h2>
                <pre>
                <?php echo sprintf('%s <br> %s <br> %s <br>%s <br>', $d->nit, $d->provider_name, $d->provider_company, $d->provider_email) ?>
                </pre>
            </td>
        </tr>
    </table>

    <!-- Información de la empresa -->
    <table width="100%">
        <tr>
            <td><strong>De: </strong><?php echo sprintf('%s - %s', $d->provider_name, $d->provider_company) ?></td>
            <td><strong>Para: </strong> <?php echo sprintf('%s - %s (%s)', $d->name, $d->company, $d->email) ?></td>
        </tr>
    </table>

    <br>

    <!-- Resumen de la cotización -->
    <table width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th class="blue_bg ">Descripción</th>
                <th class="blue_bg ">Precio Unitario</th>
                <th class="blue_bg ">Cantidad</th>
                <th class="blue_bg ">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($d->items as $item) : ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $item->concept; ?></td>
                    <td align="center"><?php echo '$' . number_format($item->price, 2); ?></td>
                    <td align="center"><?php echo $item->quantity; ?></td>
                    <td align="right"><?php echo '$' . number_format($item->total, 2); ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td align="right">Subtotal: </td>
                <td align="right"><?php echo '$' . number_format($d->subtotal, 2); ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Impuestos: </td>
                <td align="right"><?php echo '$' . number_format($d->taxes, 2); ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Envío: </td>
                <td align="right"><?php echo '$' . number_format($d->shipping, 2); ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Total: </td>
                <td align="right" class="gray">
                    <h3 style="margin: 0px 0px;"><?php echo '$' . number_format($d->total, 2); ?></h3>
                </td>
            </tr>
            <tr>
                <td colspan="5" align="right"><?php echo sprintf('Impuestos del %s%% incluido (IVA).', TAXES_RATE); ?>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>