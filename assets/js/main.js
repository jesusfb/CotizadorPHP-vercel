$("document").ready(() => {
  //Función para generar notificaciones
  function notify(content, type = "success") {
    let wrapper = $(".wrapper_notifications"),
      id = Math.floor(Math.random() * 500 + 1),
      notification =
        '<div class="alert alert-' +
        type +
        '"id="noty_' +
        id +
        '">' +
        content +
        "</div>",
      time = 5000;

    wrapper.append(notification);

    setTimeout(() => {
      $("#noty_" + id).remove();
    }, time);

    return true;
  }

  //Cargar contenido de la cotización
  function get_quote() {
    let wrapper = $(".wrapper_quote");

    action = "get_quote_res";
    nombre = $("#nombre");
    company = $("#empresa");
    email = $("#email");
    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "GET",
      cache: false,
      dataType: "json",
      data: { action },
      beforeSend: function () {
        wrapper.waitMe({
          effect: "facebook",
        });
      },
    })
      .done((res) => {
        if (res.status == 200) {
          nombre.val(res.data.quote.name);
          company.val(res.data.quote.company);
          email.val(res.data.quote.email);
          wrapper.html(res.data.html);
        } else {
          // nombre.val("");
          // company.val("");
          // email.val("");
          wrapper.html(res.message);
        }
      })
      .fail((err) => {
        wrapper.html("Ocurrió un error, recarga la página.");
      })
      .always(() => {
        wrapper.waitMe("hide");
      });
  }

  get_quote();

  //Función para agregar concepto a la cotización
  $("#add_to_quote").on("submit", add_to_quote);
  function add_to_quote(e) {
    e.preventDefault();
    let form = $("#add_to_quote"),
      action = "add_to_quote",
      data = new FormData(form.get(0));
    errors = 0;

    data.append("action", action);

    let concepto = $("#concepto").val(),
      precio = parseFloat($("#precio_unitario").val());

    if (concepto.length < 5) {
      notify("Ingresa un concepto válido.", "danger");
      errors++;
    }

    if (precio <= 51 || isNaN(precio)) {
      notify("Ingresa un precio válido.", "danger");
      errors++;
    }

    if (errors > 0) {
      notify("Verifica los campos.", "danger");
      return false;
    }
    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      data: data,
      beforeSend: () => {
        form.waitMe({
          effect: "facebook",
        });
      },
    })
      .done((res) => {
        if (res.status === 201) {
          notify(res.message);
          form.trigger("reset");
          get_quote();
        } else {
          notify(res.message, "danger");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
        form.trigger("reset");
      })
      .always(() => {
        form.waitMe("hide");
      });
  }
  $(".restart_quote").on("click", restart_quote);
  function restart_quote(e) {
    e.preventDefault();
    let button = $(this),
      action = "restart_quote";
    download = $("#download_quote");
    generate = $("#generate_quote");
    default_text = "Generar cotización";

    if (!confirm("¿Estás seguro de reiniciar la cotización?")) return false;
    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: { action },
    })
      .done((res) => {
        if (res.status === 200) {
          download.fadeOut();
          download.attr("href", "");
          generate.html(default_text);
          notify(res.message);
          get_quote();
        } else {
          notify(res.message, "danger");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
      })
      .always(() => {});
  }

  $("body").on("click", ".delete_concept", delete_concept);
  function delete_concept(e) {
    e.preventDefault();
    let button = $(this),
      id = button.data("id"),
      action = "delete_concept";

    if (!confirm("¿Estás seguro de eliminar este concepto?")) return false;

    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: { action, id },
      beforeSend: () => {
        $("body").waitMe({
          effect: "facebook",
        });
      },
    })
      .done((res) => {
        if (res.status === 200) {
          notify(res.message);
          get_quote();
        } else {
          notify(res.message, "danger");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
      })
      .always(() => {
        $("body").waitMe("hide");
      });
  }
  $("body").on("click", ".edit_concept", edit_concept);
  function edit_concept(e) {
    e.preventDefault();
    let button = $(this),
      id = button.data("id"),
      action = "edit_concept";
    wrapper_update_concept = $(".wrapper_update_concept");
    form_update_concept = $("#save_concept");

    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: { action, id },
      beforeSend: () => {
        $("body").waitMe({
          effect: "facebook",
        });
      },
    })
      .done((res) => {
        if (res.status === 200) {
          $("#id_concepto", form_update_concept).val(res.data.id);
          $("#concepto", form_update_concept).val(res.data.concept);
          $(
            "#tipo option[value=" + res.data.tipo + "]",
            form_update_concept
          ).attr("selected", true);
          $("#cantidad", form_update_concept).val(res.data.quantity);
          $("#precio_unitario", form_update_concept).val(res.data.price);
          wrapper_update_concept.fadeIn();
          notify(res.message);
        } else {
          notify(res.message, "danger");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
      })
      .always(() => {
        $("body").waitMe("hide");
      });
  }

  //Función guardar cambios de concepto editado
  $("#save_concept").on("submit", save_concept);
  function save_concept(e) {
    e.preventDefault();
    let form = $("#save_concept"),
      action = "save_concept",
      data = new FormData(form.get(0));
    wrapper_update_concept = $(".wrapper_update_concept");
    errors = 0;

    data.append("action", action); //Agregamos la acción al FormData

    let concepto = $("#concepto", form).val(),
      precio = parseFloat($("#precio_unitario", form).val());

    if (concepto.length < 5) {
      notify("Ingresa un concepto válido.", "danger");
      errors++;
    }

    if (precio <= 51 || isNaN(precio)) {
      notify("Ingresa un precio válido.", "danger");
      errors++;
    }

    if (errors > 0) {
      notify("Verifica los campos.", "danger");
      return false;
    }
    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      data: data,
      beforeSend: () => {
        form.waitMe({
          effect: "facebook",
        });
      },
    })
      .done((res) => {
        if (res.status === 200) {
          notify(res.message);
          form.trigger("reset");
          wrapper_update_concept.fadeOut();
          notify(res.message);
          get_quote();
        } else {
          notify(res.message, "danger");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
        wrapper_update_concept.fadeOut();
        form.trigger("reset");
      })
      .always(() => {
        form.waitMe("hide");
      });
  }

  $("#cancel_edit").on("click", (e) => {
    e.preventDefault();
    let button = $(this),
      wrapper_update_concept = $(".wrapper_update_concept");
    form = $("#save_concept");
    wrapper_update_concept.fadeOut();
    form.trigger("reset");
  });

  $("#generate_quote").on("click", generate_quote);
  function generate_quote(e) {
    e.preventDefault();
    let button = $(this),
      default_text = button.html(),
      new_text = "Volver a generar",
      send = $("#send_quote"),
      download = $("#download_quote"),
      nombre = $("#nombre").val(),
      empresa = $("#empresa").val(),
      email = $("#email").val(),
      nit = $("#nit").val(),
      nombre_proveedor = $("#nombre_proveedor").val(),
      empresa_proveedor = $("#empresa_proveedor").val(),
      email_proveedor = $("#email_proveedor").val(),
      action = "generate_quote";
    errors = 0;

    if (!confirm("¿Estás seguro de generar la cotización?")) return false;

    if (nit.length < 8) {
      notify("Ingresa un NIT válido.", "danger");
      errors++;
    }

    if (nombre_proveedor.length < 3) {
      notify("Ingresa un nombre para el proveedor.", "danger");
      errors++;
    }

    if (nombre.length < 3) {
      notify("Ingresa un nombre para el cliente.", "danger");
      errors++;
    }

    if (empresa.length < 5 || empresa_proveedor.length < 5) {
      notify("Ingresa un nombre para la empresa.", "danger");
      errors++;
    }

    if (email.length < 5 || email_proveedor.length < 5) {
      notify("Ingresa un correo electrónico válido.", "danger");
      errors++;
    }

    if (errors > 0) {
      notify("Verifica los campos.", "danger");
      return false;
    }

    $.ajax({
      url: "Cotizador_PHP/ajax.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: {
        action,
        nombre,
        empresa,
        email,
        nit,
        nombre_proveedor,
        empresa_proveedor,
        email_proveedor,
      },
      beforeSend: () => {
        $("body").waitMe({
          effect: "facebook",
        });
        button.html("Generando...");
      },
    })
      .done((res) => {
        if (res.status === 200) {
          notify(res.message);
          download.attr("href", res.data.url);
          download.fadeIn();
          send.fadeIn();
          button.html(new_text);
          get_quote();
        } else {
          notify(res.message, "danger");
          download.attr("href", "");
          download.fadeOut();
          send.fadeOut();
          button.html("Reintentar");
        }
      })
      .fail((err) => {
        notify("Ocurrió un error, recarga la página.", "danger");
        button.html(default_text);
      })
      .always(() => {
        $("body").waitMe("hide");
      });
  }
});
