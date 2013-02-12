/**
 * Script to handle comment publishing.
 * This script is loaded in view "posts/read.php"
 */
$(document).on('ready', function (e)
{
  var $CommentsContainer = $('#comments_container');
  var $InputCommentToken = $('#comment_token');
  var $BtnSubmitComment  = $('#btn_submit_comment');
  var $CommentErrorMsg   = $('#comment_error_msg');
  var $MsgNoComments     = $('#msg_no_comments');
  
  var btn_submit_comment_text_busy = 'Enviando...';
  var btn_submit_comment_text      = '';
  
  $('#frm_comment').on('submit', function (e)
  {
    var $FrmComment = $(this);

    Quark.ajax('post/ajax-post-comment', {
      data: $FrmComment.serialize(),
      success: function (Response)
      {
        /* Actualizar el token para realizar comentarios, agregar el nuevo
         * comentario al documento e inicializar su "elapsedTime", ocultar el mensaje
         * "Sin comentarios" (solo cuando es el primer comentario) y limpiar el
         * formulario de comentario */
        $InputCommentToken.val(Response.result.new_token);
        $CommentsContainer.append(Response.result.comment_html);
        $CommentsContainer.find('[data-timestamp]:last').elapsedTime();
        $MsgNoComments.remove();
        $FrmComment.trigger('reset');
      },
      fail: function (Response) {
        $CommentErrorMsg.text(Response.message).show();
      },
      beforeSend: function ()
      {
        btn_submit_comment_text = $BtnSubmitComment.text();
        $BtnSubmitComment.text(btn_submit_comment_text_busy).prop('disabled', 'disabled');
        $CommentErrorMsg.hide();
      },
      complete: function ()
      {
        $BtnSubmitComment.removeProp('disabled').text(btn_submit_comment_text);
      }
    });
  });
});
