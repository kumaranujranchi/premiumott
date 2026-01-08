<?php
include 'includes/db.php';

$products = [
    [
        "name" => "Nexus CRM",
        "tagline" => "Better alternative to Salesforce for SMBs",
        "description" => "The all-in-one CRM that helps you close deals faster without the enterprise bloat. Perfect for startups and growing businesses looking for powerful sales tools at an affordable price.",
        "original_price" => 199,
        "discounted_price" => 49,
        "rating" => 4.9,
        "reviews" => 124,
        "discount_percent" => 75,
        "category" => "CRM",
        "license_type" => "Lifetime Deal",
        "icon" => "users",
        "color" => "#3B82F6",
        "features" => [
            "Unlimited contacts & deals",
            "Email integration & tracking",
            "Pipeline management",
            "Team collaboration",
            "Mobile app access",
            "API access",
            "Priority support",
            "Custom fields & workflows"
        ]
    ],
    [
        "name" => "Rocket Outreach",
        "tagline" => "Automated LinkedIn & Email sequencing",
        "description" => "Scale your outbound marketing with intelligent automation sequences that feel personal. Connect with prospects across multiple channels effortlessly.",
        "original_price" => 99,
        "discounted_price" => 39,
        "rating" => 4.9,
        "reviews" => 89,
        "discount_percent" => 60,
        "category" => "Marketing",
        "license_type" => "Lifetime Deal",
        "icon" => "rocket",
        "color" => "#F59E0B",
        "features" => [
            "Multi-channel sequences",
            "LinkedIn automation",
            "Email personalization",
            "A/B testing",
            "Analytics dashboard",
            "CRM integrations",
            "Team workspaces",
            "Unlimited campaigns"
        ]
    ],
    [
        "name" => "Eco Analytics",
        "tagline" => "Privacy-first Google Analytics alternative",
        "description" => "Simple, lightweight, and GDPR-compliant website analytics that doesn't track personal data. Get actionable insights without compromising user privacy.",
        "original_price" => 79,
        "discounted_price" => 29,
        "rating" => 4.9,
        "reviews" => 156,
        "discount_percent" => 63,
        "category" => "Analytics",
        "license_type" => "Lifetime Deal",
        "icon" => "barchart",
        "color" => "#10B981",
        "features" => [
            "Privacy-compliant tracking",
            "Real-time dashboard",
            "Custom events",
            "Goal tracking",
            "UTM campaign analysis",
            "Export capabilities",
            "Multiple websites",
            "No cookie banners needed"
        ]
    ],
    [
        "name" => "CloudBase DB",
        "tagline" => "Serverless Postgres with AI superpowers",
        "description" => "The easiest way to ship AI apps. Vector embeddings and auth built-in. Scale automatically from prototype to production.",
        "original_price" => 249,
        "discounted_price" => 59,
        "rating" => 4.8,
        "reviews" => 67,
        "discount_percent" => 76,
        "category" => "Database",
        "license_type" => "Lifetime Deal",
        "icon" => "database",
        "color" => "#8B5CF6",
        "features" => [
            "Serverless architecture",
            "Vector embeddings",
            "Built-in authentication",
            "Auto-scaling",
            "Real-time subscriptions",
            "Row-level security",
            "Dashboard & API",
            "99.9% uptime SLA"
        ]
    ],
    [
        "name" => "FormFlow Pro",
        "tagline" => "Beautiful forms that convert",
        "description" => "Create stunning multi-step forms, surveys, and quizzes that boost conversions. No coding required with our drag-and-drop builder.",
        "original_price" => 149,
        "discounted_price" => 39,
        "rating" => 4.7,
        "reviews" => 203,
        "discount_percent" => 74,
        "category" => "Forms",
        "license_type" => "Lifetime Deal",
        "icon" => "form",
        "color" => "#EC4899",
        "features" => [
            "Drag & drop builder",
            "Conditional logic",
            "Payment collection",
            "File uploads",
            "Custom branding",
            "Webhook integrations",
            "Analytics & reporting",
            "Unlimited responses"
        ]
    ],
    [
        "name" => "ScreenCast Studio",
        "tagline" => "Professional screen recording made simple",
        "description" => "Record, edit, and share beautiful screen recordings in minutes. Perfect for tutorials, demos, and async communication.",
        "original_price" => 129,
        "discounted_price" => 29,
        "rating" => 4.8,
        "reviews" => 178,
        "discount_percent" => 78,
        "category" => "Video",
        "license_type" => "Lifetime Deal",
        "icon" => "video",
        "color" => "#EF4444",
        "features" => [
            "4K recording",
            "Built-in editor",
            "Webcam overlay",
            "Cloud hosting",
            "Custom branding",
            "Instant sharing",
            "Embed anywhere",
            "Viewer analytics"
        ]
    ],
    [
        "name" => "TaskMaster AI",
        "tagline" => "AI-powered project management",
        "description" => "Let AI handle the busywork. Smart task prioritization, automatic scheduling, and intelligent progress tracking for modern teams.",
        "original_price" => 199,
        "discounted_price" => 49,
        "rating" => 4.9,
        "reviews" => 92,
        "discount_percent" => 75,
        "category" => "Productivity",
        "license_type" => "Lifetime Deal",
        "icon" => "tasks",
        "color" => "#06B6D4",
        "features" => [
            "AI task prioritization",
            "Smart scheduling",
            "Team workspaces",
            "Time tracking",
            "Kanban & list views",
            "File attachments",
            "Integrations",
            "Mobile apps"
        ]
    ],
    [
        "name" => "EmailBlast Pro",
        "tagline" => "Email marketing for growing businesses",
        "description" => "Send beautiful newsletters that actually get opened. Advanced automation, segmentation, and analytics without the complexity.",
        "original_price" => 179,
        "discounted_price" => 39,
        "rating" => 4.8,
        "reviews" => 145,
        "discount_percent" => 78,
        "category" => "Email",
        "license_type" => "Lifetime Deal",
        "icon" => "mail",
        "color" => "#F97316",
        "features" => [
            "Drag & drop editor",
            "Smart automation",
            "Advanced segmentation",
            "A/B testing",
            "Detailed analytics",
            "Landing pages",
            "Form builder",
            "API access"
        ]
    ]
];

try {
    $pdo->beginTransaction();

    foreach ($products as $p) {
        $stmt = $pdo->prepare("INSERT INTO products (name, tagline, description, original_price, discounted_price, rating, reviews, discount_percent, category, license_type, icon, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $p['name'],
            $p['tagline'],
            $p['description'],
            $p['original_price'],
            $p['discounted_price'],
            $p['rating'],
            $p['reviews'],
            $p['discount_percent'],
            $p['category'],
            $p['license_type'],
            $p['icon'],
            $p['color']
        ]);

        $productId = $pdo->lastInsertId();

        foreach ($p['features'] as $f) {
            $fStmt = $pdo->prepare("INSERT INTO product_features (product_id, feature_text) VALUES (?, ?)");
            $fStmt->execute([$productId, $f]);
        }
    }

    $pdo->commit();
    echo "Seed data inserted successfully!";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>