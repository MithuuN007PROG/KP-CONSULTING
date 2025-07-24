// Mobile navigation toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');
hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    hamburger.innerHTML = navLinks.classList.contains('active') ?
        '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
});
// Close mobile menu when clicking a link
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        hamburger.innerHTML = '<i class="fas fa-bars"></i>';
    });
});
// Header scroll effect
window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    header.classList.toggle('scrolled', window.scrollY > 50);
});
// Bike search functionality
const bikeSearch = document.getElementById('bikeSearch');
const headerSearch = document.getElementById('headerSearch');
const bikeCards = document.querySelectorAll('.bike-card');
const sortSelect = document.getElementById('sort');
const clearBtn = document.querySelector('.clear-btn');

function filterBikes(searchTerm) {
    bikeCards.forEach(card => {
        const bikeName = card.querySelector('h3').textContent.toLowerCase();
        const bikeSpecs = card.querySelector('p').textContent.toLowerCase();
        const bikePrice = card.querySelector('.bike-price').textContent.toLowerCase();

        if (bikeName.includes(searchTerm) ||
            bikeSpecs.includes(searchTerm) ||
            bikePrice.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
// Main search functionality
if (bikeSearch) {
    bikeSearch.addEventListener('input', function() {
        filterBikes(this.value.toLowerCase());
    });
}
// Header search functionality
if (headerSearch) {
    headerSearch.addEventListener('input', function() {
        filterBikes(this.value.toLowerCase());
        // Scroll to featured section if not already there
        if (window.location.hash !== '#featured') {
            window.location.hash = '#featured';

        }
    });
}
// Clear search functionality
if (clearBtn) {
    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (bikeSearch) bikeSearch.value = '';
        if (headerSearch) headerSearch.value = '';
        bikeCards.forEach(card => {
            card.style.display = 'block';
        });
    });
}
// Sort functionality
if (sortSelect) {
    sortSelect.addEventListener('change', function() {
        const bikesContainer = document.querySelector('.bikes-grid');
        const sortedCards = Array.from(bikeCards).sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.bike-price').textContent.replace(/[^\d.]/g, ''));
            const priceB = parseFloat(b.querySelector('.bike-price').textContent.replace(/[^\d.]/g, ''));
            const nameA = a.querySelector('h3').textContent.toLowerCase();
            const nameB = b.querySelector('h3').textContent.toLowerCase();

            switch (this.value) {
                case 'price_asc':
                    return priceA - priceB;
                case 'price_desc':
                    return priceB - priceA;
                case 'name_asc':
                    return nameA.localeCompare(nameB);
                case 'name_desc':
                    return nameB.localeCompare(nameA);
                default:
                    return 0;
            }
        });

        // Re-append sorted cards
        sortedCards.forEach(card => bikesContainer.appendChild(card));
    });
}
// Form submission handling
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        if (!name || !email || !subject || !message) {
            alert('Please fill in all required fields.');
            return;
        }
        // Submit the form
        fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    alert('Thank you! Your message has been sent.');
                    contactForm.reset();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                alert('There was a problem sending your message. Please try again later.');
                console.error('Error:', error);
            });
    });
}
// Animate elements when they come into view
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.service-card, .bike-card, .feature-item, .testimonial-card');
    elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;
        if (elementPosition < screenPosition) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
};
// Initialize animation styles
document.querySelectorAll('.service-card, .bike-card, .feature-item, .testimonial-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
});
window.addEventListener('load', animateOnScroll);
window.addEventListener('scroll', animateOnScroll);
// Remove all duplicate feedback slider code blocks
// Replace them with this single, corrected version:

document.addEventListener('DOMContentLoaded', function() {
    // Initialize feedback slider
    const feedbackSlider = document.querySelector('.feedback-slider');
    const sliderDots = document.querySelector('.slider-dots');
    const dots = document.querySelectorAll('.dot');
    const feedbackItems = document.querySelectorAll('.feedback-item');

    if (!feedbackSlider || !sliderDots || !dots.length || !feedbackItems.length) {
        console.log('Feedback slider elements not found');
        return;
    }

    let currentSlide = 0;
    const totalSlides = feedbackItems.length;

    // Set initial active dot
    dots[0].classList.add('active');

    // Function to move to specific slide
    function goToSlide(index) {
        currentSlide = index;
        const slideWidth = feedbackSlider.offsetWidth;
        feedbackSlider.style.transform = `translateX(-${index * slideWidth}px)`;

        // Update active dot
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    // Add click event to dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    // Auto slide functionality
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        goToSlide(currentSlide);
    }

    // Start auto sliding
    const slideInterval = setInterval(nextSlide, 5000);

    // Pause auto sliding when user interacts with slider
    feedbackSlider.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });

    feedbackSlider.addEventListener('mouseleave', () => {
        slideInterval = setInterval(nextSlide, 5000);
    });

    // Add active class to start animations
    setTimeout(() => {
        feedbackSlider.classList.add('active');
        sliderDots.classList.add('active');
    }, 500);
});