	var current = 0;	//	the result that is currently highlighted
	var threshold = 1;	//	minimum length of the string
	var max = 5;	//	maximum results we're displaying
	var searchFor = "";	//	placeholder for what we typed in the search box

	function doSearch(e) {
		var q = $("#q").val();

		if (q.length >= threshold) {	//	make sure we're above the threshold
			whichKey(e, q);

			//	when we're pressing up and down going through the results, 0 means we're back at where we started and no results are highlighted so lets reset the value of the textbox back to what we actually typed in
			if (current == 0) {
				$("#q").val(searchFor);
			}

			//	perform the AJAX request to our PHP file to pull the results
			$.ajax({
				type: "POST",
				data: "q="+escape(searchFor),
				url : "includes/search.php",
				success : function (data) {
					//	did we get anything back?
					if (data != "") {
						$("#matches").show();	//	show the matches div
						$("#matches").html(data);	//	set the contents of the matches div to the response we got (unordered list)

						//	if we've pressed the up or down keys, highlight the proper div
						if (current != 0) {
							if (current == 1) {
								//	set the class for the first list item
								var highlight = "#matches ul li:first-child";
							} else {
								//	set the class for the [current] list item
								var highlight = "#matches ul li:first-child";
								for (i = 1; i < current; i++) {
									highlight += "+li";
								}
							}

							//	highlight the list item
							$(highlight).addClass("match-highlight");
							//	set the textbox value to the title attribute of the highlighted list item
							$("#q").val($(highlight).attr("title"));
						}
						//	when we click on a list item, highlight it and set the textbox value
						$("#matches ul li").click(function() {
							$("#q").val($(this).attr("title"));
						});
						//	when we hover over a list item, highlight it and remove the highlight when we mouse out
						$("#matches ul li").hover(function() {
							$(this).addClass("match-hover");
						}, function() {
							$(this).removeClass("match-hover");
						});
					} else {
						//	we didn't get anything back from our AJAX request so hide the matches div
						$("#matches").hide();
					}
				}
			});
		} else {
			//	string was too short
			searchFor = "";
			$("#matches").hide();
		}
	}

	//	function to determine keycodes for keys that are pressed
	function whichKey(event, q) {
		if (event.keyCode == 38) {	//	up
			if (current == 0) {
				current = max;
			} else {
				current = (current - 1);
			}
		} else if (event.keyCode == 40) {	//	down
			if (current == max) {
				current = 0;
			} else {
				current = (current + 1);
			}
		} else if (event.keyCode == 8) {	//	backspace
			current = 0;
			searchFor = $("#q").val();
		} else if (event.keyCode == 46) {	//	delete
			current = 0;
			searchFor = $("#q").val();
		} else {
			searchFor = q;
		}
	}

	//	bind the keyup and click actions respectively
	$(document).ready(function() {
		$("#q").keyup(doSearch);
		$(document).click(function() {
			$("#matches").hide();
		});
		$("#q").click(doSearch);
	});