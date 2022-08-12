(function ($) {
  "use strict";

  // Multistepper form
  $(document).ready(function () {
    if ($("#custom-user-tool__search--form").length > 0) {
      var datePickerIdfrom = document.getElementById(
        "custom-user-tool__search--dobfrom"
      );
      datePickerIdfrom.max = new Date().toISOString().split("T")[0];
      var datePickerIdto = document.getElementById(
        "custom-user-tool__search--dobto"
      );
      datePickerIdto.max = new Date().toISOString().split("T")[0];
    }
    if ($("#contact").length > 0) {
      var datePickerId = document.getElementById("date_of_birth");
      datePickerId.max = new Date().toISOString().split("T")[0];
    }

    var form = $("#contact");

    form.on("focusout", "#userName", function () {
      var userName = $("#userName").val();

      var data = {
        action: "custom_username_data_verification",
        userName,
        nonce: Custom_User_params.nonce,
      };
      $.ajax({
        url: Custom_User_params.ajaxurl,
        type: "POST",
        data: data,
        success: function (response) {
          if (response != " ") {
            $("#userName-error__message").text(response);
          }
        },
      });
    });

    form.on("focusout", "#email", function () {
      var email = $("#email").val();

      var data = {
        action: "custom_email_data_verification",
        email,
        nonce: Custom_User_params.nonce,
      };
      $.ajax({
        url: Custom_User_params.ajaxurl,
        type: "POST",
        data: data,
        success: function (response) {
          if (response != " ") {
            $("#email-error__message").text(response);
          }
        },
      });
    });

    form.validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error);
      },
      rules: {
        password: {
          required: true,
          minlength: 5,
        },
        confirm_password: {
          required: true,
          minlength: 5,
          equalTo: "#password",
        },
        profile_photo: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
      },
    });
    form.children("div").steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      onStepChanging: function (event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
      },
      onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
      },
      onFinished: function (event, currentIndex) {
        event.preventDefault();
        var fd = new FormData(form[0]);
        fd.append("action", "custom_user_insertion_form");
        fd.append("nonce", Custom_User_params.nonce);
        fd.append("userAvatar", $("#profile_photo")[0].files[0]);
        var inputs = $("#contact :input");
        inputs.each(function () {
          fd.append(this.name, $(this).val());
        });
        $.ajax({
          url: Custom_User_params.ajaxurl,
          type: "POST",
          data: fd,
          contentType: false,
          processData: false,
          success: function (response) {
            var json = $.parseJSON(response);
            console.log(json);
            if (json.Success == 1) {
              $("#error-message").text(
                "Form Submitted Sucessfully , Admin will verify your account soon..."
              );
              $("#error-message").css("color", "green");
              alert("Form Submitted Successfully");
              window.setTimeout(function () {
                form[0].reset();
                window.location.replace(document.location.origin);
              }, 2000);
            } else if (json.Success == 0) {
              $("#error-message").text("User already registere!");
              $("#error-message").css("color", "orange");
              alert("User already Registered");
              window.setTimeout(function () {
                form[0].reset();
                window.location.replace(document.location.origin);
              }, 2000);
            } else {
              $("#error-message").text("Something is wrong from our side!!");
              $("#error-message").css("color", "red");
            }
          },
        });
      },
    });
  });

  $(document).ready(function () {
    // Multiselect dropdown initilization
    $("#custom_user_skill").select2();
    $("#custom_user_cat").select2();

    // Pagination
    $(".custom-pagination .page-numbers:first-child").addClass("current");
  });

  // Search Tool Ajax Call
  $(document).on("click", "#custom-user-tool__search--submit", function (e) {
    var keyWord = $("#custom-user-tool__search--keyword").val();
    var dobfrom = $("#custom-user-tool__search--dobfrom").val();
    var dobto = $("#custom-user-tool__search--dobto").val();
    var skills = $("#custom-user-tool__search--skill").val();
    var category = $("#custom_user_cat_public").val();
    var ratings = $("#custom-user-tool__search--ratings").val();

    var data = {
      action: "custom_search_listing_data",
      keyWord,
      skills,
      category,
      ratings,
      dobfrom,
      dobto,
      nonce: Custom_User_params.nonce,
    };

    $.ajax({
      url: Custom_User_params.ajaxurl,
      data: data,
      type: "POST",
      beforeSend: function () {
        $("#custom-user-registration-form-container").addClass("loading");
      },
      success: function (response) {
        $("#custom-user-registration-form-container").removeClass("loading");
        $(".custom-user-tool__list").replaceWith(response);
      },
      error: function () {
        alert("Opps! Something went wrong Please try again");
      },
    });
  });

  // Displays Ratings on the search tool
  $(document).on("change", "#custom-user-tool__search--ratings", function (e) {
    $("#custom-user-tool__search--ratingsvalue").text($(this).val());
  });

  //Toggle for show and Hide Password
  $(document).on("click", "#password_show", function () {
    if ($(this).prop("checked") == true) {
      $("#password").attr("type", "text");
    } else if ($(this).prop("checked") == false) {
      $("#password").attr("type", "password");
    }
  });

  //To avoid the special character insertion
  $(document).on("keypress", ".user_input", function (e) {
    var regex = new RegExp("^[a-zA-Z0-9_ s,\r\n]+$");
    var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!regex.test(key)) {
      e.preventDefault();
      return false;
    }
  });

  // Preview Image of profile photo
  $(document).on("change", "#profile_photo", function () {
    const [file] = this.files;
    if (file) {
      $("#profile_photo_preview").css("display", "block");
      $("#profile_photo_preview").attr("src", URL.createObjectURL(file));
    }
  });

  // Pagination
  $(document).on("click", ".custom-pagination .page-numbers", function () {
    $(".custom-pagination .page-numbers").removeClass("current");
    $(this).addClass("current");
    var pageNo = parseInt($(this).attr("page-no"));
    ajax_call(pageNo);
  });

  // Paginaiton call back funciton
  function ajax_call(pageNo) {
    var keyWord = $("#custom-user-tool__search--keyword").val();
    var dobfrom = $("#custom-user-tool__search--dobfrom").val();
    var dobto = $("#custom-user-tool__search--dobto").val();
    var skills = $("#custom-user-tool__search--skill").val();
    var category = $("#custom_user_cat_public").val();
    var ratings = $("#custom-user-tool__search--ratings").val();

    var data = {
      action: "custom_search_listing_data",
      keyWord,
      skills,
      category,
      ratings,
      dobfrom,
      dobto,
      page_no: pageNo,
      nonce: Custom_User_params.nonce,
    };

    $.ajax({
      url: Custom_User_params.ajaxurl,
      data: data,
      success: function (response) {
        $(".custom-user-tool__list").replaceWith(response);
        var currentPage = $(".custom-user-tool__list").attr("current_page");
        log;
        $(".custom-pagination")
          .find(".page-number" + currentPage)
          .addClass("current");
      },
    });
  }
})(jQuery);
