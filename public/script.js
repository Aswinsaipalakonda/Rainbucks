// Mobile Navigation Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navMenu.classList.remove('active');
}));

// Dropdown functionality for mobile
const dropdownItems = document.querySelectorAll('.dropdown');
dropdownItems.forEach(item => {
    const link = item.querySelector('.nav-link');
    const dropdownMenu = item.querySelector('.dropdown-menu');
    
    link.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }
    });
});

// Hero Section Animations
function initHeroAnimations() {
    // Add entrance animations for hero elements
    const heroElements = document.querySelectorAll('.hero-icon, .hero-text h1, .hero-text .btn, .image-container');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    heroElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `opacity 0.6s ease ${index * 0.2}s, transform 0.6s ease ${index * 0.2}s`;
        observer.observe(element);
    });
}


    // Counter effect on scroll
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.counter');
        let started = false;

        function animateCounters() {
            if (started) return;
            const section = document.querySelector('.stats-section');
            const sectionTop = section.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (sectionTop < windowHeight - 100) {
                started = true;
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const suffix = counter.getAttribute('data-suffix') || '+';
                    let count = 0;
                    const duration = 4500;
                    const increment = Math.ceil(target / (duration / 16));

                    function updateCounter() {
                        count += increment;
                        if (count >= target) {
                            count = target;
                            if (suffix === '+') {
                                counter.textContent = target + '+';
                            } else {
                                counter.textContent = target + suffix;
                            }
                        } else {
                            if (suffix === '+') {
                                counter.textContent = count + '+';
                            } else {
                                counter.textContent = count + suffix;
                            }
                            requestAnimationFrame(updateCounter);
                        }
                    }
                    updateCounter();
                });
            }
        }

        window.addEventListener('scroll', animateCounters);
        // In case already in view on load
        animateCounters();
    });

// Statistics Section Animations
function initStatsAnimations() {
    const statItems = document.querySelectorAll('.stat-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 200);
            }
        });
    }, { threshold: 0.3 });
    
    statItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
}

// Transform Section Animations
function initTransformAnimations() {
    // Add entrance animations for transform section elements
    const transformElements = document.querySelectorAll('.transform-text h2, .transform-text p, .transform-text .btn, .transform-image-container');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    transformElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `opacity 0.6s ease ${index * 0.2}s, transform 0.6s ease ${index * 0.2}s`;
        observer.observe(element);
    });
}

// Smooth Scrolling for Navigation Links
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Navbar Background Change on Scroll
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            navbar.style.background = 'var(--text-light)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.background = 'var(--text-light)';
            navbar.style.boxShadow = 'none';
        }
    });
}

// Intersection Observer for Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Observe stats
    document.querySelectorAll('.stat').forEach(stat => {
        stat.style.opacity = '0';
        stat.style.transform = 'translateY(20px)';
        stat.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(stat);
    });
}

// Counter Animation for Stats
function animateCounters() {
    const stats = document.querySelectorAll('.stat h3');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalValue = target.textContent;
                
                // Extract number from text (e.g., "$2.5B+" -> 2.5)
                const numberMatch = finalValue.match(/[\d.]+/);
                if (numberMatch) {
                    const finalNumber = parseFloat(numberMatch[0]);
                    const suffix = finalValue.replace(/[\d.]+/, '');
                    
                    let currentNumber = 0;
                    const increment = finalNumber / 50; // 50 steps
                    
                    const counter = setInterval(() => {
                        currentNumber += increment;
                        if (currentNumber >= finalNumber) {
                            currentNumber = finalNumber;
                            clearInterval(counter);
                        }
                        
                        // Format number based on original format
                        if (finalValue.includes('B')) {
                            target.textContent = `$${currentNumber.toFixed(1)}B+`;
                        } else if (finalValue.includes('K')) {
                            target.textContent = `${Math.round(currentNumber)}K+`;
                        } else {
                            target.textContent = `${currentNumber.toFixed(1)}%`;
                        }
                    }, 50);
                }
                
                observer.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    stats.forEach(stat => observer.observe(stat));
}

// Button Click Effects
function initButtonEffects() {
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Add ripple effect CSS
function addRippleCSS() {
    const style = document.createElement('style');
    style.textContent = `
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// Featured Courses Auto-scroll and Indicators (restored)
function initFeaturedCourses() {
    const coursesScroll = document.querySelector('.courses-scroll');
    const indicators = document.querySelectorAll('.indicator');
    let currentIndicator = 0;

    if (coursesScroll) {
        const cards = coursesScroll.querySelectorAll('.course-card');
        // Avoid duplicating clones: only clone if not already extended
        if (cards.length > 0 && coursesScroll.children.length === cards.length) {
            cards.forEach(card => {
                const clone = card.cloneNode(true);
                coursesScroll.appendChild(clone);
            });
        }

        coursesScroll.addEventListener('scroll', () => {
            const scrollLeft = coursesScroll.scrollLeft;
            const firstCard = coursesScroll.querySelector('.course-card');
            if (!firstCard || indicators.length === 0) return;
            const cardWidth = firstCard.offsetWidth + 32;
            const newIndicator = Math.floor(scrollLeft / Math.max(cardWidth, 1)) % indicators.length;
            if (newIndicator !== currentIndicator) {
                indicators[currentIndicator]?.classList.remove('active');
                indicators[newIndicator]?.classList.add('active');
                currentIndicator = newIndicator;
            }
        });

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                const firstCard = coursesScroll.querySelector('.course-card');
                if (!firstCard) return;
                const cardWidth = firstCard.offsetWidth + 32;
                coursesScroll.scrollTo({ left: index * cardWidth, behavior: 'smooth' });
            });
        });
    }
}

// Explore Our Courses Section Animations
function initExploreAnimations() {
    const exploreElements = document.querySelectorAll('.explore-content h2, .explore-content p, .explore-content h3, .categories-grid, .explore-btn, .explore-image-container');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    exploreElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `opacity 0.6s ease ${index * 0.2}s, transform 0.6s ease ${index * 0.2}s`;
        observer.observe(element);
    });
}

// Success Stories Section Animations
function initSuccessStoriesAnimations() {
    const storyElements = document.querySelectorAll('.success-stories .section-header, .story-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    storyElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `opacity 0.6s ease ${index * 0.2}s, transform 0.6s ease ${index * 0.2}s`;
        observer.observe(element);
    });
}

// Packages Section Animations
function initPackagesAnimations() {
    const packageElements = document.querySelectorAll('.packages-section .section-header, .package-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    packageElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `opacity 0.6s ease ${index * 0.2}s, transform 0.6s ease ${index * 0.2}s`;
        observer.observe(element);
    });
}

// Featured Courses Modal Functions
function openCourseModal(courseId) {
    const courses = JSON.parse(localStorage.getItem('courses') || '[]');
    const packages = JSON.parse(localStorage.getItem('packages') || '[]');
    
    const course = courses.find(c => c.id === courseId);
    if (!course) return;
    
    const packageInfo = packages.find(p => p.id === course.packageId) || { name: 'General Package' };
    
    // Populate modal with course data
    document.getElementById('modalCourseTitle').textContent = course.name;
    document.getElementById('modalCourseFullTitle').textContent = course.name;
    document.getElementById('modalCoursePackage').textContent = packageInfo.name;
    document.getElementById('modalCourseRating').textContent = course.rating || '4.9';
    document.getElementById('modalCourseDuration').textContent = course.duration || '4-6 weeks';
    document.getElementById('modalCourseClasses').textContent = `${course.classes || 20} Classes`;
    document.getElementById('modalCourseDescription').textContent = course.description || 'This comprehensive course will teach you essential skills and provide hands-on experience with real-world projects.';
    
    // Set course image
    const modalImage = document.getElementById('modalCourseImage');
    if (course.image) {
        modalImage.src = course.image;
        modalImage.alt = course.name;
    } else {
        modalImage.src = 'img/courses/default-course.jpg';
        modalImage.alt = 'Course Image';
    }
    
    // Populate course features
    const featuresContainer = document.getElementById('modalCourseFeatures');
    featuresContainer.innerHTML = '';
    
    const features = course.features || [
        'Comprehensive curriculum designed by industry experts',
        'Hands-on projects and real-world applications',
        'Certificate of completion',
        'Lifetime access to course materials',
        'Community support and discussion forums'
    ];
    
    features.forEach(feature => {
        const li = document.createElement('li');
        li.textContent = feature;
        featuresContainer.appendChild(li);
    });
    
    // Show modal
    document.getElementById('courseDetailModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('courseDetailModal').style.display = 'none';
}

// Populate Featured Courses
function populateFeaturedCourses() {
    const courses = JSON.parse(localStorage.getItem('courses') || '[]');
    const packages = JSON.parse(localStorage.getItem('packages') || '[]');
    const featuredCoursesContainer = document.getElementById('featuredCoursesContainer');
    
    if (!featuredCoursesContainer) return;
    
    // Filter courses marked as featured
    const featuredCourses = courses.filter(course => course.featured === true || course.featured === 'yes');
    
    if (featuredCourses.length === 0) {
        // Show default featured courses if none are marked
        featuredCoursesContainer.innerHTML = `
            <div class="course-card featured" onclick="openDefaultCourseModal('social-media')">
                <div class="recommendation-bars" id="recommendationBars">
                    <!-- Default recommendations will be replaced by featured courses -->
                    <div class="recommendation-item">
                        <div class="course-info">
                            <span class="course-name">Digital Marketing Mastery</span>
                            <span class="completion-rate">95% completion rate</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 95%"></div>
                        </div>
                        <span class="rating">4.9★</span>
                    </div>
                    <div class="recommendation-item">
                        <div class="course-info">
                            <span class="course-name">Social Media Marketing</span>
                            <span class="completion-rate">92% completion rate</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%"></div>
                        </div>
                        <span class="rating">4.8★</span>
                    </div>
                    <div class="recommendation-item">
                        <div class="course-info">
                            <span class="course-name">Content Creation & Copywriting</span>
                            <span class="completion-rate">89% completion rate</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 89%"></div>
                        </div>
                        <span class="rating">4.7★</span>
                    </div>
                    <div class="recommendation-item">
                        <div class="course-info">
                            <span class="course-name">Business Communication</span>
                            <span class="completion-rate">87% completion rate</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 87%"></div>
                        </div>
                        <span class="rating">4.6★</span>
                    </div>
                    <div class="recommendation-item">
                        <div class="course-info">
                            <span class="course-name">Personal Finance Management</span>
                            <span class="completion-rate">85% completion rate</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%"></div>
                        </div>
                        <span class="rating">4.5★</span>
                    </div>
                </div>
                <div class="course-title">
                    <h3>Professional Copywriting</h3>
                </div>
                <div class="course-details">
                    <i class="fas fa-book-open"></i>
                    <span>22 Classes</span>
                </div>
                <div class="course-footer">
                    <span>Powered by Rainbucks</span>
                </div>
            </div>
            <div class="course-card featured" onclick="openDefaultCourseModal('ms-word')">
                <div class="course-image">
                    <img src="img/courses/MS WORD HINDI.jpg" alt="MS Word Learning">
                </div>
                <div class="course-category">
                    <span>Office Tools</span>
                </div>
                <div class="course-rating">
                    <i class="fas fa-star"></i>
                    <span>4.8</span>
                </div>
                <div class="course-title">
                    <h3>MS Word Mastery</h3>
                </div>
                <div class="course-details">
                    <i class="fas fa-book-open"></i>
                    <span>25 Classes</span>
                </div>
                <div class="course-footer">
                    <span>Powered by Rainbucks</span>
                </div>
            </div>
            <div class="course-card featured" onclick="openDefaultCourseModal('copywriting')">
                <div class="course-image">
                    <img src="img/courses/COPY WRITING HINDI.jpg" alt="Copy Writing">
                </div>
                <div class="course-category">
                    <span>Content Creation</span>
                </div>
                <div class="course-rating">
                    <i class="fas fa-star"></i>
                    <span>4.7</span>
                </div>
                <div class="course-title">
                    <h3>Professional Copywriting</h3>
                </div>
                <div class="course-details">
                    <i class="fas fa-book-open"></i>
                    <span>22 Classes</span>
                </div>
                <div class="course-footer">
                    <span>Powered by Rainbucks</span>
                </div>
            </div>
        `;
        return;
    }
    
    // Display featured courses from localStorage
    featuredCoursesContainer.innerHTML = featuredCourses.map(course => {
        const packageInfo = packages.find(p => p.slug === course.packageSlug) || { name: 'General Package' };
        
        return `
            <div class="course-card featured" onclick="window.open('course/detail.html?slug=${course.slug}', '_blank')">
                <div class="course-image">
                    <img src="${course.image || 'img/courses/default-course.jpg'}" alt="${course.name}">
                </div>
                <div class="course-category">
                    <span>${packageInfo.name}</span>
                </div>
                <div class="course-rating">
                    <i class="fas fa-star"></i>
                    <span>${course.rating || '4.9'}</span>
                </div>
                <div class="course-title">
                    <h3>${course.name}</h3>
                </div>
                <div class="course-details">
                    <i class="fas fa-book-open"></i>
                    <span>${course.duration || 20} Hours</span>
                </div>
                <div class="course-footer">
                    <span>Powered by Rainbucks</span>
                </div>
            </div>
        `;
    }).join('');
}

// Handle default course modals for static courses
function openDefaultCourseModal(courseType) {
    const courseData = {
        'social-media': {
            title: 'Social Media Marketing',
            package: 'Digital Marketing',
            rating: '4.9',
            duration: '6-8 weeks',
            classes: '30 Classes',
            image: 'img/courses/SOCIAL MEDIA.jpg',
            description: 'Master the art of social media marketing with our comprehensive course. Learn to create engaging content, build brand awareness, and drive conversions across all major social platforms.',
            features: [
                'Complete Facebook and Instagram marketing strategies',
                'Content creation and visual design principles',
                'Analytics and performance tracking',
                'Influencer marketing and collaborations',
                'Paid advertising campaigns optimization',
                'Community management best practices'
            ]
        },
        'ms-word': {
            title: 'MS Word Mastery',
            package: 'Office Tools',
            rating: '4.8',
            duration: '3-4 weeks',
            classes: '25 Classes',
            image: 'img/courses/MS WORD HINDI.jpg',
            description: 'Become proficient in Microsoft Word with our detailed course covering everything from basic formatting to advanced document creation and automation.',
            features: [
                'Advanced formatting and styling techniques',
                'Document templates and automation',
                'Mail merge and data integration',
                'Collaboration and review features',
                'Professional document design',
                'Productivity tips and shortcuts'
            ]
        },
        'copywriting': {
            title: 'Professional Copywriting',
            package: 'Content Creation',
            rating: '4.7',
            duration: '5-6 weeks',
            classes: '22 Classes',
            image: 'img/courses/COPY WRITING HINDI.jpg',
            description: 'Learn the art of persuasive writing and create compelling copy that converts. Master the psychology of sales writing and build a successful copywriting career.',
            features: [
                'Psychology of persuasive writing',
                'Sales page and email copywriting',
                'Social media and ad copy creation',
                'Brand voice and tone development',
                'A/B testing and optimization',
                'Building a copywriting portfolio'
            ]
        }
    };
    
    const course = courseData[courseType];
    if (!course) return;
    
    // Populate modal with course data
    document.getElementById('modalCourseTitle').textContent = course.title;
    document.getElementById('modalCourseFullTitle').textContent = course.title;
    document.getElementById('modalCoursePackage').textContent = course.package;
    document.getElementById('modalCourseRating').textContent = course.rating;
    document.getElementById('modalCourseDuration').textContent = course.duration;
    document.getElementById('modalCourseClasses').textContent = course.classes;
    document.getElementById('modalCourseDescription').textContent = course.description;
    document.getElementById('modalCourseImage').src = course.image;
    document.getElementById('modalCourseImage').alt = course.title;
    
    // Populate course features
    const featuresContainer = document.getElementById('modalCourseFeatures');
    featuresContainer.innerHTML = '';
    
    course.features.forEach(feature => {
        const li = document.createElement('li');
        li.textContent = feature;
        featuresContainer.appendChild(li);
    });
    
    // Show modal
    document.getElementById('courseDetailModal').style.display = 'block';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('courseDetailModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Populate Top Recommended Courses
function populateRecommendedCourses() {
    const courses = JSON.parse(localStorage.getItem('courses') || '[]');
    const recommendationBars = document.getElementById('recommendationBars');
    
    if (!recommendationBars) return;
    
    // Filter courses marked as featured
    const featuredCourses = courses.filter(course => course.featured === true || course.featured === 'yes');
    
    if (featuredCourses.length === 0) {
        // Keep default recommendations if no featured courses
        return;
    }
    
    // Generate recommendation bars from featured courses
    recommendationBars.innerHTML = featuredCourses.slice(0, 5).map((course, index) => {
        const completionRate = Math.max(85, Math.min(98, 95 - (index * 2))); // Generate realistic completion rates
        const rating = (4.9 - (index * 0.1)).toFixed(1);
        
        return `
            <div class="recommendation-item" onclick="window.open('course/detail.html?slug=${course.slug}', '_blank')" style="cursor: pointer;">
                <div class="course-info">
                    <span class="course-name">${course.name}</span>
                    <span class="completion-rate">${completionRate}% completion rate</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${completionRate}%"></div>
                </div>
                <span class="rating">${rating}★</span>
            </div>
        `;
    }).join('');
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize hero animations
    initHeroAnimations();

    // Initialize statistics animations
    initStatsAnimations();

    // Initialize transform section animations
    initTransformAnimations();

    // Initialize explore courses animations
    initExploreAnimations();

    // Initialize success stories animations
    initSuccessStoriesAnimations();

    // Initialize packages animations
    initPackagesAnimations();

    // Initialize featured courses
    initFeaturedCourses();
    
    // Populate featured courses from admin data
    populateFeaturedCourses();
    
    // Populate recommended courses section
    populateRecommendedCourses();

    // Keep only default packages in All Courses dropdown (exclude dynamically added ones)
    try {
        const packages = JSON.parse(localStorage.getItem('packages') || '[]');
        const allCoursesMenu = document.querySelector('.dropdown-menu');
        
        // Define default packages that should always be shown
        const defaultPackages = [
            'starter-package',
            'professional-package', 
            'advanced-package',
            'expert-package',
            'ultimate-package',
            'super-ultimate-package'
        ];
        
        if (allCoursesMenu && Array.isArray(packages) && packages.length > 0) {
            const existing = new Set(Array.from(allCoursesMenu.querySelectorAll('a.dropdown-item')).map(a => a.textContent?.trim().toLowerCase()));
            
            // Only add packages that are in the default list
            packages.forEach(pkg => {
                const name = pkg.name || pkg.slug;
                const slug = (pkg.slug || '').toLowerCase();
                if (!name || !slug) return;
                
                // Only show default packages in main dropdown
                if (defaultPackages.includes(slug)) {
                    const key = name.trim().toLowerCase();
                    if (!existing.has(key)) {
                        const a = document.createElement('a');
                        a.className = 'dropdown-item';
                        a.textContent = name;
                        a.href = `package/dynamic.php?package=${slug}`;
                        allCoursesMenu.appendChild(a);
                    }
                }
            });
        }
    } catch {}

    // Initialize smooth scrolling
    initSmoothScrolling();

    // Initialize navbar scroll effect
    initNavbarScroll();

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize counter animations
    animateCounters();

    // Initialize button effects
    initButtonEffects();

    // Add ripple CSS
    addRippleCSS();

    // Add loading animation
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';

    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
});

// Handle window resize
window.addEventListener('resize', () => {
    // Recalculate any layout-dependent elements if needed
    const navbar = document.querySelector('.navbar');
    if (window.innerWidth > 768) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});

// Add some interactive hover effects for feature cards
document.addEventListener('DOMContentLoaded', () => {
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
}); 

// Populate homepage testimonials from localStorage (slider format like the image)
function populateHomeTestimonials() {
  const container = document.getElementById('home-testimonials-list');
  const empty = document.getElementById('home-testimonials-empty');
  if (!container) return;
  try {
    const testimonials = JSON.parse(localStorage.getItem('testimonials') || '[]');
    container.innerHTML = '';
    if (!Array.isArray(testimonials) || testimonials.length === 0) {
      if (empty) empty.style.display = 'block';
      return;
    }
    if (empty) empty.style.display = 'none';
    
    // Create testimonial slider container
    const sliderContainer = document.createElement('div');
    sliderContainer.className = 'testimonial-slider-container';
    sliderContainer.style.cssText = 'position: relative; overflow: hidden; width: 100%; max-width: 800px; margin: 0 auto;';
    
    const slider = document.createElement('div');
    slider.className = 'testimonial-slider';
    slider.style.cssText = 'display: flex; transition: transform 0.5s ease; width: 100%;';
    
    testimonials.slice(0, 6).forEach((t, index) => {
      const slide = document.createElement('div');
      slide.className = 'testimonial-slide';
      slide.style.cssText = 'min-width: 100%; background: linear-gradient(135deg, #4E944F 0%, #7B3F00 100%); border-radius: 20px; padding: 40px 30px; text-align: center; color: white; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);';
      
      // Handle image display properly
      let imgSrc;
      if (t.photo) {
        if (t.photo.startsWith('data:image/') || t.photo.startsWith('http')) {
          imgSrc = t.photo;
        } else {
          imgSrc = t.photo;
        }
      } else {
        imgSrc = 'https://via.placeholder.com/80x80/ffffff/4E944F?text=' + (t.name ? t.name.charAt(0).toUpperCase() : 'U');
      }
      
      slide.innerHTML = `
        <div style="margin-bottom: 20px;">
          <img src="${imgSrc}" alt="${t.name || 'User'}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.3); margin: 0 auto; display: block;" />
        </div>
        <div style="font-size: 18px; font-weight: 600; margin-bottom: 5px; color: white;">${t.name || 'User'}</div>
        <div style="font-size: 14px; margin-bottom: 15px; color: rgba(255,255,255,0.8);">${t.location || ''}</div>
        <div style="margin-bottom: 20px; font-size: 20px;">${'⭐'.repeat(parseInt(t.rating) || 5)}</div>
        <div style="font-size: 16px; line-height: 1.6; font-style: italic; color: rgba(255,255,255,0.95); max-width: 500px; margin: 0 auto;">"${(t.review || '').slice(0, 200)}${(t.review || '').length > 200 ? '…' : ''}"</div>
      `;
      
      slider.appendChild(slide);
    });
    
    // Add navigation arrows
    const prevArrow = document.createElement('button');
    prevArrow.innerHTML = '❮';
    prevArrow.style.cssText = 'position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; z-index: 10; transition: background 0.3s ease;';
    prevArrow.addEventListener('mouseenter', () => prevArrow.style.background = 'rgba(255,255,255,0.3)');
    prevArrow.addEventListener('mouseleave', () => prevArrow.style.background = 'rgba(255,255,255,0.2)');
    
    const nextArrow = document.createElement('button');
    nextArrow.innerHTML = '❯';
    nextArrow.style.cssText = 'position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; z-index: 10; transition: background 0.3s ease;';
    nextArrow.addEventListener('mouseenter', () => nextArrow.style.background = 'rgba(255,255,255,0.3)');
    nextArrow.addEventListener('mouseleave', () => nextArrow.style.background = 'rgba(255,255,255,0.2)');
    
    let currentSlide = 0;
    const totalSlides = testimonials.slice(0, 6).length;
    
    function updateSlider() {
      slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }
    
    prevArrow.addEventListener('click', () => {
      currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
      updateSlider();
    });
    
    nextArrow.addEventListener('click', () => {
      currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
      updateSlider();
    });
    
    // Auto-slide every 5 seconds
    setInterval(() => {
      currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
      updateSlider();
    }, 5000);
    
    sliderContainer.appendChild(slider);
    sliderContainer.appendChild(prevArrow);
    sliderContainer.appendChild(nextArrow);
    container.appendChild(sliderContainer);
    
  } catch {}
}

document.addEventListener('DOMContentLoaded', populateHomeTestimonials);

document.addEventListener('DOMContentLoaded', function() {
    // Course Carousel
    const carouselTrack = document.querySelector('.carousel-track');
    const carouselCards = document.querySelectorAll('.course-card');
    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');
    const indicators = document.querySelectorAll('.carousel-indicator');
    
    let currentIndex = 0;
    const cardWidth = 350; // Width of each card including margin
    const gap = 30; // Gap between cards
    let autoSlideInterval;
    
    // Set initial position
    function setInitialPosition() {
        carouselTrack.style.transform = `translateX(-${currentIndex * (cardWidth + gap)}px)`;
    }
    
    // Update indicators
    function updateIndicators() {
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }
    
    // Move to specific slide
    function goToSlide(index) {
        // Calculate the maximum index based on number of cards
        const maxIndex = carouselCards.length - 1;
        
        // Handle looping
        if (index < 0) {
            index = maxIndex;
        } else if (index > maxIndex) {
            index = 0;
        }
        
        currentIndex = index;
        carouselTrack.style.transition = 'transform 0.5s ease-in-out';
        carouselTrack.style.transform = `translateX(-${currentIndex * (cardWidth + gap)}px)`;
        
        updateIndicators();
    }
    
    // Auto slide function
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, 5000); // Change slide every 5 seconds
    }
    
    // Pause auto slide on hover
    function pauseAutoSlide() {
        clearInterval(autoSlideInterval);
    }
    
    // Resume auto slide when mouse leaves
    function resumeAutoSlide() {
        startAutoSlide();
    }
    
    // Event Listeners
    prevButton.addEventListener('click', () => {
        goToSlide(currentIndex - 1);
        pauseAutoSlide();
        setTimeout(resumeAutoSlide, 10000); // Resume after 10 seconds
    });
    
    nextButton.addEventListener('click', () => {
        goToSlide(currentIndex + 1);
        pauseAutoSlide();
        setTimeout(resumeAutoSlide, 10000); // Resume after 10 seconds
    });
    
    // Indicator click events
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
            pauseAutoSlide();
            setTimeout(resumeAutoSlide, 10000); // Resume after 10 seconds
        });
    });
    
    // Pause on hover
    carouselTrack.parentElement.addEventListener('mouseenter', pauseAutoSlide);
    carouselTrack.parentElement.addEventListener('mouseleave', resumeAutoSlide);
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            carouselTrack.style.transition = 'none';
            setInitialPosition();
            setTimeout(() => {
                carouselTrack.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }, 250);
    });
    
    // Initialize
    setInitialPosition();
    updateIndicators();
    startAutoSlide();
    
    // Touch events for mobile swipe
    let touchStartX = 0;
    let touchEndX = 0;
    
    carouselTrack.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        pauseAutoSlide();
    }, { passive: true });
    
    carouselTrack.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        setTimeout(resumeAutoSlide, 5000);
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50; // Minimum distance to trigger slide change
        
        // Left swipe - next slide
        if (touchStartX - touchEndX > swipeThreshold) {
            goToSlide(currentIndex + 1);
        }
        
        // Right swipe - previous slide
        if (touchEndX - touchStartX > swipeThreshold) {
            goToSlide(currentIndex - 1);
        }
    }
});

// Disable right click
document.addEventListener("contextmenu", (e) => e.preventDefault());

// Disable text selection
document.addEventListener("selectstart", (e) => e.preventDefault());

// Disable copy, cut, paste
document.addEventListener("copy", (e) => e.preventDefault());
document.addEventListener("cut", (e) => e.preventDefault());
document.addEventListener("paste", (e) => e.preventDefault());

// Disable common DevTools shortcuts
document.addEventListener("keydown", (e) => {
  // F12
  if (e.key === "F12") {
    e.preventDefault();
  }
  // Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+Shift+C
  if (e.ctrlKey && e.shiftKey && ["I", "J", "C"].includes(e.key.toUpperCase())) {
    e.preventDefault();
  }
  // Ctrl+U (View Page Source)
  if (e.ctrlKey && e.key.toUpperCase() === "U") {
    e.preventDefault();
  }
});