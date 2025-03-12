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
    definedLatitude : null,
    definedLongitude : null,
    maxRadius: 100,

    // Initialize the application
    init: function () {
      console.log("Defined Latitude: ", this.definedLatitude);
      console.log("Defined Longitude: ", this.definedLongitude);
      $(document).on("click", "#open-camera", function () {
        App.triggerCamera();
      });

      $(document).on("click", ".get-location", function () {
        App.getGeoLocation();
      });

      $(".loadingpage").hide();

      App.getGeoLocation();
    },

    // Show loading modal
    showLoading: function () {
      document.getElementById("loadingModal").style.display = "block";
    },
    hideLoading: function () {
      document.getElementById("loadingModal").style.display = "none";
    },
    hideOpenCamera: function () {
      document.getElementById("open-camera").classList.remove('d-none');
    },
    hideCamera: function () {
      document.getElementById("open-camera").addClass('d-none');
    },
    logoMerah: function() {
      const logo = document.getElementById("logo-merah");
      if (logo) {
          logo.classList.remove("d-none");
      }
    },
    logoHijau: function() {
        const logo = document.getElementById("logo-hijau");
        if (logo) {
            logo.classList.remove("d-none");
        }
    },
  
    logoHijauMerah: function () {
    },

    getGeoLocation: function () {
      App.showLoading();
      
      if (navigator.geolocation) {
          navigator.geolocation.watchPosition(
              (position) => {
                  App.hideLoading();
                
                  
                  // Hitung jarak dari lokasi yang ditentukan
                  const distance = App.calculateDistance(
                    App.userLatitude = (position.coords.latitude),
                    App.userLongitude = (position.coords.longitude),
                    // '-6.9178',
                    // '107.6604',
                    '-6.9564297',
                    '107.7719317',
                    // App.definedLatitude = App.definedLatitude,
                    // App.definedLongitude = App.definedLongitude,
                    
                  );

                  // console.log("User Lat:", App.userLatitude, "User Lon:", App.userLongitude);
                  // console.log("Defined Lat:", App.definedLatitude, "Defined Lon:", App.definedLongitude);
                  console.log("Calculated Distance:", distance);

                  // Perbarui elemen lokasi
                  document.getElementById("geo-location").innerText = `📍 Latitude: ${App.userLatitude}, Longitude: ${App.userLongitude}`;
                  
                  // Perbarui elemen jarak di dalam kartu biodata
                  var jarak = Math.round(distance);
                  let formattedDistance = jarak.toLocaleString() + " m"; // Semua dalam meter
                  
                  document.getElementById("distance-info").innerText = formattedDistance;
                  
                  
                  // Perbarui elemen jam dengan waktu real-time
                  const now = new Date();
                  const timeString = now.toLocaleTimeString();
                  document.getElementById("time-info").innerText = timeString;
                  
                  // toastr.success("Lokasi diperbarui secara real-time.");
                  App.checkLocationAndOpenCamera(jarak);
              },
              (error) => {
                  // App.hideLoading();
                  // document.getElementById("geo-location").innerText = "Lokasi tidak dapat diakses!";
                  // toastr.error("Lokasi tidak dapat diakses!");
              },
              {
                  enableHighAccuracy: true, // Meningkatkan akurasi lokasi
                  maximumAge: 0, // Tidak menggunakan cache
                  timeout: 5000 // Timeout request
              }
          );
      } else {
          App.hideLoading();
          document.getElementById("geo-location").innerText = "Geolocation tidak didukung oleh browser ini.";
          toastr.error("Geolocation tidak didukung oleh browser ini.");
      }
    },
    // calculateDistance: function(lat1, lon1, lat2, lon2) { // Urutan dibalik
    //   const R = 6371; // Radius Bumi dalam km
    //   const dLat = (lat2 - lat1) * (Math.PI / 180);
    //   const dLon = (lon2 - lon1) * (Math.PI / 180);
    //   const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    //             Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
    //             Math.sin(dLon / 2) * Math.sin(dLon / 2);
    //   const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    //   return Math.round(R * c * 1000); // Menggunakan `Math.round` agar hasil lebih akurat
    // },
  
  
    calculateDistance: function (userLat, userLon, centerLat, centerLon) {
      const R = 6371; // Radius bumi dalam km
      const dLat = (centerLat - userLat) * (Math.PI / 180);
      const dLon = (centerLon - userLon) * (Math.PI / 180);
  
      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(userLat * (Math.PI / 180)) * Math.cos(centerLat * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
                
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  
      return Math.round(R * c * 1000); // Hasil dalam meter, dibulatkan
  },
  
  
  

  checkLocationAndOpenCamera: function (jarak) {
    let redSignal = document.querySelector(".sinyal");
    let greenSignal = document.querySelector(".green_sinyal");
    let camera = document.getElementById("open-camera");


    console.log("jarak",jarak)
    if (jarak < 30) {
        console.log("Dalam radius yang diizinkan, membuka kamera...");
        camera.classList.remove("d-none");
        redSignal.classList.add("d-none");
        greenSignal.classList.remove("d-none"); // Munculkan sinyal hijau
    } else {
        console.log("Di luar radius, menyembunyikan kamera...");
        camera.classList.add("d-none");
        redSignal.classList.remove("d-none"); // Munculkan sinyal merah
        greenSignal.classList.add("d-none");
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
        url: App.baseUrl + "absensi_guru/uploads",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          document.getElementById("submit-status").innerText =
            "Foto berhasil dikirim!";
          toastr.success("Foto berhasil dikirim!");
          // setTimeout(function () {
          //   window.location.reload();
          // }, 1000);
        },
        error: function () {
          document.getElementById("submit-status").innerText =
            "Terjadi kesalahan saat mengirim foto!";
          toastr.error("Terjadi kesalahan saat mengirim foto!");
        },
      });
    },
  };
});
