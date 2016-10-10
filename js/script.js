var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {
	$scope.products = [];

	$scope.name = "";
	$scope.weight;
	$scope.calories;
	$scope.proteins;
	$scope.fats;
	$scope.carbohydrates;

	$scope.list = [];	// summary table list

	$scope.summaryObj = {};
	$scope.order = "";
	$scope.reverse = false;

	$scope.loadProducts = function() {
		$http({
			method: 'GET',
			url: 	'serverside.php',
		}).then(function mySucces(response) {
			$scope.products = response.data.products;
		}, function myError(response) {
			console.log(response.statusText);
		});
	}

	$scope.saveProduct = function() {
		var product = {
			"name"			: $scope.name,
			"calories"		: $scope.calories,
			"proteins"		: $scope.proteins,
			"fats"			: $scope.fats,
			"carbohydrates"	: $scope.carbohydrates
		}

		var jsonProduct = JSON.stringify(product);

		$http({
			method: 'GET',
			url: 	'saveproduct.php?q=' + jsonProduct
		}).then(function mySucces(response) {
			response.data ?	$scope.products.push(product) : null
		}, function myError(response) {
			console.log(response.data);
		});
	}

	$scope.updateProduct = function(event, obj) {

		var name = obj.x.name
		var column = event.target.attributes.getNamedItem("data-column").value;
		var value = event.target.innerText;

		var product = {
			"name" 	 : name,
			"column" : column,
			"value"  : value
		}

		var jsonProduct = JSON.stringify(product);

		$http({
			method: 'GET',
			url: 	'updateproduct.php?q=' + jsonProduct
		}).then(function mySucces(response) {
			console.log(response.data);
		}, function myError(response) {
			console.log(response.data);
		});
	}

	$scope.calculate = function(obj, event) {

		var prod = obj.x;
		prod.weight = event.target.value;

		var containObj = isContain(prod, $scope.list);

		if (prod.weight) {
			//add product to array if list not contain it
			if (!containObj.is) {
				$scope.list.push( $scope.calculateProduct(prod) );
			}
		} else {
			// remove product from array if list contain it
			if (containObj.is) {
				$scope.list.splice(containObj.index, 1);
			}
		}

		// check that array contains an element or not
		function isContain(product, list) {

			var obj = {
				"is": false,
				"index": 0
			};

			list.forEach(function (curVal, index) {
				if (product.name === curVal.name) {
					obj.is = true;
					obj.index = index;
				}
			});
			return obj;
		}

		$scope.calculateSummary($scope.list);
	}

	$scope.calculateProduct = function(prod) {

		var obj = {}

		obj.name 	= prod.name;
		obj.weight 			= prod.weight;
		obj.calories 		= prod.calories * prod.weight / 100;
		obj.proteins 		= prod.proteins * prod.weight / 100;
		obj.fats 			= prod.fats * prod.weight / 100;
		obj.carbohydrates 	= prod.carbohydrates * prod.weight / 100;
		

		return obj;
	}

	// fill up summary table
	$scope.calculateSummary = function(list) {

		var obj = {
			"totalWeight"		: 0, 
			"totalCalories"		: 0,
			"totalProteins"		: 0,
			"totalFats"			: 0,
			"totalCarbohydrates": 0
		};

		for(var prod of list) {
			obj.totalWeight 		+= parseFloat(prod.weight);
			obj.totalCalories 		+= parseFloat(prod.calories);
			obj.totalProteins 		+= parseFloat(prod.proteins);
			obj.totalFats 			+= parseFloat(prod.fats);
			obj.totalCarbohydrates 	+= parseFloat(prod.carbohydrates);
		}

		$scope.summaryObj = obj;
	}

	// define to show or hide summary table
	$scope.trig = function() {
		return Boolean($scope.list.length);
	}

	// sorting summary table
	$scope.sortBy = function(order) {
		$scope.order = order;
		$scope.reverse = !$scope.reverse;
	}

	// clear summary table and products input fields
	$scope.clearTable = function() {
		$scope.list = [];
		var inputs = document.getElementById("list-products").getElementsByTagName("input");
		for(var input of inputs) {
			(input.value !== "") ? input.value = "" : null
		}
	}
});