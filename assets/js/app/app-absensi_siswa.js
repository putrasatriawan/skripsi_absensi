define(["datatablesBS4", "jqvalidate", "toastr"], function (
  datatablesBS4,
  jqvalidate,
  toastr
) {
  return {
    // Global variables for latitude and longitude
    table: null,
    userLatitude: null,
    userLongitude: null,
    definedLatitude: -6.9321906,
    definedLongitude: 107.7754766,
    maxRadius: 100,

    // Initialize the application
    init: function () {
      $(document).on("click", ".open-camera", function () {
        App.checkLocationAndOpenCamera();
      });

      $(document).on("click", ".shoot-button", function () {
        App.capturePhoto();
      });

      $(document).on("click", ".get-location", function () {
        App.getGeoLocation();
      });

      $(".loadingpage").hide();

      // Automatically get location when initialized
      App.getGeoLocation();
      App.DisableCard();
      App.SendDataPengampu();
    },

    // Function to initialize the loading of attendance (prepares environment)
    initLoadAbsen: function () {
      // Initialization code if needed
    },

    // Show loading modal
    showLoading: function () {
      document.getElementById("loadingModal").style.display = "block";
    },

    // Hide loading modal
    hideLoading: function () {
      document.getElementById("loadingModal").style.display = "none";
    },

    // Get user's geolocation with loading modal
    getGeoLocation: function () {
      App.showLoading();

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            App.hideLoading();
            App.userLatitude = position.coords.latitude;
            App.userLongitude = position.coords.longitude;
            document.getElementById(
              "geo-location"
            ).innerText = `📍 Latitude: ${App.userLatitude}, Longitude: ${App.userLongitude}`;
            toastr.success("Lokasi Didapatkan.");
          },
          function (error) {
            App.hideLoading();
            document.getElementById("geo-location").innerText =
              "Lokasi tidak dapat diakses!";
              toastr.error("Lokasi tidak dapat diakses!");
          }
        );
      } else {
        App.hideLoading();
        document.getElementById("geo-location").innerText =
          "Geolocation tidak didukung oleh browser ini.";
          toastr.error("Geolocation tidak didukung oleh browser ini.");
      }
    },

    // Check if user is within the allowed radius
    checkLocation: function () {
      const distance = App.calculateDistance(
        App.userLatitude,
        App.userLongitude,
        App.definedLatitude,
        App.definedLongitude
      );
      return distance <= App.maxRadius;
    },

    // Calculate distance between two geographic points using Haversine Formula
    calculateDistance: function (lat1, lon1, lat2, lon2) {
      const R = 6371e3;
      const φ1 = (lat1 * Math.PI) / 180;
      const φ2 = (lat2 * Math.PI) / 180;
      const Δφ = ((lat2 - lat1) * Math.PI) / 180;
      const Δλ = ((lon2 - lon1) * Math.PI) / 180;

      const a =
        Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      return R * c;
    },

    // Check location and open camera if within the radius
    checkLocationAndOpenCamera: function () {
      if (App.checkLocation()) {
        console.log("Dalam radius yang diizinkan, membuka kamera...");
        App.triggerCamera();
      } else {
        alert("Anda berada di luar radius lokasi yang diizinkan!");
        toastr.error("Anda berada di luar radius lokasi yang diizinkan!");
      }
    },

    // Open the camera
    triggerCamera: function () {
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices
          .getUserMedia({
            video: true,
          })
          .then(function (stream) {
            const video = document.getElementById("video");
            const shootButton = document.querySelector(".shoot-button");
            video.style.display = "block";
            shootButton.style.display = "block";
            video.srcObject = stream;
          })
          .catch(function (error) {
            console.log("Error accessing camera: ", error);
          });
      } else {
        alert("Kamera tidak tersedia pada perangkat ini.");
        toastr.error("Kamera tidak tersedia pada perangkat ini.");
      }
    },

    // Capture screenshot from video
    capturePhoto: function () {
      const video = document.getElementById("video");
      const canvas = document.getElementById("canvas");
      const context = canvas.getContext("2d");
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      const photoData = canvas.toDataURL("image/png"); // Capture photo as base64
      App.sendPhoto(photoData); // Send photo and location via AJAX
    },

    // Send photo and location using jQuery AJAX
    sendPhoto: function (photoData) {
      const formData = new FormData();
      formData.append("photo", photoData); // Add photo to formData
      formData.append("latitude", App.userLatitude); // Add latitude to formData
      formData.append("longitude", App.userLongitude); // Add longitude to formData

      console.log(formData);
      $.ajax({
        url: App.baseUrl + "absensi_siswa/uploads",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          document.getElementById("submit-status").innerText = "Foto berhasil dikirim!";
          toastr.success("Foto berhasil dikirim!");
        },
        error: function () {
          document.getElementById("submit-status").innerText = "Terjadi kesalahan saat mengirim foto!";
          toastr.error("Terjadi kesalahan saat mengirim foto!");
        },
      });
    },

    DisableCard: function(){
      document.getElementById('myButton').disabled = true;
      document.getElementById('myButtonBolos').addEventListener('click', function(){
        alert('Bilangin siah bolos!');
      });
    },

    SendDataPengampu: function(){
      $(document).ready(function() {
          var formData = $("#form-pengampu").serialize(); 
      
          $.ajax({
              url: App.baseUrl + "absensi_siswa/uploads",
              type: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                  console.log('Data berhasil dikirim otomatis.');
              },
              error: function() {
                  console.log('Gagal mengirim data otomatis.');
              }
          });
      });    
    },
  };
});
