/**
 * Created by Guzmle2 on 11/3/2017.
 */
$(window).load(function ()
	{
		//  var constant = "http://localhost/Ejercicio2/backend/";
		var constant = "http://guzmle2.cba.pl/backend/";
		var userId = 1;

		function getBalanceUser(id)
		{
			var parametros = {
				"function": 'getBalanceUser',
				"id": id
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					getCart();
					$("#balance").html(response);
				}


			});

		}

		function getAllProduct()
		{
			var parametros = {
				"function": 'getAllProduct'
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					response = $.parseJSON(response);
					$.each(response, function (key, value)
					{

						$('<tr class="elementAllProd">').append(
							$('<td>').append(
								$('<input  style="width: 50px; text-align: center;" min="1" type="number" id="cant_' +
									value.id +
									'" value="1">'),
								$('<input type="button" class="btnAddCar" id="' + value.id + '" value="Add To Cart">')),
							$('<td>').text(value.nombre),
							$('<td>').text(value.price + '$'),
							$('<td>').append(
								$('<p class="center" id="media_' + value.id + '" />'),
								$('<div id="vote_' + value.id + '" />').append(
									$('<label />', {
										'text': 1
									}).append(
										$('<input />', {
											type: 'radio',
											name: value.id,
											value: 1
										})
									),
									$('<label />', {
										'text': 2
									}).append(
										$('<input />', {
											type: 'radio',
											name: value.id,
											value: 2
										})
									),
									$('<label />', {
										'text': 3
									}).append(
										$('<input />', {
											type: 'radio',
											name: value.id,
											value: 3
										})
									),
									$('<label />', {
										'text': 4
									}).append(
										$('<input />', {
											type: 'radio',
											name: value.id,
											value: 4
										})
									),
									$('<label />', {
										'text': 5
									}).append(
										$('<input />', {
											type: 'radio',
											name: value.id,
											value: 5
										})
									),
									$('<input />', {
										type: 'button',
										id: value.id,
										value: 'vote',
										class: 'btnRanking'
									})
								)
							)
						).appendTo('#product');

						getDisableVot();

					});

				}

			});

		}

		function getMedia()
		{
			var parametros = {
				"function": 'getMedia'
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{

					response = $.parseJSON(response);

					$.each(response, function (key, value)
					{
						var idLabel = "#media_" + value.id;
						$(idLabel).empty();
						$(idLabel).html("avg " + value.media);
					});

					setTimeout(getBalanceUser(userId), 2000);

				}


			});
		}

		function getCart()
		{
			var parametros = {
				"function": 'getCarsCount',
				"idUser": userId
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					response = $.parseJSON(response);
					$("#itemCar").empty();

					if (response[0].result == null)
					{
						response[0].result = 0;
					}

					$("#itemCar").html(response[0].result);
					setTimeout(getMedia(), 2000);
				}
			});
		}

		$('#product').on('click', '.btnAddCar', function ()
		{
			var bla = $('#cant_' + $(this).attr('id')).val();
			$('#cant_' + $(this).attr('id')).val("1");
			if (bla && bla > 0)
			{

				var parametros = {
					"function": 'addProductCart',
					"idProduct": $(this).attr('id'),
					"idUser": userId,
					"qty": bla
				};
				$.ajax({
					data: parametros,
					url: constant + "test.php",
					type: 'post',
					success: function (response)
					{
						$("#addCars").html(response);
						getCart();
					}


				});
			}
		});

		$('#product').on('click', '.btnRanking', function ()
		{

			var name = $(this).attr('id');
			var parametros = {
				"function": 'addRanking',
				"idProduct": name,
				"idUser": userId,
				"ranking": $('input[name=' + name + ']:checked').val()
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					getMedia();
					getDisableVot();
					var idVote = "#vote_" + name;
					$(idVote).remove();
				}


			});
		});

		$('#elementCars').on('click', '.btnAddCar', function ()
		{
			var bla = $('#cant_' + $(this).attr('id')).val();
			$('#cant_' + $(this).attr('id')).val("1");
			if (bla && bla > 0)
			{

				var parametros = {
					"function": 'addProductCart',
					"idProduct": $(this).attr('id'),
					"idUser": userId,
					"qty": bla
				};
				$.ajax({
					data: parametros,
					url: constant + "test.php",
					type: 'post',
					success: function (response)
					{
						getElementCars();
					}


				});
			}
		});

		$('#elementCars').on('click', '.btnDelCar', function ()
		{
			var bla = $('#cantdel_' + $(this).attr('id')).val();
			$('#cantdel_' + $(this).attr('id')).val("1");
			if (bla && bla > 0)
			{

				var parametros = {
					"function": 'delProd',
					"idProduct": $(this).attr('id'),
					"idUser": userId,
					"qty": bla
				};
				$.ajax({
					data: parametros,
					url: constant + "test.php",
					type: 'post',
					success: function (response)
					{
						getElementCars();
					}


				});
			}
		});


		function getTotal()
		{
			var parametros = {
				"function": 'getTotalCart',
				"idUser": userId
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{

					response = $.parseJSON(response);
					$("#total").html(response.total);
					$("#totalI").html(response.total);

					loadPay();
				}


			});
		}

		$('select').on('change', function ()
		{
			if(this.value>= 0)
			{
				$("#transpo").html(this.value);
				loadPay();
			}
		});

		$('#pay').click(function (e)
		{
			var totalShop = ( parseFloat($('#transpo').text()) + parseFloat($('#totalI').text())).toFixed(2);
			var totalShosp = (parseFloat($('#balance').text()) -totalShop).toFixed(2);


			if (!$('#totalTotal').text())
			{
				alert("Not items ");
			} else if (parseFloat($('#balance').text()) < parseFloat($('#totalTotal').text()))
			{
				alert("Insufficient balance");
			} else if ($('select[name=transport]').val() < 0)
			{
				alert("Select transport method");
			} else
			{
				var parametros = {
					"function": 'payCar',
					"cart": totalShop,
					"idUser": userId
				};

				$.ajax({
					data: parametros,
					url: constant + "test.php",
					type: 'post',
					success: function (response)
					{
						if(response == "")
						{
							response = "Your payment for "
								+ (totalShop)
								+ " was successful, your new balance is "
								+ totalShosp;


							$(".quit").remove();
						}
						$("#result").html(response);
					}
				});
			}

		});

		function loadPay()
		{
			$("#totalTotal").html(parseFloat($('#transpo').text()) + parseFloat($('#totalI').text()));
		}

		function getElementCars()
		{

			var parametros = {
				"function": 'getCart',
				"idUser": userId
			};

			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{

					$(".element").remove("tr");
					response = $.parseJSON(response);
					$.each(response, function (key, value)
					{
						$('<tr class="element">').append(
							$('<td>').text(value.qty),
							$('<td>').text(value.nombre),
							$('<td>').text(value.price + '$'),
							$('<td>').text(value.totalPrice + '$'),
							$('<td>').append(
								$('<input  style="width: 50px; text-align: center;" min="1"  type="number" id="cant_' +
									value.idProducto +
									'" value="1">'),
								$('<input type="button" class="btnAddCar" id="' + value.idProducto + '" value="+">')),
							$('<td>').append(
								$('<input  style="width: 50px; text-align: center;" min="1" max="' + value.qty +
									'"  type="number"' +
									' id="cantdel_' +
									value.idProducto +
									'" value="1">'),
								$('<input type="button" class="btnDelCar" id="' + value.idProducto + '" value="-">'))
						).appendTo('#elementCars');
					});

					getTotal();
				}


			});
		}

		function getDisableVot()
		{
			var parametros = {
				"function": 'getElementVote'
			};

			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					$("#resultado").html(response);
					response = $.parseJSON(response);
					setTimeout(
						$.each(response, function (index, value)
						{
							var idVote = "#vote_" + value;
							$(idVote).hide();
						})
						, 3000);
				}
			});
		}


		$('#resetSession').on('click', function ()
		{

			var parametros = {
				"function": 'resetSession'
			};
			$.ajax({
				data: parametros,
				url: constant + "test.php",
				type: 'post',
				success: function (response)
				{
					setTimeout(location.reload(), 3000)
				}

			});
		});

		getAllProduct();
		getElementCars();
		getBalanceUser(userId);

	}
);