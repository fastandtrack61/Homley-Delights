<style>

    /* Style for the rating stars */
.rating {
    cursor: pointer;
}

.star {
    color: #ccc; /* Set the color for empty stars */
}

.filled {
    color: #45a049; /* Set the color for filled stars */
}

</style>
<div class="rating">
    <?php
    // Assuming this is the rating value retrieved from your database or somewhere else

    for ($i = 1; $i <= 5; $i++) {
        
        
            echo '<span class="star" data-value="' . $i . '">★</span>';
        
    }
    ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var stars = document.querySelectorAll(".star");
    
    stars.forEach(function(star) {
        star.addEventListener("click", function() {
            var ratingValue = this.getAttribute("data-value");
            alert('You rated ' + ratingValue + ' stars.');
            
            // Change color scheme for stars up to the clicked star
            for (var i = 0; i < stars.length; i++) {
                if (i < ratingValue) {
                    stars[i].classList.add("filled");
                } else {
                    stars[i].classList.remove("filled");
                }
            }
        });
    });
});
</script>
