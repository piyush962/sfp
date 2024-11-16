
// siderbar dropdown
jQuery(document).ready(function () {
	jQuery('.dropdown_toggle').append(`<button class="drop-btn"><svg width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M1 1.5L6.5 6.5L12 1.5" stroke="white" stroke-width="2"/>
	</svg>
	</button>`);

	jQuery(document).on('click', '.dropdown_toggle', function () {
		jQuery(this).parent().children('.sub-menu').slideToggle();
	})

})


// Side toogle js
jQuery(document).on('click', '.sidebar_togger', function () {
	jQuery(this).parents('.page-wrapper').toggleClass('collapsed');
	if (jQuery(this).parents('.page-wrapper').hasClass('collapsed')) {
		jQuery(this).parents('.page-wrapper').removeClass('sidebar_open');
	} else {
		jQuery(this).parents('.page-wrapper').addClass('sidebar_open');
	}
});

jQuery(document).on('click', '.mobile-toggler-btn', function () {
	jQuery('.sidebar-left').toggleClass('open');
	if (jQuery('.sidebar-left').hasClass('open')) {
		console.log('true');
		jQuery('.sidebar-left').removeClass('closed');
	} else {
		console.log('false');
		jQuery('.sidebar-left').addClass('closed');
	}
})

jQuery(document).on('click', '.mob-close-sidebar', function () {
	jQuery('.mobile-toggler-btn').trigger('click');
})
// end

// Chart on Dashboard js
google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawChart);
var chartData = [];

$(document).ready(function(){
    $.ajax({
        headers: {
            'X-CSRF-Token': csrftoken,
        },
        method: 'POST',
        url: '/dashboard/dynamicchart',
        dataType: 'json',
        success: function(res){
			// console.log(res);
            $.each(res, function(index, element){
                chartData.push([element.category.name, element.productCount]);
            });
            
        },
        error: function(err){
            alert(err); 
        }
    });
});
console.log(chartData);
// var chartData = [
// 	['Chips Pouches', 70],
// 	['Flour Pouches', 50],
// 	['Tea Pouches', 20],
// 	['Pickle Pouches', 30],
// ];
function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Task', 'Hours per Day'],
		...chartData
	]);

	var options = {
		pieHole: 0.83,
		legend: 'none',
		colors: ["#3366cc","#dc3912","#ff9900","#109618","#990099","#0099c6","#dd4477","#66aa00","#b82e2e","#316395",
          "#994499","#22aa99","#aaaa11","#6633cc","#e67300","#8b0707","#651067","#329262","#5574a6","#3b3eac","#b77322",
          "#16d620","#b91383","#f4359e","#9c5935","#a9c413","#2a778d","#668d1c","#bea413","#0c5922","#743411"],

	};

	var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
	
	var colors = options.colors;
	console.log('data colors: ', colors);
	
	var total1 = [];
	var total = 0;
	for (var i = 0; i < data.getNumberOfRows(); i++) {
		total1.push(data.getValue(i, 1));
		total += data.getValue(i, 1);
	}
	// console.log('dikha: ', total1);
	for (var i = 0; i < data.getNumberOfRows(); i++) {
		var sliceValue = data.getValue(i, 1);
		let bgcolor = colors[i]
		var percentage = (sliceValue / total) * 100;
		// console.log("Slice: " + data.getValue(i, 0) + ", Value: " + sliceValue + ", Percentage: " + percentage.toFixed(2) + "%");
		let progressHTML = `<div class="progressItem"><h6>${data.getValue(i, 0) }</h6><div class="progress">
		<div class="progress-bar" role="progressbar" style="width: ${percentage.toFixed(2)}%;background-color: ${bgcolor};" aria-valuenow="25" aria-valuemin="0"
		aria-valuemax="100"></div>
		</div><p>${sliceValue}</p></div>`;
		jQuery('#progressBars').append(progressHTML);
	}
	
	chart.draw(data, options);

}	


// Notification see more
jQuery(document).ready(function () {
	jQuery('.notification_list > li').slice(0, 3).show();
	jQuery(document).on('click', '.toggle_more', function () {
		jQuery('.notification_list > li').show();
		jQuery(this).attr('value', 'See Less');
		if (jQuery('.notification_list').hasClass('showed')) {
			jQuery('.notification_list').removeClass('showed')
			jQuery('.notification_list > li').hide();
			jQuery('.notification_list > li').slice(0, 3).show();
			jQuery(this).attr('value', 'See more');
		}else{
			jQuery('.notification_list').addClass('showed')
		}
	})
});


// Dropdown js script
// Fix menu overflow under the responsive table
jQuery(document).on('click', '.table [data-bs-toggle="dropdown"]', function (event) {
	// $dropdown.hide();
	// Prevent the dropdown from closing when clicking on it
	event.stopPropagation();

	var $button = $(this);
	var $dropdown = $button.next('.dropdown-menu');

	var buttonOffset = $button.offset();
	var dropDownTop = buttonOffset.top;
	var dropDownLeft = buttonOffset.left - 20;

	// Set position
	console.log('top: ', dropDownTop);
	console.log('left: ', dropDownLeft);
	$dropdown.css({
		'top': dropDownTop + 'px',
		'left': dropDownLeft + 'px',
		'position': 'fixed',
		'width': $dropdown.width() + 'px'
	});

	$dropdown.addClass('tabledropdown');

	// Append dropdown to body if not already appended
	if (!$dropdown.parent().is('body')) {
		jQuery('body').append($dropdown);

	}
});

// Hide menu on click outside the dropdown
jQuery(document).on('click', function (event) {
	if (!$(event.target).closest('.dropdown').length) {
		jQuery('.dropdown-menu[data-bs-parent]').hide();
	}
});
