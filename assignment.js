var processArray = function(){
	
	return function(list) {
		if(!Array.isArray(list)) {
			console.log('Input is not an Array. Please provide as Array to continue');
			return;
		}
		if(!list.length) {
			console.log('Array is empty. Exit processing');
			return;
		}
		var sortedList = list.sort(),
			length = sortedList.length,
			sum = sortedList.reduce(function(previousValue, currentValue){
				return previousValue + currentValue;
			}),
			mean = Math.round((sum/length)*100)/100,
			sd = Math.sqrt(sortedList.reduce(function(previousValue, currentValue) {
				return previousValue + Math.pow(currentValue-mean, 2);
			}, 0)/length),
			roundedSD = Math.round(sd * 100000)/100000;
		
		console.log('Sorted Array: ' + sortedList);
		console.log('Min Value: ' + sortedList[0]);
		console.log('Max Value: ' + sortedList[length-1]);
		console.log('Sum: ' + sum);
		console.log('Mean: ' + mean);
		console.log('Standard Deviation: ' + roundedSD);
	};
}();

console.log('\nTEST - Null Array');
processArray();
console.log('\nTEST - Non Array');
processArray("test");
console.log('\nTEST - Empty Array');
processArray([]);
console.log('\nTEST - Small Array');
processArray([2, 3, 1, 0]);
console.log('\nTEST - Big Array');
processArray([66, 3, 56, 7, 12, 43, 22, 14, 16, 8, 69, 33, 89, 19]);
console.log('\n');