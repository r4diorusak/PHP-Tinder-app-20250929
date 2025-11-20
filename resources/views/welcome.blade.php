<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Tinder app 20250929</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 800px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .emoji {
            font-size: 4em;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            font-size: 1.2em;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .endpoints {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            text-align: left;
            margin-top: 30px;
        }
        .endpoints h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .endpoint {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .method {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.8em;
            margin-right: 10px;
        }
        .get {
            background: #4CAF50;
            color: white;
        }
        .post {
            background: #2196F3;
            color: white;
        }
        .url {
            color: #666;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .description {
            color: #999;
            font-size: 0.85em;
            margin-top: 5px;
        }
        .status {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .docs-link {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        .docs-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">❤️🔥</div>
        <h1>PHP Tinder app 20250929</h1>
        <div class="status">✓ Server Running</div>
        <p>Welcome to PHP Tinder app 20250929! Your Laravel backend is up and running.</p>
        
        <div class="endpoints">
            <h2>📡 Available API Endpoints</h2>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/people?user_id=1&page=1&per_page=10</span>
                <div class="description">Get recommended people (paginated)</div>
            </div>
            
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url">/api/people/{id}/like</span>
                <div class="description">Like a person (30% chance match with simulate_match)</div>
            </div>
            
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url">/api/people/{id}/dislike</span>
                <div class="description">Dislike a person</div>
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/people/liked?user_id=1</span>
                <div class="description">Get people you liked (Liked Opponents)</div>
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/people/disliked?user_id=1</span>
                <div class="description">Get people you disliked</div>
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/people/liked-opponents?user_id=1</span>
                <div class="description">Get people who liked you</div>
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/people/matches?user_id=1</span>
                <div class="description">Get mutual matches (both liked each other)</div>
            </div>
            
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url">/api/people/check-popular</span>
                <div class="description">Check and notify popular people (50+ likes) - Admin cronjob</div>
            </div>
        </div>
        
        <p style="margin-top: 30px; font-size: 1em;">
            💻 Test Laravel API: <a href="/api/people?user_id=1" style="color: #667eea; font-weight: bold; text-decoration: none;">GET /api/people?user_id=1</a>
        </p>
        
        <a href="/api/documentation" class="docs-link">
            📚 API Documentation (Swagger) →
        </a>
        
        <a href="/mobile-demo.html" class="docs-link" style="margin-top: 10px; background: linear-gradient(135deg, #4ECDC4, #44A7A0);">
            📱 Open Mobile Demo →
        </a>
    </div>
</body>
</html>
