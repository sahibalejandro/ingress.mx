/**
 * Script for the view "views/profile.php"
 */
$(document).on('ready', function ()
{
  $('#states_id').on('change', function (e)
  {
    load_cities(this.value);
  });
});

function load_cities(states_id)
{
  var $SelectCities = $('#cities_id').empty();

  if (states_id != '-') {
    Quark.ajax('catalogs/cities', {
      data: {'states_id': states_id},
      success: function (Response)
      {
        $(Response.result).each(function(i, City)
        {
          $SelectCities.append(
            $('<option>').val(City.id).text(City.name)
          );
        });
      },
      beforeSend: function ()
      {
        $('#states_id').attr('disabled', 'disabled');
      },
      complete: function ()
      {
        $('#states_id').removeAttr('disabled');
      }
    });
  }
};
