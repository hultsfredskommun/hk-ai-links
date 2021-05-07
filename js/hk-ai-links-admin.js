jQuery(function($) {
	$(document).ready(function () {



		/* fetch click cancel button */
    $("#hkail_cancel").click(function (ev) {
			ev.preventDefault();
      $(".hkail_add_new input[type=text]").each(function(){
        $(this).val('');
      })
      $("#hkail_add").show();
      $("#hkail_update, #hkail_cancel, #hkail_delete").hide();

    });

    /* fetch click add button */
		$("#hkail_add").click(function (ev) {
			ev.preventDefault();

      data = {  "name": $("#hkail_add_name").val(),
                "href": $("#hkail_add_href").val(),
                "groups": $("#hkail_add_groups").val(),
                "classes": $("#hkail_add_classes").val() };
			jsonData = JSON.stringify(data);

			$.ajax({
				type : "post",
				dataType : "text",
				url : hkail_admin_js_object.ajaxurl,
				data : {action: "hkail_add_new", data : jsonData},
				success: function(response, status) {
				 	if(status == "success") {
            jsonData = JSON.parse(response);
            if (jsonData.success) {
              $('#hkail_add_message').html(jsonData.result).removeClass('failed');
              $(".hkail_add_new input[type=text]").each(function(){
                $(this).val('');
              })
              refreshLinkList();
            }
            else {
              $('#hkail_add_message').html(jsonData.result).addClass('failed');
            }
						console.log(response);
				 	}
				 	else {
					 	console.log("status: " + status + " response: " + response);
				 	}
				},
				error: function(request, error) {

				},
				complete: function(data) {

				}
			});
		});

    /* fetch click update button */
		$("#hkail_update").click(function (ev) {
			ev.preventDefault();

      data = {  "id": $("#hkail_add_id").val(),
                "name": $("#hkail_add_name").val(),
                "href": $("#hkail_add_href").val(),
                "groups": $("#hkail_add_groups").val(),
                "classes": $("#hkail_add_classes").val() };
			jsonData = JSON.stringify(data);

			$.ajax({
				type : "post",
				dataType : "text",
				url : hkail_admin_js_object.ajaxurl,
				data : {action: "hkail_update", data : jsonData},
				success: function(response, status) {
				 	if(status == "success") {
            jsonData = JSON.parse(response);
            if (jsonData.success) {
              $('#hkail_add_message').html(jsonData.result).removeClass('failed');
              $(".hkail_add_new input[type=text]").each(function(){
                $(this).val('');
              })
              refreshLinkList();
              $("#hkail_add").show();
              $("#hkail_update, #hkail_cancel").hide();
            }
            else {
              $('#hkail_add_message').html(jsonData.result).addClass('failed');
            }
						console.log(response);
				 	}
				 	else {
					 	console.log("status: " + status + " response: " + response);
				 	}
				},
				error: function(request, error) {

				},
				complete: function(data) {

				}
			});
		});


    /* fetch click update button */
    $("#hkail_delete").click(function (ev) {
      ev.preventDefault();

      data = { "id": $("#hkail_add_id").val() };
      jsonData = JSON.stringify(data);

      $.ajax({
        type : "post",
        dataType : "text",
        url : hkail_admin_js_object.ajaxurl,
        data : {action: "hkail_delete", data : jsonData},
        success: function(response, status) {
          if(status == "success") {
            jsonData = JSON.parse(response);
            if (jsonData.success) {
              $('#hkail_add_message').html(jsonData.result).removeClass('failed');
              $(".hkail_add_new input[type=text]").each(function(){
                $(this).val('');
              })
              refreshLinkList();
              $("#hkail_add").show();
              $("#hkail_delete").hide();
            }
            else {
              $('#hkail_add_message').html(jsonData.result).addClass('failed');
            }
            console.log(response);
          }
          else {
            console.log("status: " + status + " response: " + response);
          }
        },
        error: function(request, error) {

        },
        complete: function(data) {

        }
      });
    });


    /* get link list */
    function refreshLinkList() {
      $(".hkail_link_list").html('');

  		$.ajax({
  			type : "post",
  			dataType : "text",
  			url : hkail_admin_js_object.ajaxurl,
  			data : {action: "hkail_get_link_list", data : ''},
  			success: function(response, status) {
  				if(status == "success") {
  					$(".hkail_link_list").html(response);

            /* fetch click on all edit links */
        		$(".hkail_edit").each(function(){
              $(this).click(function (ev) {
          			ev.preventDefault();
                link = $(this).parent().find(".hkail_link");
                $("#hkail_add_id").val(link.data('id'));
                $("#hkail_add_name").val(link.html());
                $("#hkail_add_href").val(link.attr('href'));
                $("#hkail_add_groups").val(link.data('groups'));
                $("#hkail_add_classes").val(link.data('classes'));
                $("#hkail_add").hide();
                $("#hkail_update, #hkail_cancel").show();
								$([document.documentElement, document.body]).animate({
										 scrollTop: $("#link_handling").offset().top
								 }, 500);
              })
            });
            /* fetch click on all delete links */
        		$(".hkail_delete").each(function(){
              $(this).click(function (ev) {
          			ev.preventDefault();
                link = $(this).parent().find(".hkail_link");
                $("#hkail_add_id").val(link.data('id'));
                $("#hkail_add_name").val(link.html());
                $("#hkail_add_href").val(link.attr('href'));
                $("#hkail_add_groups").val(link.data('groups'));
                $("#hkail_add_classes").val(link.data('classes'));
                $("#hkail_add").hide();
                $("#hkail_delete, #hkail_cancel").show();
								$([document.documentElement, document.body]).animate({
										 scrollTop: $("#link_handling").offset().top
								 }, 500);
              })
            });
  					//console.log(response);
  				}
  			},
  			error: function(request, error) {

  			},
  			complete: function(data) {

  			}
  		});
    }

    refreshLinkList();

	});
});
