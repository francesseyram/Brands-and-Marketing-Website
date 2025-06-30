<?php
/**
 * EVENT DETAILS PAGE
 * Creative Summit 2024 - Event Registration System
 * Displays comprehensive event information with dynamic content
 */

// Database configuration
$host = 'localhost';
$dbname = 'event_registration';
$username = 'root'; // Change as needed
$password = '';     // Change as needed

// Initialize variables
$event = null;
$attendee_count = 0;
$error_message = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get event details (assuming event_id = 1 for Creative Summit 2024)
    $event_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$event) {
        throw new Exception("Event not found");
    }
    
    // Get attendee count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM attendees WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $attendee_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Event schedule data (could be from database or JSON)
$schedule = [
    [
        'time' => '8:00 AM',
        'title' => 'Registration & Welcome Coffee',
        'speaker' => '',
        'type' => 'registration'
    ],
    [
        'time' => '9:00 AM',
        'title' => 'Opening Keynote: The Future of Creative Technology',
        'speaker' => 'Dr. Sarah Mitchell',
        'type' => 'keynote'
    ],
    [
        'time' => '10:30 AM',
        'title' => 'Coffee Break & Networking',
        'speaker' => '',
        'type' => 'break'
    ],
    [
        'time' => '11:00 AM',
        'title' => 'Workshop: AI in Creative Design',
        'speaker' => 'Marcus Chen',
        'type' => 'workshop'
    ],
    [
        'time' => '12:30 PM',
        'title' => 'Lunch & Networking',
        'speaker' => '',
        'type' => 'break'
    ],
    [
        'time' => '2:00 PM',
        'title' => 'Panel: Building Sustainable Creative Businesses',
        'speaker' => 'Various Industry Leaders',
        'type' => 'panel'
    ],
    [
        'time' => '3:30 PM',
        'title' => 'Afternoon Break',
        'speaker' => '',
        'type' => 'break'
    ],
    [
        'time' => '4:00 PM',
        'title' => 'Closing Keynote: Innovation in Creative Industries',
        'speaker' => 'Alex Rodriguez',
        'type' => 'keynote'
    ],
    [
        'time' => '5:30 PM',
        'title' => 'Networking Reception',
        'speaker' => '',
        'type' => 'social'
    ]
];

// Speakers data
$speakers = [
    [
        'name' => 'Dr. Sarah Mitchell',
        'title' => 'Chief Innovation Officer, TechCreate Inc.',
        'bio' => 'Leading expert in creative technology with 15+ years of experience in digital innovation.',
        'image' => '👩‍💼'
    ],
    [
        'name' => 'Marcus Chen',
        'title' => 'AI Designer & Founder, Creative AI Lab',
        'bio' => 'Pioneer in AI-assisted design tools and creative automation technologies.',
        'image' => '👨‍💻'
    ],
    [
        'name' => 'Alex Rodriguez',
        'title' => 'Creative Director, Global Design Studio',
        'bio' => 'Award-winning creative director with expertise in brand strategy and digital experiences.',
        'image' => '🎨'
    ]
];

// Pricing information
$pricing = [
    'early_bird' => [
        'standard' => 150,
        'vip' => 250,
        'deadline' => '2024-07-15'
    ],
    'regular' => [
        'standard' => 200,
        'vip' => 350
    ]
];

// Check if early bird pricing is still available
$is_early_bird = date('Y-m-d') <= $pricing['early_bird']['deadline'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name'] ?? 'Event Details'); ?> - Event Registration</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <meta name="description" content="<?php echo htmlspecialchars($event['description'] ?? ''); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($event['name'] ?? ''); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($event['description'] ?? ''); ?>">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎨 Event Hub</h1>
                </div>
                <nav>
                    <ul class="nav-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="event-details.php" class="active">Event Details</a></li>
                        <li><a href="registration.php">Register</a></li>
                        <li><a href="attendees.php">Attendees</a></li>
                        <li><a href="login.php">Login</a></li>
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

    <main>
        <div class="container">
            <?php if ($error_message): ?>
                <div class="alert error">
                    <span>⚠️</span>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($event): ?>
                <!-- Event Hero Section -->
                <section class="event-hero">
                    <div class="card">
                        <div class="card-header">
                            <h1><?php echo htmlspecialchars($event['name']); ?></h1>
                            <div class="event-badge">
                                <?php echo date('M j, Y', strtotime($event['date'])); ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="event-meta">
                                <div class="meta-item">
                                    <span class="detail-icon">📅</span>
                                    <span><?php echo date('l, F j, Y', strtotime($event['date'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="detail-icon">📍</span>
                                    <span><?php echo htmlspecialchars($event['location']); ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="detail-icon">👥</span>
                                    <span><span class="stat-number"><?php echo $attendee_count; ?></span> Registered</span>
                                </div>
                            </div>
                            <p class="event-description">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                            <div class="hero-actions">
                                <a href="registration.php" class="btn btn-primary">
                                    Register Now 🎫
                                </a>
                                <a href="#schedule" class="btn btn-outline">
                                    View Schedule 📋
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Event Highlights -->
                <section class="event-highlights">
                    <div class="stats-bar">
                        <div class="stat-item">
                            <div class="stat-number">8</div>
                            <div class="stat-label">Hours of Content</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">3</div>
                            <div class="stat-label">Expert Speakers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $attendee_count; ?></div>
                            <div class="stat-label">Attendees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">2</div>
                            <div class="stat-label">Networking Sessions</div>
                        </div>
                    </div>

                    <div class="highlights-grid">
                        <div class="highlight-item card">
                            <div class="highlight-icon">🎯</div>
                            <h3>Expert Insights</h3>
                            <p>Learn from industry leaders and gain valuable insights into the latest trends and technologies.</p>
                        </div>
                        <div class="highlight-item card">
                            <div class="highlight-icon">🤝</div>
                            <h3>Networking</h3>
                            <p>Connect with like-minded professionals and build meaningful relationships in the creative industry.</p>
                        </div>
                        <div class="highlight-item card">
                            <div class="highlight-icon">🛠️</div>
                            <h3>Hands-on Workshops</h3>
                            <p>Participate in interactive workshops and gain practical skills you can apply immediately.</p>
                        </div>
                        <div class="highlight-item card">
                            <div class="highlight-icon">🎁</div>
                            <h3>Exclusive Resources</h3>
                            <p>Access exclusive tools, templates, and resources available only to event attendees.</p>
                        </div>
                    </div>
                </section>

                <!-- Event Schedule -->
                <section id="schedule" class="event-schedule">
                    <div class="card">
                        <div class="card-header">
                            <h2>📋 Event Schedule</h2>
                            <p>Full day agenda with sessions, workshops, and networking opportunities</p>
                        </div>
                        <div class="schedule-container">
                            <?php foreach ($schedule as $item): ?>
                                <div class="schedule-item <?php echo $item['type']; ?>">
                                    <div class="schedule-time">
                                        <strong><?php echo $item['time']; ?></strong>
                                    </div>
                                    <div class="schedule-content">
                                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                        <?php if ($item['speaker']): ?>
                                            <p class="schedule-speaker">
                                                <span class="detail-icon">🎤</span>
                                                <?php echo htmlspecialchars($item['speaker']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="schedule-type">
                                        <?php
                                        $type_icons = [
                                            'registration' => '📝',
                                            'keynote' => '🎯',
                                            'workshop' => '🛠️',
                                            'panel' => '💬',
                                            'break' => '☕',
                                            'social' => '🥂'
                                        ];
                                        echo $type_icons[$item['type']] ?? '📅';
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- Speakers Section -->
                <section class="speakers-section">
                    <div class="page-header">
                        <h2>🎤 Featured Speakers</h2>
                        <p>Learn from industry experts and thought leaders</p>
                    </div>
                    
                    <div class="speakers-grid">
                        <?php foreach ($speakers as $speaker): ?>
                            <div class="speaker-card card">
                                <div class="speaker-avatar">
                                    <?php echo $speaker['image']; ?>
                                </div>
                                <div class="speaker-info">
                                    <h3><?php echo htmlspecialchars($speaker['name']); ?></h3>
                                    <p class="speaker-title"><?php echo htmlspecialchars($speaker['title']); ?></p>
                                    <p class="speaker-bio"><?php echo htmlspecialchars($speaker['bio']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Pricing Section -->
                <section class="pricing-section">
                    <div class="page-header">
                        <h2>💰 Ticket Pricing</h2>
                        <p>Choose the perfect ticket type for your needs</p>
                    </div>

                    <?php if ($is_early_bird): ?>
                        <div class="alert success">
                            <span>🎉</span>
                            <span>Early Bird pricing available until <?php echo date('F j, Y', strtotime($pricing['early_bird']['deadline'])); ?>!</span>
                        </div>
                    <?php endif; ?>

                    <div class="pricing-grid">
                        <!-- Standard Ticket -->
                        <div class="pricing-card card">
                            <div class="pricing-header">
                                <h3>Standard Ticket</h3>
                                <div class="ticket-badge ticket-standard">Most Popular</div>
                            </div>
                            <div class="pricing-content">
                                <div class="price-display">
                                    <div class="price">
                                        $<?php echo $is_early_bird ? $pricing['early_bird']['standard'] : $pricing['regular']['standard']; ?>
                                    </div>
                                    <?php if ($is_early_bird): ?>
                                        <div class="early-bird">Early Bird Special</div>
                                        <div class="price-note">Regular: $<?php echo $pricing['regular']['standard']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <ul class="ticket-features">
                                    <li>✅ Full event access</li>
                                    <li>✅ All sessions & workshops</li>
                                    <li>✅ Networking opportunities</li>
                                    <li>✅ Welcome kit & materials</li>
                                    <li>✅ Lunch & refreshments</li>
                                </ul>
                                <a href="register.php?event_id=<?php echo $event['id']; ?>&ticket_type=standard" class="btn btn-primary full-width">
                                    Select Standard
                                </a>
                            </div>
                        </div>

                        <!-- VIP Ticket -->
                        <div class="pricing-card card vip-card">
                            <div class="pricing-header">
                                <h3>VIP Ticket</h3>
                                <div class="ticket-badge ticket-vip">Premium Experience</div>
                            </div>
                            <div class="pricing-content">
                                <div class="price-display">
                                    <div class="price">
                                        $<?php echo $is_early_bird ? $pricing['early_bird']['vip'] : $pricing['regular']['vip']; ?>
                                    </div>
                                    <?php if ($is_early_bird): ?>
                                        <div class="early-bird">Early Bird Special</div>
                                        <div class="price-note">Regular: $<?php echo $pricing['regular']['vip']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <ul class="ticket-features">
                                    <li>✅ Everything in Standard</li>
                                    <li>✅ VIP seating area</li>
                                    <li>✅ Exclusive VIP networking</li>
                                    <li>✅ Meet & greet with speakers</li>
                                    <li>✅ Premium welcome gift</li>
                                    <li>✅ Event recording access</li>
                                </ul>
                                <a href="register.php?event_id=<?php echo $event['id']; ?>&ticket_type=vip" class="btn btn-secondary full-width">
                                    Select VIP
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Venue Information -->
                <section class="venue-section">
                    <div class="card">
                        <div class="card-header">
                            <h2>📍 Venue Information</h2>
                        </div>
                        <div class="card-content">
                            <div class="venue-grid">
                                <div class="venue-info">
                                    <h3><?php echo htmlspecialchars($event['location']); ?></h3>
                                    <div class="venue-details">
                                        <div class="detail-item">
                                            <span class="detail-icon">🏢</span>
                                            <span>Modern conference facility with state-of-the-art equipment</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-icon">🚗</span>
                                            <span>Ample parking available</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-icon">🚌</span>
                                            <span>Public transportation accessible</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-icon">♿</span>
                                            <span>Wheelchair accessible</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="venue-map">
                                    <div class="map-placeholder">
                                        <div class="map-icon">🗺️</div>
                                        <p>Interactive map coming soon</p>
                                        <a href="https://maps.google.com/?q=<?php echo urlencode($event['location']); ?>" 
                                           target="_blank" class="btn btn-outline">
                                            View on Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Call to Action -->
                <section class="cta-section">
                    <div class="card">
                        <div class="card-content text-center">
                            <h2>Ready to Join Us?</h2>
                            <p>Don't miss out on this incredible opportunity to learn, network, and grow your creative career.</p>
                            <?php if ($is_early_bird): ?>
                                <p class="early-bird-reminder">
                                    <strong>⏰ Early Bird pricing ends <?php echo date('F j, Y', strtotime($pricing['early_bird']['deadline'])); ?>!</strong>
                                </p>
                            <?php endif; ?>
                            <div class="cta-buttons">
                                <a href="registration.php" class="btn btn-primary">
                                    Register Now 🎫
                                </a>
                                <a href="attendees.php" class="btn btn-outline">
                                    View Attendees 👥
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            <?php else: ?>
                <div class="empty-state">
                    <h2>Event Not Found</h2>
                    <p>The requested event could not be found. Please check the URL or contact support.</p>
                    <a href="index.php" class="btn btn-primary">Return to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="scripts.js"></script>
    
    <!-- Additional CSS for this page -->
    <style>
        .event-hero {
            margin-bottom: 3rem;
        }
        
        .event-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 12px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #374151;
            font-weight: 500;
        }
        
        .event-description {
            font-size: 1.1rem;
            line-height: 1.7;
            margin: 1.5rem 0;
        }
        
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .highlights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .highlight-item {
            text-align: center;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .highlight-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .schedule-container {
            padding: 2rem;
        }
        
        .schedule-item {
            display: grid;
            grid-template-columns: 120px 1fr 60px;
            gap: 1.5rem;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .schedule-item:last-child {
            border-bottom: none;
        }
        
        .schedule-item.keynote {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 0.5rem 0;
        }
        
        .schedule-item.break {
            opacity: 0.7;
        }
        
        .schedule-time {
            color: #0f766e;
            font-weight: 600;
        }
        
        .schedule-content h4 {
            margin-bottom: 0.5rem;
            color: #374151;
        }
        
        .schedule-speaker {
            color: #6b7280;
            font-size: 0.9rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .schedule-type {
            text-align: center;
            font-size: 1.5rem;
        }
        
        .speakers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .speaker-card {
            text-align: center;
            padding: 2rem;
        }
        
        .speaker-avatar {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .speaker-title {
            color: #0f766e;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .speaker-bio {
            color: #6b7280;
            line-height: 1.6;
        }
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .pricing-card {
            position: relative;
        }
        
        .vip-card {
            border: 2px solid #fb7185;
            transform: scale(1.05);
        }
        
        .pricing-header {
            text-align: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .pricing-content {
            padding: 2rem;
        }
        
        .ticket-features {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }
        
        .ticket-features li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .venue-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        
        .venue-details {
            margin-top: 1rem;
        }
        
        .venue-details .detail-item {
            margin-bottom: 1rem;
        }
        
        .map-placeholder {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }
        
        .map-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .cta-section {
            margin: 3rem 0;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .early-bird-reminder {
            background: #fb7185;
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }
        
        @media (max-width: 768px) {
            .event-meta {
                grid-template-columns: 1fr;
            }
            
            .hero-actions {
                flex-direction: column;
            }
            
            .hero-actions .btn {
                width: 100%;
            }
            
            .schedule-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: center;
            }
            
            .venue-grid {
                grid-template-columns: 1fr;
            }
            
            .vip-card {
                transform: none;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-buttons .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</body>
</html>