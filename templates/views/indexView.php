<?php require_once INCLUDES . 'head.php' ?>
<?php require_once INCLUDES . 'navbar.php' ?>

<!-- MAIN CONTENT -->
<main class="container-fluid py-5">
    <div class="row">
        <div class="col-12 wrapper_notifications">

        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="card mb-3">
                <div class="card-header">Información del Proveedor</div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group row">
                            <div class="col-3"><label for="nit">NIT</label><input type="text" class="form-control"
                                    id="nit" name="nit" placeholder="1014566485-2" required></div>
                            <div class="col-3"><label for="nombre_proveedor">Nombre</label><input type="text"
                                    class="form-control" id="nombre_proveedor" name="nombre_proveedor"
                                    placeholder="Adriana Gúzman" required></div>
                            <div class="col-3"><label for="empresa_proveedor">Empresa</label><input type="text"
                                    class="form-control" id="empresa_proveedor" name="empresa_proveedor"
                                    placeholder="Teleperformance" required></div>
                            <div class="col-3"><label for="email_proveedor">Email</label><input type="email"
                                    class="form-control" id="email_proveedor" name="email_proveedor"
                                    placeholder="atencion@teleperformance.com" required></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">

                <div class="card-header">Información del cliente</div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group row">
                            <div class="col-4"><label for="nombre">Nombre</label><input type="text" class="form-control"
                                    id="nombre" name="nombre" placeholder="Gustavo Briceño" required></div>
                            <div class="col-4"><label for="empresa">Empresa</label><input type="text"
                                    class="form-control" id="empresa" name="empresa"
                                    placeholder="Diseño y Desarrollo Creativo" required></div>
                            <div class="col-4"><label for="email">Email</label><input type="email" class="form-control"
                                    id="email" name="email" placeholder="gustavb@gmail.com" required></div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Guardar Concepto -->
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form id="add_to_quote" method="$_POST">
                        <div class="form-group row">
                            <div class="col-3"><label for="concepto">Concepto</label><input type="text"
                                    class="form-control" name="concepto" id="concepto" placeholder="Diseño Brochure"
                                    required></div>
                            <div class="col-3"><label for="tipo">Tipo de Concepto</label>
                                <select name="tipo" id="tipo" class="form-control form-select">
                                    <option value="producto">Producto</option>
                                    <option value="servicio">Servicio</option>
                                </select>
                            </div>
                            <div class="col-3"><label for="cantidad">Cantidad</label><input type="number"
                                    class="form-control" id="cantidad" name="cantidad" min="1" max="99999" value="1"
                                    required></div>
                            <div class="col-3">
                                <label for="precio_unitario">Precio unitario</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" id="precio_unitario" name="precio_unitario"
                                        placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-success" type="submit">Agregar concepto</button>
                        <button class="btn btn-danger" type="reset">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <!-- Editar Concepto -->
            <div class="wrapper_update_concept" style="display: none;">
                <div class="card mb-3">
                    <div class="card-header">Editar Concepto</div>
                    <div class="card-body">
                        <form id="save_concept" method="$_POST">
                            <input type="hidden" class="form-control" id="id_concepto" name="id_concepto">
                            <div class="form-group row">
                                <div class="col-3"><label for="concepto">Concepto</label><input type="text"
                                        class="form-control" name="concepto" id="concepto" placeholder="Diseño Brochure"
                                        required></div>
                                <div class="col-3"><label for="tipo">Tipo de Concepto</label>
                                    <select name="tipo" id="tipo" class="form-control form-select">
                                        <option value="producto">Producto</option>
                                        <option value="servicio">Servicio</option>
                                    </select>
                                </div>
                                <div class="col-3"><label for="cantidad">Cantidad</label><input type="number"
                                        class="form-control" id="cantidad" name="cantidad" min="1" max="99999" value="1"
                                        required></div>
                                <div class="col-3">
                                    <label for="precio_unitario">Precio unitario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" id="precio_unitario"
                                            name="precio_unitario" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-success" type="submit">Guardar concepto</button>
                            <button class="btn btn-danger" type="reset" id="cancel_edit">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Resumen Cotización -->
            <div class="card">
                <div class="card-header d-flex justify-content-around">
                    <h3>Resumen de Cotización</h3> <button class="btn btn-sm float-end restart_quote"
                        style="background-color: #c6013f; color: white;">Reiniciar</button>
                </div>
                <div class="card-body wrapper_quote">
                    <!-- tabla de datos -->
                </div>
                <div class="card-footer">
                    <button class="btn btn-success" id="generate_quote">Generar cotización</button>
                    <button class="btn btn-primary" id="download_quote" style="display: none;">Descargar PDF</button>
                    <button class="btn btn-success" id="send_quote" style="display: none;">Enviar por correo</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- FOOTER -->

<?php require_once INCLUDES . 'footer.php' ?>