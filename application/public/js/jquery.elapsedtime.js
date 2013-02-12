/**
 * Plugin para mostrar el tiempo transcurrido en los elementos que tienen el
 * atributo "data-timestamp"
 *
 * @author Sahib J. Leo
 */
(function ($)
{
  var time_interval_id = null;
  var elements  = [];
  var week_days = ['Dom.', 'Lun.', 'Mar.', 'Mié.', 'Jue.', 'Vie.', 'Sáb.'];
  var months    = ['Ene.', 'Feb.', 'Mar.', 'Abr.',
                   'May.', 'Jun.', 'Jul.', 'Ago.',
                   'Sep.', 'Oct.', 'Nov.', 'Dic.'];

  function update_elements()
  {
    $(elements).each(function (i, $Element)
    {
      var date_string = '';
      var CurrentDate = new Date();
      var ElementDate = new Date(parseInt($Element.data('timestamp'), 10) * 1000);
      var seconds     = Math.round((CurrentDate.getTime() - ElementDate.getTime()) / 1000);
      var minutes     = Math.round(seconds / 60);
      var hours       = Math.round(seconds / 3600);
      var days        = Math.round(seconds / 86400);
      
      if (seconds < 60) {
        // Segundos
        if (seconds < 2) {
          date_string = 'hace ' + seconds + ' segundo';
        } else {
          date_string = 'hace ' + seconds + ' segundos';
        }
      } else if (minutes < 60) {
        // Minutos
        if (minutes < 2) {
          date_string = 'hace ' + minutes + ' minuto';
        } else {
          date_string = 'hace ' + minutes + ' minutos';
        }
      } else if (hours < 24) {
        // Horas
        if (hours < 2) {
          date_string = 'hace ' + hours + ' hora';
        } else {
          date_string = 'hace ' + hours + ' horas';
        }
      } else if (days < 5) {
        // Días
        if (days < 2) {
          date_string = 'ayer';
        } else {
          date_string = 'hace ' + days + ' días';
        }
      } else {
        /* Si el tiempo es >= 5 días damos formato final y eliminamos el elemento
         * del array de elementos actualizables por que ya no será necesario
         * actualizar este elemento. */
        elements.splice(i, 1);
        
        // Forzar a las horas a tener 2 digitos
        var hours_two_digits   = ElementDate.getHours();
        if (hours_two_digits < 10) {
          hours_two_digits = '0' + hours_two_digits;
        }
        
        // Forzar a los minutos a tener 2 digitos
        var minutes_two_digits = ElementDate.getMinutes();
        if (minutes_two_digits < 10) {
          minutes_two_digits = '0' + minutes_two_digits;
        }

        date_string = 'el '
          + week_days[ElementDate.getDay()]
          + ' ' + ElementDate.getDate()
          + ' de ' + months[ElementDate.getMonth()]
          + ' ' + ElementDate.getFullYear()
          + ', ' + hours_two_digits
          + ':' + minutes_two_digits
          + ' Hrs.';
      }
      $Element.text(date_string);
    });
  }

  $.fn.elapsedTime = function ()
  {
    // Agregar los nuevos elementos al array de elementos actualizables
    this.each(function (i, Element)
    {
      elements.push($(Element));
    });
    
    // Actualizar los textos de las fechas
    update_elements();
    
    // Inicializar el intervalo (solo si no ha sido iniciado antes)
    if (time_interval_id == null) {
      time_interval_id = setInterval(update_elements, 10000);
    }

    return this;
  }
})(jQuery);
