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

	// Register form
	$("#register-form").submit(function() {
		$("i.fa-times-circle").remove();
		console.log("Register submitted");
		$.post("scripts/check-user.php",
		{
			regUser: $("#regUser").val(),
			regPassword: $("#regPassword").val()
		},
		function(data,status){
			console.log("Register returned "+data);
			if(data.indexOf("Register successful") > -1) {
	        	goTo("index.php");
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
