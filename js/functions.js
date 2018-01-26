/* Created by Musa Fletcher         *
 * Last Edit 2/12/17                *
 * Licensed under Apache2 license   *
 *                                  */

 // On document fully loaded, run the following
$(document).ready(function(){

    // Append active to current nav link
	var page;
    if(document.location.pathname.match(/[^\/]+$/)) page = document.location.pathname.match(/[^\/]+$/)[0];
	else page='index.php';
	console.log(page);
    $("li[name='"+page+"']").addClass('active');

	// Login form
	$("#login-form").submit(function() {
		$("i.fa-times-circle").remove();
		console.log("Login submitted");
		$.post("scripts/check-user.php",
		{
			username: $("#username").val(),
			password: $("#password").val()
		},
		function(data,status){
			console.log("Login returned "+data);
			if(data.indexOf("Login successful") > -1) {
	        	goTo("index.php");
			}
			else {
				$("label[for='username']").append("<i class='fa fa-times-circle' style='color:red; margin-left: 5px;'></i>");
				$("label[for='password']").append("<i class='fa fa-times-circle' style='color:red; margin-left: 5px;'></i>");
			}
		});
	});

	//Remove item form 
	$("#remove-item").submit(function() {
		console.log("Removing item");
		$("#removeItemName").removeClass("is-valid");
		$("#removeItemName").removeClass("is-invalid");

		$.post("scripts/delete-item.php",
		{
			itemName: $("#removeItemName").val(),
		},
		function(data,status){
			console.log(data);
			if( data.indexOf("Item deleted") > -1) {
				$("#removeItemName").addClass("is-valid");
			}
			else {
				$("#removeItemName").addClass("is-invalid");
			}
		});
	});

	//Update item form
	$("#update-item").submit(function() {
		console.log("Updating item");
		$("#update-item > div").children().removeClass("is-valid");
		$("#update-item > div").children().removeClass("is-invalid");

		$.post("scripts/update-item.php",
		{
			itemName: $("#newItemName").val(),
			itemOption: $("#newItemOption").val(),
			priceIn : $("#newPriceIn").val(),
			priceOut: $("#newPriceOut").val()
		},
		function(data,status){
			console.log(data);
			if( data.indexOf("Item updated") > -1 ) {
				$("#update-item > div").children().addClass("is-valid");
			}
			else {
				$("#update-item > div").children().addClass("is-invalid");
			}
		});
	});

	//Add item form
	$("#add-item").submit(function() {
		console.log("Item submitted.");
		$("#add-item > div").children().removeClass("is-valid");
		$("#add-item > div").children().removeClass("is-invalid");

		$.post("scripts/add-item.php",
		{
			itemName: $("#itemName").val(),
			itemOption: $("#itemOption").val(),
			priceIn: $("#priceIn").val(),
			priceOut: $("#priceOut").val(),
			category: $("#category").val(),
		},
		function(data,status){
			console.log(data);
			if( data.indexOf("Item added") > -1 ) {
				$("#add-item > div").children().addClass("is-valid");
			}
			else {
				$("#add-item > div").children().addClass("is-invalid");
			}
		});
	});

	// Make admin form 
	$("#make-admin").submit(function() {
		$("#make-admin > div").children().removeClass("is-valid");
		$("#make-admin > div").children().removeClass("is-invalid");

		$.post("scripts/make-admin.php",
		{
			accountName: $("#new-admin").val(),
		},
		function(data,status) {
			console.log(data);

			if(data.indexOf("User account admin") > -1) {
				$("#make-admin > div").children().addClass("is-valid");
			}
			else {
				$("#make-admin > div").children().addClass("is-invalid");
			}
		});
	});

	// Add employee form
	$("#add-employee-form").submit(function() {
		console.log("Employee add submitted.");
		$("#add-employee-form > div").children().removeClass("is-valid");
		$("#add-employee-form > div").children().removeClass("is-invalid");

		$.post("scripts/add-employee.php",
		{
			regUser: $("#regUser").val(),
			regPassword: $("#regPassword").val()
		},
		function(data,status){
			console.log("Register returned "+data);
			if(data.indexOf("Employee added") > -1) {
				$("#add-employee-form > div").children().addClass("is-valid");
			}
			else {
				$("#add-employee-form > div").children().addClass("is-invalid");
			}
		});
	});

	// Register form
	$("#register-form").submit(function() {
		$("i.fa-times-circle").remove();
		console.log("Register submitted");

		//Check business
		if( $("#regBusiness").val() != "" &&  $("#regBusiness").val() != "undefined") {
			var business = $("#regBusiness").val();
		}
		else {
			var business = null;
		}
		$.post("scripts/check-user.php",
		{
			regUser: $("#regUser").val(),
			regPassword: $("#regPassword").val(),
			regBusiness: business
		},
		function(data,status){
			console.log("Register returned "+data);
			if(data.indexOf("Register successful") > -1) {
	        	refresh();
			}
			else {
				$("label[for='regUser']").append("<i class='fa fa-times-circle' style='color:red; margin-left: 5px;'></i>");
				$("label[for='regPassword']").append("<i class='fa fa-times-circle' style='color:red; margin-left: 5px;'></i>");
			}
		});
	});

	// Add item to order
	$(".add-to-order").submit(function(){
		var id = $(this).attr('id');
		var meat = $(this).find("select[name=meat]").val();
		var quantity = $(this).find("input[name=quantity]").val();
		var name = $(this).find("input[name=name]").val();

		// Log order
		console.log("Add "+id+" to order submitted" + "\n" +
			"Meat " + meat + "\n" +
			"Quantity " + quantity + "\n" +
			"Name " + name);

		// Post to script
		$.post("scripts/add-to-order.php",
		{
			meat: meat,
			quantity: quantity,
			name: name
		},
		function(data,status){
			console.log("Add to order post returned "+data);
			if(data.indexOf("Added successfully") > -1) refresh();
			else goTo(data);
		});
	});

	// Save customer info for order
	$("#customer-info-form").submit(function(){
		var customer = {};
		customer.name = $(this).find("input[name=customerName]").val();
		customer.phone = $(this).find("input[name=customerPhoneNumber]").val();
		customer.address = $(this).find("input[name=customerAddress]").val();
		customer.card = $(this).find("input[name=cardNumber]").val();
		customer.table = $(this).find("input[name=tableNumber]").val();
		customer.fee = $(this).find("input[name=delivCost]").val();
		customer.mode = $(this).find("select[name=mode]").val();
		customer.url = $(this).find("input[name=url]").val();
		for(let prop in customer) {
			if(customer[prop] == "undefined") {
				customer[prop] = "";
			}
		}
		console.log(customer);
		$.post("scripts/add-to-order.php",
		{
			customerName: customer.name,
			customerPhoneNumber: customer.phone,
			customerAddress: customer.address,
			cardNumber: customer.card,
			tableNumber: customer.table,
			delivCost: customer.fee,
			mode: customer.mode,
			url: customer.url
		},
		function(data,status){
			console.log(data);
			if(data.indexOf("Customer info saved") > -1) goTo('index.php');
			else printError(data);
		});
	});

    // If the remove button of any order item is clicked
    $("button[name='order-item']").click(function(){
        // Log item ID
        console.log("Item ID: "+this.id);
        // Post item ID to PHP to remove from session
        $.post('scripts/remove-item.php',
        {
            id: this.id
        },
        // Log data and status
        function(data,status){
            console.log("Data: "+data);
			// Reload page to display updated menu
			if(data == "Success") refresh();
        });
    });

	// Submit current order
	$("#current-order-form").submit(function(){
		console.log("Order submitted");
		$.post("scripts/submit-order.php",
		{
			price: $(this).find("input[name=price]").val()
		},
		function(data,status) {
			console.log("Order submit returned "+data);
			goTo(data);
		});
	})

    // When the date is changed in view-orders.php change the page
    $("select[name='date-control']").change(function(){
        console.log("Date changed!");
        var url = "view-orders.php?date="+this.value;
        goTo(url);
    });
});

// Redirect page
function goTo(url)
{
	window.location.assign(url);
};

// Print order frame
function printOrder()
{
	var orderFrame = window.frames['order'];
	orderFrame.focus();
	orderFrame.print();
};

// logout user
function logout()
{
	$.get("scripts/logout.php",
	function(data,status){
		console.log(data);
		goTo("login.php");
	});
};

// Print error to page
function printError(error)
{
	$("nav").after("<div class='alert alert-warning'><i class='fa fa-exclamation-circle'></i>&nbsp;" + error + "</div>");
};

// Refresh page
function refresh()
{
	location.reload();
};
