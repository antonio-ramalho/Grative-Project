const http = require('http');
const { parse } = require('querystring');

const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/submit') {
        let body = '';

        // 1. Listen for data chunks
        req.on('data', chunk => {
            body += chunk.toString();
        });

        // 2. All data received
        req.on('end', () => {
            const formData = parse(body);
            
            // 3. Logic/Validation
            if (!formData.name) {
                res.writeHead(400, { 'Content-Type': 'text/plain' });
                return res.end('Error: Username is required');
            }

            console.log('User submitted:', formData.name);

            // 4. Response
            res.writeHead(200, { 'Content-Type': 'text/html' });
            res.end(`<h1>Success!</h1><p>Welcome, ${formData.name}</p>`);
        });

    } else {
        // Handle GET or 404
        res.writeHead(404);
        res.end('Not Found');
    }
});

server.listen(3000, () => console.log('Server running on port 3000'));