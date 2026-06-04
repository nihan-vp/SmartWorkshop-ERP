import { WebSocketServer } from 'ws';
import mysql from 'mysql2/promise';
import dotenv from 'dotenv';
import crypto from 'crypto';
import path from 'path';
import { fileURLToPath } from 'url';
import express from 'express';

// Load .env
const __dirname = path.dirname(fileURLToPath(import.meta.url));
dotenv.config({ path: path.resolve(__dirname, '.env') });

const PORT = process.env.WEBSOCKET_PORT || 8080;
const APP_KEY = process.env.APP_KEY || '';

// Create MySQL connection pool
const pool = mysql.createPool({
    host: process.env.DB_HOST || '127.0.0.1',
    port: parseInt(process.env.DB_PORT || '3306'),
    database: process.env.DB_DATABASE || 'suhaim_workshop',
    user: process.env.DB_USERNAME || 'root',
    password: process.env.DB_PASSWORD || '',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// Helper to verify secure signature
function verifySignature(userId, time, signature) {
    if (!APP_KEY) {
        console.error('Error: APP_KEY not defined in environment.');
        return false;
    }
    
    // Check if timestamp is within a secure window (e.g., 12 hours)
    const now = Math.floor(Date.now() / 1000);
    const diff = Math.abs(now - parseInt(time));
    if (diff > 43200) { // 12 hours in seconds
        console.warn(`Auth failed: signature expired by ${diff}s`);
        return false;
    }

    // Generate expected signature
    // Remove "base64:" prefix if it exists in Laravel APP_KEY
    const cleanKey = APP_KEY.replace('base64:', '');
    
    const hmac = crypto.createHmac('sha256', cleanKey);
    hmac.update(`${userId}:${time}`);
    const expectedSignature = hmac.digest('hex');

    return crypto.timingSafeEqual(Buffer.from(signature), Buffer.from(expectedSignature));
}

// Start WebSocket Server
const wss = new WebSocketServer({ port: PORT });
console.log(`🔒 Secure WebSocket server started on port ${PORT}`);

wss.on('connection', (ws, req) => {
    let isAuthenticated = false;
    let currentUser = null;

    ws.on('message', async (message) => {
        try {
            const data = JSON.parse(message);

            // 1. Authenticate connection
            if (data.type === 'auth') {
                const { userId, time, signature } = data;
                
                if (verifySignature(userId, time, signature)) {
                    isAuthenticated = true;
                    currentUser = userId;
                    ws.send(JSON.stringify({ type: 'auth_success', message: 'Connected & Securely Authenticated' }));
                    console.log(`🟢 Client Authenticated: User ID ${userId}`);
                } else {
                    ws.send(JSON.stringify({ type: 'error', message: 'Invalid or expired authentication signature' }));
                    ws.close();
                    console.log('🔴 Client connection rejected: Invalid signature');
                }
                return;
            }

            // Reject all requests if not authenticated
            if (!isAuthenticated) {
                ws.send(JSON.stringify({ type: 'error', message: 'Unauthorized. Please authenticate first.' }));
                ws.close();
                return;
            }

            // 2. Fetch Customer Vehicles
            if (data.type === 'get_vehicles') {
                const { customerId } = data;
                const [rows] = await pool.execute(
                    'SELECT * FROM vehicles WHERE customer_id = ?',
                    [customerId]
                );
                ws.send(JSON.stringify({
                    type: 'vehicles_data',
                    customerId,
                    vehicles: rows
                }));
            }

            // 3. Fetch Billing Templates
            if (data.type === 'get_bill_template') {
                const { templateId } = data;
                
                // Get template header
                const [templates] = await pool.execute(
                    'SELECT * FROM bill_templates WHERE id = ?',
                    [templateId]
                );

                if (templates.length === 0) {
                    ws.send(JSON.stringify({ type: 'error', message: 'Template not found' }));
                    return;
                }

                // Get template items
                const [items] = await pool.execute(
                    'SELECT * FROM bill_template_items WHERE bill_template_id = ?',
                    [templateId]
                );

                ws.send(JSON.stringify({
                    type: 'bill_template_data',
                    templateId,
                    template: {
                        ...templates[0],
                        items
                    }
                }));
            }

        } catch (err) {
            console.error('Error handling WebSocket message:', err);
            ws.send(JSON.stringify({ type: 'error', message: 'Internal server processing error' }));
        }
    });

    ws.on('close', () => {
        if (currentUser) {
            console.log(`🔌 Connection closed for user ID ${currentUser}`);
        }
    });
});

// Setup Express App
const app = express();
const expressPort = process.env.PORT || 4000;

app.get('/', (req, res) => {
  res.send('Hello World!');
});

app.listen(expressPort, () => {
  console.log(`Example app listening on port ${expressPort}`);
});
