// Sample tweets data
const tweets = [
    {
        id: 1,
        name: 'John Doe',
        username: '@john',
        avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop',
        content: 'Welcome to Twitter! 🌟',
        time: '2 hours ago',
        likes: 245,
        retweets: 34,
        replies: 12
    },
    {
        id: 2,
        name: 'Sarah Khalid',
        username: '@sarah',
        avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop',
        content: 'It’s a beautiful day to create and code! 💻',
        time: '3 hours ago',
        likes: 567,
        retweets: 89,
        replies: 45
    }
];

// Function to create tweet HTML
function createTweetElement(tweet) {
    return `
        <div class="tweet" data-id="${tweet.id}">
            <div class="tweet-header">
                <img src="${tweet.avatar}" alt="${tweet.name}" class="avatar">
                <div>
                    <span class="name">${tweet.name}</span>
                    <span class="username">${tweet.username}</span>
                    <span class="time">· ${tweet.time}</span>
                </div>
            </div>
            <div class="tweet-content">
                ${tweet.content}
            </div>
            <div class="tweet-actions">
                <button class="action-button" onclick="handleAction('reply', ${tweet.id})">
                    <i class="fa-regular fa-comment"></i> <span>${tweet.replies}</span>
                </button>
                <button class="action-button" onclick="handleAction('retweet', ${tweet.id})">
                    <i class="fa-solid fa-retweet"></i> <span>${tweet.retweets}</span>
                </button>
                <button class="action-button" onclick="handleAction('like', ${tweet.id})">
                    <i class="fa-regular fa-heart"></i> <span>${tweet.likes}</span>
                </button>
                <button class="action-button" onclick="handleAction('share', ${tweet.id})">
                    <i class="fa-solid fa-share"></i>
                </button>
            </div>
        </div>
    `;
}

// Function to render all tweets
function renderTweets() {
    const feedElement = document.getElementById('tweetsFeed');
    feedElement.innerHTML = tweets.map(tweet => createTweetElement(tweet)).join('');
}

// Function to handle tweet actions (like, retweet, etc.)
function handleAction(action, tweetId) {
    const tweet = tweets.find(t => t.id === tweetId);
    if (!tweet) return;

    switch (action) {
        case 'like':
            tweet.likes++;
            break;
        case 'retweet':
            tweet.retweets++;
            break;
        case 'reply':
            tweet.replies++;
            break;
        case 'share':
            alert('Tweet shared!');
            break;
    }

    renderTweets();
}

// Function to post a new tweet
function postTweet() {
    const tweetText = document.getElementById('tweetText').value;
    if (!tweetText.trim()) return;

    const newTweet = {
        id: tweets.length + 1,
        name: 'Current User',
        username: '@user',
        avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop',
        content: tweetText,
        time: 'Just now',
        likes: 0,
        retweets: 0,
        replies: 0
    };

    tweets.unshift(newTweet);
    renderTweets();
    document.getElementById('tweetText').value = '';
}

// Initialize the feed
document.addEventListener('DOMContentLoaded', renderTweets);

//upload profile image
document.getElementById('profileImageUpload').addEventListener('change', function () {
    this.form.submit(); // يرفع الملف تلقائيًا عند اختياره
});


// // Sample comments data
// const comments = {};

// // Function to toggle the comments section
// function toggleComments(tweetId) {
//     const tweetElement = document.querySelector(`.tweet[data-id="${tweetId}"]`);
//     let commentsSection = tweetElement.querySelector('.comments-section');

//     if (!commentsSection) {
//         // Create comments section if it doesn't exist
//         commentsSection = document.createElement('div');
//         commentsSection.classList.add('comments-section');

//         const commentInput = `
//             <div class="add-comment">
//                 <textarea placeholder="Write a comment..." id="commentInput-${tweetId}"></textarea>
//                 <button onclick="addComment(${tweetId})">Post</button>
//             </div>
//         `;

//         commentsSection.innerHTML = commentInput;
//         tweetElement.appendChild(commentsSection);
//     } else {
//         // Toggle visibility
//         commentsSection.style.display = 
//             commentsSection.style.display === 'none' ? 'block' : 'none';
//     }
// }

// // Function to add a comment
// function addComment(tweetId) {
//     const input = document.getElementById(`commentInput-${tweetId}`);
//     const comment = input.value.trim();
//     if (!comment) return;

//     if (!comments[tweetId]) comments[tweetId] = [];
//     comments[tweetId].push(comment);
//     input.value = '';

//     renderComments(tweetId);
// }

// // Function to render comments
// function renderComments(tweetId) {
//     const tweetElement = document.querySelector(`.tweet[data-id="${tweetId}"]`);
//     const commentsSection = tweetElement.querySelector('.comments-section');

//     const commentList = comments[tweetId] || [];
//     const commentsHTML = commentList.map(
//         (c) => `<div class="comment">${c}</div>`
//     ).join('');

//     commentsSection.innerHTML = `
//         <div class="comment-list">${commentsHTML}</div>
//         <div class="add-comment">
//             <textarea placeholder="Write a comment..." id="commentInput-${tweetId}"></textarea>
//             <button onclick="addComment(${tweetId})">Post</button>
//         </div>
//     `;
// }

