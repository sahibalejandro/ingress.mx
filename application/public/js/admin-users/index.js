/**
 * Script para la vista "admin-users/index.php"
 * @author MasterZero
 */
$(document).on('ready', function (e)
{
  /**
   * Mostrar dialogo para desactivar usuario
   */
  $('.btn_show_block_dialog').on('click', function (e)
  {
    // Obtener el ID y nombre del usuario para usarlos en el dialogo
    var user_id = $(this).data('user-id');
    var user_name = $(this).data('user-name');

    // Mostrar el dialogo
    $('#modal_block_user')
    .off('show')
    .off('shown')
    .on('show', function()
    {
      $('#block_error_msg').hide();
      $('#block_user_name').html(user_name);
      $('#block_user_id').val(user_id);
      $('#block_reason').val('');
    })
    .on('shown', function (e)
    {
      $('#block_reason').focus();
    })
    .modal({
      backdrop: 'static',
      keyboard: false
    });
  });

  /**
   * Enviar solicitud para activar usuario
   */
  $('#frm_block_user').on('submit', function (e)
  {
    var $BtnSubmit      = $('#btn_block_submit');
    var $BtnClose       = $('#btn_block_close');
    var $ErrorMsg       = $('#block_error_msg');
    var btn_submit_text = $BtnSubmit.text();

    Quark.ajax('admin-users/ajax-deactivate-user', {
      data: $(this).serialize(),
      success: function (Response)
      {
        // Ocultar el dialogo
        $('#modal_block_user').modal('hide');

        // Actualizar la interfaz para mostrar los controles correctos
        var user_id  = $('#block_user_id').val();
        var $UserRow = $('#user' + user_id);
        $UserRow.find('.controls_user_active').hide();
        $UserRow.find('.controls_user_inactive').show();
      },
      fail: function (Response)
      {
        $ErrorMsg.show().text(Response.message);
      },
      beforeSend: function ()
      {
        $ErrorMsg.hide();
        $BtnSubmit.prop('disabled', 'disabled').text('Espere...');
        $BtnClose.prop('disabled', 'disabled');
      },
      complete: function ()
      {
        $BtnSubmit.removeProp('disabled').text(btn_submit_text);
        $BtnClose.removeProp('disabled');
      }
    });
  });

  /**
   * Mostrar dialogo para activar usuario
   */
  $('.btn_show_unblock_dialog').on('click', function (e)
  {
    // Obtener el ID y nombre del usuario para usarlos en el dialogo
    var user_id = $(this).data('user-id');
    var user_name = $(this).data('user-name');

    // Mostrar el dialogo
    $('#modal_unblock_user')
    .off('show')
    .on('show', function()
    {
      $('#unblock_error_msg').hide();
      $('#unblock_user_name').html(user_name);
      $('#unblock_user_id').val(user_id);
    })
    .modal({
      backdrop: 'static',
      keyboard: false
    });
  });

  /**
   * Enviar solicitud para activar usuario
   */
  $('#frm_unblock_user').on('submit', function (e)
  {
    var $BtnSubmit      = $('#btn_unblock_submit');
    var $BtnClose       = $('#btn_unblock_close');
    var $ErrorMsg       = $('#unblock_error_msg');
    var btn_submit_text = $BtnSubmit.text();

    Quark.ajax('admin-users/ajax-activate-user', {
      data: $(this).serialize(),
      success: function (Response)
      {
        // Ocultar el dialogo
        $('#modal_unblock_user').modal('hide');

        // Actualizar la interfaz para mostrar los controles correctos
        var user_id  = $('#unblock_user_id').val();
        var $UserRow = $('#user' + user_id);
        $UserRow.find('.controls_user_inactive').hide();
        $UserRow.find('.controls_user_active').show();
      },
      fail: function (Response)
      {
        $ErrorMsg.show().text(Response.message);
      },
      beforeSend: function ()
      {
        $ErrorMsg.hide();
        $BtnSubmit.prop('disabled', 'disabled').text('Espere...');
        $BtnClose.prop('disabled', 'disabled');
      },
      complete: function ()
      {
        $BtnSubmit.removeProp('disabled').text(btn_submit_text);
        $BtnClose.removeProp('disabled');
      }
    });
  });

  /**
   * Mostrar dialogo para cambiar el role del usuario
   */
  $('.btn_change_role').on('click', function (e)
  {
    // Obtener el ID y nombre del usuario para usarlos en el dialogo
    var user_id   = $(this).data('user-id');
    var user_name = $(this).data('user-name');

    // Mostrar el dialogo
    $('#modal_change_role')
    .off('show')
    .on('show', function()
    {
      $('#change_role_error_msg').hide();
      $('#change_role_user').html(user_name);
      $('#change_role_user_id').val(user_id);
    })
    .modal({
      backdrop: 'static',
      keyboard: false
    });
  });

  /**
   * Enviar solicitud para cambiar el role del usuario
   */
  $('#frm_change_user_role').on('submit', function (e)
  {
    var $BtnSubmit      = $('#btn_change_role_submit');
    var $BtnClose       = $('#btn_change_role_close');
    var $ErrorMsg       = $('#change_role_error_msg');
    var btn_submit_text = $BtnSubmit.text();

    Quark.ajax('admin-users/ajax-change-user-role', {
      data: $(this).serialize(),
      success: function (Response)
      {
        // Ocultar el dialogo
        $('#modal_change_role').modal('hide');

        // Actualizar la interfaz para mostrar el nombre del nuevo role
        var user_id = $('#change_role_user_id').val();
        $('.cell_role', '#user' + user_id).text(Response.result.new_role_name);
      },
      fail: function (Response)
      {
        $ErrorMsg.show().text(Response.message);
      },
      beforeSend: function ()
      {
        $ErrorMsg.hide();
        $BtnSubmit.prop('disabled', 'disabled').text('Espere...');
        $BtnClose.prop('disabled', 'disabled');
      },
      complete: function ()
      {
        $BtnSubmit.removeProp('disabled').text(btn_submit_text);
        $BtnClose.removeProp('disabled');
      }
    });
  });
});
