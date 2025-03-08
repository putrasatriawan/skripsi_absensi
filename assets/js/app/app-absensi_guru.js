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
                  App.userLatitude = position.coords.latitude;
                  App.userLongitude = position.coords.longitude;
                  
                  // Hitung jarak dari lokasi yang ditentukan
                  const distance = App.calculateDistance(
                      App.userLatitude, 
                      App.userLongitude, 
                      App.definedLatitude, 
                      App.definedLongitude
                  );

                  console.log(distance)
                  
                  var jarak = Math.floor(distance);
                  // Perbarui elemen lokasi
                  document.getElementById("geo-location").innerText = `📍 Latitude: ${App.userLatitude}, Longitude: ${App.userLongitude}`;
                  
                  // Perbarui elemen jarak di dalam kartu biodata
                  document.getElementById("distance-info").innerText = `${Math.floor(distance)} Meter`;
                  
                  // Perbarui elemen jam dengan waktu real-time
                  const now = new Date();
                  const timeString = now.toLocaleTimeString();
                  document.getElementById("time-info").innerText = timeString;
                  
                  toastr.success("Lokasi diperbarui secara real-time.");
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
    calculateDistance: function(lat2, lon2, lat1, lon1,) {
      const R = 6371;
      const dLat = (lat2 - lat1) * (Math.PI / 180);
      const dLon = (lon2 - lon1) * (Math.PI / 180);
      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      return parseInt(R * c * 1000);
  },
  
  // calculateDistance: function (userLat, userLon, centerLat, centerLon) {
  //     const latRange = 30 / 111320; // Selisih latitude untuk radius 30 meter
  //     const lonRange = 30 / 111320; // Selisih longitude untuk radius 30 meter (tanpa cos)
  
  //     // Cek apakah user berada dalam rentang latitude & longitude
  //     const isLatInRange = userLat >= centerLat - latRange && userLat <= centerLat + latRange;
  //     const isLonInRange = userLon >= centerLon - lonRange && userLon <= centerLon + lonRange;
  
  //     if (isLatInRange && isLonInRange) {
  //         return 0; // Jika dalam radius 30 meter, jarak dianggap 0 meter
  //     }
      
  //     // Jika di luar radius 30 meter, hitung jarak dalam meter
  //     const dLat = (userLat - centerLat) * 111320;
  //     const dLon = (userLon - centerLon) * 111320; // Tidak dikalikan cos(latitude) karena pendekatan sederhana
  //     return Math.sqrt(dLat * dLat + dLon * dLon); // Jarak dalam meter
  // },
  
  

  checkLocationAndOpenCamera: function (jarak) {
    let redSignal = document.querySelector(".sinyal");
    let greenSignal = document.querySelector(".green_sinyal");
    let camera = document.getElementById("open-camera");

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
