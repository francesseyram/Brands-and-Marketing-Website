

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Creative Summit 2024</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎯 Creative Summit 2024</h1>
                </div>
                <nav>
                    <ul class="nav-links">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="register.php" class="active">Register</a></li>
                        <li><a href="attendees.php">Attendees</a></li>
                    </ul>
                </nav>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <main class="container" style="padding: 2rem 0;">
        <!-- Page Header -->
        <div class="page-header animate-slide-down">
            <h1>Register for Creative Summit 2024</h1>
            <p>Join industry leaders and tech enthusiasts for an inspiring day of innovation and networking</p>
        </div>

        <!-- Event Info Card -->
        <div class="card animate-fade-in" style="margin-bottom: 2rem;">
            <div class="card-header">
                <div class="event-badge">📅 August 15, 2024</div>
                <h2 style="margin-top: 1rem; color: #374151;">Austin Convention Center</h2>
                <p style="color: #6b7280; margin: 0;">Experience cutting-edge talks, workshops, and networking opportunities</p>
            </div>
        </div>

        <!-- Pricing Display -->
        <div class="price-display animate-slide-up">
            <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 2rem;">
                <div style="text-align: center;">
                    <div class="price">$75</div>
                    <div class="price-note">Standard Ticket</div>
                    <div class="early-bird">Best Value</div>
                </div>
                <div style="text-align: center;">
                    <div class="price">$150</div>
                    <div class="price-note">VIP Ticket</div>
                    <div class="early-bird">Premium Experience</div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="card animate-slide-up">
            <div class="card-content">
                <h2 style="text-align: center; margin-bottom: 2rem; color: #374151;">Complete Your Registration</h2>
                
                <form id="registrationForm" action="process_registration.php" method="POST" data-autosave>
                    <!-- Personal Information Section -->
                    <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
                        <legend style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">Personal Information</legend>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name *</label>
                                <input type="text" id="full_name" name="full_name" required maxlength="100" 
                                       placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required maxlength="100" 
                                       placeholder="your.email@example.com">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required maxlength="20" 
                                       placeholder="0XX XXX XXXX" pattern="[0-9+\-\s()]+">
                            </div>
                            <div class="form-group">
                                <label for="ticket_type">Ticket Type *</label>
                                <select id="ticket_type" name="ticket_type" required onchange="updateTicketPrice()">
                                    <option value="">Select Ticket Type</option>
                                    <option value="Standard">Standard - $75</option>
                                    <option value="VIP">VIP - $150</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Professional Information Section -->
                    <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
                        <legend style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">Professional Information</legend>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="company_organization">Company/Organization</label>
                                <input type="text" id="company_organization" name="company_organization" maxlength="150" 
                                       placeholder="Your company or organization">
                            </div>
                            <div class="form-group">
                                <label for="job_title">Job Title</label>
                                <input type="text" id="job_title" name="job_title" maxlength="100" 
                                       placeholder="Your current position">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Event Preferences Section -->
                    <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
                        <legend style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">Event Preferences</legend>
                        
                        <div class="form-group">
                            <label for="dietary_requirements">Dietary Requirements</label>
                            <textarea id="dietary_requirements" name="dietary_requirements" rows="3" 
                                      placeholder="Please specify any dietary restrictions or preferences"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="special_accommodations">Special Accommodations</label>
                            <textarea id="special_accommodations" name="special_accommodations" rows="3" 
                                      placeholder="Any accessibility needs or special requests"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="how_did_you_hear">How did you hear about this event?</label>
                            <select id="how_did_you_hear" name="how_did_you_hear">
                                <option value="">Please select</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Google Search">Google Search</option>
                                <option value="Email Newsletter">Email Newsletter</option>
                                <option value="Colleague Referral">Colleague Referral</option>
                                <option value="Friend Recommendation">Friend Recommendation</option>
                                <option value="Professional Network">Professional Network</option>
                                <option value="Industry Publication">Industry Publication</option>
                                <option value="Previous Event">Previous Event</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </fieldset>

                    <!-- Ticket Summary -->
                    <div id="ticketSummary" class="registration-details" style="display: none;">
                        <h3>Registration Summary</h3>
                        <div class="detail-row">
                            <span class="detail-label">Ticket Type:</span>
                            <span class="detail-value" id="summaryTicketType">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Price:</span>
                            <span class="detail-value" id="summaryPrice">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Event Date:</span>
                            <span class="detail-value">August 15, 2024</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value">Austin Convention Center</span>
                        </div>
                    </div>

                    <!-- Agreement Section -->
                    <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
                        <div class="checkbox-group">
                            <input type="checkbox" id="newsletter_subscribe" name="newsletter_subscribe" value="1">
                            <label for="newsletter_subscribe">
                                Subscribe to our newsletter for event updates and future announcements
                            </label>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="terms_accepted" name="terms_accepted" value="1" required>
                            <label for="terms_accepted">
                                I agree to the <a href="#" style="color: #0f766e;">Terms and Conditions</a> 
                                and <a href="#" style="color: #0f766e;">Privacy Policy</a> *
                            </label>
                        </div>
                    </fieldset>

                    <!-- Submit Button -->
                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-primary full-width" id="submitBtn">
                            🎯 Complete Registration
                        </button>
                        <p style="margin-top: 1rem; color: #6b7280; font-size: 0.9rem;">
                            You will receive a confirmation email after registration
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer (Simple) -->
    <footer style="background: #374151; color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <p>&copy; 2024 Creative Summit. All rights reserved.</p>
    </footer>

    <script src="scripts.js"></script>
    <script>
        // Ticket Price Update Function
        function updateTicketPrice() {
            const ticketSelect = document.getElementById('ticket_type');
            const summary = document.getElementById('ticketSummary');
            const summaryType = document.getElementById('summaryTicketType');
            const summaryPrice = document.getElementById('summaryPrice');
            
            if (ticketSelect.value) {
                summary.style.display = 'block';
                summaryType.textContent = ticketSelect.value;
                
                if (ticketSelect.value === 'VIP') {
                    summaryPrice.textContent = '$150.00';
                } else {
                    summaryPrice.textContent = '$75.00';
                }
            } else {
                summary.style.display = 'none';
            }
        }

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Real-time validation
            form.addEventListener('input', function() {
                validateForm();
            });
            
            form.addEventListener('change', function() {
                validateForm();
            });
            
            function validateForm() {
                const requiredFields = form.querySelectorAll('[required]');
                let allValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allValid = false;
                    }
                });
                
                submitBtn.disabled = !allValid;
                
                if (allValid) {
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                } else {
                    submitBtn.style.opacity = '0.6';
                    submitBtn.style.cursor = 'not-allowed';
                }
            }
            
            // Initial validation
            validateForm();
            
            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0') && value.length <= 10) {
                    // Ghana phone format: 0XX XXX XXXX
                    if (value.length > 3 && value.length <= 6) {
                        value = value.substr(0, 3) + ' ' + value.substr(3);
                    } else if (value.length > 6) {
                        value = value.substr(0, 3) + ' ' + value.substr(3, 3) + ' ' + value.substr(6);
                    }
                }
                e.target.value = value;
            });
        });

        // Add this simple script to your registration.php file

// Check for success parameter and show alert
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        alert('Registration successful! You will receive a confirmation email shortly.');
        
        // Clean the URL to remove the success parameter
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});
    </script>
</body>
</html>