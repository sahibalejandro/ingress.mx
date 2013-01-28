/**
 * Script para la vista "admin-users/authorize-account.js"
 */
$(document).on('ready', function (e)
{
  var $FrmAuthorize      = $('#frm_authorize');
  var $ReasonDenial      = $('#reason_denial');
  var $BtnSubmit         = $('#btn_submit');
  var $AuthorizeErrorMsg = $('#authorize_error_msg');
  var $DoneMsg           = $('#done_msg');
  var btn_submit_text    = $BtnSubmit.text();

  // Habilitar o deshabilitar el textarea para el mensaje de denegación
  $('input[name=authorize]').on('click', function (e)
  {
    if (this.value == '0') {
      $ReasonDenial.removeProp('disabled').focus();
    } else {
      $ReasonDenial.prop('disabled', 'disabled');
    }
  });

  $FrmAuthorize.on('submit', function (e)
  {
    Quark.ajax('admin-users/ajax-authorize', {
      data: $FrmAuthorize.serialize(),
      success: function (Response) {
        $DoneMsg.show();
        $BtnSubmit.text(btn_submit_text);
        $BtnSubmit = null;
      },
      fail: function (Response) {
        $AuthorizeErrorMsg.text(Response.message).show();
      },
      beforeSend: function ()
      {
        $BtnSubmit.prop('disabled', 'disabled').text('Espere...');
        $AuthorizeErrorMsg.hide();
      },
      complete: function ()
      {
        if ($BtnSubmit) {
          $BtnSubmit.removeProp('disabled').text(btn_submit_text);
        }
      }
    });
  });
});
