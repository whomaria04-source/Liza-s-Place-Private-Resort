// ========================================
// NAVIGATION & HAMBURGER MENU
// ========================================

const hamburger = document.querySelector('.hamburger');
const navbarMenu = document.querySelector('.navbar-menu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
}

// Close menu when clicking on a link
document.querySelectorAll('.navbar-menu a').forEach(link => {
    link.addEventListener('click', () => {
        navbarMenu.classList.remove('active');
        hamburger?.classList.remove('active');
    });
});

// ========================================
// FORM VALIDATION
// ========================================

function validateBookingForm() {
    const checkIn = document.querySelector('input[name="check_in"]');
    const checkOut = document.querySelector('input[name="check_out"]');
    const guests = document.querySelector('select[name="number_of_guests"]');

    if (!checkIn || !checkOut || !guests) return true;

    const checkInDate = new Date(checkIn.value);
    const checkOutDate = new Date(checkOut.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (checkInDate < today) {
        alert('Check-in date cannot be in the past.');
        return false;
    }

    if (checkOutDate <= checkInDate) {
        alert('Check-out date must be after check-in date.');
        return false;
    }

    return true;
}

// Form submission validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {
        // Basic validation
        const inputs = this.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });
});

// ========================================
// DATE INPUT VALIDATION
// ========================================

const checkInInput = document.querySelector('input[name="check_in"]');
const checkOutInput = document.querySelector('input[name="check_out"]');

if (checkInInput && checkOutInput) {
    checkInInput.addEventListener('change', () => {
        const checkInDate = new Date(checkInInput.value);
        const minCheckOutDate = new Date(checkInDate);
        minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);

        checkOutInput.min = minCheckOutDate.toISOString().split('T')[0];

        if (new Date(checkOutInput.value) <= checkInDate) {
            checkOutInput.value = minCheckOutDate.toISOString().split('T')[0];
        }
    });
}

// ========================================
// PRICE PREVIEW UPDATE
// ========================================

function updatePricePreview() {
    const checkIn = document.querySelector('input[name="check_in"]');
    const checkOut = document.querySelector('input[name="check_out"]');
    const pricePerNight = parseFloat(document.querySelector('.room-price')?.innerText.replace('$', '') || 0);

    if (checkIn && checkOut && checkIn.value && checkOut.value) {
        const checkInDate = new Date(checkIn.value);
        const checkOutDate = new Date(checkOut.value);
        const nights = (checkOutDate - checkInDate) / (1000 * 60 * 60 * 24);

        if (nights > 0) {
            const subtotal = pricePerNight * nights;
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            // Update price summary if it exists
            const priceSummary = document.querySelector('.price-summary');
            if (priceSummary) {
                priceSummary.innerHTML = `
                    <div class="price-row">
                        <span>${nights} nights × $${pricePerNight.toFixed(2)}</span>
                        <span>$${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="price-row">
                        <span>Taxes & Fees (10%)</span>
                        <span>$${tax.toFixed(2)}</span>
                    </div>
                    <div class="price-row total">
                        <span>Total:</span>
                        <span>$${total.toFixed(2)}</span>
                    </div>
                `;
            }
        }
    }
}

if (checkInInput) checkInInput.addEventListener('change', updatePricePreview);
if (checkOutInput) checkOutInput.addEventListener('change', updatePricePreview);

// ========================================
// GALLERY LIGHTBOX
// ========================================

const galleryItems = document.querySelectorAll('.gallery-item');
if (galleryItems.length > 0) {
    galleryItems.forEach(item => {
        item.addEventListener('click', function () {
            const img = this.querySelector('.gallery-image');
            const src = img.src;
            const alt = img.alt;

            // Create lightbox modal
            const lightbox = document.createElement('div');
            lightbox.className = 'lightbox';
            lightbox.innerHTML = `
                <div class="lightbox-content">
                    <img src="${src}" alt="${alt}">
                    <button class="lightbox-close">&times;</button>
                </div>
            `;

            document.body.appendChild(lightbox);

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
                    lightbox.remove();
                }
            });
        });
    });
}

// ========================================
// SMOOTH SCROLLING
// ========================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// ========================================
// ALERT DISMISSAL
// ========================================

document.querySelectorAll('.alert').forEach(alert => {
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alert.classList.contains('alert-success')) {
            alert.style.animation = 'fadeOut 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
});

// ========================================
// TOOLTIP FUNCTIONALITY
// ========================================

document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', function () {
        const tooltipText = this.getAttribute('data-tooltip');
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.innerText = tooltipText;
        document.body.appendChild(tooltip);

        const rect = this.getBoundingClientRect();
        tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
        tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
    });

    element.addEventListener('mouseleave', () => {
        document.querySelector('.tooltip')?.remove();
    });
});

// ========================================
// LAZY LOADING IMAGES
// ========================================

if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// ========================================
// ANIMATIONS ON SCROLL
// ========================================

const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animated');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.room-card, .review-card, .gallery-item').forEach(element => {
    observer.observe(element);
});

// ========================================
// KEYBOARD SHORTCUTS
// ========================================

document.addEventListener('keydown', (e) => {
    // Escape key to close modals
    if (e.key === 'Escape') {
        document.querySelector('.lightbox')?.remove();
    }
});

// ========================================
// CONSOLE LOG FOR DEBUGGING
// ========================================

console.log('Resort Booking System - Script Loaded Successfully');