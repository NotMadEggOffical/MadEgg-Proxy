const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const path = require('path');

const app = express();

// Serve your HTML page (menu.html)
app.use(express.static(path.join(__dirname, 'public')));

// Proxy endpoint to forward requests to target URL
app.use('/proxy', (req, res, next) => {
    const targetUrl = req.query.url;
    if (targetUrl) {
        createProxyMiddleware({
            target: targetUrl,
            changeOrigin: true,
            pathRewrite: {
                '^/proxy': '', // Rewrite /proxy to the actual target URL
            },
        })(req, res, next);
    } else {
        res.status(400).send('URL is required');
    }
});

// Listen on a dynamic port assigned by Vercel
const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Proxy server running at http://localhost:${port}`);
});
