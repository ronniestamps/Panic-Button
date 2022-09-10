jQuery(document).ready(function () {
	setTimeout(function () {
		if (jQuery("#follow-form").length) {
			jQuery.magnificPopup.open({
				items: {
					src: "#follow-form"
				},
				type: "inline",
				closeOnBgClick: true,
				closeMarkup:
					'<button title="%title%" class="mfp-close" style="display:none">Close</button>'
			});
		}
	}, 1000);
	jQuery("#ack").click(function () {
		jQuery.magnificPopup.close();
	});
});
