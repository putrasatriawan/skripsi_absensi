define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();

      $(".loadingpage").hide();
    },
    initEvent: function() {
          $('#configForm').on('submit', function(event) {
              event.preventDefault(); // Prevent form submission
              
              var longitude = $('#longitude').val();
              var latitude = $('#latitude').val();

              $.ajax({
                  url: App.baseUrl + 'config/save', // Use site_url as a global variable or pass it
                  method: 'POST',
                  data: {
                      longitude: longitude,
                      latitude: latitude
                  },
                  dataType: 'json',
                  success: function(response) {
                      var alertClass = (response.status === 'success') ? 'alert-success' : 'alert-danger';
                      $('#alert-container').html('<div class="alert ' + alertClass + '">' + response.message + '</div>');

                      // Display notification
                      if (response.status === 'success') {
                          toastr.success(response.message);
                      } else {
                          toastr.error(response.message);
                      }

                      // Remove alert after a few seconds
                      setTimeout(function() {
                          $('#alert-container .alert').fadeOut();
                      }, 3000);
                  }
              });
          });
      }
  };
});
