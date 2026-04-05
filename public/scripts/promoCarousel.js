// Wait for DOM to be fully loaded
        window.addEventListener('load', function() {
            // Initialize Flickity
            const carouselElement = document.querySelector('#promoCarousel');
            const indicatorsContainer = document.querySelector('#carouselIndicators');
            
            if (!carouselElement) {
                console.error('Carousel element not found');
                return;
            }

            // Initialize Flickity with options
            const flkty = new Flickity(carouselElement, {
                cellAlign: 'left',
                contain: true,
                wrapAround: true,
                autoPlay: 3000,
                pageDots: false,
                prevNextButtons: false,
                draggable: true,
                freeScroll: false
            });

            // Get all indicator dots
            const indicators = document.querySelectorAll('.indicator-dot');
            
            console.log('Flickity initialized', flkty);
            console.log('Indicators found:', indicators.length);

            // Function to update indicators
            function updateIndicators(index) {
                console.log('Updating indicator to index:', index);
                indicators.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Set initial state
            updateIndicators(0);

            // Listen to select event (fires when slide is selected)
            flkty.on('select', function(index) {
                console.log('Select event fired, index:', index);
                updateIndicators(index);
            });

            // Add click event to indicators
            indicators.forEach((dot, index) => {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Indicator clicked, index:', index);
                    flkty.select(index);
                });
            });
        });