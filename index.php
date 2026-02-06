<?php require_once 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZabencoCorp Intranet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #1a5f2a 0%, #2d8f4e 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.2em; opacity: 0.9; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #1a5f2a; border-bottom: 2px solid #1a5f2a; padding-bottom: 10px; margin-bottom: 20px; }
        .nav-links { display: flex; gap: 15px; flex-wrap: wrap; }
        .nav-links a { color: #1a5f2a; text-decoration: none; font-weight: 600; padding: 8px 16px; background: #e8f5e9; border-radius: 5px; transition: all 0.3s; }
        .nav-links a:hover { background: #1a5f2a; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #1a5f2a; color: white; font-weight: 600; }
        tr:hover { background: #f5f5f5; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 600; }
        .status-open { background: #ffebee; color: #c62828; }
        .status-in_progress { background: #fff3e0; color: #ef6c00; }
        .status-resolved { background: #e8f5e9; color: #2e7d32; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 0.9em; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #1a5f2a 0%, #2d8f4e 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-card h3 { font-size: 2em; margin-bottom: 5px; }
        .stat-card p { opacity: 0.9; }
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .action-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #1a5f2a; }
        .action-card h4 { color: #1a5f2a; margin-bottom: 10px; }
        .action-card p { color: #666; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏢 ZabencoCorp Intranet</h1>
        <p>Corporate Employee Portal | Welcome to Your Corporate Home</p>
    </div>
    
    <div class="container">
        <!-- System Status Dashboard -->
        <div class="section">
            <h2>📊 System Status Dashboard</h2>
            <div class="stats">
                <?php
                try {
                    $empCount = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'active'")->fetchColumn();
                    $deptCount = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
                    $openTickets = $pdo->query("SELECT COUNT(*) FROM it_tickets WHERE status IN ('open', 'in_progress')")->fetchColumn();
                    $docCount = $pdo->query("SELECT COUNT(*) FROM documents WHERE status = 'active'")->fetchColumn();
                    
                    echo "<div class='stat-card'><h3>$empCount</h3><p>Active Employees</p></div>";
                    echo "<div class='stat-card'><h3>$deptCount</h3><p>Departments</p></div>";
                    echo "<div class='stat-card'><h3>$openTickets</h3><p>Open IT Tickets</p></div>";
                    echo "<div class='stat-card'><h3>$docCount</h3><p>Active Documents</p></div>";
                } catch (PDOException $e) {
                    echo "<p>Error loading stats: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
            </div>
            <div class="nav-links">
                <a href="#">✅ View Full Dashboard</a>
                <a href="#">📝 Report Issue</a>
                <a href="#">📞 Contact IT Support</a>
            </div>
        </div>

        <!-- Employee Directory -->
        <div class="section">
            <h2>👥 Employee Directory</h2>
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Location</th>
                </tr>
                <?php
                try {
                    $stmt = $pdo->query("
                        SELECT e.employee_id, e.first_name, e.last_name, e.job_title, e.email, e.location, d.name as department 
                        FROM employees e 
                        JOIN departments d ON e.department_id = d.id 
                        WHERE e.status = 'active'
                        ORDER BY e.last_name
                    ");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "'>" . htmlspecialchars($row['email']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='6'>Error loading employees: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </table>
            <div class="nav-links" style="margin-top: 20px;">
                <a href="#">🔍 Search People</a>
                <a href="#">📋 View Org Chart</a>
                <a href="#">➕ Add New Employee</a>
            </div>
        </div>

        <!-- IT Support Center -->
        <div class="section">
            <h2>🔧 IT Support Center</h2>
            <div class="quick-actions">
                <div class="action-card">
                    <h4>📝 Submit New Ticket</h4>
                    <p>Report issues, request hardware, or get help with software</p>
                    <a href="#">Create Ticket →</a>
                </div>
                <div class="action-card">
                    <h4>📚 IT Knowledge Base</h4>
                    <p>Find answers to common questions and troubleshooting guides</p>
                    <a href="#">Browse Articles →</a>
                </div>
                <div class="action-card">
                    <h4>💻 Hardware Requests</h4>
                    <p>Request new equipment, upgrades, or replacements</p>
                    <a href="#">Submit Request →</a>
                </div>
                <div class="action-card">
                    <h4>🔒 Security Resources</h4>
                    <p>Access security policies, VPN guides, and compliance information</p>
                    <a href="#">View Resources →</a>
                </div>
            </div>
            <?php
            try {
                $stmt = $pdo->query("
                    SELECT t.ticket_number, t.title, t.category, t.priority, t.status, 
                           e.first_name || ' ' || e.last_name as submitted_by, t.created_at
                    FROM it_tickets t
                    JOIN employees e ON t.submitted_by = e.id
                    WHERE t.status IN ('open', 'in_progress')
                    ORDER BY t.created_at DESC
                    LIMIT 5
                ");
                $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($tickets)) {
                    echo "<h3 style='margin-top: 25px; color: #1a5f2a;'>📋 Recent Open Tickets</h3>";
                    echo "<table>";
                    echo "<tr><th>Ticket #</th><th>Title</th><th>Category</th><th>Priority</th><th>Status</th><th>Submitted By</th></tr>";
                    foreach ($tickets as $ticket) {
                        $statusClass = 'status-' . str_replace('_', '-', $ticket['status']);
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($ticket['ticket_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($ticket['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($ticket['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($ticket['priority']) . "</td>";
                        echo "<td><span class='status-badge $statusClass'>" . htmlspecialchars($ticket['status']) . "</span></td>";
                        echo "<td>" . htmlspecialchars($ticket['submitted_by']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            } catch (PDOException $e) {
                echo "<p>Error loading tickets: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>

        <!-- HR Portal -->
        <div class="section">
            <h2>📅 HR Portal</h2>
            <div class="quick-actions">
                <div class="action-card">
                    <h4>🏖️ Request PTO</h4>
                    <p>Submit vacation, sick leave, or personal time requests</p>
                    <a href="#">Submit Request →</a>
                </div>
                <div class="action-card">
                    <h4>📋 Benefits Overview</h4>
                    <p>View health insurance, 401k, and other employee benefits</p>
                    <a href="#">View Benefits →</a>
                </div>
                <div class="action-card">
                    <h4>📖 Employee Handbook</h4>
                    <p>Company policies, procedures, and guidelines</p>
                    <a href="#">Read Handbook →</a>
                </div>
                <div class="action-card">
                    <h4>🎓 Training Programs</h4>
                    <p>Browse available training courses and development opportunities</p>
                    <a href="#">View Training →</a>
                </div>
            </div>
        </div>

        <!-- Document Center -->
        <div class="section">
            <h2>📁 Document Center</h2>
            <?php
            try {
                $stmt = $pdo->query("
                    SELECT d.title, d.description, d.document_type, d.version, 
                           e.first_name || ' ' || e.last_name as uploaded_by, d.created_at
                    FROM documents d
                    JOIN employees e ON d.uploaded_by = e.id
                    WHERE d.status = 'active'
                    ORDER BY d.created_at DESC
                    LIMIT 5
                ");
                $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($docs)) {
                    echo "<table>";
                    echo "<tr><th>Document</th><th>Type</th><th>Version</th><th>Uploaded By</th><th>Date</th></tr>";
                    foreach ($docs as $doc) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($doc['title']) . "</strong>";
                        if ($doc['description']) {
                            echo "<br><small style='color: #666;'>" . htmlspecialchars($doc['description']) . "</small>";
                        }
                        echo "</td>";
                        echo "<td>" . htmlspecialchars($doc['document_type']) . "</td>";
                        echo "<td>v" . htmlspecialchars($doc['version']) . "</td>";
                        echo "<td>" . htmlspecialchars($doc['uploaded_by']) . "</td>";
                        echo "<td>" . date('M j, Y', strtotime($doc['created_at'])) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: #666; font-style: italic;'>No documents available yet.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error loading documents: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <div class="nav-links" style="margin-top: 20px;">
                <a href="#">📄 View All Documents</a>
                <a href="#">📤 Upload Document</a>
                <a href="#">🔍 Search Documents</a>
            </div>
        </div>

        <!-- Career Hub -->
        <div class="section">
            <h2>💼 Career Hub</h2>
            <?php
            try {
                $stmt = $pdo->query("
                    SELECT j.title, j.location, j.employment_type, j.salary_range, j.status, j.posted_date
                    FROM job_postings j
                    WHERE j.status = 'open'
                    ORDER BY j.posted_date DESC
                    LIMIT 5
                ");
                $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($jobs)) {
                    echo "<table>";
                    echo "<tr><th>Job Title</th><th>Location</th><th>Type</th><th>Salary Range</th><th>Posted</th></tr>";
                    foreach ($jobs as $job) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($job['title']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($job['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($job['employment_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($job['salary_range']) . "</td>";
                        echo "<td>" . date('M j, Y', strtotime($job['posted_date'])) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: #666; font-style: italic;'>No open positions at this time.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error loading jobs: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <div class="nav-links" style="margin-top: 20px;">
                <a href="#">💼 View All Jobs</a>
                <a href="#">📝 Submit Internal Application</a>
                <a href="#">🎓 Training & Development</a>
            </div>
        </div>

        <!-- Company Policies -->
        <div class="section">
            <h2>📜 Company Policies</h2>
            <?php
            try {
                $stmt = $pdo->query("
                    SELECT p.title, p.description, p.policy_type, p.status, p.effective_date
                    FROM policies p
                    WHERE p.status = 'active'
                    ORDER BY p.effective_date DESC
                    LIMIT 5
                ");
                $policies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($policies)) {
                    echo "<table>";
                    echo "<tr><th>Policy</th><th>Description</th><th>Type</th><th>Effective Date</th></tr>";
                    foreach ($policies as $policy) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($policy['title']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($policy['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($policy['policy_type']) . "</td>";
                        echo "<td>" . date('M j, Y', strtotime($policy['effective_date'])) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: #666; font-style: italic;'>No active policies available.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error loading policies: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <div class="nav-links" style="margin-top: 20px;">
                <a href="#">📖 View All Policies</a>
                <a href="#">✍️ Acknowledge Policy</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>🏢 ZabencoCorp Internal Portal | © 2026 ZabencoCorp | <a href="#" style="color: #1a5f2a;">Privacy Policy</a> | <a href="#" style="color: #1a5f2a;">Terms of Use</a></p>
        <p style="margin-top: 10px; font-size: 0.85em;">Need help? Contact IT Support: <a href="mailto:it-support@zabencocorp.com">it-support@zabencocorp.com</a> | Ext. 5555</p>
    </div>
</body>
</html>
