define([
  "jquery",
  "bootstrap",
  "metismenu",
  "scrollbar",
  "architect",
  "blockui",
], function ($, bootstrap, metismenu, scrollbar, architect, blockui) {
  return {
    clickEvent: "click",
    baseUrl: document.getElementById("base_url").value,
    initFunc: function () {
      console.log("INIT FUNCTION");
      // $.blockUI.defaults = {
      //     timeout: 1000,
      //     fadeIn: 200,
      //     fadeOut: 400,
      // };
      // $.blockUI({message: $('.body-block-example-1')});

      setTimeout(function () {
        if ($(".scrollbar-container")[0]) {
          $(".scrollbar-container").each(function () {
            const ps = new scrollbar($(this)[0], {
              wheelSpeed: 2,
              wheelPropagation: false,
              minScrollbarLength: 20,
            });
          });

          const ps = new scrollbar(".scrollbar-sidebar", {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20,
          });
        }
      }, 1000);

      App.initValidationForm();
      App.initClickPemberitahuan();
      App.initTooltip();
    },

    initTooltip: function () {
      $('[data-toggle="tooltip"]').tooltip();
      console.log("tooltip/");
    },
    initValidationForm: function () {
      $(".number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (
          $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)
        ) {
          // let it happen, don't do anything
          return;
        }
        // Ensure that it is a number and stop the keypress
        if (
          (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
          (e.keyCode < 96 || e.keyCode > 105)
        ) {
          e.preventDefault();
        }
      });
    },
    alert: function (msg, callback) {
      $("#alert_modal .modal-title").text("");
      // if (title != undefined && title != false && title != "") {
      //     $("#alert_modal .modal-title").text(title);
      // }
      $(".alert-msg").text(msg);
      $(".alert-cancel").hide();
      $(".alert-ok").show();

      $("#alert_modal").modal("show");

      $("#alert_modal .alert-ok").bind(App.clickEvent, function (e) {
        if (callback != undefined && callback != null && callback != false) {
          callback();
        }

        setTimeout(function () {
          $("#alert_modal").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
      });
    },
    confirm: function (msg, callbackOk, callbackCancel) {
      $("#alert_modal .modal-title").text("");
      // if (title != undefined && title != false && title != "") {
      //     $("#alert_modal .modal-title").text(title);
      // }

      $(".alert-msg").text(msg);
      $(".alert-cancel").show();
      $(".alert-ok").show();

      $("#alert_modal").modal("show");

      $("#alert_modal .alert-ok").bind(App.clickEvent, function (e) {
        if (
          callbackOk != undefined &&
          callbackOk != null &&
          callbackOk != false
        ) {
          callbackOk();
        }
        setTimeout(function () {
          $("#alert_modal").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
        $("#alert_modal .alert-cancel").unbind();
      });

      $("#alert_modal .alert-cancel").bind(App.clickEvent, function (e) {
        if (
          callbackCancel != undefined &&
          callbackCancel != null &&
          callbackCancel != false
        ) {
          callbackCancel();
        }
        setTimeout(function () {
          $("#alert_modal").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
        $("#alert_modal .alert-ok").unbind();
      });
    },
    approval: function (msg, callbackOk, callbackCancel) {
      $("#alert_approval .modal-title").text("");

      $(".alert-msg").text(msg);
      $(".alert-cancel").show();
      $(".alert-reject").show();
      $(".alert-approve").show();

      $("#alert_approval").modal("show");
      $("#alert_approval .alert-cancel").bind(App.clickEvent, function (e) {
        setTimeout(function () {
          $("#alert_approval").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
        $("#alert_approval .alert-approve").unbind();
      });
      $("#alert_approval .alert-approve").bind(App.clickEvent, function (e) {
        if (
          callbackOk != undefined &&
          callbackOk != null &&
          callbackOk != false
        ) {
          callbackOk();
        }
        setTimeout(function () {
          $("#alert_approval").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
        $("#alert_approval .alert-cancel").unbind();
      });

      $("#alert_approval .alert-reject").bind(App.clickEvent, function (e) {
        if (
          callbackCancel != undefined &&
          callbackCancel != null &&
          callbackCancel != false
        ) {
          callbackCancel();
        }
        setTimeout(function () {
          $("#alert_approval").modal("hide");
        }, 200);

        e.preventDefault();
        $(this).unbind();
        $("#alert_approval .alert-ok").unbind();
      });
    },

    format: function (obj) {
      var restoreMoneyValueFloat = function (obj) {
        var r = obj.value.replace(/\./g, "");
        r = r.replace(/,/, "#");
        r = r.replace(/,/g, "");
        r = r.replace(/#/, ".");
        return r;
      };

      var getDecimalSeparator = function () {
        var f = parseFloat(1 / 4);
        var n = new Number(f);
        var r = new RegExp(",");
        if (r.test(n.toLocaleString())) return ",";
        else return ".";
      };

      if (obj.value == "-") return;

      var val = restoreMoneyValueFloat(obj);

      var myreg = /\.([0-9]*)$/;
      var adakoma = myreg.test(val);
      var lastkoma = adakoma ? RegExp.$1 == "" : false;

      myreg = /\.(0+)$/;
      var lastnol = adakoma && myreg.test(val);

      myreg = /(0+)$/;
      var tailnol = adakoma && myreg.test(val);
      var adanol = tailnol ? RegExp.$1 : "";

      var n = parseFloat(val);

      n = isNaN(n) ? 0 : n;
      //if (entryFormatMoney.arguments[1] && n > entryFormatMoney.arguments[1]) n = entryFormatMoney.arguments[1];
      var n = new Number(n);
      var r = n.toLocaleString();

      if (getDecimalSeparator() == ".") {
        r = r.replace(/\./g, "#");
        r = r.replace(/,/g, ".");
        r = r.replace(/#/g, ",");
      }

      myreg = /([0-9\.]*)(,?[0-9]{0,4})/;
      if (myreg.test(r)) {
        r = RegExp.$1 + RegExp.$2;
      }

      obj.value =
        r + (lastkoma || lastnol ? "," : "") + (tailnol ? adanol : "");
    },
    // fungsi untuk mengembalikan nilai 122.311.312 tanpa tanda titik sebelum submit form.
    noFormattedNumber: function (element) {
      if (Array.isArray(element)) {
        $.each(element, function (index, value) {
          this.noFormattedNumber(value);
        });
      }

      var val;
      function restoreMoneyValueFloatFromStr(str) {
        // fungsi ini utk mengembalikan string dari format money standar ke nilai float
        // nilai float dengan saparator decimal titik biar php/javascript bisa parsing
        var rr = new String(str);
        var r = rr.replace(/ /g, "");
        r = r.replace(/\./g, "");
        r = r.replace(/,/, "#");
        r = r.replace(/,/g, "");
        r = r.replace(/#/, ".");
        return r;
      }
      val = restoreMoneyValueFloatFromStr($(element).val());
      $(element).val(val);
    },
    // initRoleForm: function () {},

    initClickPemberitahuan: function () {
      $(document).ready(function () {
        // Menangani klik pada dropdown dengan ID 'notifDropdown'
        $("#notifDropdown").on("click", function () {
          // Panggil fungsi initNotifForm saat dropdown diklik
          App.initNotifForm();
        });
      });
    },
    initNotifForm: function () {
      $.ajax({
        url: App.baseUrl + "pengumuman/get_pengumuman",
        type: "GET",
        dataType: "json",
        success: function (data) {
          console.log(data);
          var pengumumanList = $("#pengumuman");
          pengumumanList.empty();

          var readNotifications = [];
          function truncateText(text, maxWords) {
            if (!text) {
              return ""; // Handle null or undefined text
            }

            // Split the text into words
            var words = text.split(" ");

            // Truncate to the specified number of words
            var truncatedText = words.slice(0, maxWords).join(" ");

            // Add an ellipsis (...) if the text was truncated
            if (words.length > maxWords) {
              truncatedText += "...";
            }

            return truncatedText;
          }
          for (var i = 0; i < data.length; i++) {
            var id = data[i].id;
            var action_text = data[i].action_text;
            var ref_table = data[i].ref_table;
            var description = data[i].description;
            var date = data[i].created_at;
            var ref_function = data[i].ref_function;
            var ref_id = data[i].ref_id;
            // console.log(ref_table);
            // console.log(ref_function);
            // console.log(ref_id);

            var isRead = data[i].is_read === "1";

            var truncatedDescription = truncateText(description, 50);
            var listItem =
              '<li class="nav-item notification-item">' +
              '<a class="nav-link read" href="' +
              App.baseUrl +
              ref_table +
              "/" +
              ref_function +
              "/" +
              ref_id +
              '">' +
              '<div style="display: flex; flex-direction: column; align-items: start;">' +
              "<strong style='color: #519893;'>" +
              action_text +
              "</strong>" +
              "<p style='color:#000000;'>" +
              truncatedDescription +
              "</p>" +
              "<span style='color:#000000; font-size:12px;'>" +
              date +
              "</span>" +
              "</div>" +
              "</a>" +
              '<input type="hidden" name="id" value="' +
              id +
              '">' +
              "</li>" +
              "<hr>";

            var $listItem = $(listItem); // Convert the HTML string to a jQuery object
            var $dot = $listItem.find(".dot"); // Find the dot element within the list item

            // Add a click event listener to the list item
            $listItem.click(function () {
              // Toggle the dot's background color between green and red
              if (!isRead) {
                $dot.css("background-color", "#ff0000"); // Red
                readNotifications.push(id); // Mark as read
                markAsRead(id); // Call the function to mark as read
              }
            });

            pengumumanList.append($listItem); // Append the list item to the target element
          }

          // Function to mark a notification as read
          function markAsRead(id) {
            $.ajax({
              url: App.baseUrl + "pengumuman/read_pengumuman",
              type: "POST",
              dataType: "json",
              data: { id_read: id },
              success: function (response) {
                console.log(response);
              },
              error: function (response) {
                console.log("Error");
              },
            });
          }
        },
      });
    },
    setTemporaryLocalStorage: function (key, value, expired = 10) {
      value.timestamp = Date.now();
      localStorage.setItem(key, JSON.stringify(value));
    },

    getTemporaryLocalStorage: function (key, expired = 10) {
      const storedData = localStorage.getItem(key);
      const data = JSON.parse(storedData);

      // If no data is found, return null
      if (!storedData) {
        return null;
      }

      // Parse the stored data
      const timestamp = data.timestamp;

      // Check if the data has expired
      const expirationTime = expired * 60 * 1000; // Convert minutes to milliseconds
      if (Date.now() - timestamp > expirationTime) {
        // Data has expired, remove it from local storage
        localStorage.removeItem(key);
        return null;
      }

      // Data is still valid, return the value
      return data;
    },
  };
});
