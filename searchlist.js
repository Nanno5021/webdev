function search() {
    // Get the value from the input box
    var input = document.getElementById("input-box").value.toLowerCase().trim('');

    // Define mappings from keywords to page URLs
    var pageMappings = {
        'adopt': 'adopt.php',
        'gallery': 'gallery.php',
        'surrender': 'surrender.php',
        'shop': 'gallery.html',
        'report': 'gallery.html',
        'donation': 'gallery.html',
        'volunter': 'volunteer.php',
        'event': 'event.php',
        'news letter': 'gallery.html',
        'blog': 'viewblog.php',
        'education': 'edupage.php',
        'faq': 'faq.html',
        'home':'index.php'
        // Add more mappings as needed
    };

    // Check if the input matches any keyword
    if (input in pageMappings) {
        // Navigate to the corresponding page
        window.location.href = pageMappings[input];
    } else {
        // Handle case when no match is found
        alert("No matching found for your search.");
    }
}

// Allow pressing Enter key to trigger search
document.getElementById("input-box").addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
        search();
    }
});