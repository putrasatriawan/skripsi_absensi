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

      $(document).on("click", ".shoot-button", function () {
        App.capturePhoto();
      });


      $(".loadingpage").hide();

      
      App.updateClock();
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


    updateClock : function () {
      const now = new Date();
      const timeString = now.toLocaleTimeString(); // Format: HH:MM:SS AM/PM
      document.getElementById("time-info").innerText = timeString;
      setInterval(App.updateClock, 1000);
  },

    getGeoLocation: function () {
      App.showLoading();
      
      if (navigator.geolocation) {
          navigator.geolocation.watchPosition(
              (position) => {
                  App.hideLoading();
                
                  
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

                  document.getElementById("geo-location").innerText = `📍 Latitude: ${App.userLatitude}, Longitude: ${App.userLongitude}`;
                  
                  var jarak = Math.round(distance);
                  let formattedDistance = jarak.toLocaleString() + " m"; // Semua dalam meter
                  
                  document.getElementById("distance-info").innerText = formattedDistance;
                  
                  // console.log("jarak",jarak)
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
  
      return Math.round(R * c * 1000); 
  },
  
  
  

  checkLocationAndOpenCamera: function (jarak) {
    let redSignal = document.querySelector(".sinyal");
    let greenSignal = document.querySelector(".green_sinyal");
    let camera = document.getElementById("open-camera");


    console.log("jarak",jarak)
    if (jarak < 30) {
        console.log("Dalam radius yang diizinkan, membuka kamera...");
        // camera.classList.remove("d-none");
        redSignal.classList.add("d-none");
        greenSignal.classList.remove("d-none"); 


        document.getElementById("status-info").innerText = "Work From Office";
        document.getElementById("status-info").classList.remove("text-danger");
        document.getElementById("status-info").classList.add("text-success");


    } else {
        console.log("Di luar radius, menyembunyikan kamera...");
        // camera.classList.add("d-none");
        redSignal.classList.remove("d-none"); 
        greenSignal.classList.add("d-none");
        camera.classList.remove("d-none");


        document.getElementById("status-info").innerText = "Work From Home";
        document.getElementById("status-info").classList.remove("text-success");
        document.getElementById("status-info").classList.add("text-danger");
    }
  },



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
      const photoData = canvas.toDataURL("image/png"); 
      App.sendPhoto(photoData); 
    },

    // Send photo and location using jQuery AJAX
    sendPhoto: function (photoData) {
      const formData = new FormData();
      const checkInTime = document.getElementById("check_in").textContent.trim();
      const checkOutTime = document.getElementById("check_out").textContent.trim();      
      const distance = document.getElementById("distance-info").innerText; 
      const status = document.getElementById("status-info").innerText;
  
      // Ambil waktu sekarang
      const now = new Date();
      const initTime = now.toLocaleTimeString('en-GB', { hour12: false }); 
  
      formData.append("photo", photoData); 
      formData.append("latitude", App.userLatitude);
      formData.append("longitude", App.userLongitude); 
      formData.append("check_in_const", checkInTime); 
      formData.append("check_out_const", checkOutTime); 
      formData.append("distance", distance); 
      formData.append("status", status); 
      formData.append("init_time", initTime); 
  
      console.log("Form Data:", Array.from(formData.entries()));
  
      $.ajax({
        url: App.baseUrl + "absensi/init_absensi",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          document.getElementById("submit-status").innerText = "Absen Berhasil!";
          toastr.success("Absen berhasil!");
          location.reload();
        },
        error: function () {
          document.getElementById("submit-status").innerText = "Terjadi kesalahan saat mengirim foto!";
          toastr.error("Terjadi kesalahan saat mengirim foto!");
          location.reload();
        },
      });
  },
  
  };
});
