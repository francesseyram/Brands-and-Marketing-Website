<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Handle filtering and sorting
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'registration_date';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Build query with search and sort
$query = "SELECT a.*, e.name as event_name 
          FROM attendees a 
          JOIN events e ON a.event_id = e.id 
          WHERE (a.full_name LIKE :search OR a.email LIKE :search OR a.ticket_type LIKE :search)
          ORDER BY a.$sort $order";

$stmt = $db->prepare($query);
$searchParam = "%$search%";
$stmt->bindParam(':search', $searchParam);
$stmt->execute();
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM attendees a WHERE (a.full_name LIKE :search OR a.email LIKE :search OR a.ticket_type LIKE :search)";
$countStmt = $db->prepare($countQuery);
$countStmt->bindParam(':search', $searchParam);
$countStmt->execute();
$totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendees - Creative Summit 2024</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎨 Creative Summit 2024</h1>
                </div>
                <nav class="nav-links">
                    <a href="../index.php">Home</a>
                    <a href="attendees.php" class="active">Attendees</a>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
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
            <div class="page-header">
                <h1>Event Attendees</h1>
                <p>Meet the amazing people joining us at Creative Summit 2024</p>
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $totalCount; ?></div>
                    <div class="stat-label">Total Attendees</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count(array_filter($attendees, function($a) { return $a['ticket_type'] === 'VIP'; })); ?></div>
                    <div class="stat-label">VIP Tickets</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count(array_filter($attendees, function($a) { return $a['ticket_type'] === 'Standard'; })); ?></div>
                    <div class="stat-label">Standard Tickets</div>
                </div>
            </div>

            <div class="controls">
                <form method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Search attendees by name, email, or ticket type..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                </form>
                
                <div class="sort-controls">
                    <label>Sort by:</label>
                    <select onchange="updateSort(this.value)">
                        <option value="registration_date" <?php echo $sort === 'registration_date' ? 'selected' : ''; ?>>Registration Date</option>
                        <option value="full_name" <?php echo $sort === 'full_name' ? 'selected' : ''; ?>>Name</option>
                        <option value="ticket_type" <?php echo $sort === 'ticket_type' ? 'selected' : ''; ?>>Ticket Type</option>
                    </select>
                    
                    <select onchange="updateOrder(this.value)">
                        <option value="DESC" <?php echo $order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                        <option value="ASC" <?php echo $order === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" onclick="document.querySelector('.search-box form').submit()">Search</button>
                <a href="attendees.php" class="btn btn-secondary">Clear</a>
            </div>

            <div class="attendees-container">
                <div class="table-header">
                    <h2>Registered Attendees</h2>
                    <p>Showing <?php echo count($attendees); ?> of <?php echo $totalCount; ?> attendees</p>
                </div>

                <div class="attendees-grid">
                    <?php if (empty($attendees)): ?>
                        <div class="empty-state">
                            <h3>No attendees found</h3>
                            <p>Try adjusting your search criteria or check back later.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($attendees as $attendee): ?>
                            <div class="attendee-card">
                                <div class="attendee-header">
                                    <div class="attendee-name"><?php echo htmlspecialchars($attendee['full_name']); ?></div>
                                    <div class="ticket-badge ticket-<?php echo strtolower($attendee['ticket_type']); ?>">
                                        <?php echo htmlspecialchars($attendee['ticket_type']); ?>
                                    </div>
                                </div>
                                
                                <div class="attendee-details">
                                    <div class="detail-item">
                                        <span class="detail-icon">📧</span>
                                        <span><?php echo htmlspecialchars($attendee['email']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-icon">📱</span>
                                        <span><?php echo htmlspecialchars($attendee['phone']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-icon">🎫</span>
                                        <span>ID: #<?php echo str_pad($attendee['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                </div>
                                
                                <div class="registration-date">
                                    Registered: <?php echo date('M j, Y g:i A', strtotime($attendee['registration_date'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>
