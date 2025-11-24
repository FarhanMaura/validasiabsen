<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Debug Test</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .btn-secondary {
            background: #2196F3;
        }
        .btn-secondary:hover {
            background: #0b7dda;
        }
        #response {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 14px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .log {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .log-entry {
            padding: 8px;
            margin-bottom: 5px;
            border-left: 3px solid #007bff;
            background: white;
        }
        .timestamp {
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 RFID Debug Test</h1>
        
        <div class="form-group">
            <label for="rfid_uid">RFID UID (Test):</label>
            <input type="text" id="rfid_uid" placeholder="Masukkan RFID UID untuk test" value="TEST123456">
        </div>

        <div class="form-group">
            <label for="api_url">API Endpoint:</label>
            <input type="text" id="api_url" value="{{ url('/api/absensi/check') }}" readonly>
        </div>

        <button onclick="testRFID()">Test RFID Tap</button>
        <button onclick="clearLog()" class="btn-secondary">Clear Log</button>

        <div id="response"></div>

        <div class="log">
            <h3>Request/Response Log:</h3>
            <div id="log"></div>
        </div>
    </div>

    <script>
        function addLog(message, type = 'info') {
            const log = document.getElementById('log');
            const entry = document.createElement('div');
            entry.className = 'log-entry';
            const timestamp = new Date().toLocaleTimeString();
            entry.innerHTML = `<span class="timestamp">[${timestamp}]</span> ${message}`;
            log.insertBefore(entry, log.firstChild);
        }

        function clearLog() {
            document.getElementById('log').innerHTML = '';
            document.getElementById('response').innerHTML = '';
        }

        async function testRFID() {
            const rfidUid = document.getElementById('rfid_uid').value;
            const apiUrl = document.getElementById('api_url').value;
            const responseDiv = document.getElementById('response');

            if (!rfidUid) {
                responseDiv.className = 'error';
                responseDiv.textContent = 'Error: RFID UID tidak boleh kosong!';
                return;
            }

            addLog(`📤 Sending request to: ${apiUrl}`);
            addLog(`📋 RFID UID: ${rfidUid}`);

            responseDiv.textContent = 'Sending request...';
            responseDiv.className = 'info';

            try {
                const startTime = Date.now();
                
                const response = await axios.post(apiUrl, {
                    rfid_uid: rfidUid
                }, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const duration = Date.now() - startTime;

                addLog(`✅ Response received (${duration}ms)`);
                addLog(`📊 Status: ${response.status} ${response.statusText}`);
                
                responseDiv.className = 'success';
                responseDiv.textContent = 'SUCCESS!\n\n' + JSON.stringify(response.data, null, 2);

                addLog(`📦 Data: ${JSON.stringify(response.data)}`);

            } catch (error) {
                const duration = Date.now() - startTime;
                
                addLog(`❌ Error occurred (${duration}ms)`, 'error');
                
                responseDiv.className = 'error';
                
                if (error.response) {
                    // Server responded with error
                    addLog(`🔴 HTTP ${error.response.status}: ${error.response.statusText}`);
                    addLog(`📦 Error data: ${JSON.stringify(error.response.data)}`);
                    
                    responseDiv.textContent = `HTTP Error ${error.response.status}\n\n` + 
                        JSON.stringify(error.response.data, null, 2);
                } else if (error.request) {
                    // Request made but no response
                    addLog(`🔴 No response from server`);
                    addLog(`📡 Request: ${JSON.stringify(error.request)}`);
                    
                    responseDiv.textContent = 'ERROR: No response from server\n\n' +
                        'Possible causes:\n' +
                        '- Server is down\n' +
                        '- Network connection issue\n' +
                        '- CORS policy blocking request\n\n' +
                        'Check browser console for more details';
                } else {
                    // Something else happened
                    addLog(`🔴 Request setup error: ${error.message}`);
                    
                    responseDiv.textContent = 'ERROR: ' + error.message;
                }

                console.error('Full error:', error);
            }
        }

        // Auto-test on page load (optional)
        window.addEventListener('load', () => {
            addLog('🚀 Debug page loaded');
            addLog('💡 Enter RFID UID and click "Test RFID Tap" to test');
        });
    </script>
</body>
</html>
