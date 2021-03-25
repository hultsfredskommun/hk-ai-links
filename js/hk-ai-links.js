jQuery(function($) {
	$(document).ready(function () {

		//alert(hkail_js_object.iframe_src);


		/* fetch clicks on all hkail_link links */
		$("a.hkail_link").each(function () {
			addClickEvent($(this));
		});


		/** get most clicked links */
		data = {"user": hkail_js_object["user"],
						"numLinks": $(".hkail_most_click_wrapper_v2").data('num-links'),
						"text": $(".hkail_most_click_wrapper_v2").text()};
		jsonData = JSON.stringify(data);

		$.ajax({
			type : "post",
			dataType : "text",
			url : hkail_js_object.ajaxurl,
			data : {action: "hkail_get_most_clicked", data : jsonData},
			success: function(response, status) {
				if(status == "success") {
					if (response != '') {
						$(".hkail_most_click_wrapper_v2").html(response);
						/* fetch clicks on all hkail_link links */
						$(".hkail_most_click_wrapper_v2").find("a.hkail_link").each(function () {
							addClickEvent($(this));
							if (hk_trigger_link_hook != undefined) {
								if ($(this).is('[href*="support.hultsfred.se"]')) {
									$(this).on('click', { href: $(this).attr('href') }, hk_trigger_link_hook);	
								}

							}
						});

						//console.log(response);
					}
				}
			},
			error: function(request, error) {

			},
			complete: function(data) {

			}
		});


	});


	function addClickEvent(el) {
		$(el).mousedown(function (ev) {
			//ev.preventDefault();
			data = {"user": hkail_js_object["user"], "id": $(this).data('id')};
			jsonData = JSON.stringify(data);

			$.ajax({
				type : "post",
				dataType : "text",
				url : hkail_js_object.ajaxurl,
				data : {action: "hkail_click", data : jsonData},
				success: function(response, status) {
					if(status == "success") {
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
	}
});
