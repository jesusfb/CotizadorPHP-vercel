<?php if (empty($d->items)) : ?>
    <div class="text-center">
        <h3>La cotización esta vacía.</h3>
        <img src="<?php echo IMG . "empty.webp"; ?>" alt="sin contenido" class="img-fluid" style="width:150px;">
    </div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Acciones</th>
                    <th class="text-center">Concepto</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($d->items as $item) : ?>
                    <tr>
                        <td>
                            <div class="button-group d-flex justify-content-evenly align-content-center ">
                                <div class="btn btn-sm btn-primary edit_concept" data-id="<?php echo $item->id;  ?>"><i class="bi bi-pen-fill"></i> Editar</div>
                                <div class="btn btn-sm btn-danger delete_concept" data-id="<?php echo $item->id;  ?>"><i class="bi bi-trash3-fill"></i>
                                    Borrar</div>
                            </div>
                        </td>
                        <td>
                            <?php echo $item->concept; ?>
                            <small class="text-muted d-block">
                                <img src="<?php echo IMG . ($item->type === 'producto' ? 'producto.png' : 'servicio.png'); ?>" alt="<php? echo $item -> concept ?>" style="width: 20px;">
                                <?php echo $item->type === 'producto' ? 'Producto' : 'Servicio'; ?>
                            </small>
                        </td>
                        <td class="text-center"><?php echo '$' . number_format($item->price, 2); ?></td>
                        <td class="text-center"><?php echo $item->quantity; ?></td>
                        <td class="text-end"><?php echo '$' . number_format($item->total, 2); ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="4" class="text-right">Subtotal</td>
                    <td class="text-end"><?php echo '$' . number_format($d->subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">IVA</td>
                    <td class="text-end"><?php echo '$' . number_format($d->taxes, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">Envío</td>
                    <td class="text-end"><?php echo '$' . number_format($d->shipping, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end"><b>Total</b>
                        <h3 class="text-success"><b><?php echo '$' . number_format($d->total, 2); ?></b></h3>
                        <small class="text-muted"><?php echo sprintf('Impuestos incluidos %s%% IVA', TAXES_RATE) ?></small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>