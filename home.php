<?php

session_start();
// include_once 'connect.php';
// include '../TwitterClone/config/register.php';
if(!isset($_SESSION['username'])){
    header('location: ./register.php');
}else{ ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter</title>
    <link rel="stylesheet" href="./front_back/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fab fa-twitter twitter-icon"></i>
            </div>
            <nav>
                <a href="home.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-hashtag"></i>
                    <span>Explore</span>
                </a>
                <a href="./notification.php" class="nav-item">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
                <a href="./message.php" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                </a>
                <a href="./profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
            <button class="tweet-btn">Tweet</button>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Home</h1>
            </header>

            <!-- Tweet Composer -->
            <div class="tweet-composer">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop" alt="Profile Picture" class="avatar">
                <div class="composer-content">
                    <textarea placeholder="What's happening?" id="tweetText"></textarea>
                    <div class="composer-actions">
                        <button class="tweet-btn" onclick="postTweet()">Tweet</button>
                    </div>
                </div>
            </div>

            <!-- Tweets Feed -->
            <div class="tweets-feed" id="tweetsFeed">
                <!-- Tweets will be inserted here by JavaScript -->
            </div>
        </main>

        <!-- Trending Section -->
        <aside class="trending">
            <div class="search-box">
                <input type="text" placeholder="Search Twitter">
            </div>

            <div class="trending-card">
                <h2>What's Trending</h2>
                <div class="trending-item">
                    <span class="category">Trending</span>
                    <h3>#WebDevelopment</h3>
                    <span class="tweet-count">52.8K Tweets</span>
                </div>
                <div class="trending-item">
                    <span class="category">Technology</span>
                    <h3>#JavaScript</h3>
                    <span class="tweet-count">32.4K Tweets</span>
                </div>
            </div>

            <div class="follow-suggestions">
                <h2>Who to Follow</h2>
                <div class="suggestion-item">
                    <img src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=150&h=150&fit=crop" alt="User Picture" class="avatar">
                    <div class="user-info">
                        <h4>Mohammed Ahmed</h4>
                        <span>@mohammed</span>
                    </div>
                    <button class="follow-btn">Follow</button>
                </div>
            </div>
        </aside>
    </div>

    <script src="./front_back/script.js"></script>
</body>
</html>


<?php }?>




