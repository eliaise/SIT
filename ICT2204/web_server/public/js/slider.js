var slider = document.getElementById("priceRange");
var output = document.getElementById("rangeLimit");
output.innerHTML = slider.value; // Display the default slider value

// Update the current slider value (each time you drag the slider handle)
slider.oninput = function() {
    console.log("hello");
    output.innerHTML = this.value;
}


